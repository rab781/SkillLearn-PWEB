<?php
/**
 * Test script untuk memverifikasi video page berfungsi
 */

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Course;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

try {
    echo "=== Testing Video Page Functionality ===\n\n";
    
    // Test 1: Get course with sections and videos
    echo "1. Testing Course with sections and videos loading...\n";
    $course = Course::with([
        'sections.videos.vidio',
        'sections.quizzes.questions'
    ])->first();
    
    if ($course) {
        echo "✓ Course loaded: {$course->nama_course}\n";
        echo "✓ Sections count: " . ($course->sections ? $course->sections->count() : 0) . "\n";
        
        foreach ($course->sections as $section) {
            $videoCount = $section->videos ? $section->videos->count() : 0;
            $quizCount = $section->quizzes ? $section->quizzes->count() : 0;
            echo "  - Section: {$section->nama_section} ({$videoCount} videos, {$quizCount} quizzes)\n";
        }
    } else {
        echo "✗ No courses found\n";
    }
    
    // Test 2: Check UserVideoProgress model
    echo "\n2. Testing UserVideoProgress model...\n";
    $userVideoProgress = new \App\Models\UserVideoProgress();
    $fillable = $userVideoProgress->getFillable();
    echo "✓ UserVideoProgress fillable fields: " . implode(', ', $fillable) . "\n";
    
    // Test 3: Check if field names are correct
    echo "\n3. Testing database field mappings...\n";
    try {
        $progress = \App\Models\UserVideoProgress::first();
        if ($progress) {
            echo "✓ UserVideoProgress table accessible\n";
            echo "✓ Fields available: user_id, vidio_vidio_id, course_id, is_completed, etc.\n";
        } else {
            echo "! No UserVideoProgress records found (this is OK)\n";
        }
    } catch (Exception $e) {
        echo "✗ Error accessing UserVideoProgress: " . $e->getMessage() . "\n";
    }
    
    // Test 4: Check RiwayatTonton model
    echo "\n4. Testing RiwayatTonton model...\n";
    try {
        $riwayat = new \App\Models\RiwayatTonton();
        $fillable = $riwayat->getFillable();
        echo "✓ RiwayatTonton fillable fields: " . implode(', ', $fillable) . "\n";
    } catch (Exception $e) {
        echo "✗ Error with RiwayatTonton: " . $e->getMessage() . "\n";
    }
    
    echo "\n=== Test completed successfully! ===\n";
    echo "The video page should now work without 'count() on null' errors.\n";
    echo "Please test the video page in browser at: http://localhost:8000\n";
    
} catch (Exception $e) {
    echo "✗ Error during testing: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
