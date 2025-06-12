<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfilPelanggan extends Model
{
    use HasFactory;

    protected $table = 'profil_pelanggans';

    protected $fillable = [
        'nama_lengkap',
        'email',
        'no_telepon',
        'username',
        'alamat',
        'password',
    ];

    protected $hidden = [
        'password',
    ];
}
