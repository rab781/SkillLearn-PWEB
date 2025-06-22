<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResultQuiz extends Model
{
    use HasFactory;

    protected $table = 'result_quiz';
    protected $primaryKey = 'id';

    protected $fillable = [
        'vidio_id',
        'users_id',
        'nilai_total',
    ];

    // Relationships
    public function vidio()
    {
        return $this->belongsTo(Vidio::class, 'vidio_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}
