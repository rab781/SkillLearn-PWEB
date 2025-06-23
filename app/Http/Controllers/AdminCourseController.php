<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseSection;
use App\Models\CourseVideo;
use App\Models\CourseQuiz;
use App\Models\QuickReview;
use App\Models\Kategori;
use App\Models\Vidio;
use App\Models\User;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class AdminCourseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // Add admin middleware here if you have role-based access control
    }

    /**
     * Display admin dashboard with statistics and recent data
     */
    public function dashboard()
    {
        // Get basic statistics
        $stats = [
            'total_users' => User::where('role', '!=', 'admin')->count(),
            'total_courses' => Course::count(),
            'total_videos' => Vidio::count(),
            'total_categories' => Kategori::count(),
            'total_feedbacks' => Feedback::count(),
        ];

        // Get recent feedbacks (last 5)
        $recentFeedbacks = Feedback::with('user')
            ->latest()
            ->take(5)
            ->get();

        // Get popular courses (courses with most enrollments/progress)
        $popularCourses = Course::with(['kategori'])
            ->withCount(['userProgress as enrollments'])
            ->orderBy('enrollments', 'desc')
            ->take(5)
            ->get();

        return view('dashboard.admin', compact('stats', 'recentFeedbacks', 'popularCourses'));
    }

    /**
     * Display courses management page
     */
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'courses');
        $courses = Course::with(['kategori', 'sections'])
            ->withCount(['videos', 'userProgress']);

        if ($tab === 'quizzes') {
            // If the "quizzes" tab is selected, add relationship to quizzes and only return courses with quizzes
            $courses = $courses->with('quizzes')->has('quizzes');
        }

        $courses = $courses->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.courses.index', compact('courses', 'tab'));
    }

    /**
     * Show the form for creating a new course
     */
    public function create()
    {
        $kategoris = Kategori::all();
        return view('admin.courses.create', compact('kategoris'));
    }

    /**
     * Store a newly created course
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_course' => 'required|string|max:150',
            'deskripsi_course' => 'required|string',
            'level' => 'required|in:pemula,menengah,lanjut',
            'kategori_kategori_id' => 'required|exists:kategori,kategori_id',
            'gambar_course' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();

        // Handle image upload
        if ($request->hasFile('gambar_course')) {
            $file = $request->file('gambar_course');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $filename);
            $data['gambar_course'] = 'uploads/' . $filename;
        }

        $course = Course::create($data);

        return redirect()->route('admin.courses.show', $course->course_id)
            ->with('success', 'Course berhasil dibuat!');
    }

    /**
     * Display course details and management
     */
    public function show($id)
    {
        $course = Course::with([
            'kategori',
            'sections.videos.vidio',
            'quickReviews',
            'userProgress',
            'courseQuizzes.quiz'
        ])->findOrFail($id);

        $availableVideos = Vidio::whereNotIn('vidio_id',
            $course->videos()->pluck('vidio_vidio_id')
        )->get();

        return view('admin.courses.show', compact('course', 'availableVideos'));
    }

    /**
     * Show the form for editing course
     */
    public function edit($id)
    {
        $course = Course::findOrFail($id);
        $kategoris = Kategori::all();
        return view('admin.courses.edit', compact('course', 'kategoris'));
    }

    /**
     * Update course
     */
    public function update(Request $request, $id)
    {
        $course = Course::findOrFail($id);

        $request->validate([
            'nama_course' => 'required|string|max:150',
            'deskripsi_course' => 'required|string',
            'level' => 'required|in:pemula,menengah,lanjut',
            'kategori_kategori_id' => 'required|exists:kategori,kategori_id',
            'gambar_course' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();

        // Handle image upload
        if ($request->hasFile('gambar_course')) {
            // Delete old image
            if ($course->gambar_course && file_exists(public_path($course->gambar_course))) {
                unlink(public_path($course->gambar_course));
            }
            $file = $request->file('gambar_course');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $filename);
            $data['gambar_course'] = 'uploads/' . $filename;
        }

        $course->update($data);

        return redirect()->route('admin.courses.show', $course->course_id)
            ->with('success', 'Course berhasil diupdate!');
    }

    /**
     * Delete course
     */
    public function destroy($id)
    {
        $course = Course::findOrFail($id);

        // Delete image
        if ($course->gambar_course && file_exists(public_path($course->gambar_course))) {
            unlink(public_path($course->gambar_course));
        }

        $course->delete();

        return redirect()->route('admin.courses.index')
            ->with('success', 'Course berhasil dihapus!');
    }

    /**
     * Add section to course
     */
    public function addSection(Request $request, $courseId)
    {
        $request->validate([
            'nama_section' => 'required|string|max:100',
            'deskripsi_section' => 'nullable|string',
        ]);

        $course = Course::findOrFail($courseId);
        $nextOrder = $course->sections()->max('urutan_section') + 1;

        CourseSection::create([
            'nama_section' => $request->nama_section,
            'deskripsi_section' => $request->deskripsi_section,
            'urutan_section' => $nextOrder,
            'course_id' => $courseId,
        ]);

        return redirect()->route('admin.courses.show', $courseId)
            ->with('success', 'Section berhasil ditambahkan!');
    }

    /**
     * Add video to section
     */
    public function addVideoToSection(Request $request, $courseId)
    {
        if ($request->video_method === 'youtube') {
            // YouTube URL method - create new video record first
            $request->validate([
                'section_id' => 'required|exists:course_sections,section_id',
                'youtube_url' => 'required|url',
                'video_title' => 'required|string|max:150',
                'video_description' => 'nullable|string',
                'durasi_menit' => 'nullable|integer|min:1',
                'catatan_admin' => 'nullable|string',
            ]);

            // Auto-fill duration if empty
            $durasi = $request->durasi_menit;
            if (empty($durasi)) {
                $durasi = $this->getYouTubeDuration($request->youtube_url);
                if (!$durasi) {
                    $durasi = 10; // Default fallback duration
                }
            }

            // Create new video record
            $gambarPath = $this->generateVideoThumbnail($request->youtube_url, $request->video_title);

            $video = Vidio::create([
                'nama' => $request->video_title,
                'judul' => $request->video_title,
                'url' => $request->youtube_url,
                'gambar' => $gambarPath ?: 'uploads/default-video-thumbnail.png',
                'deskripsi' => $request->video_description,
                'durasi_menit' => $durasi,
                'kategori_kategori_id' => Course::findOrFail($courseId)->kategori_kategori_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $vidioId = $video->vidio_id;
        } else {
            // Existing video method
            $request->validate([
                'section_id' => 'required|exists:course_sections,section_id',
                'vidio_vidio_id' => 'required|exists:vidio,vidio_id',
                'durasi_menit' => 'required|integer|min:1',
                'catatan_admin' => 'nullable|string',
            ]);

            $vidioId = $request->vidio_vidio_id;
            $durasi = $request->durasi_menit;
        }

        $section = CourseSection::findOrFail($request->section_id);
        $nextOrder = $section->videos()->max('urutan_video') + 1;

        CourseVideo::create([
            'course_id' => $courseId,
            'section_id' => $request->section_id,
            'vidio_vidio_id' => $vidioId,
            'urutan_video' => $nextOrder,
            'durasi_menit' => $durasi,
            'catatan_admin' => $request->catatan_admin,
        ]);

        // Update course statistics
        $course = Course::findOrFail($courseId);
        $course->updateCourseStatistics();

        $message = $request->video_method === 'youtube' ?
                  'Video YouTube berhasil ditambahkan ke section!' :
                  'Video berhasil ditambahkan ke section!';

        return redirect()->route('admin.courses.show', $courseId)
            ->with('success', $message);
    }

    /**
     * Add quick review
     */
    public function addQuickReview(Request $request, $courseId)
    {
        $request->validate([
            'judul_review' => 'required|string|max:150',
            'konten_review' => 'required|string',
            'tipe_review' => 'required|in:setelah_video,setelah_section,tengah_course',
            'section_id' => 'nullable|exists:course_sections,section_id',
            'vidio_vidio_id' => 'nullable|exists:vidio,vidio_id',
        ]);

        $nextOrder = QuickReview::where('course_id', $courseId)->max('urutan_review') + 1;

        QuickReview::create([
            'judul_review' => $request->judul_review,
            'konten_review' => $request->konten_review,
            'tipe_review' => $request->tipe_review,
            'course_id' => $courseId,
            'section_id' => $request->section_id,
            'vidio_vidio_id' => $request->vidio_vidio_id,
            'urutan_review' => $nextOrder,
        ]);

        return redirect()->route('admin.courses.show', $courseId)
            ->with('success', 'Quick Review berhasil ditambahkan!');
    }

    /**
     * Reorder sections in a course
     */
    public function reorderSections(Request $request, $courseId)
    {
        $request->validate([
            'sections' => 'required|array',
            'sections.*.section_id' => 'required|exists:course_sections,section_id',
            'sections.*.order' => 'required|integer|min:1',
        ]);

        try {
            DB::transaction(function () use ($request, $courseId) {
                foreach ($request->sections as $sectionData) {
                    CourseSection::where('section_id', $sectionData['section_id'])
                        ->where('course_id', $courseId)
                        ->update(['urutan_section' => $sectionData['order']]);
                }
            });

            return response()->json([
                'success' => true,
                'message' => 'Urutan section berhasil diperbarui!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui urutan section: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reorder videos in a section (updated version)
     */
    public function reorderVideos(Request $request, $courseId)
    {
        $request->validate([
            'section_id' => 'required|exists:course_sections,section_id',
            'videos' => 'required|array',
            'videos.*.video_id' => 'required|exists:course_videos,course_video_id',
            'videos.*.order' => 'required|integer|min:1',
        ]);

        try {
            DB::transaction(function () use ($request, $courseId) {
                foreach ($request->videos as $videoData) {
                    CourseVideo::where('course_video_id', $videoData['video_id'])
                        ->where('course_id', $courseId)
                        ->where('section_id', $request->section_id)
                        ->update(['urutan_video' => $videoData['order']]);
                }
            });

            // Update course statistics
            $course = Course::findOrFail($courseId);
            $course->updateCourseStatistics();

            return response()->json([
                'success' => true,
                'message' => 'Urutan video berhasil diperbarui!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui urutan video: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get available quizzes for a course
     */
    public function getAvailableQuizzes($courseId)
    {
        try {
            // Get all quizzes
            $allQuizzes = \App\Models\Quiz::select('quiz_id', 'judul_quiz')
                ->orderBy('judul_quiz')
                ->get();

            // Get quizzes already used in this course
            $usedQuizIds = CourseQuiz::where('course_id', $courseId)
                ->pluck('quiz_id')
                ->toArray();

            // Mark used quizzes
            $quizzes = $allQuizzes->map(function ($quiz) use ($usedQuizIds) {
                return [
                    'quiz_id' => $quiz->quiz_id,
                    'nama_quiz' => $quiz->judul_quiz,
                    'is_used' => in_array($quiz->quiz_id, $usedQuizIds)
                ];
            });

            return response()->json([
                'success' => true,
                'quizzes' => $quizzes
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat daftar quiz: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add quiz to course at specific position
     */
    public function addQuizToCourse(Request $request, $courseId)
    {
        $request->validate([
            'position' => 'required|in:start,end,after_section,after_video,between_sections',
            'reference_id' => 'nullable|integer',
        ]);

        try {
            $quizId = null;
            $isNewQuiz = false;

            DB::transaction(function () use ($request, $courseId, &$quizId, &$isNewQuiz) {
                // Handle new quiz creation
                if ($request->has('quiz_title') && $request->quiz_title) {
                    $quiz = \App\Models\Quiz::create([
                        'judul_quiz' => $request->quiz_title,
                        'deskripsi_quiz' => $request->quiz_description ?? 'Quiz untuk course',
                        'durasi_menit' => $request->quiz_duration ?? 30,
                        'tipe_quiz' => $request->quiz_type ?? 'setelah_video',
                        'is_active' => true,
                        'course_id' => $courseId // Associate with course
                    ]);
                    $quizId = $quiz->quiz_id;
                    $isNewQuiz = true;
                } else {
                    $quizId = $request->quiz_id;
                }

                if (!$quizId) {
                    throw new \Exception('Quiz ID tidak valid');
                }

                // Check if quiz already exists in course (only for existing quizzes)
                if (!$isNewQuiz) {
                    $existingCourseQuiz = CourseQuiz::where('course_id', $courseId)
                        ->where('quiz_id', $quizId)
                        ->first();

                    if ($existingCourseQuiz) {
                        throw new \Exception('Quiz sudah digunakan di course ini');
                    }
                }

                // Determine position order
                $order = $this->calculateQuizOrder($courseId, $request->position, $request->reference_id);

                // Create course quiz relationship
                CourseQuiz::create([
                    'course_id' => $courseId,
                    'quiz_id' => $quizId,
                    'position' => $request->position,
                    'reference_id' => $request->reference_id,
                    'order' => $order
                ]);
            });

            $message = $isNewQuiz ? 'Quiz baru berhasil dibuat dan ditambahkan ke course!' : 'Quiz berhasil ditambahkan ke course!';

            return response()->json([
                'success' => true,
                'message' => $message,
                'quiz_id' => $quizId,
                'type' => $isNewQuiz ? 'new' : 'existing'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Remove quiz from course
     */
    public function removeQuizFromCourse($courseId, $courseQuizId)
    {
        try {
            $courseQuiz = CourseQuiz::where('id', $courseQuizId)
                ->where('course_id', $courseId)
                ->firstOrFail();

            $courseQuiz->delete();

            return response()->json([
                'success' => true,
                'message' => 'Quiz berhasil dihapus dari course!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus quiz: ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * Calculate quiz order based on position
     */
    private function calculateQuizOrder($courseId, $position, $referenceId)
    {
        $maxOrder = \App\Models\CourseQuiz::where('course_id', $courseId)->max('order') ?? 0;

        switch ($position) {
            case 'start':
                return 1;
            case 'end':
                return $maxOrder + 1;
            case 'after_section':
            case 'after_video':
            case 'between_sections':
                // For simplicity, add at the end for now
                // In a more sophisticated implementation, you might want to
                // insert at specific positions and reorder existing quizzes
                return $maxOrder + 1;
            default:
                return $maxOrder + 1;
        }
    }

    /**
     * Toggle course active status
     */
    public function toggleStatus($id)
    {
        $course = Course::findOrFail($id);
        $course->update(['is_active' => !$course->is_active]);

        $status = $course->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->route('admin.courses.show', $id)
            ->with('success', "Course berhasil {$status}!");
    }

    /**
     * Get YouTube video duration in minutes
     */
    private function getYouTubeDuration($url)
    {
        $videoId = $this->extractYouTubeVideoId($url);
        if (!$videoId) {
            return null;
        }

        // Try to get duration from YouTube API (if available)
        // For now, we'll try to estimate based on common patterns or use oEmbed API
        try {
            // Using YouTube oEmbed API (doesn't provide duration but validates the video)
            $oembedUrl = "https://www.youtube.com/oembed?url=" . urlencode($url) . "&format=json";

            // Use curl with timeout for better error handling
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $oembedUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($response && $httpCode === 200) {
                $data = json_decode($response, true);
                if ($data && isset($data['title'])) {
                    // Video exists, return a reasonable default duration based on video type
                    // Analyze title for common patterns
                    $title = strtolower($data['title']);

                    // Educational/tutorial videos tend to be longer
                    if (strpos($title, 'tutorial') !== false ||
                        strpos($title, 'course') !== false ||
                        strpos($title, 'learn') !== false) {
                        return 20; // 20 minutes for tutorials
                    }

                    // Short format indicators
                    if (strpos($title, 'short') !== false ||
                        strpos($title, 'quick') !== false ||
                        strpos($title, '#shorts') !== false) {
                        return 2; // 2 minutes for shorts
                    }

                    // Default duration for educational content
                    return 15;
                }
            }
        } catch (\Exception $e) {
            // If API call fails, continue with fallback
        }

        // Try to parse duration from URL parameters (some YouTube URLs include time)
        if (preg_match('/[?&]t=(\d+)/', $url, $matches)) {
            $seconds = intval($matches[1]);
            return max(1, ceil($seconds / 60)); // Convert to minutes, minimum 1
        }

        // Final fallback for any valid YouTube URL
        return 10; // Default 10 minutes
    }

    /**
     * Generate thumbnail for video (YouTube or placeholder)
     */
    private function generateVideoThumbnail($url, $title)
    {
        // Try to extract YouTube video ID
        $videoId = $this->extractYouTubeVideoId($url);

        if ($videoId) {
            // Try to download YouTube thumbnail
            $thumbnailUrl = "https://img.youtube.com/vi/{$videoId}/maxresdefault.jpg";
            $thumbnailPath = $this->downloadYouTubeThumbnail($thumbnailUrl, $videoId);

            if ($thumbnailPath) {
                return $thumbnailPath;
            }
        }

        // If YouTube thumbnail failed, create a placeholder
        return $this->createPlaceholderThumbnail($title);
    }

    /**
     * Extract YouTube video ID from URL
     */
    private function extractYouTubeVideoId($url)
    {
        $patterns = [
            '/youtube\.com\/watch\?v=([a-zA-Z0-9_-]+)/',
            '/youtube\.com\/embed\/([a-zA-Z0-9_-]+)/',
            '/youtu\.be\/([a-zA-Z0-9_-]+)/',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $matches)) {
                return $matches[1];
            }
        }

        return null;
    }

    /**
     * Download YouTube thumbnail
     */
    private function downloadYouTubeThumbnail($thumbnailUrl, $videoId)
    {
        try {
            $thumbnailData = @file_get_contents($thumbnailUrl);

            if ($thumbnailData) {
                $filename = 'youtube_' . $videoId . '_' . time() . '.jpg';
                $filePath = public_path('uploads/' . $filename);

                if (file_put_contents($filePath, $thumbnailData)) {
                    return 'uploads/' . $filename;
                }
            }
        } catch (\Exception $e) {
            // Silently fail and return null to use placeholder
        }

        return null;
    }

    /**
     * Create placeholder thumbnail
     */
    private function createPlaceholderThumbnail($title)
    {
        // Create a simple placeholder image
        $width = 640;
        $height = 360;
        $image = imagecreate($width, $height);

        // Colors
        $bgColor = imagecolorallocate($image, 45, 55, 72); // Dark gray
        $textColor = imagecolorallocate($image, 255, 255, 255); // White
        $accentColor = imagecolorallocate($image, 59, 130, 246); // Blue

        // Fill background
        imagefill($image, 0, 0, $bgColor);

        // Add accent rectangle
        imagefilledrectangle($image, 0, 0, $width, 60, $accentColor);

        // Add play icon (simple triangle)
        $playSize = 40;
        $centerX = $width / 2;
        $centerY = $height / 2;

        $playIcon = [
            $centerX - $playSize/2, $centerY - $playSize/2,
            $centerX - $playSize/2, $centerY + $playSize/2,
            $centerX + $playSize/2, $centerY
        ];
        imagefilledpolygon($image, $playIcon, 3, $textColor);

        // Add title text (truncated)
        $shortTitle = strlen($title) > 30 ? substr($title, 0, 27) . '...' : $title;
        $fontSize = 3;
        $textWidth = imagefontwidth($fontSize) * strlen($shortTitle);
        $textX = ($width - $textWidth) / 2;
        imagestring($image, $fontSize, $textX, 20, $shortTitle, $textColor);

        // Save image
        $filename = 'placeholder_' . time() . '_' . rand(1000, 9999) . '.png';
        $filePath = public_path('uploads/' . $filename);

        if (imagepng($image, $filePath)) {
            imagedestroy($image);
            return 'uploads/' . $filename;
        }

        imagedestroy($image);

        // If image creation fails, return a default path
        return 'uploads/default-video-thumbnail.png';
    }
}
