<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $table = 'feedback';
    protected $primaryKey = 'feedback_id';

    protected $fillable = [
        'tanggal',
        'pesan',
        'balasan',
        'vidio_vidio_id',
        'users_id',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    // Relationships
    public function vidio()
    {
        return $this->belongsTo(Vidio::class, 'vidio_vidio_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}
