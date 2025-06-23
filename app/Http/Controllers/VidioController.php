<?php

namespace App\Http\Controllers;

use App\Models\Vidio;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VidioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Vidio::with('kategori');

        // Filter by category if provided
        if ($request->has('kategori_id') && $request->kategori_id) {
            $query->where('kategori_kategori_id', $request->kategori_id);
        }

        // Search by name if provided
        if ($request->has('search') && $request->search) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        // Handle sorting
        $sort = $request->get('sort', 'created_at');
        $order = $request->get('order', 'desc');

        // Validate sort fields
        $allowedSorts = ['created_at', 'jumlah_tayang', 'nama'];
        if (!in_array($sort, $allowedSorts)) {
            $sort = 'created_at';
        }

        // Validate order
        if (!in_array($order, ['asc', 'desc'])) {
            $order = 'desc';
        }

        $videos = $query->orderBy($sort, $order)->paginate(12);

        return response()->json([
            'success' => true,
            'videos' => $videos
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:120',
            'deskripsi' => 'nullable|string',
            'url' => 'required|url',
            'gambar' => 'nullable|url',
            'kategori_kategori_id' => 'required|exists:kategori,kategori_id',
            'channel' => 'required|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Extract YouTube video ID and get thumbnail if applicable
        $youtubeId = Vidio::extractYouTubeId($request->url);
        $thumbnail = $request->gambar;

        // If no thumbnail provided but YouTube URL, use YouTube thumbnail
        if ((!$thumbnail || $thumbnail == '') && $youtubeId) {
            $thumbnail = "https://img.youtube.com/vi/{$youtubeId}/maxresdefault.jpg";
        }

        // If YouTube URL, also try to get duration
        $durasi = null;
        if ($youtubeId) {
            $durasi = Vidio::getYouTubeDuration($request->url);
        }

        $video = Vidio::create([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'url' => $request->url,
            'gambar' => $thumbnail,
            'kategori_kategori_id' => $request->kategori_kategori_id,
            'jumlah_tayang' => 0,
            'channel' => $request->channel,
            'durasi_menit' => $durasi
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Video berhasil ditambahkan',
            'video' => $video->load('kategori')
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Vidio $vidio)
    {
        // Increment view count
        $vidio->increment('jumlah_tayang');

        return response()->json([
            'success' => true,
            'video' => $vidio->load(['kategori', 'feedbacks.user'])
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vidio $vidio)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'sometimes|string|max:120',
            'deskripsi' => 'sometimes|string',
            'url' => 'sometimes|url',
            'gambar' => 'sometimes|nullable|url',
            'kategori_kategori_id' => 'sometimes|exists:kategori,kategori_id',
            'channel' => 'sometimes|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Prepare update data
        $updateData = $request->only([
            'nama', 'deskripsi', 'kategori_kategori_id', 'channel'
        ]);

        // Handle URL change
        if ($request->has('url')) {
            $updateData['url'] = $request->url;

            // If URL changed to YouTube URL, update thumbnail and duration if not explicitly set
            $youtubeId = Vidio::extractYouTubeId($request->url);
            if ($youtubeId) {
                // Update thumbnail if not explicitly provided
                if (!$request->has('gambar') || !$request->gambar) {
                    $updateData['gambar'] = "https://img.youtube.com/vi/{$youtubeId}/maxresdefault.jpg";
                }

                // Update duration if not set or if URL changed
                if (!$vidio->durasi_menit || $vidio->url !== $request->url) {
                    $updateData['durasi_menit'] = Vidio::getYouTubeDuration($request->url);
                }
            }
        }

        // Only update gambar if explicitly provided
        if ($request->has('gambar')) {
            $updateData['gambar'] = $request->gambar;
        }

        $vidio->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Video berhasil diupdate',
            'video' => $vidio->load('kategori')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vidio $vidio)
    {
        $vidio->delete();

        return response()->json([
            'success' => true,
            'message' => 'Video berhasil dihapus'
        ]);
    }

    /**
     * Get videos by category
     */
    public function getByCategory($kategoriId)
    {
        $videos = Vidio::with('kategori')
            ->where('kategori_kategori_id', $kategoriId)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'videos' => $videos
        ]);
    }

    /**
     * Get popular videos (most viewed)
     */
    public function popular()
    {
        $videos = Vidio::with('kategori')
            ->orderBy('jumlah_tayang', 'desc')
            ->take(10)
            ->get();

        return response()->json([
            'success' => true,
            'videos' => $videos
        ]);
    }

    /**
     * Get latest videos
     */
    public function latest()
    {
        $videos = Vidio::with('kategori')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return response()->json([
            'success' => true,
            'videos' => $videos
        ]);
    }

    /**
     * Increment view count for a video
     */
    public function incrementView(Vidio $vidio)
    {
        $vidio->increment('jumlah_tayang');

        return response()->json([
            'success' => true,
            'message' => 'View count incremented',
            'views' => $vidio->jumlah_tayang
        ]);
    }
}
