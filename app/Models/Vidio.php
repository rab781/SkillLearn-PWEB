<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vidio extends Model
{
    use HasFactory;

    protected $table = 'vidio';
    protected $primaryKey = 'vidio_id';

    protected $fillable = [
        'nama',
        'deskripsi',
        'url',
        'gambar',
        'jumlah_tayang',
        'kategori_kategori_id',
        'channel',
        'durasi_menit',
        'is_active',
    ];

    // Relationships
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_kategori_id');
    }

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class, 'vidio_vidio_id');
    }

    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class, 'vidio_vidio_id');
    }

    // Increment view count
    public function incrementViews()
    {
        $this->increment('jumlah_tayang');
    }
}
