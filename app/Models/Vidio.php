<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Vidio extends Model
{
    use HasFactory;

    protected $table = 'vidio';
    protected $primaryKey = 'vidio_id';

    protected $fillable = [
        'nama',
        'deskripsi',
        'url',
        'gambar',
        'jumlah_tayang',
        'kategori_kategori_id',
        'channel',
        'durasi_menit',
        'is_active',
    ];

    // Relationships
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_kategori_id');
    }

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class, 'vidio_vidio_id');
    }

    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class, 'vidio_vidio_id');
    }

    // Increment view count
    public function incrementViews()
    {
        $this->increment('jumlah_tayang');
    }

    /**
     * Get video thumbnail URL or default thumbnail
     */
    public function getGambarUrlAttribute()
    {
        if (!$this->gambar) {
            return asset('images/default-video.jpg');
        }

        // Check if it's already a URL
        if (filter_var($this->gambar, FILTER_VALIDATE_URL)) {
            return $this->gambar;
        }

        // Check if it's a YouTube video ID and generate thumbnail URL
        if (preg_match('/^[a-zA-Z0-9_-]{11}$/', $this->gambar)) {
            return "https://img.youtube.com/vi/{$this->gambar}/maxresdefault.jpg";
        }

        // Check if it's a path to a storage file
        if (strpos($this->gambar, 'public/') === 0) {
            return \Storage::url($this->gambar);
        }

        // Otherwise, assume it's a path to an uploaded file
        return asset('uploads/' . $this->gambar);
    }

    /**
     * Extract YouTube video ID from URL
     */
    public static function extractYouTubeId($url)
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
     * Get YouTube video duration from URL
     */
    public static function getYouTubeDuration($url)
    {
        $videoId = self::extractYouTubeId($url);
        if (!$videoId) {
            return 10; // Default duration if not YouTube URL
        }

        try {
            // Using YouTube oEmbed API
            $oembedUrl = "https://www.youtube.com/oembed?url=" . urlencode($url) . "&format=json";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $oembedUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($response && $httpCode === 200) {
                $data = json_decode($response, true);
                if ($data) {
                    // Parse title for duration hints
                    $title = strtolower($data['title'] ?? '');

                    if (preg_match('/\[(\d+):(\d+)\]/', $title, $matches)) {
                        // Time format in title [mm:ss]
                        return intval($matches[1]) + ceil(intval($matches[2]) / 60);
                    }

                    if (strpos($title, 'tutorial') !== false ||
                        strpos($title, 'course') !== false) {
                        return 20; // Likely longer educational content
                    }

                    if (strpos($title, 'short') !== false ||
                        strpos($title, '#shorts') !== false) {
                        return 2; // Short content
                    }

                    // Default estimate for found videos
                    return 15;
                }
            }
        } catch (\Exception $e) {
            // Silent fail, use fallback
        }

        return 10; // Default fallback
    }
}
