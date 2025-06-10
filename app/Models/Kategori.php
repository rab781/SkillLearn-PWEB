<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    protected $table = 'kategori';
    protected $primaryKey = 'kategori_id';

    protected $fillable = [
        'kategori',
    ];

    // Relationships
    public function vidios()
    {
        return $this->hasMany(Vidio::class, 'kategori_kategori_id');
    }
}
