<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCourseProgress extends Model
{
    use HasFactory;

    protected $table = 'user_course_progress';
    protected $primaryKey = 'progress_id';

    protected $fillable = [
        'user_id',
        'course_id',
        'current_section_id',
        'current_video_id',
        'videos_completed',
        'total_videos',
        'progress_percentage',
        'started_at',
        'completed_at',
        'status',
    ];

    protected $casts = [
        'progress_percentage' => 'decimal:2',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'users_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function currentSection()
    {
        return $this->belongsTo(CourseSection::class, 'current_section_id');
    }    public function currentVideo()
    {
        return $this->belongsTo(Vidio::class, 'current_video_id');
    }

    public function videoProgress()
    {
        return $this->hasMany(UserVideoProgress::class, 'user_id', 'user_id')
            ->where('course_id', $this->course_id);
    }

    // Scopes
    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeNotStarted($query)
    {
        return $query->where('status', 'not_started');
    }

    // Helper methods
    public function updateProgress()
    {
        $totalVideos = $this->course->videos()->count();
        $completedVideos = UserVideoProgress::where('user_id', $this->user_id)
            ->where('course_id', $this->course_id)
            ->where('is_completed', true)
            ->count();

        $percentage = $totalVideos > 0 ? round(($completedVideos / $totalVideos) * 100, 2) : 0;
        
        $status = 'not_started';
        if ($completedVideos > 0 && $completedVideos < $totalVideos) {
            $status = 'in_progress';
        } elseif ($completedVideos === $totalVideos && $totalVideos > 0) {
            $status = 'completed';
        }

        $this->update([
            'videos_completed' => $completedVideos,
            'total_videos' => $totalVideos,
            'progress_percentage' => $percentage,
            'status' => $status,
            'completed_at' => $status === 'completed' ? now() : null,
        ]);

        return $this;
    }

    public function startCourse()
    {
        if ($this->status === 'not_started') {
            $this->update([
                'started_at' => now(),
                'status' => 'in_progress',
            ]);
        }
    }

    public function getStatusLabel()
    {
        $statuses = [
            'not_started' => 'Belum Dimulai',
            'in_progress' => 'Sedang Berlangsung',
            'completed' => 'Selesai'
        ];
        
        return $statuses[$this->status] ?? $this->status;
    }
}
