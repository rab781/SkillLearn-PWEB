<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\QuizResult;

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
        'completed_at' => 'datetime',
    ];

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
    }

    public function currentVideo()
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
        $course = $this->course()->with(['videos', 'quizzes'])->first();

        if (!$course) {
            return;
        }

        // Video progress calculation
        $totalVideos = $course->videos()->count();
        $completedVideos = UserVideoProgress::where('user_id', $this->user_id)
            ->where('course_id', $this->course_id)
            ->where('is_completed', true)
            ->count();

        $videoProgress = $totalVideos > 0 ? ($completedVideos / $totalVideos) * 100 : 0;

        // Quiz progress calculation
        $totalQuizzes = $course->quizzes->count();
        $completedQuizzes = QuizResult::where('users_id', $this->user_id)
            ->whereIn('quiz_id', $course->quizzes->pluck('quiz_id'))
            ->distinct('quiz_id')
            ->count();

        $quizProgress = $totalQuizzes > 0 ? ($completedQuizzes / $totalQuizzes) * 100 : 0;

        // Calculate overall progress as the average of video and quiz progress
        $overallProgress = 0;
        $divisor = 0;

        if ($totalVideos > 0) {
            $overallProgress += $videoProgress;
            $divisor++;
        }

        if ($totalQuizzes > 0) {
            $overallProgress += $quizProgress;
            $divisor++;
        }

        $percentage = $divisor > 0 ? round($overallProgress / $divisor, 2) : 0;

        $status = 'not_started';
        if ($percentage > 0 && $percentage < 100) {
            $status = 'in_progress';
        } elseif ($percentage >= 100) {
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
