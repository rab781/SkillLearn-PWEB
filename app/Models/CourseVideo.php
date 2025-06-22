<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseVideo extends Model
{
    use HasFactory;

    protected $table = 'course_videos';
    protected $primaryKey = 'course_video_id';

    protected $fillable = [
        'course_id',
        'section_id',
        'vidio_vidio_id',
        'urutan_video',
        'durasi_menit',
        'is_required',
        'catatan_admin',
    ];

    protected $casts = [
        'is_required' => 'boolean',
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

    public function userProgress()
    {
        return $this->hasMany(UserVideoProgress::class, 'vidio_vidio_id', 'vidio_vidio_id');
    }

    // Helper methods
    public function getNextVideo()
    {
        return CourseVideo::where('section_id', $this->section_id)
            ->where('urutan_video', '>', $this->urutan_video)
            ->orderBy('urutan_video')
            ->first();
    }

    public function getPreviousVideo()
    {
        return CourseVideo::where('section_id', $this->section_id)
            ->where('urutan_video', '<', $this->urutan_video)
            ->orderBy('urutan_video', 'desc')
            ->first();
    }

    public function isCompletedByUser($userId)
    {
        return UserVideoProgress::where('user_id', $userId)
            ->where('vidio_vidio_id', $this->vidio_vidio_id)
            ->where('course_id', $this->course_id)
            ->where('is_completed', true)
            ->exists();
    }

    public function getProgressForUser($userId)
    {
        return UserVideoProgress::where('user_id', $userId)
            ->where('vidio_vidio_id', $this->vidio_vidio_id)
            ->where('course_id', $this->course_id)
            ->first();
    }
}
