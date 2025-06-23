<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\CourseSection;
use App\Models\CourseVideo;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\Kategori;
use App\Models\User;
use App\Models\Vidio;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SimpleCourseSeeder extends Seeder
{
    /**
     * Create basic courses, sections, videos, and quizzes.
     */
    public function run(): void
    {
        DB::transaction(function () {
            // Ensure we have required categories
            $programmingKategori = Kategori::firstOrCreate(['kategori' => 'Programming']);
            $designKategori = Kategori::firstOrCreate(['kategori' => 'Web Design']);
            $marketingKategori = Kategori::firstOrCreate(['kategori' => 'Digital Marketing']);

            // Get or create a test user
            $user = User::firstOrCreate(
                ['email' => 'student@skilllearn.com'],
                [
                    'nama_lengkap' => 'Test Student',
                    'username' => 'teststudent',
                    'password' => bcrypt('password123'),
                    'no_telepon' => '081234567890',
                    'role' => 'CU',
                ]
            );

            // Create main Web Development course
            $webDevCourse = Course::create([
                'nama_course' => 'Web Development Mastery 2025',
                'deskripsi_course' => 'Kursus komprehensif untuk menjadi web developer profesional. Mempelajari HTML, CSS, JavaScript, dan backend development dengan PHP dan MySQL.',
                'level' => 'menengah',
                'kategori_kategori_id' => $programmingKategori->kategori_id,
                'gambar_course' => 'https://images.unsplash.com/photo-1498050108023-c5249f4df085?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1172&q=80',
                'is_active' => true,
            ]);

            // Create sections
            $htmlSection = CourseSection::create([
                'nama_section' => 'HTML & Semantic Markup',
                'deskripsi_section' => 'Belajar HTML5, struktur dokumen, dan semantic elements',
                'urutan_section' => 1,
                'course_id' => $webDevCourse->course_id,
            ]);

            $cssSection = CourseSection::create([
                'nama_section' => 'CSS & Modern Styling',
                'deskripsi_section' => 'Mempelajari CSS3, selectors, layout, dan responsive design',
                'urutan_section' => 2,
                'course_id' => $webDevCourse->course_id,
            ]);

            $jsSection = CourseSection::create([
                'nama_section' => 'JavaScript Fundamentals',
                'deskripsi_section' => 'Mempelajari dasar-dasar JavaScript, DOM manipulation, dan event handling',
                'urutan_section' => 3,
                'course_id' => $webDevCourse->course_id,
            ]);

            // Create videos for HTML section
            $this->createSectionVideos($htmlSection, [
                [
                    'judul' => 'HTML Introduction',
                    'deskripsi' => 'Pengenalan HTML dan struktur dokumen',
                    'durasi' => 10,
                ],
                [
                    'judul' => 'HTML Tags and Elements',
                    'deskripsi' => 'Belajar tags dan elements dasar HTML',
                    'durasi' => 15,
                ],
                [
                    'judul' => 'Semantic HTML',
                    'deskripsi' => 'Penggunaan semantic elements untuk struktur yang baik',
                    'durasi' => 12,
                ],
            ]);

            // Create videos for CSS section
            $this->createSectionVideos($cssSection, [
                [
                    'judul' => 'CSS Introduction',
                    'deskripsi' => 'Pengenalan CSS dan cara kerjanya',
                    'durasi' => 10,
                ],
                [
                    'judul' => 'CSS Selectors',
                    'deskripsi' => 'Belajar tentang selectors di CSS',
                    'durasi' => 15,
                ],
                [
                    'judul' => 'CSS Layout',
                    'deskripsi' => 'Memahami layout dan positioning di CSS',
                    'durasi' => 18,
                ],
            ]);

            // Create videos for JS section
            $this->createSectionVideos($jsSection, [
                [
                    'judul' => 'JavaScript Introduction',
                    'deskripsi' => 'Pengenalan JavaScript dan cara kerjanya',
                    'durasi' => 12,
                ],
                [
                    'judul' => 'JavaScript Syntax',
                    'deskripsi' => 'Memahami syntax dasar JavaScript',
                    'durasi' => 15,
                ],
                [
                    'judul' => 'DOM Manipulation',
                    'deskripsi' => 'Memanipulasi DOM dengan JavaScript',
                    'durasi' => 18,
                ],
            ]);

            // Update course with total videos and duration
            $totalVideos = CourseVideo::where('course_id', $webDevCourse->course_id)->count();
            $totalDuration = CourseVideo::where('course_id', $webDevCourse->course_id)->sum('durasi_menit');

            $webDevCourse->update([
                'total_video' => $totalVideos,
                'total_durasi_menit' => $totalDuration,
            ]);

            // Create quizzes
            $this->createCourseTenantQuizzes($webDevCourse, $htmlSection, $cssSection, $jsSection);

            // Second course (UI/UX Design)
            $uiuxCourse = Course::create([
                'nama_course' => 'UI/UX Design Fundamentals',
                'deskripsi_course' => 'Pelajari prinsip dasar desain UI/UX untuk menciptakan pengalaman pengguna yang luar biasa.',
                'level' => 'pemula',
                'kategori_kategori_id' => $designKategori->kategori_id,
                'gambar_course' => 'https://images.unsplash.com/photo-1561070791-2526d30994b5?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=764&q=80',
                'is_active' => true,
            ]);

            // Create basic sections for second course
            $uiuxSection1 = CourseSection::create([
                'nama_section' => 'UI/UX Introduction',
                'deskripsi_section' => 'Pengenalan UI/UX Design',
                'urutan_section' => 1,
                'course_id' => $uiuxCourse->course_id,
            ]);

            $this->createSectionVideos($uiuxSection1, [
                [
                    'judul' => 'What is UI/UX',
                    'deskripsi' => 'Pengenalan UI/UX dan perbedaannya',
                    'durasi' => 10,
                ],
                [
                    'judul' => 'UI/UX Design Principles',
                    'deskripsi' => 'Prinsip-prinsip dasar dalam UI/UX design',
                    'durasi' => 15,
                ],
            ]);

            // Update course totals
            $totalVideos = CourseVideo::where('course_id', $uiuxCourse->course_id)->count();
            $totalDuration = CourseVideo::where('course_id', $uiuxCourse->course_id)->sum('durasi_menit');

            $uiuxCourse->update([
                'total_video' => $totalVideos,
                'total_durasi_menit' => $totalDuration,
            ]);

            $this->command->info('✅ Courses created successfully');
        });
    }

    /**
     * Create videos for a section
     */
    private function createSectionVideos($section, $videoData)
    {
        $courseId = $section->course_id;
        $order = 1;
        $videoUrl = 'https://www.youtube.com/embed/UB1O30fR-EE'; // Default URL

        foreach ($videoData as $data) {
            // Create video
            $video = Vidio::create([
                'nama' => $data['judul'],
                'deskripsi' => $data['deskripsi'],
                'url' => $videoUrl,
                'gambar' => 'https://picsum.photos/seed/' . Str::slug($data['judul']) . '/640/360',
                'jumlah_tayang' => rand(10, 100),
                'kategori_kategori_id' => $section->course->kategori_kategori_id,
                'durasi_menit' => $data['durasi'],
                'is_active' => true,
                'channel' => 'SkillLearn',
            ]);

            // Connect video to course section
            CourseVideo::create([
                'course_id' => $courseId,
                'section_id' => $section->section_id,
                'vidio_vidio_id' => $video->vidio_id,
                'urutan_video' => $order,
                'durasi_menit' => $data['durasi'],
                'is_required' => true,
            ]);

            $order++;
        }
    }

    /**
     * Create quizzes for the course
     */
    private function createCourseTenantQuizzes($course, $htmlSection, $cssSection, $jsSection)
    {
        // Introduction quiz
        $introQuiz = Quiz::create([
            'judul_quiz' => 'Selamat Datang di ' . $course->nama_course,
            'deskripsi_quiz' => 'Pengenalan kursus',
            'konten_quiz' => 'Kursus ini dirancang untuk memberikan Anda pemahaman mendalam tentang web development. Pastikan untuk mengikuti setiap video secara berurutan untuk hasil terbaik!',
            'tipe_quiz' => 'tengah_course', // Used for welcome message
            'course_id' => $course->course_id,
            'urutan' => 1,
            'is_active' => true,
        ]);

        // HTML section quiz
        $htmlQuiz = Quiz::create([
            'judul_quiz' => 'HTML Fundamentals Quiz',
            'deskripsi_quiz' => 'Tes pemahaman Anda tentang dasar-dasar HTML',
            'tipe_quiz' => 'setelah_section',
            'durasi_menit' => 15,
            'course_id' => $course->course_id,
            'section_id' => $htmlSection->section_id,
            'urutan' => 2,
            'is_active' => true,
        ]);

        // Add questions to HTML quiz
        $this->addQuizQuestions($htmlQuiz, [
            [
                'pertanyaan' => 'Tag HTML untuk heading level 1 adalah:',
                'pilihan' => ['<h1>', '<heading1>', '<head1>', '<title>'],
                'jawaban_benar' => '<h1>',
            ],
            [
                'pertanyaan' => 'Elemen HTML yang digunakan untuk membuat list bernomor adalah:',
                'pilihan' => ['<ul>', '<ol>', '<dl>', '<list>'],
                'jawaban_benar' => '<ol>',
            ],
            [
                'pertanyaan' => 'Tag yang digunakan untuk menambahkan gambar ke halaman web adalah:',
                'pilihan' => ['<picture>', '<image>', '<img>', '<src>'],
                'jawaban_benar' => '<img>',
            ],
        ]);

        // JavaScript section quiz
        $jsQuiz = Quiz::create([
            'judul_quiz' => 'JavaScript Quiz',
            'deskripsi_quiz' => 'Tes pemahaman Anda tentang JavaScript',
            'tipe_quiz' => 'setelah_section',
            'durasi_menit' => 20,
            'course_id' => $course->course_id,
            'section_id' => $jsSection->section_id,
            'urutan' => 3,
            'is_active' => true,
        ]);

        // Add questions to JavaScript quiz
        $this->addQuizQuestions($jsQuiz, [
            [
                'pertanyaan' => 'Cara yang benar untuk mendeklarasikan variabel di JavaScript adalah:',
                'pilihan' => ['var x = 5;', 'variable x = 5;', 'x = 5;', 'int x = 5;'],
                'jawaban_benar' => 'var x = 5;',
            ],
            [
                'pertanyaan' => 'Fungsi yang digunakan untuk memilih elemen HTML berdasarkan ID adalah:',
                'pilihan' => ['document.query()', 'document.getElementById()', 'document.getElement()', 'document.findById()'],
                'jawaban_benar' => 'document.getElementById()',
            ],
            [
                'pertanyaan' => 'Event yang terjadi ketika pengguna mengklik elemen HTML adalah:',
                'pilihan' => ['onhover', 'onchange', 'onclick', 'onmouseover'],
                'jawaban_benar' => 'onclick',
            ],
        ]);

        // Final review
        $finalQuiz = Quiz::create([
            'judul_quiz' => 'Selamat Atas Pencapaian Anda!',
            'deskripsi_quiz' => 'Review akhir kursus',
            'konten_quiz' => 'Anda telah menyelesaikan seluruh kursus Web Development Mastery! Ini adalah pencapaian yang luar biasa. Anda sekarang memiliki keterampilan untuk membuat website lengkap dari awal hingga akhir.',
            'tipe_quiz' => 'akhir_course',
            'course_id' => $course->course_id,
            'urutan' => 4,
            'is_active' => true,
        ]);
    }

    /**
     * Add questions to a quiz
     */
    private function addQuizQuestions($quiz, $questions)
    {
        $questionNumber = 1;

        foreach ($questions as $q) {
            QuizQuestion::create([
                'quiz_id' => $quiz->quiz_id,
                'urutan_pertanyaan' => $questionNumber,
                'pertanyaan' => $q['pertanyaan'],
                'pilihan_jawaban' => json_encode($q['pilihan']),
                'jawaban_benar' => $q['jawaban_benar'],
                'is_active' => true,
            ]);

            $questionNumber++;
        }
    }
}
