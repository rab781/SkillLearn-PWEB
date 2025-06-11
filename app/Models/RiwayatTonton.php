<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatTonton extends Model
{
    use HasFactory;

    protected $table = 'riwayat_tonton';
    protected $primaryKey = 'id_riwayat_tonton';

    protected $fillable = [
        'id_pengguna',
        'id_video',
        'waktu_ditonton',
        'durasi_tonton',
        'persentase_progress'
    ];

    protected $casts = [
        'waktu_ditonton' => 'datetime',
        'persentase_progress' => 'decimal:2'
    ];

    /**
     * Relationship dengan model User
     */
    public function pengguna()
    {
        return $this->belongsTo(User::class, 'id_pengguna', 'users_id');
    }

    /**
     * Relationship dengan model Video
     */
    public function video()
    {
        return $this->belongsTo(Vidio::class, 'id_video', 'vidio_id');
    }

    /**
     * Scope untuk mendapatkan riwayat tonton berdasarkan user
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('id_pengguna', $userId);
    }

    /**
     * Scope untuk mendapatkan riwayat tonton terbaru
     */
    public function scopeRecent($query, $limit = 10)
    {
        return $query->orderBy('waktu_ditonton', 'desc')->limit($limit);
    }

    /**
     * Method untuk mencatat atau memperbarui riwayat tonton
     */
    public static function recordWatch($userId, $videoId, $duration = 0, $progress = 0)
    {
        return self::updateOrCreate(
            [
                'id_pengguna' => $userId,
                'id_video' => $videoId,
                'waktu_ditonton' => now()->format('Y-m-d H:i:s')
            ],
            [
                'durasi_tonton' => $duration,
                'persentase_progress' => $progress
            ]
        );
    }
}
