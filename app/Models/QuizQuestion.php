<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizQuestion extends Model
{
    use HasFactory;

    protected $table = 'quiz_questions';
    protected $primaryKey = 'question_id';

    protected $fillable = [
        'quiz_id',
        'urutan_pertanyaan',
        'pertanyaan',
        'pilihan_jawaban',
        'jawaban_benar',
        'bobot_nilai',
        'is_active'
    ];

    protected $casts = [
        'pilihan_jawaban' => 'array',
        'is_active' => 'boolean'
    ];

    // Relationships
    public function quiz()
    {
        return $this->belongsTo(Quiz::class, 'quiz_id', 'quiz_id');
    }

    // Helper methods
    public function getPilihanArray()
    {
        return is_string($this->pilihan_jawaban) ? json_decode($this->pilihan_jawaban, true) : $this->pilihan_jawaban;
    }

    public function checkAnswer($userAnswer)
    {
        return $userAnswer === $this->jawaban_benar;
    }
}
