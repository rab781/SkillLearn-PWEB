<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseSection;
use App\Models\CourseVideo;
use App\Models\QuickReview;
use App\Models\UserCourseProgress;
use App\Models\UserVideoProgress;
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
            'quickReviews' => function($query) {
                $query->active()->orderBy('urutan_review');
            }
        ])->active()->findOrFail($id);

        $userProgress = null;
        if (Auth::check()) {
            $userProgress = UserCourseProgress::where('user_id', Auth::id())
                ->where('course_id', $id)
                ->first();

            // Load video progress for each course video if user has progress
            if ($userProgress) {
                foreach ($course->sections as $section) {
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

        return view('courses.show', compact('course', 'userProgress'));
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
        $firstVideo = $course->sections()
            ->orderBy('urutan_section')
            ->first()
            ->videos()
            ->orderBy('urutan_video')
            ->first();

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
        $course = Course::active()->findOrFail($courseId);
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

        $videoProgress = UserVideoProgress::where('user_id', $userId)
            ->where('vidio_vidio_id', $courseVideo->vidio_vidio_id)
            ->where('course_id', $courseId)
            ->first();

        if ($videoProgress) {
            $videoProgress->markAsCompleted();
        }

        // Check for section reviews
        $sectionReviews = QuickReview::where('course_id', $courseId)
            ->where('section_id', $courseVideo->section_id)
            ->where('tipe_review', 'setelah_section')
            ->active()
            ->get();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Video berhasil diselesaikan!',
                'has_section_reviews' => $sectionReviews->count() > 0,
                'section_reviews' => $sectionReviews
            ]);
        }

        return redirect()->back()->with('success', 'Video berhasil diselesaikan!');
    }

    /**
     * Update video watch time
     */
    public function updateWatchTime(Request $request, $courseId, $videoId)
    {
        $request->validate([
            'watch_time' => 'required|integer|min:0',
            'total_duration' => 'nullable|integer|min:0',
        ]);

        $courseVideo = CourseVideo::where('course_id', $courseId)
            ->where('course_video_id', $videoId)
            ->firstOrFail();

        $userId = Auth::id();

        $videoProgress = UserVideoProgress::getOrCreateProgress(
            $userId,
            $courseVideo->vidio_vidio_id,
            $courseId
        );

        if ($request->filled('total_duration')) {
            $videoProgress->total_duration_seconds = $request->total_duration;
        }

        $videoProgress->updateWatchTime($request->watch_time);

        return response()->json([
            'success' => true,
            'completion_percentage' => $videoProgress->completion_percentage,
            'is_completed' => $videoProgress->is_completed
        ]);
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
}
