<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bookmark extends Model
{
    use HasFactory;

    protected $table = 'bookmark';
    protected $primaryKey = 'bookmark_id';

    protected $fillable = [
        'users_id',
        'course_id',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'users_id', 'users_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'course_id');
    }
}
