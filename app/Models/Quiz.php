<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    protected $table = 'quiz';
    protected $primaryKey = 'id';

    protected $fillable = [
        'vidio_id',
        'soal',
        'pilihan',
        'jawaban_benar',
    ];

    protected $casts = [
    'pilihan' => 'array',
    ];

    // Relationships
    public function vidio()
    {
        return $this->belongsTo(Vidio::class, 'vidio_id');
    }

    public function detailQuiz()
    {
        return $this->hasMany(DetailQuiz::class, 'quiz_id');
    }
}

