<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizResult extends Model
{
    use HasFactory;

    protected $table = 'quiz_results';
    protected $primaryKey = 'result_quiz_id';

    protected $fillable = [
        'quiz_id',
        'users_id',
        'nilai_total',
    ];

    protected $casts = [
        'nilai_total' => 'decimal:2'
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

    // Helper methods
    public function getGrade()
    {
        if ($this->nilai_total >= 90) return 'A';
        if ($this->nilai_total >= 80) return 'B';
        if ($this->nilai_total >= 70) return 'C';
        if ($this->nilai_total >= 60) return 'D';
        return 'F';
    }

    public function isPassed()
    {
        return $this->nilai_total >= 60;
    }
}
