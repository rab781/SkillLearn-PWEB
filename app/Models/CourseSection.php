<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseSection extends Model
{
    use HasFactory;

    protected $table = 'course_sections';
    protected $primaryKey = 'section_id';

    protected $fillable = [
        'nama_section',
        'deskripsi_section',
        'urutan_section',
        'course_id',
    ];

    // Relationships
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function videos()
    {
        return $this->hasMany(CourseVideo::class, 'section_id')->orderBy('urutan_video');
    }    public function quickReviews()
    {
        return $this->hasMany(QuickReview::class, 'section_id')->orderBy('urutan_review');
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class, 'section_id')->orderBy('urutan');
    }

    // Helper methods
    public function getTotalDuration()
    {
        return $this->videos()->sum('durasi_menit');
    }

    public function getTotalVideos()
    {
        return $this->videos()->count();
    }

    public function getNextSection()
    {
        return CourseSection::where('course_id', $this->course_id)
            ->where('urutan_section', '>', $this->urutan_section)
            ->orderBy('urutan_section')
            ->first();
    }

    public function getPreviousSection()
    {
        return CourseSection::where('course_id', $this->course_id)
            ->where('urutan_section', '<', $this->urutan_section)
            ->orderBy('urutan_section', 'desc')
            ->first();
    }
}
