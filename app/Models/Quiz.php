<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    protected $table = 'quizzes';
    protected $primaryKey = 'quiz_id';

    protected $fillable = [
        'judul_quiz',
        'deskripsi_quiz',
        'tipe_quiz',
        'course_id',
        'section_id',
        'vidio_vidio_id',
        'urutan',
        'durasi_menit',
        'konten_quiz',
        'is_active',
        'soal', // backward compatibility
        'jawaban', // backward compatibility
    ];

    protected $casts = [
        'jawaban' => 'array',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'course_id');
    }

    public function section()
    {
        return $this->belongsTo(CourseSection::class, 'section_id', 'section_id');
    }

    public function video()
    {
        return $this->belongsTo(Vidio::class, 'vidio_vidio_id', 'vidio_id');
    }

    public function userAnswers()
    {
        return $this->hasMany(QuizResult::class, 'quiz_id');
    }

    public function results()
    {
        return $this->hasMany(QuizResult::class, 'quiz_id');
    }

    public function questions()
    {
        return $this->hasMany(QuizQuestion::class, 'quiz_id', 'quiz_id')->orderBy('urutan_pertanyaan');
    }

    // Helper methods
    public function getCorrectAnswer()
    {
        if (is_array($this->jawaban)) {
            foreach ($this->jawaban as $answer) {
                if (isset($answer['is_correct']) && $answer['is_correct']) {
                    return $answer;
                }
            }
        }
        return null;
    }

    public function checkAnswer($userAnswer)
    {
        $correctAnswer = $this->getCorrectAnswer();
        return $correctAnswer && $correctAnswer['text'] === $userAnswer;
    }
}
