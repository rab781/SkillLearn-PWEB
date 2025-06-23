<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Vidio;
use App\Models\Course;
use App\Models\Kategori;
use App\Models\Feedback;
use App\Models\Bookmark;
use App\Models\UserCourseProgress;
use App\Models\UserVideoProgress;
use App\Models\QuizResult;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    /**
     * Customer Dashboard
     */
    public function customerDashboard()
    {
        try {
            /** @var User $user */
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            // Get user statistics
            $bookmarksCount = Bookmark::where('users_id', $user->users_id)->count();
            $feedbacksCount = Feedback::where('users_id', $user->users_id)->count();

            // Get learning progress statistics
            $learningStats = $this->getLearningStats($user);

            // Get recent bookmarks with error handling
            $recentBookmarks = Bookmark::with(['course' => function($query) {
                    $query->with('kategori');
                }])
                ->where('users_id', $user->users_id)
                ->orderBy('created_at', 'desc')
                ->take(6)
                ->get()
                ->filter(function($bookmark) {
                    return $bookmark->course !== null; // Filter out bookmarks with deleted courses
                });

            // Get popular courses with error handling
            try {
                $popularCourses = Course::with('kategori')
                    ->where('is_active', true) // Use where instead of scope to prevent errors
                    ->whereNotNull('gambar_course')
                    ->whereNotNull('nama_course')
                    ->orderBy('created_at', 'desc')
                    ->take(6)
                    ->get();

                // Get latest courses as fallback if no popular courses
                if ($popularCourses->count() === 0) {
                    $popularCourses = Course::with('kategori')
                        ->where('is_active', true) // Use where instead of scope
                        ->whereNotNull('nama_course')
                        ->orderBy('created_at', 'desc')
                        ->take(6)
                        ->get();
                }
            } catch(\Exception $e) {
                Log::error('Error fetching popular courses: ' . $e->getMessage());
                $popularCourses = collect([]);
            }

            // Get categories with video count
            $categories = Kategori::withCount('vidios')
                ->having('vidios_count', '>', 0) // Only categories with videos
                ->orderBy('vidios_count', 'desc')
                ->take(8)
                ->get();

            // Add default categories if none exist
            if ($categories->count() === 0) {
                $categories = collect([
                    (object)[
                        'kategori_id' => 1,
                        'kategori' => 'Programming',
                        'vidios_count' => 0
                    ],
                    (object)[
                        'kategori_id' => 2,
                        'kategori' => 'Design',
                        'vidios_count' => 0
                    ],
                    (object)[
                        'kategori_id' => 3,
                        'kategori' => 'Business',
                        'vidios_count' => 0
                    ],
                    (object)[
                        'kategori_id' => 4,
                        'kategori' => 'Marketing',
                        'vidios_count' => 0
                    ]
                ]);
            }

            return response()->json([
                'success' => true,
                'user' => [
                    'id' => $user->users_id,
                    'name' => $user->nama_lengkap,
                    'email' => $user->email
                ],
                'stats' => [
                    'bookmarks_count' => $bookmarksCount,
                    'learning_progress' => $learningStats['overall_progress'],
                    'enrolled_courses' => $learningStats['enrolled_courses'],
                    'completed_videos' => $learningStats['completed_videos'],
                    'quiz_pass_rate' => $learningStats['quiz_pass_rate'],
                ],
                'recent_bookmarks' => $recentBookmarks->values(),
                'enrolled_courses' => $learningStats['courses_with_progress'],
                'recent_activity' => $learningStats['recent_activity'],
                'quiz_reports' => $learningStats['quiz_reports'],
                'popular_courses' => $popularCourses,
                'categories' => $categories
            ]);
        } catch (\Exception $e) {
            Log::error('Dashboard error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memuat dashboard',
                'stats' => [
                    'bookmarks_count' => 0,
                    'feedbacks_count' => 0,
                ],
                'recent_bookmarks' => [],
                'popular_courses' => [],
                'categories' => []
            ], 500);
        }
    }

    /**
     * Admin Dashboard
     */
    public function adminDashboard()
    {
        // Get overall statistics
        $totalUsers = User::where('role', 'CU')->count();
        $totalCourses = Course::count();
        $totalVideos = DB::table('course_videos')->count();
        $totalQuizzes = DB::table('quizzes')->count();
        $totalCategories = Kategori::count();

        // Get recent activities
        $recentFeedbacks = Feedback::with(['user', 'course'])
            ->orderBy('tanggal', 'desc')
            ->take(5)
            ->get();

        // Get most popular courses
        $popularCourses = Course::with(['kategori'])
            ->withCount(['userProgress', 'videos'])
            ->orderBy('user_progress_count', 'desc')
            ->take(10)
            ->get();

        // Get user registration stats (last 7 days)
        $userRegistrations = User::where('role', 'CU')
            ->where('created_at', '>=', now()->subDays(7))
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Get course enrollment stats (last 7 days)
        $courseEnrollments = DB::table('user_course_progress')
            ->where('created_at', '>=', now()->subDays(7))
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Get categories with course count
        $categoriesStats = Kategori::withCount('courses')
            ->orderBy('courses_count', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'stats' => [
                'total_users' => $totalUsers,
                'total_courses' => $totalCourses,
                'total_videos' => $totalVideos,
                'total_quizzes' => $totalQuizzes,
                'total_categories' => $totalCategories,
            ],
            'recent_feedbacks' => $recentFeedbacks,
            'popular_courses' => $popularCourses,
            'user_registrations' => $userRegistrations,
            'course_enrollments' => $courseEnrollments,
            'categories_stats' => $categoriesStats
        ]);
    }

    /**
     * Users Report (Admin only)
     */
    public function usersReport()
    {
        $users = User::where('role', 'CU')
            ->withCount(['feedbacks', 'bookmarks'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $userStats = [
            'total_users' => User::where('role', 'CU')->count(),
            'users_today' => User::where('role', 'CU')
                ->whereDate('created_at', today())
                ->count(),
            'users_this_week' => User::where('role', 'CU')
                ->where('created_at', '>=', now()->startOfWeek())
                ->count(),
            'users_this_month' => User::where('role', 'CU')
                ->where('created_at', '>=', now()->startOfMonth())
                ->count(),
        ];

        return response()->json([
            'success' => true,
            'users' => $users,
            'stats' => $userStats
        ]);
    }

    /**
     * Videos Report (Admin only)
     */
    public function videosReport()
    {
        $videos = Vidio::with('kategori')
            ->withCount(['feedbacks', 'bookmarks'])
            ->orderBy('jumlah_tayang', 'desc')
            ->paginate(20);

        $videoStats = [
            'total_videos' => Vidio::count(),
            'total_views' => Vidio::sum('jumlah_tayang'),
            'videos_today' => Vidio::whereDate('created_at', today())->count(),
            'videos_this_week' => Vidio::where('created_at', '>=', now()->startOfWeek())->count(),
            'videos_this_month' => Vidio::where('created_at', '>=', now()->startOfMonth())->count(),
            'most_viewed_video' => Vidio::orderBy('jumlah_tayang', 'desc')->first(),
        ];

        return response()->json([
            'success' => true,
            'videos' => $videos,
            'stats' => $videoStats
        ]);
    }

    /**
     * Customer Statistics for video page
     */
    public function customerStats()
    {
        /** @var User $user */
        $user = Auth::user();

        // Get customer specific stats
        $bookmarksCount = Bookmark::where('users_id', $user->users_id)->count(); // Fix: Bookmark uses users_id
        $feedbacksCount = Feedback::where('users_id', $user->users_id)->count(); // Fix: Feedback likely uses users_id

        // Calculate learning streak (simplified - count consecutive days with activity)
        $recentActivity = collect([]);

        // Get recent bookmarks dates
        $recentBookmarks = Bookmark::where('users_id', $user->users_id) // Fix: Bookmark uses users_id
            ->orderBy('created_at', 'desc')
            ->take(30)
            ->get()
            ->pluck('created_at')
            ->map(fn($date) => $date->format('Y-m-d'))
            ->unique();

        // Get recent feedback dates
        $recentFeedbacks = Feedback::where('users_id', $user->users_id) // Fix: Feedback likely uses users_id
            ->orderBy('created_at', 'desc')
            ->take(30)
            ->get()
            ->pluck('created_at')
            ->map(fn($date) => $date->format('Y-m-d'))
            ->unique();

        // Merge activity dates
        $activityDates = $recentBookmarks->merge($recentFeedbacks)->unique()->sort()->values();

        // Calculate streak
        $streak = 0;
        $today = now()->format('Y-m-d');
        $yesterday = now()->subDay()->format('Y-m-d');

        if ($activityDates->contains($today) || $activityDates->contains($yesterday)) {
            $streak = 1;
            $currentDate = $activityDates->contains($today) ? $today : $yesterday;

            for ($i = 1; $i < $activityDates->count(); $i++) {
                $checkDate = now()->subDays($i)->format('Y-m-d');
                if ($activityDates->contains($checkDate)) {
                    $streak++;
                } else {
                    break;
                }
            }
        }

        // Get unique videos interacted with (approximation of videos watched)
        $videosWatched = Bookmark::where('users_id', $user->users_id) // Fix: Bookmark uses users_id
            ->distinct('vidio_vidio_id')
            ->count('vidio_vidio_id');

        $feedbackVideos = Feedback::where('users_id', $user->users_id) // Fix: Feedback likely uses users_id
            ->distinct('vidio_vidio_id')
            ->count('vidio_vidio_id');

        $totalVideosWatched = max($videosWatched, $feedbackVideos);

        return response()->json([
            'success' => true,
            'stats' => [
                'videos_watched' => $totalVideosWatched,
                'learning_streak' => $streak,
                'total_bookmarks' => $bookmarksCount,
                'total_feedbacks' => $feedbacksCount
            ]
        ]);
    }

    /**
     * Get comprehensive learning statistics for a user
     */
    private function getLearningStats($user)
    {
        // Get enrolled courses with progress
        $enrolledCourses = UserCourseProgress::with(['course.sections.videos', 'course.quizzes'])
            ->where('user_id', $user->users_id) // UserCourseProgress uses user_id field
            ->get();

        $coursesWithProgress = $enrolledCourses->map(function ($progress) use ($user) {
            $course = $progress->course;

            // Calculate video progress
            $totalVideos = $course->sections->sum(function ($section) {
                return $section->videos->count();
            });

            // Get completed videos - fix field name from video_id to vidio_vidio_id
            $completedVideos = UserVideoProgress::where('user_id', $user->users_id)
                ->whereIn('vidio_vidio_id', $course->sections->flatMap->videos->pluck('vidio_vidio_id'))
                ->where('is_completed', true)
                ->count();

            // Calculate quiz progress
            $totalQuizzes = $course->quizzes->count();
            $completedQuizzes = QuizResult::where('users_id', $user->users_id) // Fix: QuizResult uses users_id
                ->whereIn('quiz_id', $course->quizzes->pluck('quiz_id'))
                ->distinct('quiz_id')
                ->count();

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
                'overall_progress' => round($overallProgress, 1),
                'is_completed' => $overallProgress >= 90
            ];
        });

        // Get recent learning activity
        $recentActivity = $this->getRecentLearningActivity($user->users_id);

        // Get quiz performance reports
        $quizReports = $this->getQuizReports($user->users_id);

        // Calculate overall statistics
        $totalEnrolledCourses = $enrolledCourses->count();
        $completedCourses = $coursesWithProgress->where('is_completed', true)->count();
        $totalCompletedVideos = UserVideoProgress::where('user_id', $user->users_id)
            ->where('is_completed', true)
            ->count();

        // Calculate quiz pass rate
        $allQuizResults = QuizResult::where('users_id', $user->users_id)->get(); // Fix: QuizResult uses users_id
        $passedQuizzes = $allQuizResults->where('nilai_total', '>=', 70)->count(); // Fix: field name is nilai_total not skor_akhir
        $totalQuizAttempts = $allQuizResults->count();
        $quizPassRate = $totalQuizAttempts > 0 ? ($passedQuizzes / $totalQuizAttempts) * 100 : 0;

        // Calculate overall learning progress
        $overallProgress = $coursesWithProgress->avg('overall_progress') ?? 0;

        return [
            'enrolled_courses' => $totalEnrolledCourses,
            'completed_courses' => $completedCourses,
            'completed_videos' => $totalCompletedVideos,
            'overall_progress' => round($overallProgress, 1),
            'quiz_pass_rate' => round($quizPassRate, 1),
            'courses_with_progress' => $coursesWithProgress->values(),
            'recent_activity' => $recentActivity,
            'quiz_reports' => $quizReports
        ];
    }

    /**
     * Get recent learning activity for dashboard
     */
    private function getRecentLearningActivity($userId, $limit = 10)
    {
        $activities = collect();

        // Get recent video progress
        $videoProgress = UserVideoProgress::with(['vidio'])
            ->where('user_id', $userId)
            ->where('updated_at', '>=', now()->subDays(7))
            ->orderBy('updated_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($progress) {
                return [
                    'type' => 'video_progress',
                    'title' => $progress->vidio->judul_vidio ?? 'Video',
                    'course' => null, // We don't have direct access to course here
                    'progress' => $progress->progress_percentage,
                    'is_completed' => $progress->is_completed,
                    'timestamp' => $progress->updated_at,
                    'icon' => '🎥'
                ];
            });

        // Get recent quiz results
        $quizResults = QuizResult::with(['quiz.course'])
            ->where('users_id', $userId) // Fix: QuizResult uses users_id
            ->where('created_at', '>=', now()->subDays(7))
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($result) {
                return [
                    'type' => 'quiz_completed',
                    'title' => $result->quiz->judul_quiz ?? 'Quiz',
                    'course' => $result->quiz->course->nama_course ?? 'Course',
                    'score' => $result->nilai_total, // Fix: field name is nilai_total not skor_akhir
                    'passed' => $result->nilai_total >= 70, // Fix: field name is nilai_total
                    'timestamp' => $result->created_at,
                    'icon' => $result->nilai_total >= 70 ? '✅' : '❌' // Fix: field name is nilai_total
                ];
            });

        return $videoProgress->concat($quizResults)
            ->sortByDesc('timestamp')
            ->take($limit)
            ->values();
    }

    /**
     * Get quiz performance reports
     */
    private function getQuizReports($userId)
    {
        $quizResults = QuizResult::with(['quiz.course'])
            ->where('users_id', $userId) // Fix: QuizResult uses users_id
            ->orderBy('created_at', 'desc')
            ->get();

        // Group by quiz and get statistics
        $groupedResults = $quizResults->groupBy('quiz_id')->map(function ($results) {
            $latestResult = $results->first();
            $attempts = $results->count();
            $bestScore = $results->max('nilai_total'); // Fix: field name is nilai_total
            $averageScore = $results->avg('nilai_total'); // Fix: field name is nilai_total

            return [
                'quiz' => $latestResult->quiz,
                'latest_result' => $latestResult,
                'attempts' => $attempts,
                'best_score' => round($bestScore, 1),
                'average_score' => round($averageScore, 1),
                'passed' => $bestScore >= 70,
                'improvement' => $attempts > 1 ? round($latestResult->nilai_total - $results->last()->nilai_total, 1) : 0 // Fix: field name is nilai_total
            ];
        });

        return $groupedResults->values()->take(5); // Show top 5 recent quiz reports
    }

    /**
     * Get course syllabus with progress (AJAX endpoint)
     */
    public function getCourseSyllabus($courseId)
    {
        try {
            $user = Auth::user();
            $course = Course::with(['sections.videos', 'quizzes'])->findOrFail($courseId);            // Check if user is enrolled
            $userProgress = UserCourseProgress::where('user_id', $user->users_id) // Fix: UserCourseProgress uses user_id
                ->where('course_id', $courseId)
                ->first();

            if (!$userProgress) {
                return response()->json(['error' => 'Anda belum terdaftar di course ini'], 403);
            }

            // Get detailed sections with progress
            $sectionsWithProgress = $course->sections->map(function ($section) use ($user) {
                $videos = $section->videos->map(function ($video) use ($user) {
                    $progress = UserVideoProgress::where('user_id', $user->users_id) // Fix: UserVideoProgress uses user_id
                        ->where('vidio_vidio_id', $video->vidio_vidio_id) // Fixed: use vidio_vidio_id instead of video_id
                        ->first();

                    return [
                        'video' => $video,
                        'is_completed' => $progress ? $progress->is_completed : false,
                        'watch_progress' => $progress ? $progress->progress_percentage : 0,
                        'last_position' => $progress ? $progress->last_position : 0,
                        'duration' => $video->durasi ?? '0:00'
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
                    'progress_percentage' => round($sectionProgress, 1),
                    'is_completed' => $sectionProgress >= 100
                ];
            });

            // Get quiz results for this course
            $quizResults = QuizResult::where('users_id', $user->users_id) // Fix: QuizResult uses users_id
                ->whereIn('quiz_id', $course->quizzes->pluck('quiz_id'))
                ->with(['quiz'])
                ->get()
                ->groupBy('quiz_id')
                ->map(function ($results) {
                    return $results->sortByDesc('created_at')->first();
                });

            return response()->json([
                'success' => true,
                'course' => $course,
                'sections' => $sectionsWithProgress,
                'quiz_results' => $quizResults->values(),
                'user_progress' => $userProgress
            ]);

        } catch (\Exception $e) {
            Log::error('Course syllabus error: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal memuat silabus course'], 500);
        }
    }

    /**
     * Get quiz reports (AJAX endpoint)
     */
    public function getQuizReportsAjax(Request $request)
    {
        try {
            $user = Auth::user();
            $courseId = $request->get('course_id');            $query = QuizResult::with(['quiz.course'])
                ->where('users_id', $user->users_id); // Fix: QuizResult uses users_id

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
                $bestScore = $results->max('nilai_total'); // Fix: field name is nilai_total
                $averageScore = $results->avg('nilai_total'); // Fix: field name is nilai_total

                return [
                    'quiz' => $latestResult->quiz,
                    'latest_result' => $latestResult,
                    'attempts' => $attempts,
                    'best_score' => round($bestScore, 1),
                    'average_score' => round($averageScore, 1),
                    'passed' => $bestScore >= 70,
                    'all_attempts' => $results->map(function ($result) {
                        return [
                            'score' => $result->nilai_total, // Fix: field name is nilai_total
                            'date' => $result->created_at->format('d M Y H:i'),
                            'passed' => $result->nilai_total >= 70 // Fix: field name is nilai_total
                        ];
                    })
                ];
            });

            // Calculate overall stats
            $totalQuizzes = $groupedResults->count();
            $passedQuizzes = $groupedResults->where('passed', true)->count();
            $overallAverage = $groupedResults->avg('best_score');

            $stats = [
                'total_quizzes' => $totalQuizzes,
                'passed_quizzes' => $passedQuizzes,
                'failed_quizzes' => $totalQuizzes - $passedQuizzes,
                'pass_rate' => $totalQuizzes > 0 ? round(($passedQuizzes / $totalQuizzes) * 100, 1) : 0,
                'overall_average' => round($overallAverage, 1)
            ];

            return response()->json([
                'success' => true,
                'quiz_results' => $groupedResults->values(),
                'stats' => $stats
            ]);

        } catch (\Exception $e) {
            Log::error('Quiz reports error: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal memuat laporan quiz'], 500);
        }
    }
}
