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
        'course_id',
        'current_video_id',
        'video_position',
        'waktu_ditonton',
        'persentase_tonton'
    ];

    protected $casts = [
        'waktu_ditonton' => 'datetime',
        'persentase_tonton' => 'decimal:2'
    ];

    /**
     * Relationship dengan model User
     */
    public function pengguna()
    {
        return $this->belongsTo(User::class, 'id_pengguna', 'users_id');
    }

    /**
     * Relationship dengan model Course
     */
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    /**
     * Relationship dengan model Video (current video being watched)
     */
    public function currentVideo()
    {
        return $this->belongsTo(Vidio::class, 'current_video_id', 'vidio_id');
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
     * Scope untuk mendapatkan riwayat berdasarkan course
     */
    public function scopeByCourse($query, $courseId)
    {
        return $query->where('course_id', $courseId);
    }

    /**
     * Method untuk mencatat atau memperbarui riwayat tonton course
     */
    public static function recordCourseWatch($userId, $courseId, $videoId = null, $videoPosition = 1, $progress = 0)
    {
        return self::updateOrCreate(
            [
                'id_pengguna' => $userId,
                'course_id' => $courseId,
            ],
            [
                'current_video_id' => $videoId,
                'video_position' => $videoPosition,
                'persentase_tonton' => $progress,
                'waktu_ditonton' => now()
            ]
        );
    }

    /**
     * Get next video in course sequence
     */
    public function getNextVideo()
    {
        if (!$this->course) return null;
        
        $videos = $this->course->videos()->orderBy('urutan_video')->get();
        $currentIndex = $videos->search(function($video) {
            return $video->vidio_vidio_id == $this->current_video_id;
        });
        
        if ($currentIndex !== false && $currentIndex < $videos->count() - 1) {
            return $videos[$currentIndex + 1];
        }
        
        return null;
    }

    /**
     * Check if course is completed
     */
    public function isCompleted()
    {
        return $this->persentase_tonton >= 100;
    }
}
