<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserVideoProgress extends Model
{
    use HasFactory;

    protected $table = 'user_video_progress';
    protected $primaryKey = 'video_progress_id';

    protected $fillable = [
        'user_id',
        'vidio_vidio_id',
        'course_id',
        'is_completed',
        'watch_time_seconds',
        'total_duration_seconds',
        'completion_percentage',
        'first_watched_at',
        'completed_at',
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'completion_percentage' => 'decimal:2',
        'first_watched_at' => 'datetime',
        'completed_at' => 'datetime',
    ];    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'users_id');
    }

    public function vidio()
    {
        return $this->belongsTo(Vidio::class, 'vidio_vidio_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    // Scopes
    public function scopeCompleted($query)
    {
        return $query->where('is_completed', true);
    }

    public function scopeInProgress($query)
    {
        return $query->where('is_completed', false)
                    ->where('watch_time_seconds', '>', 0);
    }

    // Helper methods
    public function updateWatchTime($seconds)
    {
        $this->watch_time_seconds = max($this->watch_time_seconds, $seconds);
        
        if ($this->total_duration_seconds > 0) {
            $this->completion_percentage = min(100, round(($this->watch_time_seconds / $this->total_duration_seconds) * 100, 2));
        }
        
        // Mark as completed if watched 90% or more
        if ($this->completion_percentage >= 90 && !$this->is_completed) {
            $this->markAsCompleted();
        }
        
        $this->save();
        return $this;
    }

    public function markAsCompleted()
    {
        $this->update([
            'is_completed' => true,
            'completed_at' => now(),
            'completion_percentage' => 100,
        ]);

        // Update course progress
        $courseProgress = UserCourseProgress::where('user_id', $this->user_id)
            ->where('course_id', $this->course_id)
            ->first();
            
        if ($courseProgress) {
            $courseProgress->updateProgress();
        }

        return $this;
    }

    public function startWatching()
    {
        if (!$this->first_watched_at) {
            $this->update(['first_watched_at' => now()]);
        }
    }

    public static function getOrCreateProgress($userId, $videoId, $courseId = null)
    {
        return self::firstOrCreate([
            'user_id' => $userId,
            'vidio_vidio_id' => $videoId,
            'course_id' => $courseId,
        ]);
    }
}
