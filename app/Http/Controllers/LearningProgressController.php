<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use App\Models\UserCourseProgress;
use App\Models\UserVideoProgress;
use App\Models\QuizResult;
use App\Models\CourseSection;
use App\Models\Vidio;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LearningProgressController extends Controller
{
    /**
     * Get user's learning dashboard with progress
     */
    public function dashboard()
    {
        $user = Auth::user();

        // Get enrolled courses with progress
        $enrolledCourses = UserCourseProgress::with(['course.sections.videos', 'course.quizzes'])
            ->where('user_id', $user->user_id)
            ->get()
            ->map(function ($progress) {
                $course = $progress->course;
                $totalVideos = $course->sections->sum(function ($section) {
                    return $section->videos->count();
                });

                $completedVideos = UserVideoProgress::where('user_id', $progress->user_id)
                    ->whereIn('video_id', $course->sections->flatMap->videos->pluck('video_id'))
                    ->where('is_completed', true)
                    ->count();

                $completedQuizzes = QuizResult::where('users_id', $progress->user_id)
                    ->whereIn('quiz_id', $course->quizzes->pluck('quiz_id'))
                    ->distinct('quiz_id')
                    ->count();

                $totalQuizzes = $course->quizzes->count();

                $videoProgress = $totalVideos > 0 ? ($completedVideos / $totalVideos) * 100 : 0;
                $quizProgress = $totalQuizzes > 0 ? ($completedQuizzes / $totalQuizzes) * 100 : 0;
                $overallProgress = ($videoProgress + $quizProgress) / 2;

                return [
                    'course' => $course,
                    'progress' => $progress,
                    'total_videos' => $totalVideos,
                    'completed_videos' => $completedVideos,
                    'total_quizzes' => $totalQuizzes,
                    'completed_quizzes' => $completedQuizzes,
                    'video_progress' => round($videoProgress, 1),
                    'quiz_progress' => round($quizProgress, 1),
                    'overall_progress' => round($overallProgress, 1)
                ];
            });

        // Get recent learning activity
        $recentActivity = $this->getRecentActivity($user->user_id);

        // Get quiz performance stats
        $quizStats = $this->getQuizStats($user->user_id);

        return view('learning.dashboard', compact('enrolledCourses', 'recentActivity', 'quizStats'));
    }

    /**
     * Get course syllabus with progress
     */
    public function courseSyllabus($courseId)
    {
        $user = Auth::user();
        $course = Course::with(['sections.videos', 'quizzes'])->findOrFail($courseId);

        // Check if user is enrolled
        $userProgress = UserCourseProgress::where('user_id', $user->user_id)
            ->where('course_id', $courseId)
            ->first();

        if (!$userProgress) {
            return response()->json(['error' => 'User not enrolled in this course'], 403);
        }

        // Get detailed progress for each section
        $sectionsWithProgress = $course->sections->map(function ($section) use ($user) {
            $videos = $section->videos->map(function ($video) use ($user) {
                $progress = UserVideoProgress::where('user_id', $user->user_id)
                    ->where('video_id', $video->video_id)
                    ->first();

                return [
                    'video' => $video,
                    'is_completed' => $progress ? $progress->is_completed : false,
                    'watch_progress' => $progress ? $progress->progress_percentage : 0,
                    'last_position' => $progress ? $progress->last_position : 0
                ];
            });

            $completedVideos = $videos->where('is_completed', true)->count();
            $totalVideos = $videos->count();
            $sectionProgress = $totalVideos > 0 ? ($completedVideos / $totalVideos) * 100 : 0;

            return [
                'section' => $section,
                'videos' => $videos,
                'completed_videos' => $completedVideos,
                'total_videos' => $totalVideos,
                'progress_percentage' => round($sectionProgress, 1)
            ];
        });

        // Get quiz results
        $quizResults = QuizResult::where('users_id', $user->users_id)
            ->whereIn('quiz_id', $course->quizzes->pluck('quiz_id'))
            ->with(['quiz'])
            ->get()
            ->groupBy('quiz_id')
            ->map(function ($results) {
                return $results->sortByDesc('created_at')->first();
            });

        return response()->json([
            'course' => $course,
            'sections' => $sectionsWithProgress,
            'quiz_results' => $quizResults,
            'user_progress' => $userProgress
        ]);
    }

    /**
     * Get quiz reports for user
     */
    public function quizReports(Request $request)
    {
        $user = Auth::user();
        $courseId = $request->get('course_id');

        $query = QuizResult::with(['quiz.course'])
            ->where('user_id', $user->user_id);

        if ($courseId) {
            $query->whereHas('quiz', function ($q) use ($courseId) {
                $q->where('course_id', $courseId);
            });
        }

        $quizResults = $query->orderBy('created_at', 'desc')->get();

        // Group by quiz and get latest attempt
        $groupedResults = $quizResults->groupBy('quiz_id')->map(function ($results) {
            $latestResult = $results->first();
            $attempts = $results->count();
            $bestScore = $results->max('skor_akhir');
            $averageScore = $results->avg('skor_akhir');

            return [
                'quiz' => $latestResult->quiz,
                'latest_result' => $latestResult,
                'attempts' => $attempts,
                'best_score' => round($bestScore, 1),
                'average_score' => round($averageScore, 1),
                'all_attempts' => $results
            ];
        });

        // Calculate overall stats
        $totalQuizzes = $groupedResults->count();
        $passedQuizzes = $groupedResults->where('best_score', '>=', 70)->count();
        $overallAverage = $groupedResults->avg('best_score');

        $stats = [
            'total_quizzes' => $totalQuizzes,
            'passed_quizzes' => $passedQuizzes,
            'pass_rate' => $totalQuizzes > 0 ? round(($passedQuizzes / $totalQuizzes) * 100, 1) : 0,
            'overall_average' => round($overallAverage, 1)
        ];

        if ($request->expectsJson()) {
            return response()->json([
                'quiz_results' => $groupedResults->values(),
                'stats' => $stats
            ]);
        }

        return view('learning.quiz-reports', compact('groupedResults', 'stats'));
    }

    /**
     * Get recent learning activity
     */
    private function getRecentActivity($userId, $limit = 10)
    {
        $videoProgress = UserVideoProgress::with(['video.section.course'])
            ->where('user_id', $userId)
            ->where('updated_at', '>=', now()->subDays(7))
            ->orderBy('updated_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($progress) {
                return [
                    'type' => 'video_progress',
                    'title' => $progress->video->judul_video,
                    'course' => $progress->video->section->course->nama_course,
                    'progress' => $progress->progress_percentage,
                    'is_completed' => $progress->is_completed,
                    'timestamp' => $progress->updated_at
                ];
            });

        $quizResults = QuizResult::with(['quiz.course'])
            ->where('user_id', $userId)
            ->where('created_at', '>=', now()->subDays(7))
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($result) {
                return [
                    'type' => 'quiz_completed',
                    'title' => $result->quiz->judul_quiz,
                    'course' => $result->quiz->course->nama_course,
                    'score' => $result->skor_akhir,
                    'passed' => $result->skor_akhir >= 70,
                    'timestamp' => $result->created_at
                ];
            });

        return $videoProgress->concat($quizResults)
            ->sortByDesc('timestamp')
            ->take($limit)
            ->values();
    }

    /**
     * Get quiz performance statistics
     */
    private function getQuizStats($userId)
    {
        $allResults = QuizResult::where('users_id', $userId)->get();

        if ($allResults->isEmpty()) {
            return [
                'total_attempts' => 0,
                'unique_quizzes' => 0,
                'average_score' => 0,
                'best_score' => 0,
                'pass_rate' => 0
            ];
        }

        $uniqueQuizzes = $allResults->groupBy('quiz_id');
        $bestScores = $uniqueQuizzes->map(function ($results) {
            return $results->max('skor_akhir');
        });

        $passedQuizzes = $bestScores->where('*', '>=', 70)->count();

        return [
            'total_attempts' => $allResults->count(),
            'unique_quizzes' => $uniqueQuizzes->count(),
            'average_score' => round($allResults->avg('skor_akhir'), 1),
            'best_score' => round($allResults->max('skor_akhir'), 1),
            'pass_rate' => $uniqueQuizzes->count() > 0 ? round(($passedQuizzes / $uniqueQuizzes->count()) * 100, 1) : 0
        ];
    }
}
