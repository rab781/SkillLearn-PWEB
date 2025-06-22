<?php

namespace App\Http\Controllers;

use App\Models\RiwayatTonton;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RiwayatTontonController extends Controller
{
    /**
     * Display user's course history
     */
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();

        $histories = RiwayatTonton::with(['course', 'currentVideo'])
            ->byUser($user->users_id)
            ->recent(20)
            ->get();

        return response()->json([
            'success' => true,
            'histories' => $histories
        ]);
    }

    /**
     * Get specific course history for user
     */
    public function getCourseHistory($courseId)
    {
        /** @var User $user */
        $user = Auth::user();

        $history = RiwayatTonton::with(['course', 'currentVideo'])
            ->byUser($user->users_id)
            ->byCourse($courseId)
            ->first();

        return response()->json([
            'success' => true,
            'history' => $history
        ]);
    }

    /**
     * Record or update course watch progress
     */
    public function recordWatch(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_id' => 'required|exists:courses,course_id',
            'video_id' => 'nullable|exists:vidio,vidio_id',
            'video_position' => 'nullable|integer|min:1',
            'progress' => 'nullable|numeric|min:0|max:100',
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

        $history = RiwayatTonton::recordCourseWatch(
            $user->users_id,
            $request->course_id,
            $request->video_id,
            $request->video_position ?? 1,
            $request->progress ?? 0
        );

        return response()->json([
            'success' => true,
            'message' => 'Progress berhasil disimpan',
            'history' => $history->load(['course', 'currentVideo'])
        ]);
    }

    /**
     * Continue watching a course from last position
     */
    public function continueWatching($courseId)
    {
        /** @var User $user */
        $user = Auth::user();

        $history = RiwayatTonton::with(['course.videos', 'currentVideo'])
            ->byUser($user->users_id)
            ->byCourse($courseId)
            ->first();

        if (!$history) {
            // If no history, start from first video
            $course = Course::with('videos')->find($courseId);
            if (!$course || $course->videos->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Course tidak ditemukan atau tidak memiliki video'
                ], 404);
            }

            $firstVideo = $course->videos->first();
            return response()->json([
                'success' => true,
                'course' => $course,
                'video' => $firstVideo->vidio,
                'position' => 1,
                'progress' => 0
            ]);
        }

        $currentVideo = $history->currentVideo;
        if (!$currentVideo) {
            // Get first video if current video is null
            $firstVideo = $history->course->videos()->first();
            $currentVideo = $firstVideo ? $firstVideo->vidio : null;
        }

        return response()->json([
            'success' => true,
            'course' => $history->course,
            'video' => $currentVideo,
            'position' => $history->video_position,
            'progress' => $history->persentase_tonton,
            'last_watched' => $history->waktu_ditonton
        ]);
    }

    /**
     * Get user's recently watched courses
     */
    public function recentlyWatched($limit = 10)
    {
        /** @var User $user */
        $user = Auth::user();

        $recentCourses = RiwayatTonton::with(['course.kategori', 'currentVideo'])
            ->byUser($user->users_id)
            ->recent($limit)
            ->get()
            ->map(function ($history) {
                return [
                    'course' => $history->course,
                    'last_video' => $history->currentVideo,
                    'position' => $history->video_position,
                    'progress' => $history->persentase_tonton,
                    'last_watched' => $history->waktu_ditonton,
                    'is_completed' => $history->isCompleted()
                ];
            });

        return response()->json([
            'success' => true,
            'recent_courses' => $recentCourses
        ]);
    }

    /**
     * Delete course history
     */
    public function deleteHistory($courseId)
    {
        /** @var User $user */
        $user = Auth::user();

        $deleted = RiwayatTonton::byUser($user->users_id)
            ->byCourse($courseId)
            ->delete();

        if ($deleted) {
            return response()->json([
                'success' => true,
                'message' => 'Riwayat tonton berhasil dihapus'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Riwayat tonton tidak ditemukan'
        ], 404);
    }

    /**
     * Get course statistics for admin
     */
    public function getCourseStatistics($courseId)
    {
        $course = Course::find($courseId);
        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Course tidak ditemukan'
            ], 404);
        }

        $totalWatchers = RiwayatTonton::byCourse($courseId)->distinct('id_pengguna')->count();
        $completedWatchers = RiwayatTonton::byCourse($courseId)->where('persentase_tonton', '>=', 100)->count();
        $averageProgress = RiwayatTonton::byCourse($courseId)->avg('persentase_tonton') ?? 0;

        return response()->json([
            'success' => true,
            'statistics' => [
                'course' => $course,
                'total_watchers' => $totalWatchers,
                'completed_watchers' => $completedWatchers,
                'completion_rate' => $totalWatchers > 0 ? round(($completedWatchers / $totalWatchers) * 100, 2) : 0,
                'average_progress' => round($averageProgress, 2)
            ]
        ]);
    }
}
