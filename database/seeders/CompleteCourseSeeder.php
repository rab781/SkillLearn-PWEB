<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\CourseSection;
use App\Models\CourseVideo;
use App\Models\QuickReview;
use App\Models\Kategori;
use App\Models\Vidio;
use App\Models\User;
use App\Models\Bookmark;
use App\Models\Feedback;
use App\Models\RiwayatTonton;
use App\Models\Quiz;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CompleteCourseSeeder extends Seeder
{
    /**
     * Run the database seeds to create a complete and comprehensive course.
     */
    public function run(): void
    {
        DB::transaction(function () {
            // Ensure we have required categories
            $programmingKategori = $this->createOrGetCategory('Programming');
            $designKategori = $this->createOrGetCategory('Web Design');
            $marketingKategori = $this->createOrGetCategory('Digital Marketing');

            // Get or create a test user
            $user = User::firstOrCreate(
                ['email' => 'student@skilllearn.com'],
                [
                    'nama_lengkap' => 'Test Student',
                    'username' => 'teststudent',
                    'password' => bcrypt('password123'),
                    'no_telepon' => '081234567890', // Adding required phone number
                    'role' => 'CU', // Customer role
                ]
            );

            // Create main comprehensive course
            $webDevCourse = Course::create([
                'nama_course' => 'Web Development Mastery 2025',
                'deskripsi_course' => 'Kursus komprehensif untuk menjadi web developer profesional. Mempelajari HTML, CSS, JavaScript, dan backend development dengan PHP dan MySQL. Course ini mencakup pembuatan proyek website lengkap dari awal hingga deployment.',
                'level' => 'menengah',
                'kategori_kategori_id' => $programmingKategori->kategori_id,
                'gambar_course' => 'https://images.unsplash.com/photo-1498050108023-c5249f4df085?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1172&q=80',
                'is_active' => true,
            ]);

            // Create sections for Web Development course
            $sections = $this->createWebDevCourseSections($webDevCourse);

            // Create videos and connect them to sections
            $this->createWebDevCourseVideos($webDevCourse, $sections);

            // Update course with total videos and duration
            $this->updateCourseTotals($webDevCourse);

            // Create quizzes for the course (including review-type quizzes)
            $this->createQuizzes($webDevCourse, $sections);

            // Create bookmarks, feedback and watch history
            $this->createUserInteractions($webDevCourse, $user);

            // Create second course (UI/UX Design)
            $uiuxCourse = Course::create([
                'nama_course' => 'UI/UX Design Fundamentals',
                'deskripsi_course' => 'Pelajari prinsip dasar desain UI/UX untuk menciptakan pengalaman pengguna yang luar biasa. Kursus ini mencakup teori desain, wireframing, prototyping, dan user research.',
                'level' => 'pemula',
                'kategori_kategori_id' => $designKategori->kategori_id,
                'gambar_course' => 'https://images.unsplash.com/photo-1561070791-2526d30994b5?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=764&q=80',
                'is_active' => true,
            ]);

            $this->createBasicCourseSections($uiuxCourse);
            $this->updateCourseTotals($uiuxCourse);

            // Create third course (Digital Marketing)
            $marketingCourse = Course::create([
                'nama_course' => 'Digital Marketing Strategy',
                'deskripsi_course' => 'Pelajari strategi pemasaran digital modern, termasuk SEO, content marketing, social media, dan email marketing untuk membangun kehadiran online yang kuat.',
                'level' => 'lanjut',
                'kategori_kategori_id' => $marketingKategori->kategori_id,
                'gambar_course' => 'https://images.unsplash.com/photo-1432888622747-4eb9a8efeb07?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1074&q=80',
                'is_active' => true,
            ]);

            $this->createBasicCourseSections($marketingCourse);
            $this->updateCourseTotals($marketingCourse);

            // Output confirmation
            $this->command->info('✅ Main comprehensive course created: ' . $webDevCourse->nama_course);
            $this->command->info('✅ Secondary course created: ' . $uiuxCourse->nama_course);
            $this->command->info('✅ Third course created: ' . $marketingCourse->nama_course);
        });

        // Create review-type quiz after JavaScript section
        Quiz::create([
            'judul_quiz' => 'Selamat! JavaScript Fundamentals Sudah Dikuasai',
            'deskripsi_quiz' => "Review setelah JavaScript section",
            'konten_quiz' => "Anda telah menyelesaikan bagian JavaScript yang cukup menantang. Sekarang Anda bisa membuat website interaktif!\n\nSelanjutnya kita akan belajar PHP untuk menangani logika di server side.",
            'tipe_quiz' => 'tengah_course',
            'course_id' => $course->course_id,
            'section_id' => $sections[3]->section_id,
            'urutan' => 5,
            'is_active' => true,
        ]);

        // Create review-type quiz at course end
        Quiz::create([
            'judul_quiz' => 'Selamat Atas Pencapaian Anda!',
            'deskripsi_quiz' => "Review akhir kursus",
            'konten_quiz' => "Anda telah menyelesaikan seluruh kursus Web Development Mastery! Ini adalah pencapaian yang luar biasa.\n\nAnda sekarang memiliki keterampilan untuk membuat website lengkap dari awal hingga akhir. Jangan lupa untuk terus berlatih dan membangun portofolio Anda!",
            'tipe_quiz' => 'akhir_course',
            'course_id' => $course->course_id,
            'urutan' => 8,
            'is_active' => true,
        ]);

        // Add questions to final quizion run(): void
    {
        DB::transaction(function () {
            // Ensure we have required categories
            $programmingKategori = $this->createOrGetCategory('Programming');
            $designKategori = $this->createOrGetCategory('Web Design');
            $marketingKategori = $this->createOrGetCategory('Digital Marketing');

            // Get or create a test user
            $user = User::firstOrCreate(
                ['email' => 'student@skilllearn.com'],
                [
                    'nama_lengkap' => 'Test Student',
                    'username' => 'teststudent',
                    'password' => bcrypt('password123'),
                    'role' => 'CU', // Customer role
                ]
            );

            // Create main comprehensive course
            $webDevCourse = Course::create([
                'nama_course' => 'Web Development Mastery 2025',
                'deskripsi_course' => 'Kursus komprehensif untuk menjadi web developer profesional. Mempelajari HTML, CSS, JavaScript, dan backend development dengan PHP dan MySQL. Course ini mencakup pembuatan proyek website lengkap dari awal hingga deployment.',
                'level' => 'menengah',
                'kategori_kategori_id' => $programmingKategori->kategori_id,
                'gambar_course' => 'https://images.unsplash.com/photo-1498050108023-c5249f4df085?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1172&q=80',
                'is_active' => true,
            ]);

            // Create sections for Web Development course
            $sections = $this->createWebDevCourseSections($webDevCourse);

            // Create videos and connect them to sections
            $this->createWebDevCourseVideos($webDevCourse, $sections);

            // Update course with total videos and duration
            $this->updateCourseTotals($webDevCourse);

            // Create quizzes for the course (including review-type quizzes)
            $this->createQuizzes($webDevCourse, $sections);

            // Create bookmarks, feedback and watch history
            $this->createUserInteractions($webDevCourse, $user);

            // Create second course (UI/UX Design)
            $uiuxCourse = Course::create([
                'nama_course' => 'UI/UX Design Fundamentals',
                'deskripsi_course' => 'Pelajari prinsip dasar desain UI/UX untuk menciptakan pengalaman pengguna yang luar biasa. Kursus ini mencakup teori desain, wireframing, prototyping, dan user research.',
                'level' => 'pemula',
                'kategori_kategori_id' => $designKategori->kategori_id,
                'gambar_course' => 'https://images.unsplash.com/photo-1561070791-2526d30994b5?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=764&q=80',
                'is_active' => true,
            ]);

            $this->createBasicCourseSections($uiuxCourse);
            $this->updateCourseTotals($uiuxCourse);

            // Create third course (Digital Marketing)
            $marketingCourse = Course::create([
                'nama_course' => 'Digital Marketing Strategy',
                'deskripsi_course' => 'Pelajari strategi pemasaran digital modern, termasuk SEO, content marketing, social media, dan email marketing untuk membangun kehadiran online yang kuat.',
                'level' => 'lanjut',
                'kategori_kategori_id' => $marketingKategori->kategori_id,
                'gambar_course' => 'https://images.unsplash.com/photo-1432888622747-4eb9a8efeb07?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1074&q=80',
                'is_active' => true,
            ]);

            $this->createBasicCourseSections($marketingCourse);
            $this->updateCourseTotals($marketingCourse);

            // Output confirmation
            $this->command->info('✅ Main comprehensive course created: ' . $webDevCourse->nama_course);
            $this->command->info('✅ Secondary course created: ' . $uiuxCourse->nama_course);
            $this->command->info('✅ Third course created: ' . $marketingCourse->nama_course);
        });
    }

    /**
     * Create or get a category
     */
    private function createOrGetCategory($name)
    {
        return Kategori::firstOrCreate(['kategori' => $name]);
    }

    /**
     * Create sections for the web development course
     */
    private function createWebDevCourseSections($course)
    {
        $sections = [];

        $sections[] = CourseSection::create([
            'nama_section' => 'Fundamentals of Web Development',
            'deskripsi_section' => 'Pengenalan dasar web development dan cara kerja internet',
            'urutan_section' => 1,
            'course_id' => $course->course_id,
        ]);

        $sections[] = CourseSection::create([
            'nama_section' => 'HTML5 & Semantic Markup',
            'deskripsi_section' => 'Belajar HTML5, struktur dokumen, dan semantic elements',
            'urutan_section' => 2,
            'course_id' => $course->course_id,
        ]);

        $sections[] = CourseSection::create([
            'nama_section' => 'CSS3 & Modern Styling',
            'deskripsi_section' => 'Mempelajari CSS3, selectors, layout, dan responsive design',
            'urutan_section' => 3,
            'course_id' => $course->course_id,
        ]);

        $sections[] = CourseSection::create([
            'nama_section' => 'JavaScript Fundamentals',
            'deskripsi_section' => 'Mempelajari dasar-dasar JavaScript, DOM manipulation, dan event handling',
            'urutan_section' => 4,
            'course_id' => $course->course_id,
        ]);

        $sections[] = CourseSection::create([
            'nama_section' => 'PHP & Backend Development',
            'deskripsi_section' => 'Belajar PHP untuk server-side programming dan database integration',
            'urutan_section' => 5,
            'course_id' => $course->course_id,
        ]);

        $sections[] = CourseSection::create([
            'nama_section' => 'Final Project: Building a Complete Website',
            'deskripsi_section' => 'Menggabungkan semua yang telah dipelajari untuk membuat website lengkap',
            'urutan_section' => 6,
            'course_id' => $course->course_id,
        ]);

        return $sections;
    }

    /**
     * Create videos for the web development course
     */
    private function createWebDevCourseVideos($course, $sections)
    {
        // Section 1 Videos - Fundamentals
        $this->createSectionVideos($sections[0], [
            [
                'judul' => 'Pengenalan Web Development',
                'deskripsi' => 'Selamat datang di kursus Web Development Mastery',
                'durasi' => 8,
                'url' => 'https://www.youtube.com/embed/3JluqTojuME'
            ],
            [
                'judul' => 'Bagaimana Internet Bekerja',
                'deskripsi' => 'Memahami cara kerja internet, DNS, dan protokol web',
                'durasi' => 12,
                'url' => 'https://www.youtube.com/embed/7_LPdttKXPc'
            ],
            [
                'judul' => 'Web Browser dan Web Server',
                'deskripsi' => 'Memahami interaksi antara browser dan server',
                'durasi' => 10,
                'url' => 'https://www.youtube.com/embed/RsQ1tFLwldY'
            ],
            [
                'judul' => 'Setup Development Environment',
                'deskripsi' => 'Instalasi dan setup tools yang dibutuhkan',
                'durasi' => 15,
                'url' => 'https://www.youtube.com/embed/UB1O30fR-EE'
            ]
        ]);

        // Section 2 Videos - HTML5
        $this->createSectionVideos($sections[1], [
            [
                'judul' => 'HTML5 Introduction',
                'deskripsi' => 'Pengenalan HTML5 dan struktur dokumen',
                'durasi' => 10,
                'url' => 'https://www.youtube.com/embed/UB1O30fR-EE'
            ],
            [
                'judul' => 'HTML Tags and Elements',
                'deskripsi' => 'Belajar tentang tags dan elements di HTML',
                'durasi' => 15,
                'url' => 'https://www.youtube.com/embed/UB1O30fR-EE'
            ],
            [
                'judul' => 'Semantic HTML Elements',
                'deskripsi' => 'Menggunakan semantic elements untuk struktur yang lebih baik',
                'durasi' => 12,
                'url' => 'https://www.youtube.com/embed/kUMe1FH4CHE'
            ],
            [
                'judul' => 'Forms dan Input Elements',
                'deskripsi' => 'Membuat dan styling form di HTML',
                'durasi' => 18,
                'url' => 'https://www.youtube.com/embed/fNcJuPIZ2WE'
            ]
        ]);

        // Section 3 Videos - CSS3
        $this->createSectionVideos($sections[2], [
            [
                'judul' => 'CSS3 Introduction',
                'deskripsi' => 'Pengenalan CSS3 dan cara kerjanya',
                'durasi' => 10,
                'url' => 'https://www.youtube.com/embed/yfoY53QXEnI'
            ],
            [
                'judul' => 'CSS Selectors dan Properties',
                'deskripsi' => 'Belajar tentang selectors dan properties di CSS',
                'durasi' => 15,
                'url' => 'https://www.youtube.com/embed/1PnVor36_40'
            ],
            [
                'judul' => 'CSS Box Model',
                'deskripsi' => 'Memahami box model dan layout di CSS',
                'durasi' => 12,
                'url' => 'https://www.youtube.com/embed/rIO5326FgPE'
            ],
            [
                'judul' => 'Flexbox dan Grid Layout',
                'deskripsi' => 'Modern layout dengan flexbox dan grid',
                'durasi' => 20,
                'url' => 'https://www.youtube.com/embed/JJSoEo8JSnc'
            ],
            [
                'judul' => 'Responsive Design dan Media Queries',
                'deskripsi' => 'Membuat website yang responsif',
                'durasi' => 15,
                'url' => 'https://www.youtube.com/embed/2KL-z9A56SQ'
            ]
        ]);

        // Section 4 Videos - JavaScript
        $this->createSectionVideos($sections[3], [
            [
                'judul' => 'JavaScript Introduction',
                'deskripsi' => 'Pengenalan JavaScript dan cara kerjanya',
                'durasi' => 12,
                'url' => 'https://www.youtube.com/embed/hdI2bqOjy3c'
            ],
            [
                'judul' => 'Variables, Data Types, dan Operators',
                'deskripsi' => 'Fundamental JavaScript programming',
                'durasi' => 15,
                'url' => 'https://www.youtube.com/embed/hdI2bqOjy3c'
            ],
            [
                'judul' => 'Functions dan Control Flow',
                'deskripsi' => 'Belajar fungsi dan control flow',
                'durasi' => 18,
                'url' => 'https://www.youtube.com/embed/hdI2bqOjy3c'
            ],
            [
                'judul' => 'DOM Manipulation',
                'deskripsi' => 'Memanipulasi elemen HTML dengan JavaScript',
                'durasi' => 20,
                'url' => 'https://www.youtube.com/embed/0ik6X4DJKCc'
            ],
            [
                'judul' => 'Event Handling',
                'deskripsi' => 'Menangani events di JavaScript',
                'durasi' => 15,
                'url' => 'https://www.youtube.com/embed/XF1_MlZ5l6M'
            ],
            [
                'judul' => 'AJAX dan Fetch API',
                'deskripsi' => 'Melakukan HTTP requests dengan JavaScript',
                'durasi' => 20,
                'url' => 'https://www.youtube.com/embed/82hnvUYY6QA'
            ]
        ]);

        // Section 5 Videos - PHP
        $this->createSectionVideos($sections[4], [
            [
                'judul' => 'PHP Introduction',
                'deskripsi' => 'Pengenalan PHP dan server-side programming',
                'durasi' => 12,
                'url' => 'https://www.youtube.com/embed/OK_JCtrrv-c'
            ],
            [
                'judul' => 'PHP Syntax dan Variables',
                'deskripsi' => 'Belajar syntax dan variable di PHP',
                'durasi' => 15,
                'url' => 'https://www.youtube.com/embed/OK_JCtrrv-c'
            ],
            [
                'judul' => 'Form Handling dengan PHP',
                'deskripsi' => 'Mengolah form input dengan PHP',
                'durasi' => 18,
                'url' => 'https://www.youtube.com/embed/OK_JCtrrv-c'
            ],
            [
                'judul' => 'MySQL Database Integration',
                'deskripsi' => 'Menghubungkan PHP dengan database MySQL',
                'durasi' => 22,
                'url' => 'https://www.youtube.com/embed/yW0WtDqKI4s'
            ]
        ]);

        // Section 6 Videos - Final Project
        $this->createSectionVideos($sections[5], [
            [
                'judul' => 'Project Planning dan Setup',
                'deskripsi' => 'Merencanakan dan setup project website',
                'durasi' => 15,
                'url' => 'https://www.youtube.com/embed/91CxoB6DHHY'
            ],
            [
                'judul' => 'Building Frontend',
                'deskripsi' => 'Membuat frontend website dengan HTML, CSS, dan JS',
                'durasi' => 25,
                'url' => 'https://www.youtube.com/embed/91CxoB6DHHY'
            ],
            [
                'judul' => 'Building Backend',
                'deskripsi' => 'Membuat backend dengan PHP dan MySQL',
                'durasi' => 30,
                'url' => 'https://www.youtube.com/embed/91CxoB6DHHY'
            ],
            [
                'judul' => 'Deployment dan Testing',
                'deskripsi' => 'Deploy website dan testing',
                'durasi' => 20,
                'url' => 'https://www.youtube.com/embed/91CxoB6DHHY'
            ]
        ]);
    }

    /**
     * Create videos for a section
     */
    private function createSectionVideos($section, $videoData)
    {
        $courseId = $section->course_id;
        $order = 1;

        foreach ($videoData as $data) {
            // Create video
            $video = Vidio::create([
                'nama' => $data['judul'],
                'deskripsi' => $data['deskripsi'],
                'url' => $data['url'],
                'gambar' => 'https://picsum.photos/seed/' . Str::slug($data['judul']) . '/640/360',
                'jumlah_tayang' => rand(10, 200),
                'kategori_kategori_id' => $section->course->kategori_kategori_id,
                'durasi_menit' => $data['durasi'],
                'is_active' => true,
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
     * Update course with total videos and duration
     */
    private function updateCourseTotals($course)
    {
        // Calculate total videos
        $totalVideos = CourseVideo::where('course_id', $course->course_id)->count();

        // Calculate total duration
        $totalDuration = CourseVideo::where('course_id', $course->course_id)->sum('durasi_menit');

        // Update course
        $course->update([
            'total_video' => $totalVideos,
            'total_durasi_menit' => $totalDuration,
        ]);
    }

    /**
     * Create quick reviews for a course
     */
    private function createQuickReviews($course, $sections)
    {
        // Create an "awal_course" review
        QuickReview::create([
            'judul_review' => 'Selamat Datang di ' . $course->nama_course,
            'konten_review' => "Kursus ini dirancang untuk memberikan Anda pemahaman mendalam tentang web development. Anda akan belajar mulai dari fondasi hingga teknik lanjutan.\n\nPastikan untuk mengikuti setiap video secara berurutan untuk hasil terbaik!",
            'tipe_review' => 'awal_course',
            'course_id' => $course->course_id,
            'urutan_review' => 1,
            'is_active' => true,
        ]);

        // Create a "tengah_course" review after HTML section
        QuickReview::create([
            'judul_review' => 'Bagus! Fondasi HTML Anda Sudah Kuat',
            'konten_review' => "Anda telah menyelesaikan bagian HTML. Ini adalah langkah penting untuk menjadi web developer.\n\nSekarang Anda bisa lanjut ke CSS untuk memberi style pada struktur HTML yang sudah Anda pelajari!",
            'tipe_review' => 'tengah_course',
            'course_id' => $course->course_id,
            'section_id' => $sections[1]->section_id, // After HTML section
            'urutan_review' => 2,
            'is_active' => true,
        ]);

        // Create another "tengah_course" review after JavaScript section
        QuickReview::create([
            'judul_review' => 'Selamat! JavaScript Fundamentals Sudah Dikuasai',
            'konten_review' => "Anda telah menyelesaikan bagian JavaScript yang cukup menantang. Sekarang Anda bisa membuat website interaktif!\n\nSelanjutnya kita akan belajar PHP untuk menangani logika di server side.",
            'tipe_review' => 'tengah_course',
            'course_id' => $course->course_id,
            'section_id' => $sections[3]->section_id, // After JavaScript section
            'urutan_review' => 3,
            'is_active' => true,
        ]);

        // Create an "akhir_course" review
        QuickReview::create([
            'judul_review' => 'Selamat Atas Pencapaian Anda!',
            'konten_review' => "Anda telah menyelesaikan seluruh kursus Web Development Mastery! Ini adalah pencapaian yang luar biasa.\n\nAnda sekarang memiliki keterampilan untuk membuat website lengkap dari awal hingga akhir. Jangan lupa untuk terus berlatih dan membangun portofolio Anda!",
            'tipe_review' => 'akhir_course',
            'course_id' => $course->course_id,
            'urutan_review' => 4,
            'is_active' => true,
        ]);
    }

    /**
     * Create quizzes for a course (including review-type quizzes)
     */
    private function createQuizzes($course, $sections)
    {
        // Create review-type quiz at course start
        Quiz::create([
            'judul_quiz' => 'Selamat Datang di ' . $course->nama_course,
            'deskripsi_quiz' => "Pengenalan kursus",
            'konten_quiz' => "Kursus ini dirancang untuk memberikan Anda pemahaman mendalam tentang web development. Anda akan belajar mulai dari fondasi hingga teknik lanjutan.\n\nPastikan untuk mengikuti setiap video secara berurutan untuk hasil terbaik!",
            'tipe_quiz' => 'tengah_course',
            'course_id' => $course->course_id,
            'urutan' => 1,
            'is_active' => true,
        ]);

        // Create HTML quiz after section 1
        $htmlQuiz = Quiz::create([
            'judul_quiz' => 'HTML Fundamentals Quiz',
            'deskripsi_quiz' => 'Tes pemahaman Anda tentang dasar-dasar HTML',
            'tipe_quiz' => 'setelah_section',
            'durasi_menit' => 15,
            'course_id' => $course->course_id,
            'section_id' => $sections[1]->section_id,
            'urutan' => 2,
            'is_active' => true,
        ]);

        // Add questions to HTML quiz
        $this->addQuizQuestions($htmlQuiz, [
            [
                'pertanyaan' => 'Tag HTML untuk heading level 1 adalah:',
                'pilihan' => ['<h1>', '<heading1>', '<head1>', '<title>'],
                'jawaban_benar' => '<h1>'
            ],
            [
                'pertanyaan' => 'Elemen HTML yang digunakan untuk membuat list bernomor adalah:',
                'pilihan' => ['<ul>', '<ol>', '<dl>', '<list>'],
                'jawaban_benar' => '<ol>'
            ],
            [
                'pertanyaan' => 'Tag yang digunakan untuk menambahkan gambar ke halaman web adalah:',
                'pilihan' => ['<picture>', '<image>', '<img>', '<src>'],
                'jawaban_benar' => '<img>'
            ],
            [
                'pertanyaan' => 'Atribut yang menentukan URL target link adalah:',
                'pilihan' => ['href', 'src', 'link', 'target'],
                'jawaban_benar' => 'href'
            ],
            [
                'pertanyaan' => 'Semantic tag untuk konten footer adalah:',
                'pilihan' => ['<bottom>', '<footer>', '<end>', '<section>'],
                'jawaban_benar' => '<footer>'
            ],
        ]);

        // Create a quiz after JavaScript section
        $jsQuiz = Quiz::create([
            'judul' => 'JavaScript Fundamentals Quiz',
            'deskripsi' => 'Tes pemahaman Anda tentang dasar-dasar JavaScript',
            'waktu_menit' => 20,
            'min_score' => 70,
            'course_id' => $course->course_id,
            'section_id' => $sections[3]->section_id,
            'is_active' => true,
        ]);

        // Add questions to JavaScript quiz
        $this->addQuizQuestions($jsQuiz, [
            [
                'pertanyaan' => 'Cara yang benar untuk mendeklarasikan variabel di JavaScript adalah:',
                'pilihan' => ['var x = 5;', 'variable x = 5;', 'x = 5;', 'int x = 5;'],
                'jawaban_benar' => 'var x = 5;'
            ],
            [
                'pertanyaan' => 'Fungsi yang digunakan untuk memilih elemen HTML berdasarkan ID adalah:',
                'pilihan' => ['document.query()', 'document.getElementById()', 'document.getElement()', 'document.findById()'],
                'jawaban_benar' => 'document.getElementById()'
            ],
            [
                'pertanyaan' => 'Event yang terjadi ketika pengguna mengklik elemen HTML adalah:',
                'pilihan' => ['onhover', 'onchange', 'onclick', 'onmouseover'],
                'jawaban_benar' => 'onclick'
            ],
            [
                'pertanyaan' => 'Cara yang benar untuk menambahkan komentar di JavaScript adalah:',
                'pilihan' => ['/* Ini komentar */', '<!-- Ini komentar -->', '// Ini komentar', '# Ini komentar'],
                'jawaban_benar' => '// Ini komentar'
            ],
            [
                'pertanyaan' => 'Method JavaScript yang digunakan untuk menambahkan elemen ke array adalah:',
                'pilihan' => ['push()', 'add()', 'append()', 'insert()'],
                'jawaban_benar' => 'push()'
            ],
        ]);

        // Create a final quiz for the course
        $finalQuiz = Quiz::create([
            'judul' => 'Web Development Mastery Final Quiz',
            'deskripsi' => 'Tes pemahaman keseluruhan Anda tentang web development',
            'waktu_menit' => 30,
            'min_score' => 75,
            'course_id' => $course->course_id,
            'section_id' => $sections[5]->section_id,
            'is_active' => true,
        ]);

        // Add questions to final quiz
        $this->addQuizQuestions($finalQuiz, [
            [
                'pertanyaan' => 'Apa property CSS yang digunakan untuk mengatur jarak di dalam elemen?',
                'pilihan' => ['margin', 'padding', 'spacing', 'border'],
                'jawaban_benar' => 'padding'
            ],
            [
                'pertanyaan' => 'Bahasa pemrograman yang berjalan di server side adalah:',
                'pilihan' => ['JavaScript', 'HTML', 'CSS', 'PHP'],
                'jawaban_benar' => 'PHP'
            ],
            [
                'pertanyaan' => 'Apa fungsi utama dari CSS?',
                'pilihan' => ['Menangani logika', 'Styling elemen HTML', 'Mengatur struktur halaman', 'Interaksi dengan database'],
                'jawaban_benar' => 'Styling elemen HTML'
            ],
            [
                'pertanyaan' => 'Metode HTTP yang digunakan untuk mengirim data form secara aman adalah:',
                'pilihan' => ['GET', 'POST', 'PUT', 'DELETE'],
                'jawaban_benar' => 'POST'
            ],
            [
                'pertanyaan' => 'Kode PHP dijalankan di:',
                'pilihan' => ['Browser', 'Database', 'Server', 'Lokal'],
                'jawaban_benar' => 'Server'
            ],
            [
                'pertanyaan' => 'Apa yang dimaksud dengan responsif design?',
                'pilihan' => [
                    'Website yang cepat merespon klik',
                    'Website yang bisa menyesuaikan dengan ukuran layar yang berbeda',
                    'Website dengan animasi',
                    'Website dengan banyak fitur'
                ],
                'jawaban_benar' => 'Website yang bisa menyesuaikan dengan ukuran layar yang berbeda'
            ],
            [
                'pertanyaan' => 'Framework JavaScript populer adalah:',
                'pilihan' => ['Laravel', 'Django', 'React', 'Ruby on Rails'],
                'jawaban_benar' => 'React'
            ],
            [
                'pertanyaan' => 'Format data yang umum digunakan untuk pertukaran data di web adalah:',
                'pilihan' => ['HTML', 'CSV', 'JSON', 'PDF'],
                'jawaban_benar' => 'JSON'
            ],
        ]);
    }

    /**
     * Add questions to a quiz
     */
    private function addQuizQuestions($quiz, $questions)
    {
        $questionNumber = 1;

        foreach ($questions as $q) {
            $quiz->questions()->create([
                'urutan_pertanyaan' => $questionNumber,
                'pertanyaan' => $q['pertanyaan'],
                'pilihan_jawaban' => json_encode($q['pilihan']),
                'jawaban_benar' => $q['jawaban_benar'],
            ]);

            $questionNumber++;
        }
    }

    /**
     * Create user interactions with the course
     */
    private function createUserInteractions($course, $user)
    {
        // Create a bookmark
        Bookmark::create([
            'users_id' => $user->users_id,
            'course_id' => $course->course_id,
        ]);

        // Create a feedback
        Feedback::create([
            'users_id' => $user->users_id,
            'course_id' => $course->course_id,
            'rating' => 5,
            'pesan' => 'Kursus yang sangat lengkap dan mudah dipahami. Materi disajikan dengan baik dan sistematis. Saya sangat merekomendasikan kursus ini untuk siapa saja yang ingin belajar web development!',
            'status' => 'published',
        ]);

        // Create watch histories for some videos
        $videos = CourseVideo::where('course_id', $course->course_id)
                            ->orderBy('urutan_video')
                            ->limit(5)
                            ->get();

        $i = 0;
        foreach ($videos as $video) {
            RiwayatTonton::create([
                'users_id' => $user->users_id,
                'vidio_vidio_id' => $video->vidio_vidio_id,
                'course_id' => $course->course_id,
                'persentase_progress' => $i < 3 ? 100 : 50, // First 3 videos complete, others 50%
                'waktu_ditonton' => now()->subDays($i), // Watched on different days
                'durasi_tonton' => $video->durasi_menit * 60 * ($i < 3 ? 1 : 0.5), // Full duration for complete, half for incomplete
            ]);

            $i++;
        }

        // Create quiz results
        $quizzes = Quiz::where('course_id', $course->course_id)
                      ->where('tipe_quiz', '!=', 'tengah_course')
                      ->orderBy('quiz_id')
                      ->get();

        foreach ($quizzes as $quiz) {
            // Skip some quizzes randomly to simulate incomplete progress
            if (rand(0, 2) === 0) {
                continue;
            }

            $questions = $quiz->questions ?? [];
            $totalQuestions = count($questions);

            if ($totalQuestions === 0) {
                continue;
            }

            // Randomly determine how many correct answers (weighted toward good performance)
            $correctCount = rand(ceil($totalQuestions * 0.6), $totalQuestions);
            $wrongCount = $totalQuestions - $correctCount;

            // Calculate score based on correct answers
            $score = ($correctCount / $totalQuestions) * 100;

            // Create quiz result
            $quizResult = QuizResult::create([
                'quiz_id' => $quiz->quiz_id,
                'users_id' => $user->users_id,
                'nilai_total' => $score,
                'jumlah_benar' => $correctCount,
                'jumlah_salah' => $wrongCount,
                'total_soal' => $totalQuestions,
                'waktu_mulai' => now()->subDays(rand(1, 7))->subMinutes(rand(15, 30)),
                'waktu_selesai' => now()->subDays(rand(1, 7)),
            ]);

            // Create detailed answer records for each question
            $questionNumber = 1;
            foreach ($questions as $question) {
                $isCorrect = $questionNumber <= $correctCount;

                QuizAnswerDetail::create([
                    'result_quiz_id' => $quizResult->result_quiz_id,
                    'quiz_id' => $quiz->quiz_id,
                    'urutan_pertanyaan' => $questionNumber,
                    'pertanyaan' => $question->pertanyaan,
                    'jawaban_user' => $isCorrect ? $question->jawaban_benar : 'Jawaban salah contoh',
                    'jawaban_benar' => $question->jawaban_benar,
                    'is_correct' => $isCorrect,
                    'skor_pertanyaan' => $isCorrect ? 100 : 0,
                ]);

                $questionNumber++;
            }
        }
    }

    /**
     * Create basic sections and videos for a course
     */
    private function createBasicCourseSections($course)
    {
        // Create sections
        $section1 = CourseSection::create([
            'nama_section' => 'Introduction',
            'deskripsi_section' => 'Pengenalan materi kursus',
            'urutan_section' => 1,
            'course_id' => $course->course_id,
        ]);

        $section2 = CourseSection::create([
            'nama_section' => 'Core Concepts',
            'deskripsi_section' => 'Konsep utama dalam materi',
            'urutan_section' => 2,
            'course_id' => $course->course_id,
        ]);

        $section3 = CourseSection::create([
            'nama_section' => 'Advanced Topics',
            'deskripsi_section' => 'Topik lanjutan dalam materi',
            'urutan_section' => 3,
            'course_id' => $course->course_id,
        ]);

        // Create videos for each section
        for ($i = 1; $i <= 3; $i++) {
            $video = Vidio::create([
                'nama' => "Introduction Video {$i}",
                'deskripsi' => "This is an introductory video #{$i} for the course",
                'url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                'gambar' => 'https://picsum.photos/seed/intro' . $i . '/640/360',
                'jumlah_tayang' => rand(10, 100),
                'kategori_kategori_id' => $course->kategori_kategori_id,
                'durasi_menit' => rand(8, 15),
                'is_active' => true,
            ]);

            CourseVideo::create([
                'course_id' => $course->course_id,
                'section_id' => $section1->section_id,
                'vidio_vidio_id' => $video->vidio_id,
                'urutan_video' => $i,
                'durasi_menit' => $video->durasi_menit,
                'is_required' => true,
            ]);
        }

        for ($i = 1; $i <= 4; $i++) {
            $video = Vidio::create([
                'nama' => "Core Concepts Video {$i}",
                'deskripsi' => "This is a core concepts video #{$i} for the course",
                'url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                'gambar' => 'https://picsum.photos/seed/core' . $i . '/640/360',
                'jumlah_tayang' => rand(10, 100),
                'kategori_kategori_id' => $course->kategori_kategori_id,
                'durasi_menit' => rand(10, 20),
                'is_active' => true,
            ]);

            CourseVideo::create([
                'course_id' => $course->course_id,
                'section_id' => $section2->section_id,
                'vidio_vidio_id' => $video->vidio_id,
                'urutan_video' => $i,
                'durasi_menit' => $video->durasi_menit,
                'is_required' => true,
            ]);
        }

        for ($i = 1; $i <= 3; $i++) {
            $video = Vidio::create([
                'nama' => "Advanced Topic Video {$i}",
                'deskripsi' => "This is an advanced topic video #{$i} for the course",
                'url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                'gambar' => 'https://picsum.photos/seed/adv' . $i . '/640/360',
                'jumlah_tayang' => rand(10, 100),
                'kategori_kategori_id' => $course->kategori_kategori_id,
                'durasi_menit' => rand(15, 25),
                'is_active' => true,
            ]);

            CourseVideo::create([
                'course_id' => $course->course_id,
                'section_id' => $section3->section_id,
                'vidio_vidio_id' => $video->vidio_id,
                'urutan_video' => $i,
                'durasi_menit' => $video->durasi_menit,
                'is_required' => true,
            ]);
        }

        // Create a quick review
        QuickReview::create([
            'judul_review' => 'Selamat Datang di ' . $course->nama_course,
            'konten_review' => "Terima kasih telah bergabung dengan kursus ini. Materi dirancang secara sistematis dan komprehensif.",
            'tipe_review' => 'awal_course',
            'course_id' => $course->course_id,
            'urutan_review' => 1,
            'is_active' => true,
        ]);
    }
}
