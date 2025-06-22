<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $table = 'feedback';
    protected $primaryKey = 'feedback_id';

    protected $fillable = [
        'tanggal',
        'pesan',
        'balasan',
        'course_id',
        'course_video_id',
        'rating',
        'catatan',
        'users_id',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    // Relationships
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function courseVideo()
    {
        return $this->belongsTo(CourseVideo::class, 'course_video_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}
