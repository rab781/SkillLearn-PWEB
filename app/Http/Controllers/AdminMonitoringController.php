<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use App\Models\UserCourseProgress;
use App\Models\UserVideoProgress;
use App\Models\RiwayatTonton;
use App\Models\Vidio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminMonitoringController extends Controller
{
    public function index()
    {
        $courses = Course::with(['kategori', 'userProgress', 'videos'])
                        ->withCount('userProgress')
                        ->get()
                        ->map(function ($course) {
                            // Add computed properties for consistency
                            $course->total_video = $course->videos->count();
                            $course->total_durasi_menit = $course->videos->sum('durasi_menit');
                            return $course;
                        });

        $totalStudents = User::where('role', 'CU')->count();
        $totalCourses = Course::count();
        $activeCourses = Course::where('is_active', true)->count();
        $totalCompletions = UserCourseProgress::where('status', 'completed')->count();

        // Get recent activities with proper relationships
        $recentActivities = RiwayatTonton::with(['pengguna'])
                                       ->orderBy('waktu_ditonton', 'desc')
                                       ->limit(10)
                                       ->get();

        // Add video and course information manually
        $recentActivities = $recentActivities->map(function ($activity) {
            // Get video information using the correct column name
            $video = null;
            if ($activity->id_video) {
                $video = Vidio::find($activity->id_video);
            }
            $activity->video = $video;

            // Get course information 
            $course = null;
            if ($activity->course_id) {
                $course = Course::find($activity->course_id);
            } elseif ($video) {
                // Try to find course through course_videos table
                $courseVideo = DB::table('course_videos')
                              ->where('vidio_vidio_id', $video->vidio_id)
                              ->first();
                if ($courseVideo) {
                    $course = Course::find($courseVideo->course_id);
                }
            }
            $activity->course = $course;
            $activity->user = $activity->pengguna; // Add alias for compatibility

            return $activity;
        });

        // Course completion statistics
        $courseStats = Course::with('userProgress')
                            ->get()
                            ->map(function ($course) {
                                $totalStudents = $course->userProgress->count();
                                $completedStudents = $course->userProgress->where('status', 'completed')->count();
                                $completionRate = $totalStudents > 0 ? ($completedStudents / $totalStudents) * 100 : 0;

                                return [
                                    'course_id' => $course->course_id,
                                    'nama_course' => $course->nama_course,
                                    'total_students' => $totalStudents,
                                    'completed_students' => $completedStudents,
                                    'completion_rate' => round($completionRate, 1),
                                    'is_active' => $course->is_active
                                ];
                            })
                            ->sortByDesc('total_students');

        return view('admin.monitoring.index', compact(
            'courses',
            'totalStudents',
            'totalCourses',
            'activeCourses',
            'totalCompletions',
            'recentActivities',
            'courseStats'
        ));
    }

    public function courseDetail($courseId)
    {
        $course = Course::with([
            'kategori',
            'videos',
            'userProgress.user',
            'quizzes'
        ])->findOrFail($courseId);

        // Get detailed student progress
        $studentProgress = UserCourseProgress::with(['user'])
                                           ->where('course_id', $courseId)
                                           ->get()
                                           ->map(function ($progress) use ($course) {
                                               $totalVideos = $course->videos->count();

                                               // Get video progress for this user from course videos
                                               $courseVideoIds = $course->videos()->pluck('vidio_vidio_id');
                                               $videoProgress = UserVideoProgress::where('user_id', $progress->user_id)
                                                                                ->whereIn('vidio_vidio_id', $courseVideoIds)
                                                                                ->get();

                                               $completedVideos = $videoProgress->where('is_completed', true)->count();
                                               $watchedMinutes = $videoProgress->sum('watch_time_seconds') / 60; // Convert to minutes

                                               return [
                                                   'user' => $progress->user,
                                                   'progress' => $progress,
                                                   'total_videos' => $totalVideos,
                                                   'completed_videos' => $completedVideos,
                                                   'completion_percentage' => $totalVideos > 0 ? round(($completedVideos / $totalVideos) * 100, 1) : 0,
                                                   'watched_minutes' => $watchedMinutes,
                                                   'last_activity' => $progress->updated_at
                                               ];
                                           })
                                           ->sortByDesc('completion_percentage');

        // Get video watching statistics from course videos
        $videoStats = collect();
        if ($course->videos()->exists()) {
            $videoStats = $course->videos()->with('vidio')->get()->map(function ($courseVideo) {
                if (!$courseVideo->vidio) return null;
                
                $video = $courseVideo->vidio;

                // Use only id_video column for RiwayatTonton
                $watchCount = RiwayatTonton::where('id_video', $video->vidio_id)
                                         ->count();
                                         
                $totalWatchTime = RiwayatTonton::where('id_video', $video->vidio_id)
                                             ->sum('durasi_tonton') ?? 0;
                                             
                $uniqueViewers = RiwayatTonton::where('id_video', $video->vidio_id)
                                            ->distinct('id_pengguna')
                                            ->count();

                return [
                    'video' => $video,
                    'watch_count' => $watchCount,
                    'total_watch_time' => $totalWatchTime,
                    'unique_viewers' => $uniqueViewers,
                    'average_watch_time' => $uniqueViewers > 0 ? round($totalWatchTime / $uniqueViewers, 1) : 0
                ];
            })->filter()->sortByDesc('watch_count');
        }

        // Quiz statistics
        $quizStats = collect();
        if ($course->quizzes) {
            $quizStats = $course->quizzes->map(function ($quiz) {
                // Assuming quiz has results relationship
                $results = collect(); // Default empty collection
                try {
                    $results = $quiz->results ?? collect();
                } catch (\Exception $e) {
                    // Handle if results relationship doesn't exist
                    $results = collect();
                }

                $totalAttempts = $results->count();
                $passedAttempts = $results->where('nilai_total', '>=', 60)->count();
                $averageScore = $results->avg('nilai_total');

                return [
                    'quiz' => $quiz,
                    'total_attempts' => $totalAttempts,
                    'passed_attempts' => $passedAttempts,
                    'pass_rate' => $totalAttempts > 0 ? round(($passedAttempts / $totalAttempts) * 100, 1) : 0,
                    'average_score' => round($averageScore ?? 0, 1)
                ];
            });
        }

        return view('admin.monitoring.course-detail', compact(
            'course',
            'studentProgress',
            'videoStats',
            'quizStats'
        ));
    }

    public function studentDetail($userId)
    {
        $user = User::with([
            'courseProgress.course',
        ])->findOrFail($userId);

        // Get course statistics for this student
        $courseStats = $user->courseProgress->map(function ($progress) {
            $course = $progress->course;
            $totalVideos = $course->videos ? $course->videos->count() : 0;

            // Get video progress for this user from course videos
            $videoProgress = collect();
            if ($totalVideos > 0) {
                $courseVideoIds = $course->videos()->pluck('vidio_vidio_id');
                $videoProgress = UserVideoProgress::where('user_id', $progress->user_id)
                                                 ->whereIn('vidio_vidio_id', $courseVideoIds)
                                                 ->get();
            }

            $completedVideos = $videoProgress->where('is_completed', true)->count();
            $watchedMinutes = $videoProgress->sum('watch_time_seconds') / 60; // Convert to minutes

            return [
                'course' => $course,
                'progress' => $progress,
                'total_videos' => $totalVideos,
                'completed_videos' => $completedVideos,
                'completion_percentage' => $totalVideos > 0 ? round(($completedVideos / $totalVideos) * 100, 1) : 0,
                'watched_minutes' => $watchedMinutes
            ];
        })->sortByDesc('completion_percentage');

        // Get recent activities for this user
        $recentActivities = RiwayatTonton::with(['pengguna'])
                                        ->where('id_pengguna', $userId)
                                        ->orderBy('waktu_ditonton', 'desc')
                                        ->limit(20)
                                        ->get()
                                        ->map(function ($activity) {
                                            // Get video information using correct column
                                            $video = null;
                                            if ($activity->id_video) {
                                                $video = Vidio::find($activity->id_video);
                                            }
                                            $activity->video = $video;

                                            // Get course information
                                            $course = null;
                                            if ($activity->course_id) {
                                                $course = Course::find($activity->course_id);
                                            } elseif ($video) {
                                                // Try to find course through course_videos table
                                                $courseVideo = DB::table('course_videos')
                                                              ->where('vidio_vidio_id', $video->vidio_id)
                                                              ->first();
                                                if ($courseVideo) {
                                                    $course = Course::find($courseVideo->course_id);
                                                }
                                            }
                                            $activity->course = $course;
                                            $activity->user = $activity->pengguna; // Add alias

                                            return $activity;
                                        });

        // Learning time statistics using correct column names
        $userActivities = RiwayatTonton::where('id_pengguna', $userId)->get();
        $totalWatchTime = $userActivities->sum('durasi_tonton');
        $averageSessionTime = $userActivities->avg('durasi_tonton');
        $activeDays = $userActivities->groupBy(function ($item) {
            return $item->waktu_ditonton->format('Y-m-d');
        })->count();

        return view('admin.monitoring.student-detail', compact(
            'user',
            'courseStats',
            'recentActivities',
            'totalWatchTime',
            'averageSessionTime',
            'activeDays'
        ));
    }
}
