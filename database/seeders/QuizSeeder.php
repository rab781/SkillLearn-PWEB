<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Quiz;

class QuizSeeder extends Seeder
{
    public function run()
    {
        // Soal untuk Video ID 1
        Quiz::create([
            'vidio_id' => 1,
            'soal' => 'Apa kepanjangan dari HTML?',
            'pilihan' => json_encode([
                'Hypertext Markup Language',
                'Hyper Markup Language',
                'Bahasa Pemrograman',
                'Semua Benar'
            ]),
            'jawaban_benar' => 'Hypertext Markup Language',
        ]);

        Quiz::create([
            'vidio_id' => 1,
            'soal' => 'Apa fungsi dari CSS?',
            'pilihan' => json_encode([
                'Mengatur struktur halaman',
                'Mengatur tampilan halaman',
                'Mengatur logika program',
                'Semua Benar'
            ]),
            'jawaban_benar' => 'Mengatur tampilan halaman',
        ]);

        Quiz::create([
            'vidio_id' => 1,
            'soal' => 'Apa itu JavaScript?',
            'pilihan' => json_encode([
                'Bahasa Pemrograman',
                'Bahasa Markup',
                'Bahasa Styling',
                'Semua Benar'
            ]),
            'jawaban_benar' => 'Bahasa Pemrograman',
        ]);

        Quiz::create([
            'vidio_id' => 1,
            'soal' => 'Apa itu PHP?',
            'pilihan' => json_encode([
                'Bahasa Pemrograman Server-side',
                'Bahasa Pemrograman Client-side',
                'Bahasa Styling',
                'Semua Benar'
            ]),
            'jawaban_benar' => 'Bahasa Pemrograman Server-side',
        ]);

        Quiz::create([
            'vidio_id' => 1,
            'soal' => 'Apa itu SQL?',
            'pilihan' => json_encode([
                'Bahasa Query Database',
                'Bahasa Pemrograman',
                'Bahasa Styling',
                'Semua Benar'
            ]),
            'jawaban_benar' => 'Bahasa Query Database',
        ]);

        // Soal untuk Video ID 2
        Quiz::create([
            'vidio_id' => 2,
            'soal' => 'Apa itu API?',
            'pilihan' => json_encode([
                'Application Programming Interface',
                'Application Programming Integration',
                'Application Protocol Interface',
                'Semua Benar'
            ]),
            'jawaban_benar' => 'Application Programming Interface',
        ]);

        Quiz::create([
            'vidio_id' => 2,
            'soal' => 'Apa itu REST?',
            'pilihan' => json_encode([
                'Representational State Transfer',
                'Representational Style Transfer',
                'Remote State Transfer',
                'Semua Benar'
            ]),
            'jawaban_benar' => 'Representational State Transfer',
        ]);

        Quiz::create([
            'vidio_id' => 2,
            'soal' => 'Apa itu JSON?',
            'pilihan' => json_encode([
                'JavaScript Object Notation',
                'JavaScript Object Network',
                'JavaScript Online Notation',
                'Semua Benar'
            ]),
            'jawaban_benar' => 'JavaScript Object Notation',
        ]);

        Quiz::create([
            'vidio_id' => 2,
            'soal' => 'Apa itu HTTP?',
            'pilihan' => json_encode([
                'Hypertext Transfer Protocol',
                'Hypertext Transfer Process',
                'Hypertext Transfer Program',
                'Semua Benar'
            ]),
            'jawaban_benar' => 'Hypertext Transfer Protocol',
        ]);

        Quiz::create([
            'vidio_id' => 2,
            'soal' => 'Apa itu HTTPS?',
            'pilihan' => json_encode([
                'Hypertext Transfer Protocol Secure',
                'Hypertext Transfer Protocol Safety',
                'Hypertext Transfer Protocol Security',
                'Semua Benar'
            ]),
            'jawaban_benar' => 'Hypertext Transfer Protocol Secure',
        ]);
    }
}
