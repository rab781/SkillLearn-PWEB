<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\User;
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
        $feedbacks = Feedback::with(['user', 'vidio'])
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

        $feedback = Feedback::create([
            'tanggal' => now()->toDateString(),
            'pesan' => $request->pesan,
            'vidio_vidio_id' => $request->vidio_vidio_id,
            'users_id' => $user->users_id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Feedback berhasil ditambahkan',
            'feedback' => $feedback->load(['user', 'vidio'])
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Feedback $feedback)
    {
        return response()->json([
            'success' => true,
            'feedback' => $feedback->load(['user', 'vidio'])
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
            'tanggal' => now()->toDateString()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Feedback berhasil diupdate',
            'feedback' => $feedback->load(['user', 'vidio'])
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

        $feedbacks = Feedback::with('vidio')
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
            'feedback' => $feedback->load(['user', 'vidio'])
        ]);
    }
}
