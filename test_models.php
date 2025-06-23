<?php

// Load Laravel environment
require_once __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Bookmark;
use App\Models\Feedback;
use App\Models\QuizResult;
use App\Models\UserVideoProgress;
use App\Models\UserCourseProgress;
use Illuminate\Support\Facades\Log;

try {
    // Get a test user
    $user = User::where('role', 'CU')->first();
    
    if (!$user) {
        echo "No customer user found!\n";
        exit;
    }
    
    echo "Test User ID: {$user->users_id}, Name: {$user->nama_lengkap}\n\n";
    
    // Test 1: Check Bookmarks
    try {
        echo "Testing Bookmark model...\n";
        $bookmarks = Bookmark::where('users_id', $user->users_id)->take(3)->get();
        echo "Found " . count($bookmarks) . " bookmarks\n";
        foreach ($bookmarks as $bookmark) {
            echo "- Bookmark ID: {$bookmark->bookmark_id}, Course: " . 
                ($bookmark->course ? $bookmark->course->nama_course : "N/A") . "\n";
        }
    } catch (\Exception $e) {
        echo "ERROR with Bookmark model: " . $e->getMessage() . "\n";
    }
    
    // Test 2: Check Feedback
    try {
        echo "\nTesting Feedback model...\n";
        $feedbacks = Feedback::where('users_id', $user->users_id)->take(3)->get();
        echo "Found " . count($feedbacks) . " feedbacks\n";
        foreach ($feedbacks as $feedback) {
            echo "- Feedback ID: {$feedback->feedback_id}, Rating: {$feedback->rating}\n";
        }
    } catch (\Exception $e) {
        echo "ERROR with Feedback model: " . $e->getMessage() . "\n";
    }
    
    // Test 3: Check QuizResults
    try {
        echo "\nTesting QuizResult model...\n";
        $results = QuizResult::where('users_id', $user->users_id)->take(3)->get();
        echo "Found " . count($results) . " quiz results\n";
        foreach ($results as $result) {
            echo "- Quiz Result ID: {$result->result_quiz_id}, Score: {$result->nilai_total}\n";
        }
    } catch (\Exception $e) {
        echo "ERROR with QuizResult model: " . $e->getMessage() . "\n";
    }
    
    // Test 4: Check UserVideoProgress
    try {
        echo "\nTesting UserVideoProgress model...\n";
        $progress = UserVideoProgress::where('user_id', $user->users_id)->take(3)->get();
        echo "Found " . count($progress) . " video progress records\n";
        foreach ($progress as $p) {
            echo "- Progress ID: {$p->video_progress_id}, Completion: {$p->completion_percentage}%\n";
        }
    } catch (\Exception $e) {
        echo "ERROR with UserVideoProgress model: " . $e->getMessage() . "\n";
    }
    
    // Test 5: Check UserCourseProgress
    try {
        echo "\nTesting UserCourseProgress model...\n";
        $courseProgress = UserCourseProgress::where('user_id', $user->users_id)->take(3)->get();
        echo "Found " . count($courseProgress) . " course progress records\n";
        foreach ($courseProgress as $cp) {
            echo "- Progress ID: {$cp->progress_id}, Course: " . 
                ($cp->course ? $cp->course->nama_course : "N/A") . 
                ", Progress: {$cp->progress_percentage}%\n";
        }
    } catch (\Exception $e) {
        echo "ERROR with UserCourseProgress model: " . $e->getMessage() . "\n";
    }
    
} catch (\Exception $e) {
    echo "Main error: " . $e->getMessage() . "\n";
}
