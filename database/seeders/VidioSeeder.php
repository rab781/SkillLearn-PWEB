<?php

namespace Database\Seeders;

use App\Models\Vidio;
use Illuminate\Database\Seeder;

class VidioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $videos = [
            [
                'nama' => 'HTML CSS Dasar untuk Pemula',
                'deskripsi' => 'Belajar HTML CSS dari dasar untuk membuat website pertama Anda',
                'url' => 'https://www.youtube.com/watch?v=HGTJBPNC-Gw',
                'gambar' => 'https://img.youtube.com/vi/HGTJBPNC-Gw/maxresdefault.jpg',
                'kategori_kategori_id' => 1,
                'channel' => 'Channel 1'
            ],
            [
                'nama' => 'JavaScript untuk Pemula',
                'deskripsi' => 'Tutorial JavaScript lengkap dari dasar hingga mahir',
                'url' => 'https://www.youtube.com/watch?v=RUTV_5m4VeI',
                'gambar' => 'https://img.youtube.com/vi/RUTV_5m4VeI/maxresdefault.jpg',
                'kategori_kategori_id' => 1,
                'channel' => 'Channel 1'
            ],
            [
                'nama' => 'Photoshop untuk Pemula',
                'deskripsi' => 'Belajar Adobe Photoshop dari dasar untuk design grafis',
                'url' => 'https://www.youtube.com/watch?v=IyR_uYsRdPs',
                'gambar' => 'https://img.youtube.com/vi/IyR_uYsRdPs/maxresdefault.jpg',
                'kategori_kategori_id' => 2,
                'channel' => 'Channel 2'

            ],
            [
                'nama' => 'Digital Marketing Strategy',
                'deskripsi' => 'Strategi digital marketing untuk bisnis online',
                'url' => 'https://www.youtube.com/watch?v=nU-IIXBWlS4',
                'gambar' => 'https://img.youtube.com/vi/nU-IIXBWlS4/maxresdefault.jpg',
                'kategori_kategori_id' => 3,
                'channel' => 'Channel 3'
            ]
        ];

        foreach ($videos as $video) {
            Vidio::create($video);
        }
    }
}
