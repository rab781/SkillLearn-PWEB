<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuickReview extends Model
{
    use HasFactory;

    protected $table = 'quick_reviews';
    protected $primaryKey = 'review_id';

    protected $fillable = [
        'judul_review',
        'konten_review',
        'tipe_review',
        'course_id',
        'section_id',
        'vidio_vidio_id',
        'urutan_review',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationships
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function section()
    {
        return $this->belongsTo(CourseSection::class, 'section_id');
    }

    public function vidio()
    {
        return $this->belongsTo(Vidio::class, 'vidio_vidio_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('tipe_review', $type);
    }

    // Helper methods
    public static function getTypesArray()
    {
        return [
            'setelah_video' => 'Setelah Video',
            'setelah_section' => 'Setelah Section',
            'tengah_course' => 'Tengah Course'
        ];
    }

    public function getTypeLabel()
    {
        $types = self::getTypesArray();
        return $types[$this->tipe_review] ?? $this->tipe_review;
    }
}
