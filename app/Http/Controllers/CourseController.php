<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseSection;
use App\Models\CourseVideo;
use App\Models\QuickReview;
use App\Models\UserCourseProgress;
use App\Models\UserVideoProgress;
use App\Models\QuizResult;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{    public function __construct()
    {
        // Only require auth for specific routes
        $this->middleware('auth')->except(['index', 'show', 'popular', 'latest', 'getByCategory']);
    }/**
     * Display all available courses
     */    public function index(Request $request)
    {        $query = Course::with(['kategori'])
            ->withCount(['videos as videos_count', 'userProgress as user_progress_count'])
            ->withSum('videos as total_durasi_menit', 'durasi_menit')
            ->active();

        // If user is authenticated, load their progress for each course
        if (Auth::check()) {
            $query->with(['userProgress' => function($q) {
                $q->where('user_id', Auth::id());
            }]);
        }

        // Filter by category
        if ($request->filled('kategori_id')) {
            $query->where('kategori_kategori_id', $request->kategori_id);
        }

        // Filter by level
        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        // Search by name
        if ($request->filled('search')) {
            $query->where('nama_course', 'like', '%' . $request->search . '%');
        }

        // Sorting
        $sort = $request->get('sort', 'created_at');
        $order = $request->get('order', 'desc');

        if ($sort === 'popular') {
            $query->orderByDesc('user_progress_count');
        } else {
            $query->orderBy($sort, $order);
        }

        // Check if this is an API request
        if ($request->wantsJson() || $request->is('api/*')) {
            $limit = $request->get('limit', 12);
            $courses = $query->limit($limit)->get();

            return response()->json([
                'success' => true,
                'courses' => $courses
            ]);
        }        // Web response
        $courses = $query->orderBy('created_at', 'desc')->paginate(12);
        $kategoris = Kategori::all();
        $levels = ['pemula' => 'Pemula', 'menengah' => 'Menengah', 'lanjut' => 'Lanjut'];

        // Check if admin mode
        $adminMode = $request->get('admin_mode') && Auth::check() && Auth::user()->role === 'AD';

        return view('courses.index', compact('courses', 'kategoris', 'levels', 'adminMode'));
    }

    /**
     * Display course details
     */    public function show($id)
    {
        $course = Course::with([
            'kategori',
            'sections.videos.vidio',
            'sections.quizzes.questions', // Load quizzes for each section
            'quizzes', // Load all course quizzes
            'quizzes.questions' // Load quiz questions
        ])->active()->findOrFail($id);

        $userProgress = null;
        $quizResults = null;

        if (Auth::check()) {
            $userProgress = UserCourseProgress::where('user_id', Auth::id())
                ->where('course_id', $id)
                ->first();

            // Load video progress for each course video if user has progress
            if ($userProgress && $course->sections) {
                foreach ($course->sections as $section) {
                    if ($section->videos) {
                        foreach ($section->videos as $courseVideo) {
                            $videoProgress = UserVideoProgress::where('user_id', Auth::id())
                                ->where('vidio_vidio_id', $courseVideo->vidio_vidio_id)
                                ->where('course_id', $id)
                                ->first();
                            $courseVideo->video_progress = $videoProgress;
                        }
                    }
                }
            }

            // Load quiz results for the course
            $quizIds = collect();
            if ($course->quizzes) {
                $quizIds = $course->quizzes->pluck('quiz_id');
            }

            $quizResults = \App\Models\QuizResult::with(['quiz', 'answerDetails'])
                ->whereIn('quiz_id', $quizIds)
                ->where('users_id', Auth::id())
                ->get();

            // Calculate overall quiz stats
            $totalQuizzes = $course->quizzes ? $course->quizzes->count() : 0;
            $completedQuizzes = $quizResults->count();
            $averageScore = $quizResults->avg('nilai_total') ?? 0;
            $totalCorrect = $quizResults->sum('jumlah_benar');
            $totalWrong = $quizResults->sum('jumlah_salah');

            $quizStats = [
                'total_quizzes' => $totalQuizzes,
                'completed_quizzes' => $completedQuizzes,
                'completion_rate' => $totalQuizzes > 0 ? round(($completedQuizzes / $totalQuizzes) * 100, 2) : 0,
                'average_score' => round($averageScore, 2),
                'total_correct' => $totalCorrect,
                'total_wrong' => $totalWrong,
            ];
        }

        return view('courses.show', compact('course', 'userProgress', 'quizResults', 'quizStats'));
    }

    /**
     * Start or continue a course
     */
    public function start($id)
    {
        $course = Course::active()->findOrFail($id);
        $userId = Auth::id();

        // Get or create user progress
        $userProgress = UserCourseProgress::firstOrCreate([
            'user_id' => $userId,
            'course_id' => $id,
        ], [
            'total_videos' => $course->videos()->count(),
            'status' => 'not_started',
        ]);

        // Start the course if not started
        $userProgress->startCourse();

        // Get first video
        $firstSection = $course->sections()
            ->orderBy('urutan_section')
            ->first();

        $firstVideo = null;
        if ($firstSection) {
            $firstVideo = $firstSection->videos()
                ->orderBy('urutan_video')
                ->first();
        }

        if ($firstVideo) {
            return redirect()->route('courses.video', [
                'courseId' => $id,
                'videoId' => $firstVideo->course_video_id
            ])->with('success', 'Selamat! Anda telah memulai course ini.');
        }

        return redirect()->route('courses.show', $id)
            ->with('error', 'Course ini belum memiliki video untuk dipelajari.');
    }

    /**
     * Display course video
     */
    public function video($courseId, $videoId)
    {
        $course = Course::with([
            'sections.videos.vidio',
            'sections.quizzes.questions', // Load quizzes for each section
            'quizzes.questions' // Load quizzes with questions
        ])->active()->findOrFail($courseId);

        $courseVideo = CourseVideo::with(['vidio', 'section'])
            ->where('course_id', $courseId)
            ->where('course_video_id', $videoId)
            ->firstOrFail();

        $userId = Auth::id();

        // Get or create user progress for this course
        $userProgress = UserCourseProgress::firstOrCreate([
            'user_id' => $userId,
            'course_id' => $courseId,
        ], [
            'total_videos' => $course->videos()->count(),
            'status' => 'not_started',
        ]);

        // Start course if not started
        $userProgress->startCourse();

        // Get or create video progress
        $videoProgress = UserVideoProgress::getOrCreateProgress(
            $userId,
            $courseVideo->vidio_vidio_id,
            $courseId
        );

        $videoProgress->startWatching();

        // Get navigation info
        $navigation = $this->getVideoNavigation($courseVideo);

        // Get quick reviews for this video
        $quickReviews = QuickReview::where('course_id', $courseId)
            ->where('vidio_vidio_id', $courseVideo->vidio_vidio_id)
            ->where('tipe_review', 'setelah_video')
            ->active()
            ->orderBy('urutan_review')
            ->get();

        return view('courses.video', compact(
            'course',
            'courseVideo',
            'userProgress',
            'videoProgress',
            'navigation',
            'quickReviews'
        ));
    }

    /**
     * Mark video as completed
     */
    public function completeVideo(Request $request, $courseId, $videoId)
    {
        $courseVideo = CourseVideo::where('course_id', $courseId)
            ->where('course_video_id', $videoId)
            ->firstOrFail();

        $userId = Auth::id();

        // Get or create video progress
        $videoProgress = UserVideoProgress::firstOrCreate(
            [
                'user_id' => $userId,
                'vidio_vidio_id' => $courseVideo->vidio_vidio_id,
                'course_id' => $courseId
            ],
            [
                'is_completed' => false,
                'watch_time_seconds' => 0,
                'completion_percentage' => 0,
                'total_duration_seconds' => $courseVideo->durasi_menit * 60
            ]
        );

        // Get completion status from request
        $isCompleted = $request->input('is_completed', true);

        if ($isCompleted) {
            $videoProgress->markAsCompleted();
        } else {
            $videoProgress->is_completed = false;
            $videoProgress->completion_percentage = 0;
            $videoProgress->completed_at = null;
            $videoProgress->save();
        }

        // Update course progress
        $this->updateCourseProgress($userId, $courseId);

        // Get updated progress percentage
        $userProgress = UserCourseProgress::where('user_id', $userId)
            ->where('course_id', $courseId)
            ->first();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $isCompleted ? 'Video berhasil diselesaikan!' : 'Video berhasil ditandai belum selesai!',
                'is_completed' => $videoProgress->is_completed,
                'progress_percentage' => $userProgress ? $userProgress->progress_percentage : 0
            ]);
        }

        return redirect()->back()->with('success', $isCompleted ? 'Video berhasil diselesaikan!' : 'Video berhasil ditandai belum selesai!');
    }

    /**
     * Update video watch time
     */
    public function updateWatchTime(Request $request, $courseId, $videoId)
    {
        $request->validate([
            'duration' => 'required|integer|min:0', // in minutes
            'current_time' => 'nullable|integer|min:0', // in seconds
            'video_duration' => 'nullable|integer|min:0', // in seconds
            'source' => 'nullable|string|in:youtube,local',
        ]);

        // Find the video by vidio_id (not course_video_id)
        $courseVideo = CourseVideo::where('course_id', $courseId)
            ->where('vidio_vidio_id', $videoId)
            ->firstOrFail();

        $userId = Auth::id();

        // Get or create video progress
        $videoProgress = UserVideoProgress::firstOrCreate(
            [
                'user_id' => $userId,
                'vidio_vidio_id' => $videoId,
                'course_id' => $courseId
            ],
            [
                'watch_time_seconds' => 0,
                'is_completed' => false,
                'total_duration_seconds' => $request->video_duration ?? ($courseVideo->durasi_menit * 60),
                'completion_percentage' => 0
            ]
        );

        // Update watched duration (convert minutes to seconds)
        $additionalMinutes = $request->duration;
        $additionalSeconds = $additionalMinutes * 60;
        $videoProgress->watch_time_seconds = max($videoProgress->watch_time_seconds, $additionalSeconds);

        // Update total duration if provided
        if ($request->filled('video_duration')) {
            $videoProgress->total_duration_seconds = $request->video_duration;
        }

        // Calculate completion percentage
        if ($videoProgress->total_duration_seconds > 0) {
            $completionPercentage = min(100, ($videoProgress->watch_time_seconds / $videoProgress->total_duration_seconds) * 100);
            $videoProgress->completion_percentage = $completionPercentage;

            // Auto-complete if watched more than 80%
            if ($completionPercentage >= 80) {
                $videoProgress->is_completed = true;
            }
        }

        $videoProgress->save();

        // Record watch history
        \App\Models\RiwayatTonton::create([
            'id_pengguna' => $userId,
            'current_video_id' => $videoId,
            'course_id' => $courseId,
            'waktu_ditonton' => now(),
            'video_position' => $request->current_time ?? 0,
            'persentase_tonton' => $videoProgress->completion_percentage ?? 0,
        ]);

        // Update course progress
        $this->updateCourseProgress($userId, $courseId);

        return response()->json([
            'success' => true,
            'completion_percentage' => $videoProgress->completion_percentage,
            'is_completed' => $videoProgress->is_completed,
            'watch_time_seconds' => $videoProgress->watch_time_seconds,
            'message' => 'Watch time updated successfully'
        ]);
    }

    /**
     * Update course progress based on video completions and quiz completions
     */
    private function updateCourseProgress($userId, $courseId)
    {
        $course = Course::with(['sections.videos', 'quizzes'])->findOrFail($courseId);

        // Check if course has sections before accessing videos
        if (!$course->sections || $course->sections->isEmpty()) {
            return;
        }

        $totalVideos = $course->sections->flatMap->videos->count();

        // Video progress calculation
        $videoProgress = 0;
        if ($totalVideos > 0) {
            $completedVideos = UserVideoProgress::where('user_id', $userId)
                ->where('course_id', $courseId)
                ->where('is_completed', true)
                ->count();

            $videoProgress = ($completedVideos / $totalVideos) * 100;
        }

        // Quiz progress calculation
        $quizProgress = 0;
        $totalQuizzes = $course->quizzes->count();
        if ($totalQuizzes > 0) {
            $completedQuizzes = QuizResult::where('users_id', $userId)
                ->whereIn('quiz_id', $course->quizzes->pluck('quiz_id'))
                ->distinct('quiz_id')
                ->count();

            $quizProgress = ($completedQuizzes / $totalQuizzes) * 100;
        }

        // Calculate overall progress as the average of video and quiz progress
        $overallProgress = 0;
        $divisor = 0;

        if ($totalVideos > 0) {
            $overallProgress += $videoProgress;
            $divisor++;
        }

        if ($totalQuizzes > 0) {
            $overallProgress += $quizProgress;
            $divisor++;
        }

        $completionPercentage = $divisor > 0 ? $overallProgress / $divisor : 0;

        $userProgress = UserCourseProgress::firstOrCreate(
            ['user_id' => $userId, 'course_id' => $courseId],
            ['status' => 'in_progress', 'progress_percentage' => 0]
        );

        $userProgress->progress_percentage = $completionPercentage;

        if ($completionPercentage >= 100) {
            $userProgress->status = 'completed';
            $userProgress->completed_at = now();
        } elseif ($completionPercentage > 0) {
            $userProgress->status = 'in_progress';
        }

        $userProgress->save();
    }

    /**
     * Display course progress
     */
    public function progress($id)
    {
        $course = Course::with([
            'sections.videos.vidio'
        ])->findOrFail($id);

        $userId = Auth::id();

        $userProgress = UserCourseProgress::where('user_id', $userId)
            ->where('course_id', $id)
            ->first();

        if (!$userProgress) {
            return redirect()->route('courses.show', $id)
                ->with('info', 'Anda belum memulai course ini.');
        }

        // Get video progress for each video
        $videoProgressMap = UserVideoProgress::where('user_id', $userId)
            ->where('course_id', $id)
            ->get()
            ->keyBy('vidio_vidio_id');

        return view('courses.progress', compact('course', 'userProgress', 'videoProgressMap'));
    }

    /**
     * Get video navigation (previous/next)
     */
    private function getVideoNavigation($courseVideo)
    {
        $navigation = [
            'previous' => null,
            'next' => null,
        ];

        // Check if section exists
        if (!$courseVideo->section) {
            return $navigation;
        }

        // Try to find next video in same section
        $nextVideo = CourseVideo::where('section_id', $courseVideo->section_id)
            ->where('urutan_video', '>', $courseVideo->urutan_video)
            ->orderBy('urutan_video')
            ->first();

        // If no next video in section, try first video of next section
        if (!$nextVideo) {
            $nextSection = CourseSection::where('course_id', $courseVideo->course_id)
                ->where('urutan_section', '>', $courseVideo->section->urutan_section)
                ->orderBy('urutan_section')
                ->first();

            if ($nextSection) {
                $nextVideo = $nextSection->videos()->orderBy('urutan_video')->first();
            }
        }

        // Try to find previous video in same section
        $previousVideo = CourseVideo::where('section_id', $courseVideo->section_id)
            ->where('urutan_video', '<', $courseVideo->urutan_video)
            ->orderBy('urutan_video', 'desc')
            ->first();

        // If no previous video in section, try last video of previous section
        if (!$previousVideo) {
            $previousSection = CourseSection::where('course_id', $courseVideo->course_id)
                ->where('urutan_section', '<', $courseVideo->section->urutan_section)
                ->orderBy('urutan_section', 'desc')
                ->first();

            if ($previousSection) {
                $previousVideo = $previousSection->videos()->orderBy('urutan_video', 'desc')->first();
            }
        }

        $navigation['next'] = $nextVideo;
        $navigation['previous'] = $previousVideo;

        return $navigation;
    }

    /**
     * Display quick review
     */
    public function quickReview($courseId, $reviewId)
    {
        $course = Course::findOrFail($courseId);
        $review = QuickReview::where('course_id', $courseId)
            ->where('review_id', $reviewId)
            ->active()
            ->firstOrFail();

        return view('courses.quick-review', compact('course', 'review'));
    }

    /**
     * My courses - courses that user has started
     */
    public function myCourses()
    {
        $userId = Auth::id();

        $courses = UserCourseProgress::with(['course.kategori'])
            ->where('user_id', $userId)
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        return view('courses.my-courses', compact('courses'));
    }

    /**
     * Get popular courses (for API)
     */    public function popular(Request $request)
    {
        $limit = $request->get('limit', 6);
          $courses = Course::with(['kategori'])
            ->withCount(['userProgress as user_progress_count', 'videos as videos_count'])
            ->withSum('videos as total_durasi_menit', 'durasi_menit')
            ->active()
            ->orderByDesc('user_progress_count')
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'courses' => $courses
        ]);
    }

    /**
     * Get latest courses (for API)
     */    public function latest(Request $request)
    {
        $limit = $request->get('limit', 6);
          $courses = Course::with(['kategori'])
            ->withCount(['userProgress as user_progress_count', 'videos as videos_count'])
            ->withSum('videos as total_durasi_menit', 'durasi_menit')
            ->active()
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'courses' => $courses
        ]);
    }

    /**
     * Get courses by category (for API)
     */    public function getByCategory($kategoriId, Request $request)
    {
        $limit = $request->get('limit', 12);
          $courses = Course::with(['kategori'])
            ->withCount(['userProgress as user_progress_count', 'videos as videos_count'])
            ->withSum('videos as total_durasi_menit', 'durasi_menit')
            ->where('kategori_kategori_id', $kategoriId)
            ->active()
            ->orderByDesc('user_progress_count')
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'courses' => $courses
        ]);
    }

    /**
     * Get quiz report for a specific course and user
     */
    public function getQuizReport($courseId)
    {
        try {
            $course = Course::findOrFail($courseId);
            $userId = Auth::id();

            // Get all quiz results for this course and user
            $quizResults = \App\Models\QuizResult::whereHas('quiz', function($query) use ($courseId) {
                $query->where('course_id', $courseId);
            })
            ->where('users_id', $userId)
            ->with(['quiz', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();

            if ($quizResults->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Belum ada hasil quiz untuk course ini'
                ]);
            }

            // Calculate summary statistics
            $totalAttempts = $quizResults->count();
            $averageScore = round($quizResults->avg('nilai_total'), 1);
            $passedCount = $quizResults->where('nilai_total', '>=', 60)->count();
            $highestScore = $quizResults->max('nilai_total');
            $lowestScore = $quizResults->min('nilai_total');

            // Format quiz results for display
            $formattedResults = $quizResults->map(function($result) {
                return [
                    'quiz_title' => $result->quiz->judul_quiz,
                    'nilai_total' => $result->nilai_total,
                    'grade' => $result->getGrade(),
                    'jumlah_benar' => $result->jumlah_benar,
                    'jumlah_salah' => $result->jumlah_salah,
                    'total_soal' => $result->total_soal,
                    'duration' => $result->getDuration(),
                    'date' => $result->created_at->format('d/m/Y H:i'),
                    'passed' => $result->isPassed()
                ];
            });

            $summary = [
                'total_attempts' => $totalAttempts,
                'average_score' => $averageScore,
                'passed_count' => $passedCount,
                'highest_score' => $highestScore,
                'lowest_score' => $lowestScore,
                'pass_rate' => $totalAttempts > 0 ? round(($passedCount / $totalAttempts) * 100, 1) : 0
            ];

            return response()->json([
                'success' => true,
                'course_name' => $course->nama_course,
                'summary' => $summary,
                'quiz_results' => $formattedResults
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memuat laporan quiz',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
