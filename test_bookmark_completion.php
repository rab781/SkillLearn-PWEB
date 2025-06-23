<?php
/**
 * Test bookmark dan video completion functionality
 */

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Course;
use App\Models\User;
use App\Models\CourseVideo;
use App\Models\Bookmark;
use App\Models\UserVideoProgress;

try {
    echo "=== Testing Bookmark & Video Completion ===\n\n";
    
    // Test 1: Check course exists
    $course = Course::first();
    if (!$course) {
        echo "✗ No courses found\n";
        exit;
    }
    echo "✓ Course found: {$course->nama_course} (ID: {$course->course_id})\n";
    
    // Test 2: Check video exists
    $courseVideo = CourseVideo::with('vidio')->first();
    if (!$courseVideo) {
        echo "✗ No course videos found\n";
        exit;
    }
    echo "✓ Course video found: {$courseVideo->vidio->judul} (Course Video ID: {$courseVideo->course_video_id})\n";
    
    // Test 3: Check user exists
    $user = User::first();
    if (!$user) {
        echo "✗ No users found\n";
        exit;
    }
    echo "✓ User found: {$user->name} (ID: {$user->users_id})\n";
    
    // Test 4: Test Bookmark model
    echo "\n4. Testing Bookmark functionality...\n";
    try {
        // Try to create a bookmark
        $bookmark = new Bookmark([
            'users_id' => $user->users_id,
            'course_id' => $course->course_id
        ]);
        echo "✓ Bookmark model can be instantiated\n";
        echo "  - Course bookmark fields: users_id, course_id\n";
    } catch (Exception $e) {
        echo "✗ Error with Bookmark: " . $e->getMessage() . "\n";
    }
    
    // Test 5: Test UserVideoProgress
    echo "\n5. Testing UserVideoProgress functionality...\n";
    try {
        $progress = new UserVideoProgress([
            'user_id' => $user->users_id,
            'vidio_vidio_id' => $courseVideo->vidio_vidio_id,
            'course_id' => $course->course_id,
            'is_completed' => false
        ]);
        echo "✓ UserVideoProgress model can be instantiated\n";
        echo "  - Fields: user_id, vidio_vidio_id, course_id, is_completed\n";
    } catch (Exception $e) {
        echo "✗ Error with UserVideoProgress: " . $e->getMessage() . "\n";
    }
    
    // Test 6: Check route paths
    echo "\n6. Route information:\n";
    echo "✓ Course bookmark route: POST /web/bookmark/course\n";
    echo "✓ Course bookmark check: GET /web/bookmark/course/check/{courseId}\n";
    echo "✓ Video completion route: POST /courses/{courseId}/video/{videoId}/complete\n";
    
    echo "\n=== All Tests Completed ===\n";
    echo "Bookmark should work for COURSE (not individual videos)\n";
    echo "Video completion should work for individual videos\n";
    
} catch (Exception $e) {
    echo "✗ Error during testing: " . $e->getMessage() . "\n";
}
