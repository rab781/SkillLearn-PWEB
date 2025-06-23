<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * @property-read int $total_video
 * @property-read int $total_durasi_menit
 */
class Course extends Model
{
    use HasFactory;

    protected $table = 'courses';
    protected $primaryKey = 'course_id';

    protected $fillable = [
        'nama_course',
        'deskripsi_course',
        'gambar_course',
        'level',
        'total_durasi_menit',
        'total_video',
        'is_active',
        'kategori_kategori_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationships
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_kategori_id');
    }

    public function sections()
    {
        return $this->hasMany(CourseSection::class, 'course_id')->orderBy('urutan_section');
    }

    public function videos()
    {
        return $this->hasMany(CourseVideo::class, 'course_id')->orderBy('urutan_video');
    }

    public function quickReviews()
    {
        return $this->hasMany(QuickReview::class, 'course_id')->orderBy('urutan_review');
    }

    public function userProgress()
    {
        return $this->hasMany(UserCourseProgress::class, 'course_id');
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class, 'course_id');
    }

    public function courseQuizzes()
    {
        return $this->hasMany(CourseQuiz::class, 'course_id')->orderBy('order');
    }

    public function feedback()
    {
        return $this->hasMany(Feedback::class, 'course_id');
    }

    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class, 'course_id');
    }

    public function watchHistory()
    {
        return $this->hasMany(RiwayatTonton::class, 'course_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByLevel($query, $level)
    {
        return $query->where('level', $level);
    }

    // Accessors
    /**
     * Dynamic property to get total videos count.
     */
    public function getTotalVideoAttribute()
    {
        return $this->videos->count();
    }

    /**
     * Dynamic property to get total duration in minutes.
     */
    public function getTotalDurasiMenitAttribute()
    {
        return $this->videos->sum('durasi_menit');
    }

    public function getTotalQuizzesAttribute()
    {
        return $this->quizzes()->count();
    }

    public function getGambarCourseUrlAttribute()
    {
        if (!$this->gambar_course) {
            return asset('images/default-course.svg');
        }

        // Check if it's a URL
        if (filter_var($this->gambar_course, FILTER_VALIDATE_URL)) {
            return $this->gambar_course;
        }

        // Check if it's a storage path
        if (strpos($this->gambar_course, 'public/') === 0) {
            return Storage::url($this->gambar_course);
        }

        // Default to uploads directory
        return asset('uploads/' . $this->gambar_course);
    }

    // Helper methods
    public function updateCourseStatistics()
    {
        $totalVideos = $this->videos()->count();
        $totalDuration = $this->videos()->sum('durasi_menit');

        $this->update([
            'total_video' => $totalVideos,
            'total_durasi_menit' => $totalDuration,
        ]);
    }

    public function getProgressForUser($userId)
    {
        return $this->userProgress()->where('user_id', $userId)->first();
    }

    public function getCompletionRate()
    {
        $totalEnrolled = $this->userProgress()->count();
        if ($totalEnrolled === 0) return 0;

        $completed = $this->userProgress()->where('status', 'completed')->count();
        return round(($completed / $totalEnrolled) * 100, 2);
    }
}
