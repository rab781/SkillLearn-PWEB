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
            return response()->json([
                'success' => false,
                'message' => 'Video sudah ada di bookmark'
            ], 400);
        }

        $bookmark = Bookmark::create([
            'users_id' => $user->users_id,
            'vidio_vidio_id' => $request->vidio_vidio_id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Video berhasil ditambahkan ke bookmark',
            'bookmark' => $bookmark->load('vidio.kategori')
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
    public function checkBookmark(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vidio_id' => 'required|exists:vidio,vidio_id',
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

        $bookmark = Bookmark::where('users_id', $user->users_id)
            ->where('vidio_vidio_id', $request->vidio_id)
            ->first();

        return response()->json([
            'success' => true,
            'is_bookmarked' => $bookmark !== null,
            'bookmark_id' => $bookmark ? $bookmark->bookmark_id : null
        ]);
    }
}
