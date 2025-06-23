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
        $courses = Course::with(['kategori', 'userProgress'])
                        ->withCount('userProgress')
                        ->get();

        $totalStudents = User::where('role', 'CU')->count();
        $totalCourses = Course::count();
        $activeCourses = Course::where('is_active', true)->count();
        $totalCompletions = UserCourseProgress::where('status', 'completed')->count();

        // Get recent activities - simplify the query first
        $recentActivities = RiwayatTonton::with(['user'])
                                       ->orderBy('waktu_ditonton', 'desc')
                                       ->limit(10)
                                       ->get();

        // Add video and course information manually
        $recentActivities = $recentActivities->map(function ($activity) {
            // Get video information
            $video = Vidio::find($activity->vidio_vidio_id);
            $activity->video = $video;

            // Get course information through video
            if ($video) {
                $course = DB::table('course_video')
                           ->join('courses', 'course_video.course_id', '=', 'courses.course_id')
                           ->where('course_video.vidio_id', $video->vidio_id)
                           ->select('courses.*')
                           ->first();
                $activity->course = $course;
            }

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

                                               // Get video progress for this user
                                               $videoProgress = UserVideoProgress::where('user_id', $progress->user_id)
                                                                                ->whereIn('video_id', $course->videos->pluck('vidio_id'))
                                                                                ->get();

                                               $completedVideos = $videoProgress->where('is_completed', true)->count();
                                               $watchedMinutes = $videoProgress->sum('watched_duration');

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
        if ($course->videos) {
            $videoStats = $course->videos->map(function ($courseVideo) {
                $video = Vidio::find($courseVideo->vidio_id);
                if (!$video) return null;

                $watchCount = RiwayatTonton::where('vidio_vidio_id', $video->vidio_id)->count();
                $totalWatchTime = RiwayatTonton::where('vidio_vidio_id', $video->vidio_id)->sum('durasi_tonton') ?? 0;
                $uniqueViewers = RiwayatTonton::where('vidio_vidio_id', $video->vidio_id)->distinct('users_id')->count();

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

            // Get video progress for this user
            $videoProgress = collect();
            if ($totalVideos > 0) {
                $videoProgress = UserVideoProgress::where('user_id', $progress->user_id)
                                                 ->whereIn('video_id', $course->videos->pluck('vidio_id'))
                                                 ->get();
            }

            $completedVideos = $videoProgress->where('is_completed', true)->count();
            $watchedMinutes = $videoProgress->sum('watched_duration');

            return [
                'course' => $course,
                'progress' => $progress,
                'total_videos' => $totalVideos,
                'completed_videos' => $completedVideos,
                'completion_percentage' => $totalVideos > 0 ? round(($completedVideos / $totalVideos) * 100, 1) : 0,
                'watched_minutes' => $watchedMinutes
            ];
        })->sortByDesc('completion_percentage');

        // Get recent activities
        $recentActivities = RiwayatTonton::with(['user'])
                                        ->where('users_id', $userId)
                                        ->orderBy('waktu_ditonton', 'desc')
                                        ->limit(20)
                                        ->get()
                                        ->map(function ($activity) {
                                            // Get video information
                                            $video = Vidio::find($activity->vidio_vidio_id);
                                            $activity->video = $video;

                                            // Get course information through video
                                            if ($video) {
                                                $course = DB::table('course_video')
                                                           ->join('courses', 'course_video.course_id', '=', 'courses.course_id')
                                                           ->where('course_video.vidio_id', $video->vidio_id)
                                                           ->select('courses.*')
                                                           ->first();
                                                $activity->course = $course;
                                            }

                                            return $activity;
                                        });

        // Learning time statistics
        $userActivities = RiwayatTonton::where('users_id', $userId)->get();
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
