<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\User;
use App\Models\Course;
use App\Models\CourseVideo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FeedbackController extends Controller
{
    /**
     * Display a listing of the resource (Admin only).
     */
    public function index()
    {
        $feedbacks = Feedback::with(['user', 'course', 'courseVideo'])
            ->orderBy('tanggal', 'desc')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'feedbacks' => $feedbacks
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pesan' => 'required|string',
            'course_id' => 'required|exists:courses,course_id',
            'course_video_id' => 'nullable|exists:course_videos,course_video_id',
            'rating' => 'nullable|integer|min:1|max:5',
            'catatan' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        /** @var User $user */
        $user = Auth::user();

        $feedback = Feedback::create([
            'tanggal' => now()->toDateString(),
            'pesan' => $request->pesan,
            'course_id' => $request->course_id,
            'course_video_id' => $request->course_video_id,
            'rating' => $request->rating,
            'catatan' => $request->catatan,
            'users_id' => $user->users_id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Feedback berhasil ditambahkan',
            'feedback' => $feedback->load(['user', 'course', 'courseVideo'])
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Feedback $feedback)
    {
        return response()->json([
            'success' => true,
            'feedback' => $feedback->load(['user', 'course', 'courseVideo'])
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Feedback $feedback)
    {
        /** @var User $user */
        $user = Auth::user();

        // Check if user owns this feedback
        if ($feedback->users_id !== $user->users_id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk mengubah feedback ini'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'pesan' => 'required|string',
            'rating' => 'nullable|integer|min:1|max:5',
            'catatan' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $feedback->update([
            'pesan' => $request->pesan,
            'rating' => $request->rating,
            'catatan' => $request->catatan,
            'tanggal' => now()->toDateString()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Feedback berhasil diupdate',
            'feedback' => $feedback->load(['user', 'course', 'courseVideo'])
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Feedback $feedback)
    {
        /** @var User $user */
        $user = Auth::user();

        // Check if user owns this feedback or is admin
        if ($feedback->users_id !== $user->users_id && !$user->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk menghapus feedback ini'
            ], 403);
        }

        $feedback->delete();

        return response()->json([
            'success' => true,
            'message' => 'Feedback berhasil dihapus'
        ]);
    }

    /**
     * Get user's own feedbacks
     */
    public function myFeedbacks()
    {
        /** @var User $user */
        $user = Auth::user();

        $feedbacks = Feedback::with(['course', 'courseVideo'])
            ->where('users_id', $user->users_id)
            ->orderBy('tanggal', 'desc')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'feedbacks' => $feedbacks
        ]);
    }

    /**
     * Reply to feedback (Admin only)
     */
    public function reply(Request $request, Feedback $feedback)
    {
        $validator = Validator::make($request->all(), [
            'balasan' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $feedback->update([
            'balasan' => $request->balasan
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Balasan berhasil ditambahkan',
            'feedback' => $feedback->load(['user', 'course', 'courseVideo'])
        ]);
    }

    /**
     * Get feedbacks for a specific course
     */
    public function getCourseFeeedbacks(Course $course)
    {
        $feedbacks = Feedback::with(['user', 'courseVideo'])
            ->where('course_id', $course->course_id)
            ->orderBy('tanggal', 'desc')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'course' => $course,
            'feedbacks' => $feedbacks
        ]);
    }

    /**
     * Get feedbacks for a specific video in a course
     */
    public function getVideoFeedbacks(Course $course, CourseVideo $courseVideo)
    {
        $feedbacks = Feedback::with(['user'])
            ->where('course_id', $course->course_id)
            ->where('course_video_id', $courseVideo->course_video_id)
            ->orderBy('tanggal', 'desc')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'course' => $course,
            'video' => $courseVideo,
            'feedbacks' => $feedbacks
        ]);
    }

    /**
     * Get course statistics (for admin)
     */
    public function getCourseStats(Course $course)
    {
        $totalFeedbacks = Feedback::where('course_id', $course->course_id)->count();
        $averageRating = Feedback::where('course_id', $course->course_id)
            ->whereNotNull('rating')
            ->avg('rating');
        
        $ratingDistribution = Feedback::where('course_id', $course->course_id)
            ->whereNotNull('rating')
            ->selectRaw('rating, COUNT(*) as count')
            ->groupBy('rating')
            ->orderBy('rating')
            ->get();

        return response()->json([
            'success' => true,
            'course' => $course,
            'stats' => [
                'total_feedbacks' => $totalFeedbacks,
                'average_rating' => round($averageRating, 2),
                'rating_distribution' => $ratingDistribution
            ]
        ]);
    }

    /**
     * Display feedback for admin web interface
     */
    public function adminIndex(Request $request)
    {
        $query = Feedback::with(['user', 'course', 'courseVideo'])
            ->orderBy('tanggal', 'desc');

        // Filter by course if specified
        if ($request->filled('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->whereHas('course', function($q) use ($request) {
                // Add status filter logic if needed
            });
        }

        $feedbacks = $query->paginate(15);
        $courses = Course::active()->get(['course_id', 'nama_course']);

        return view('admin.feedback.index', compact('feedbacks', 'courses'));
    }
}
