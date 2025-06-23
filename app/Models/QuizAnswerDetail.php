<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizAnswerDetail extends Model
{
    use HasFactory;

    protected $table = 'quiz_answer_details';
    protected $primaryKey = 'answer_detail_id';

    protected $fillable = [
        'result_quiz_id',
        'quiz_id',
        'urutan_pertanyaan',
        'pertanyaan',
        'jawaban_user',
        'jawaban_benar',
        'is_correct',
        'skor_pertanyaan',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
    ];

    // Relationships
    public function quizResult()
    {
        return $this->belongsTo(QuizResult::class, 'result_quiz_id', 'result_quiz_id');
    }

    public function quiz()
    {
        return $this->belongsTo(Quiz::class, 'quiz_id', 'quiz_id');
    }
}
