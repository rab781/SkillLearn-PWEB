<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\CourseSection;
use App\Models\CourseVideo;
use App\Models\QuickReview;
use App\Models\Kategori;
use App\Models\Vidio;
use Illuminate\Support\Facades\DB;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            // Get existing categories and videos
            $phpKategori = Kategori::where('kategori', 'like', '%PHP%')->first() 
                ?? Kategori::where('kategori', 'like', '%Programming%')->first()
                ?? Kategori::first();
            
            $jsKategori = Kategori::where('kategori', 'like', '%JavaScript%')->first()
                ?? Kategori::where('kategori', 'like', '%Web%')->first()
                ?? Kategori::first();

            // Create PHP for Beginners Course
            $phpCourse = Course::create([
                'nama_course' => 'PHP untuk Pemula',
                'deskripsi_course' => 'Belajar PHP dari dasar hingga mahir. Course ini dirancang khusus untuk pemula yang ingin memahami fundamental PHP programming dengan pembelajaran yang terstruktur dan praktis.',
                'level' => 'pemula',
                'kategori_kategori_id' => $phpKategori->kategori_id,
                'gambar_course' => 'https://via.placeholder.com/400x300?text=PHP+Course',
                'is_active' => true,
            ]);

            // Create sections for PHP Course
            $phpSection1 = CourseSection::create([
                'nama_section' => 'Pengenalan PHP',
                'deskripsi_section' => 'Memahami dasar-dasar PHP dan setup environment',
                'urutan_section' => 1,
                'course_id' => $phpCourse->course_id,
            ]);

            $phpSection2 = CourseSection::create([
                'nama_section' => 'Syntax Dasar PHP',
                'deskripsi_section' => 'Mempelajari syntax dasar, variabel, dan tipe data',
                'urutan_section' => 2,
                'course_id' => $phpCourse->course_id,
            ]);

            $phpSection3 = CourseSection::create([
                'nama_section' => 'Control Flow',
                'deskripsi_section' => 'Conditional statements dan loops',
                'urutan_section' => 3,
                'course_id' => $phpCourse->course_id,
            ]);

            $phpSection4 = CourseSection::create([
                'nama_section' => 'Functions dan OOP',
                'deskripsi_section' => 'Functions, Classes, dan Object Oriented Programming',
                'urutan_section' => 4,
                'course_id' => $phpCourse->course_id,
            ]);

            // Get some existing videos (create sample if none exist)
            $videos = Vidio::limit(10)->get();
            if ($videos->count() === 0) {
                // Create sample videos
                for ($i = 1; $i <= 10; $i++) {
                    Vidio::create([
                        'nama' => "PHP Tutorial Video $i",
                        'deskripsi' => "Description for PHP tutorial video $i",
                        'url' => "https://www.youtube.com/watch?v=sample$i",
                        'gambar' => "https://via.placeholder.com/320x180?text=Video+$i",
                        'jumlah_tayang' => rand(100, 5000),
                        'kategori_kategori_id' => $phpKategori->kategori_id,
                        'channel' => 'PHP Learning Channel',
                    ]);
                }
                $videos = Vidio::limit(10)->get();
            }

            // Add videos to sections
            $videoIndex = 0;
            
            // Section 1 videos
            foreach ([1, 2] as $order) {
                if (isset($videos[$videoIndex])) {
                    CourseVideo::create([
                        'course_id' => $phpCourse->course_id,
                        'section_id' => $phpSection1->section_id,
                        'vidio_vidio_id' => $videos[$videoIndex]->vidio_id,
                        'urutan_video' => $order,
                        'durasi_menit' => rand(15, 45),
                        'is_required' => true,
                        'catatan_admin' => $order === 1 ? 'Video pengenalan wajib' : 'Setup environment',
                    ]);
                    $videoIndex++;
                }
            }

            // Section 2 videos
            foreach ([1, 2, 3] as $order) {
                if (isset($videos[$videoIndex])) {
                    CourseVideo::create([
                        'course_id' => $phpCourse->course_id,
                        'section_id' => $phpSection2->section_id,
                        'vidio_vidio_id' => $videos[$videoIndex]->vidio_id,
                        'urutan_video' => $order,
                        'durasi_menit' => rand(20, 35),
                        'is_required' => true,
                        'catatan_admin' => "Materi dasar syntax PHP bagian $order",
                    ]);
                    $videoIndex++;
                }
            }

            // Section 3 videos
            foreach ([1, 2] as $order) {
                if (isset($videos[$videoIndex])) {
                    CourseVideo::create([
                        'course_id' => $phpCourse->course_id,
                        'section_id' => $phpSection3->section_id,
                        'vidio_vidio_id' => $videos[$videoIndex]->vidio_id,
                        'urutan_video' => $order,
                        'durasi_menit' => rand(25, 40),
                        'is_required' => true,
                        'catatan_admin' => $order === 1 ? 'If-else dan switch' : 'For dan while loops',
                    ]);
                    $videoIndex++;
                }
            }

            // Section 4 videos
            foreach ([1, 2, 3] as $order) {
                if (isset($videos[$videoIndex])) {
                    CourseVideo::create([
                        'course_id' => $phpCourse->course_id,
                        'section_id' => $phpSection4->section_id,
                        'vidio_vidio_id' => $videos[$videoIndex]->vidio_id,
                        'urutan_video' => $order,
                        'durasi_menit' => rand(30, 50),
                        'is_required' => true,
                        'catatan_admin' => $order === 1 ? 'PHP Functions' : ($order === 2 ? 'Classes dan Objects' : 'Inheritance dan Polymorphism'),
                    ]);
                    $videoIndex++;
                }
            }

            // Add Quick Reviews
            QuickReview::create([
                'judul_review' => 'Review: Apakah Anda Sudah Memahami Dasar PHP?',
                'konten_review' => '<h3>Pertanyaan Review:</h3><ol><li>Apa itu PHP dan untuk apa digunakan?</li><li>Bagaimana cara menjalankan file PHP?</li><li>Sebutkan 3 tipe data dasar di PHP!</li></ol><p><strong>Pastikan Anda bisa menjawab pertanyaan-pertanyaan di atas sebelum melanjutkan ke section berikutnya.</strong></p>',
                'tipe_review' => 'setelah_section',
                'course_id' => $phpCourse->course_id,
                'section_id' => $phpSection1->section_id,
                'urutan_review' => 1,
            ]);

            QuickReview::create([
                'judul_review' => 'Quick Check: Syntax dan Variabel PHP',
                'konten_review' => '<h3>Mari Uji Pemahaman Anda:</h3><ul><li>Coba buat variabel dengan 3 tipe data berbeda</li><li>Praktikkan cara echo dan print</li><li>Buat array sederhana dan tampilkan isinya</li></ul><p><em>Tips: Praktik langsung akan membantu Anda memahami lebih baik!</em></p>',
                'tipe_review' => 'setelah_section',
                'course_id' => $phpCourse->course_id,
                'section_id' => $phpSection2->section_id,
                'urutan_review' => 2,
            ]);

            QuickReview::create([
                'judul_review' => 'Selamat! Anda Sudah Menguasai Setengah Course',
                'konten_review' => '<h3>🎉 Progress Anda Luar Biasa!</h3><p>Anda sudah menyelesaikan:</p><ul><li>✅ Pengenalan PHP</li><li>✅ Syntax Dasar</li><li>✅ Control Flow</li></ul><p>Selanjutnya kita akan belajar tentang Functions dan OOP. Ini adalah bagian yang sangat penting untuk menjadi PHP developer yang handal!</p><p><strong>Siap melanjutkan? Let\'s go! 🚀</strong></p>',
                'tipe_review' => 'tengah_course',
                'course_id' => $phpCourse->course_id,
                'urutan_review' => 3,
            ]);

            // Create JavaScript Course
            $jsCourse = Course::create([
                'nama_course' => 'JavaScript Modern untuk Web Development',
                'deskripsi_course' => 'Pelajari JavaScript modern (ES6+) untuk web development. Dari DOM manipulation hingga async programming.',
                'level' => 'menengah',
                'kategori_kategori_id' => $jsKategori->kategori_id,
                'gambar_course' => 'https://via.placeholder.com/400x300?text=JavaScript+Course',
                'is_active' => true,
            ]);

            // Create sections for JS Course
            $jsSection1 = CourseSection::create([
                'nama_section' => 'JavaScript Fundamentals',
                'deskripsi_section' => 'Dasar-dasar JavaScript dan ES6+ features',
                'urutan_section' => 1,
                'course_id' => $jsCourse->course_id,
            ]);

            $jsSection2 = CourseSection::create([
                'nama_section' => 'DOM Manipulation',
                'deskripsi_section' => 'Menggunakan JavaScript untuk mengontrol HTML dan CSS',
                'urutan_section' => 2,
                'course_id' => $jsCourse->course_id,
            ]);

            // Update course statistics
            $phpCourse->updateCourseStatistics();
            $jsCourse->updateCourseStatistics();

            $this->command->info('Courses seeded successfully!');
            $this->command->info("Created courses:");
            $this->command->info("- {$phpCourse->nama_course} ({$phpCourse->total_video} videos)");
            $this->command->info("- {$jsCourse->nama_course} ({$jsCourse->total_video} videos)");
        });
    }
}
