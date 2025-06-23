<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vidio;
use Illuminate\Http\Request;

class YouTubeController extends Controller
{
    /**
     * Get YouTube video data (title, thumbnail, duration)
     */
    public function getVideoData(Request $request)
    {
        $url = $request->input('url');

        if (!$url) {
            return response()->json([
                'success' => false,
                'message' => 'URL is required'
            ], 400);
        }

        try {
            // Extract video ID
            $videoId = Vidio::extractYouTubeId($url);

            if (!$videoId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid YouTube URL'
                ], 400);
            }

            // Use oEmbed to get title and other basic info
            $oembedUrl = "https://www.youtube.com/oembed?url=" . urlencode($url) . "&format=json";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $oembedUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            $embedData = [];
            if ($response && $httpCode === 200) {
                $embedData = json_decode($response, true);
            }

            // Get duration estimate
            $duration = Vidio::getYouTubeDuration($url);

            // Get thumbnail URL
            $thumbnailUrl = "https://img.youtube.com/vi/{$videoId}/maxresdefault.jpg";

            return response()->json([
                'success' => true,
                'data' => [
                    'video_id' => $videoId,
                    'title' => $embedData['title'] ?? null,
                    'description' => $embedData['description'] ?? '',
                    'author' => $embedData['author_name'] ?? null,
                    'thumbnail' => $thumbnailUrl,
                    'duration' => $duration
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch video data: ' . $e->getMessage()
            ], 500);
        }
    }
}
