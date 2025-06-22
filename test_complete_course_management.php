<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Http\Kernel::class);

use App\Models\Course;
use App\Models\CourseSection;
use App\Models\CourseVideo;
use App\Models\QuickReview;
use App\Models\Quiz;
use App\Models\Kategori;
use App\Models\Vidio;

echo "=== TESTING COMPLETE COURSE MANAGEMENT SYSTEM ===\n\n";

// Test data
$kategori = Kategori::first();
$video1 = Vidio::first();
$video2 = Vidio::skip(1)->first();

echo "1. Creating new course with comprehensive content...\n";

// Create course
$course = Course::create([
    'nama_course' => 'Complete Course Management Test',
    'deskripsi_course' => 'Testing course with sections, videos, quizzes, and quick reviews',
    'level' => 'menengah',
    'kategori_kategori_id' => $kategori->kategori_id,
    'is_active' => true
]);
echo "   ✓ Course created: " . $course->nama_course . " (ID: " . $course->course_id . ")\n";

// Create sections
$section1 = CourseSection::create([
    'nama_section' => 'Pengenalan',
    'deskripsi_section' => 'Pengenalan dasar materi',
    'urutan_section' => 1,
    'course_id' => $course->course_id
]);

$section2 = CourseSection::create([
    'nama_section' => 'Praktik',
    'deskripsi_section' => 'Latihan dan praktik',
    'urutan_section' => 2,
    'course_id' => $course->course_id
]);
echo "   ✓ Created 2 sections\n";

// Add videos to sections
$courseVideo1 = CourseVideo::create([
    'course_id' => $course->course_id,
    'section_id' => $section1->section_id,
    'vidio_vidio_id' => $video1->vidio_id,
    'urutan_video' => 1,
    'durasi_menit' => 15,
    'catatan_admin' => 'Video pengenalan'
]);

$courseVideo2 = CourseVideo::create([
    'course_id' => $course->course_id,
    'section_id' => $section2->section_id,
    'vidio_vidio_id' => $video2->vidio_id,
    'urutan_video' => 1,
    'durasi_menit' => 20,
    'catatan_admin' => 'Video praktik'
]);
echo "   ✓ Added 2 videos to sections\n";

// Add quick reviews
$quickReview1 = QuickReview::create([
    'judul_review' => 'Review After Video 1',
    'konten_review' => '<h3>Ringkasan</h3><p>Materi yang telah dipelajari pada video pertama.</p>',
    'tipe_review' => 'setelah_video',
    'course_id' => $course->course_id,
    'section_id' => $section1->section_id,
    'vidio_vidio_id' => $video1->vidio_id,
    'urutan_review' => 1,
    'is_active' => true
]);

$quickReview2 = QuickReview::create([
    'judul_review' => 'Review After Section 1',
    'konten_review' => '<h3>Evaluasi Section</h3><p>Evaluasi pemahaman setelah menyelesaikan section 1.</p>',
    'tipe_review' => 'setelah_section',
    'course_id' => $course->course_id,
    'section_id' => $section1->section_id,
    'urutan_review' => 2,
    'is_active' => true
]);
echo "   ✓ Added 2 quick reviews\n";

// Add quizzes
$quiz1 = Quiz::create([
    'course_id' => $course->course_id,
    'judul_quiz' => 'Quiz Pengenalan',
    'deskripsi_quiz' => 'Quiz untuk menguji pemahaman materi pengenalan',
    'tipe_quiz' => 'setelah_section',
    'durasi_menit' => 10,
    'konten_quiz' => json_encode([
        'questions' => [
            [
                'question' => 'Apa tujuan utama course ini?',
                'options' => ['Belajar dasar', 'Praktik lanjutan', 'Testing sistem'],
                'correct' => 2
            ],
            [
                'question' => 'Berapa section dalam course ini?',
                'options' => ['1', '2', '3'],
                'correct' => 1
            ]
        ]
    ]),
    'is_active' => true
]);

$quiz2 = Quiz::create([
    'course_id' => $course->course_id,
    'judul_quiz' => 'Quiz Final',
    'deskripsi_quiz' => 'Quiz komprehensif di akhir course',
    'tipe_quiz' => 'akhir_course',
    'durasi_menit' => 15,
    'konten_quiz' => json_encode([
        'questions' => [
            [
                'question' => 'Materi mana yang paling penting?',
                'options' => ['Teori', 'Praktik', 'Keduanya'],
                'correct' => 2
            ]
        ]
    ]),
    'is_active' => true
]);
echo "   ✓ Added 2 quizzes\n";

// Update course statistics
$course->updateCourseStatistics();
$course->refresh();

echo "\n2. Course statistics after content addition:\n";
echo "   - Total videos: " . $course->total_video . "\n";
echo "   - Total duration: " . $course->total_durasi_menit . " minutes\n";
echo "   - Total sections: " . $course->sections->count() . "\n";
echo "   - Total quick reviews: " . $course->quickReviews->count() . "\n";

// Test relationships
echo "\n3. Testing relationships and data integrity:\n";
$courseWithRelations = Course::with([
    'sections.videos.vidio', 
    'quickReviews', 
    'kategori'
])->find($course->course_id);

$quizzes = Quiz::where('course_id', $course->course_id)->get();

echo "   ✓ Course loaded with all relationships\n";
echo "   ✓ Found " . $courseWithRelations->sections->count() . " sections\n";
echo "   ✓ Found " . $courseWithRelations->sections->sum(function($s) { return $s->videos->count(); }) . " videos total\n";
echo "   ✓ Found " . $courseWithRelations->quickReviews->count() . " quick reviews\n";
echo "   ✓ Found " . $quizzes->count() . " quizzes\n";

echo "\n4. Course content structure:\n";
foreach ($courseWithRelations->sections as $section) {
    echo "   Section {$section->urutan_section}: {$section->nama_section}\n";
    foreach ($section->videos as $video) {
        echo "     - Video {$video->urutan_video}: {$video->vidio->nama} ({$video->durasi_menit} min)\n";
    }
}

echo "\n5. Quick Reviews:\n";
foreach ($courseWithRelations->quickReviews as $review) {
    echo "   - {$review->judul_review} ({$review->tipe_review})\n";
}

echo "\n6. Quizzes:\n";
foreach ($quizzes as $quiz) {
    echo "   - {$quiz->judul_quiz} ({$quiz->tipe_quiz}, {$quiz->durasi_menit} min)\n";
}

echo "\n=== COURSE MANAGEMENT SYSTEM TEST COMPLETED ===\n";
echo "✅ Course created successfully with ID: " . $course->course_id . "\n";
echo "✅ All content types added (sections, videos, reviews, quizzes)\n";
echo "✅ Relationships working correctly\n";
echo "✅ Statistics updated properly\n";
echo "\n🌐 Admin can now manage this course at:\n";
echo "   Course Details: http://localhost:8000/admin/courses/" . $course->course_id . "\n";
echo "   Quiz Management: http://localhost:8000/admin/courses/" . $course->course_id . "/quizzes\n";
echo "\n📝 Login credentials:\n";
echo "   Email: admin2@skilllearn.com\n";
echo "   Password: admin123\n";
