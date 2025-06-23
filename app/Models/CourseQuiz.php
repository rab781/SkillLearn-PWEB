<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseQuiz extends Model
{
    protected $table = 'course_quizzes';
    protected $primaryKey = 'id';

    protected $fillable = [
        'course_id',
        'quiz_id',
        'position',
        'reference_id',
        'order'
    ];

    protected $casts = [
        'course_id' => 'integer',
        'quiz_id' => 'integer',
        'reference_id' => 'integer',
        'order' => 'integer'
    ];

    /**
     * Get the course that this quiz belongs to
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id', 'course_id');
    }

    /**
     * Get the quiz
     */
    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class, 'quiz_id', 'quiz_id');
    }

    /**
     * Get the reference section (if position is after_section)
     */
    public function section(): BelongsTo
    {
        return $this->belongsTo(CourseSection::class, 'reference_id', 'section_id');
    }

    /**
     * Get the reference video (if position is after_video)
     */
    public function video(): BelongsTo
    {
        return $this->belongsTo(CourseVideo::class, 'reference_id', 'course_video_id');
    }

    /**
     * Get position label for display
     */
    public function getPositionLabelAttribute(): string
    {
        switch ($this->position) {
            case 'start':
                return 'Awal Course';
            case 'end':
                return 'Akhir Course';
            case 'after_section':
                return 'Setelah Section: ' . ($this->section->nama_section ?? 'N/A');
            case 'after_video':
                return 'Setelah Video: ' . ($this->video->vidio->nama ?? 'N/A');
            case 'between_sections':
                return 'Antara Section';
            default:
                return 'Posisi Tidak Diketahui';
        }
    }
}
