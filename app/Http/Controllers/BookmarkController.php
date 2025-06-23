<?php

namespace App\Http\Controllers;

use App\Models\Bookmark;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BookmarkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();

        $bookmarks = Bookmark::with('vidio.kategori')
            ->where('users_id', $user->users_id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'bookmarks' => $bookmarks
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vidio_vidio_id' => 'required|exists:vidio,vidio_id',
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

        // Check if bookmark already exists
        $existingBookmark = Bookmark::where('users_id', $user->users_id)
            ->where('vidio_vidio_id', $request->vidio_vidio_id)
            ->first();

        if ($existingBookmark) {
            // Remove bookmark if exists (toggle)
            $existingBookmark->delete();

            return response()->json([
                'success' => true,
                'message' => 'Video berhasil dihapus dari bookmark',
                'action' => 'removed'
            ]);
        }

        // Add new bookmark
        $bookmark = Bookmark::create([
            'users_id' => $user->users_id,
            'vidio_vidio_id' => $request->vidio_vidio_id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Video berhasil ditambahkan ke bookmark',
            'bookmark' => $bookmark->load('vidio.kategori'),
            'action' => 'added'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Bookmark $bookmark)
    {
        // Check if user owns this bookmark
        if ($bookmark->users_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'bookmark' => $bookmark->load(['video.kategori'])
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bookmark $bookmark)
    {
        /** @var User $user */
        $user = Auth::user();

        // Check if user owns this bookmark
        if ($bookmark->users_id !== $user->users_id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk menghapus bookmark ini'
            ], 403);
        }

        $bookmark->delete();

        return response()->json([
            'success' => true,
            'message' => 'Bookmark berhasil dihapus'
        ]);
    }

    /**
     * Check if video is bookmarked by user
     */
    public function checkBookmark($video)
    {
        /** @var User $user */
        $user = Auth::user();

        $bookmark = Bookmark::where('users_id', $user->users_id)
            ->where('vidio_vidio_id', $video)
            ->first();

        return response()->json([
            'success' => true,
            'bookmarked' => $bookmark !== null,
            'bookmark_id' => $bookmark ? $bookmark->bookmark_id : null
        ]);
    }

    /**
     * Store a course bookmark
     */
    public function storeCourse(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_id' => 'required|exists:courses,course_id',
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

        // Check if bookmark already exists
        $existingBookmark = Bookmark::where('users_id', $user->users_id)
            ->where('course_id', $request->course_id)
            ->first();

        if ($existingBookmark) {
            // Remove bookmark if exists (toggle)
            $existingBookmark->delete();

            return response()->json([
                'success' => true,
                'message' => 'Course berhasil dihapus dari bookmark',
                'action' => 'removed'
            ]);
        }

        // Add new bookmark
        $bookmark = Bookmark::create([
            'users_id' => $user->users_id,
            'course_id' => $request->course_id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Course berhasil ditambahkan ke bookmark',
            'bookmark' => $bookmark->load('course'),
            'action' => 'added'
        ], 201);
    }

    /**
     * Check if course is bookmarked by user
     */
    public function checkCourseBookmark($courseId)
    {
        /** @var User $user */
        $user = Auth::user();

        $bookmark = Bookmark::where('users_id', $user->users_id)
            ->where('course_id', $courseId)
            ->first();

        return response()->json([
            'success' => true,
            'bookmarked' => $bookmark !== null,
            'bookmark_id' => $bookmark ? $bookmark->bookmark_id : null
        ]);
    }

    /**
     * Toggle bookmark for specific video from course video
     */
    public function toggleVideoBookmark($videoId)
    {
        /** @var User $user */
        $user = Auth::user();

        // Get course video to find the actual video
        $courseVideo = \App\Models\CourseVideo::with('vidio')
            ->findOrFail($videoId);

        if (!$courseVideo->vidio) {
            return response()->json([
                'success' => false,
                'message' => 'Video tidak ditemukan'
            ], 404);
        }

        // Check if bookmark already exists
        $existingBookmark = Bookmark::where('users_id', $user->users_id)
            ->where('vidio_vidio_id', $courseVideo->vidio_vidio_id)
            ->first();

        if ($existingBookmark) {
            // Remove bookmark if exists (toggle)
            $existingBookmark->delete();

            return response()->json([
                'success' => true,
                'message' => 'Video berhasil dihapus dari bookmark',
                'bookmarked' => false,
                'action' => 'removed'
            ]);
        }

        // Add new bookmark
        $bookmark = Bookmark::create([
            'users_id' => $user->users_id,
            'vidio_vidio_id' => $courseVideo->vidio_vidio_id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Video berhasil ditambahkan ke bookmark',
            'bookmarked' => true,
            'bookmark' => $bookmark->load('vidio'),
            'action' => 'added'
        ], 201);
    }

    /**
     * Check if video is bookmarked by user (for course video)
     */
    public function checkVideoBookmark($videoId)
    {
        /** @var User $user */
        $user = Auth::user();

        // Get course video to find the actual video
        $courseVideo = \App\Models\CourseVideo::with('vidio')
            ->findOrFail($videoId);

        if (!$courseVideo->vidio) {
            return response()->json([
                'success' => true,
                'bookmarked' => false,
                'bookmark_id' => null
            ]);
        }

        $bookmark = Bookmark::where('users_id', $user->users_id)
            ->where('vidio_vidio_id', $courseVideo->vidio_vidio_id)
            ->first();

        return response()->json([
            'success' => true,
            'bookmarked' => $bookmark !== null,
            'bookmark_id' => $bookmark ? $bookmark->bookmark_id : null
        ]);
    }
}
