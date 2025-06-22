<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailQuiz extends Model
{
    use HasFactory;

    protected $table = 'detail_quiz';
    protected $primaryKey = 'id';

    protected $fillable = [
        'quiz_id',
        'users_id',
        'jawaban_user',
        'nilai',
    ];

    // Relationships
    public function quiz()
    {
        return $this->belongsTo(Quiz::class, 'quiz_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}
