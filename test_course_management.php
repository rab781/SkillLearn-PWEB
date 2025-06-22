<?php

require_once 'vendor/autoload.php';
require_once 'bootstrap/app.php';

use App\Models\Course;
use App\Models\CourseSection;
use App\Models\CourseVideo;
use App\Models\QuickReview;
use App\Models\Kategori;
use App\Models\Vidio;

echo "=== TESTING COURSE MANAGEMENT SYSTEM ===\n\n";

// Test 1: Check available data
echo "1. Checking available data:\n";
echo "   - Categories: " . Kategori::count() . "\n";
echo "   - Videos: " . Vidio::count() . "\n";
echo "   - Courses: " . Course::count() . "\n\n";

// Test 2: Get first category and video for testing
$kategori = Kategori::first();
$video = Vidio::first();

echo "2. Test data:\n";
echo "   - First category: " . $kategori->kategori . " (ID: " . $kategori->kategori_id . ")\n";
echo "   - First video: " . $video->nama . " (ID: " . $video->vidio_id . ")\n\n";

// Test 3: Create a test course
echo "3. Creating test course...\n";
try {
    $course = Course::create([
        'nama_course' => 'Test Course Management',
        'deskripsi_course' => 'This is a test course for the course management system',
        'level' => 'pemula',
        'kategori_kategori_id' => $kategori->kategori_id,
        'is_active' => true
    ]);
    echo "   ✓ Course created successfully (ID: " . $course->course_id . ")\n";
} catch (Exception $e) {
    echo "   ✗ Error creating course: " . $e->getMessage() . "\n";
    exit(1);
}

// Test 4: Add section to course
echo "4. Adding section to course...\n";
try {
    $section = CourseSection::create([
        'nama_section' => 'Introduction',
        'deskripsi_section' => 'Basic introduction to the topic',
        'urutan_section' => 1,
        'course_id' => $course->course_id
    ]);
    echo "   ✓ Section created successfully (ID: " . $section->section_id . ")\n";
} catch (Exception $e) {
    echo "   ✗ Error creating section: " . $e->getMessage() . "\n";
}

// Test 5: Add video to section
echo "5. Adding video to section...\n";
try {
    $courseVideo = CourseVideo::create([
        'course_id' => $course->course_id,
        'section_id' => $section->section_id,
        'vidio_vidio_id' => $video->vidio_id,
        'urutan_video' => 1,
        'durasi_menit' => 10,
        'catatan_admin' => 'Test video added for course management testing'
    ]);
    echo "   ✓ Video added to course successfully (ID: " . $courseVideo->course_video_id . ")\n";
} catch (Exception $e) {
    echo "   ✗ Error adding video: " . $e->getMessage() . "\n";
}

// Test 6: Add quick review
echo "6. Adding quick review...\n";
try {
    $quickReview = QuickReview::create([
        'judul_review' => 'Test Quick Review',
        'konten_review' => '<h3>Test Review</h3><p>This is a test quick review for course management.</p>',
        'tipe_review' => 'setelah_video',
        'course_id' => $course->course_id,
        'section_id' => $section->section_id,
        'vidio_vidio_id' => $video->vidio_id,
        'urutan_review' => 1,
        'is_active' => true
    ]);
    echo "   ✓ Quick review created successfully (ID: " . $quickReview->review_id . ")\n";
} catch (Exception $e) {
    echo "   ✗ Error creating quick review: " . $e->getMessage() . "\n";
}

// Test 7: Update course statistics
echo "7. Updating course statistics...\n";
try {
    $course->updateCourseStatistics();
    $course->refresh();
    echo "   ✓ Course statistics updated\n";
    echo "   - Total videos: " . $course->total_video . "\n";
    echo "   - Total duration: " . $course->total_durasi_menit . " minutes\n";
} catch (Exception $e) {
    echo "   ✗ Error updating statistics: " . $e->getMessage() . "\n";
}

// Test 8: Test relationships
echo "8. Testing relationships...\n";
try {
    $course->load(['sections.videos.vidio', 'quickReviews']);
    echo "   ✓ Course has " . $course->sections->count() . " sections\n";
    echo "   ✓ Course has " . $course->videos->count() . " videos total\n";
    echo "   ✓ Course has " . $course->quickReviews->count() . " quick reviews\n";
} catch (Exception $e) {
    echo "   ✗ Error testing relationships: " . $e->getMessage() . "\n";
}

echo "\n=== COURSE MANAGEMENT TEST COMPLETED ===\n";
echo "Test course created with ID: " . $course->course_id . "\n";
echo "You can now test the admin interface at: http://localhost:8000/admin/courses\n";
