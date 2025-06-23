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
        'jumlah_benar',
        'jumlah_salah',
        'total_soal',
        'detail_jawaban',
        'waktu_mulai',
        'waktu_selesai',
    ];

    protected $casts = [
        'nilai_total' => 'decimal:2',
        'detail_jawaban' => 'array',
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime'
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
        return 'E';
    }

    public function getDuration()
    {
        if (!$this->waktu_mulai || !$this->waktu_selesai) {
            return 0;
        }

        return $this->waktu_mulai->diffInMinutes($this->waktu_selesai);
    }

    public function getDetailedReport()
    {
        return [
            'user' => $this->user->nama_lengkap,
            'quiz' => $this->quiz->judul_quiz,
            'course' => $this->quiz->course->nama_course,
            'nilai' => $this->nilai_total,
            'grade' => $this->getGrade(),
            'jumlah_benar' => $this->jumlah_benar,
            'jumlah_salah' => $this->jumlah_salah,
            'total_soal' => $this->total_soal,
            'persentase_benar' => $this->total_soal > 0 ? ($this->jumlah_benar / $this->total_soal) * 100 : 0,
            'waktu_mulai' => $this->waktu_mulai,
            'waktu_selesai' => $this->waktu_selesai,
            'durasi_menit' => $this->getDuration(),
            'detail_jawaban' => $this->detail_jawaban,
        ];
    }

    public function isPassed()
    {
        return $this->nilai_total >= 60;
    }

    public function answerDetails()
    {
        return $this->hasMany(QuizAnswerDetail::class, 'result_quiz_id', 'result_quiz_id');
    }
}
