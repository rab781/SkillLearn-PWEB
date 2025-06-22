<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseSection;
use App\Models\CourseVideo;
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
    public function index()
    {
        $courses = Course::with(['kategori', 'sections'])
            ->withCount(['videos', 'userProgress'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.courses.index', compact('courses'));
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
            $data['gambar_course'] = $request->file('gambar_course')->store('courses', 'public');
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
            'quickReviews'
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
            if ($course->gambar_course) {
                Storage::disk('public')->delete($course->gambar_course);
            }
            $data['gambar_course'] = $request->file('gambar_course')->store('courses', 'public');
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
        if ($course->gambar_course) {
            Storage::disk('public')->delete($course->gambar_course);
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
        $request->validate([
            'section_id' => 'required|exists:course_sections,section_id',
            'vidio_vidio_id' => 'required|exists:vidio,vidio_id',
            'durasi_menit' => 'required|integer|min:1',
            'catatan_admin' => 'nullable|string',
        ]);

        $section = CourseSection::findOrFail($request->section_id);
        $nextOrder = $section->videos()->max('urutan_video') + 1;

        CourseVideo::create([
            'course_id' => $courseId,
            'section_id' => $request->section_id,
            'vidio_vidio_id' => $request->vidio_vidio_id,
            'urutan_video' => $nextOrder,
            'durasi_menit' => $request->durasi_menit,
            'catatan_admin' => $request->catatan_admin,
        ]);

        // Update course statistics
        $course = Course::findOrFail($courseId);
        $course->updateCourseStatistics();

        return redirect()->route('admin.courses.show', $courseId)
            ->with('success', 'Video berhasil ditambahkan ke section!');
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
     * Reorder videos in a section
     */
    public function reorderVideos(Request $request, $courseId)
    {
        $request->validate([
            'video_orders' => 'required|array',
            'video_orders.*.course_video_id' => 'required|exists:course_videos,course_video_id',
            'video_orders.*.urutan_video' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($request) {
            foreach ($request->video_orders as $order) {
                CourseVideo::where('course_video_id', $order['course_video_id'])
                    ->update(['urutan_video' => $order['urutan_video']]);
            }
        });

        return response()->json(['success' => true, 'message' => 'Urutan video berhasil diubah!']);
    }

    /**
     * Remove video from course
     */
    public function removeVideo($courseId, $courseVideoId)
    {
        $courseVideo = CourseVideo::where('course_id', $courseId)
            ->where('course_video_id', $courseVideoId)
            ->firstOrFail();

        $courseVideo->delete();

        // Update course statistics
        $course = Course::findOrFail($courseId);
        $course->updateCourseStatistics();

        return redirect()->route('admin.courses.show', $courseId)
            ->with('success', 'Video berhasil dihapus dari course!');
    }

    /**
     * Remove section from course
     */
    public function removeSection($courseId, $sectionId)
    {
        $section = CourseSection::where('course_id', $courseId)
            ->where('section_id', $sectionId)
            ->firstOrFail();

        $section->delete(); // This will cascade delete videos and reviews

        // Update course statistics
        $course = Course::findOrFail($courseId);
        $course->updateCourseStatistics();

        return redirect()->route('admin.courses.show', $courseId)
            ->with('success', 'Section berhasil dihapus!');
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
}
