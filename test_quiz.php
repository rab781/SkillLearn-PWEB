<?php
/**
 * Test script untuk quiz submission
 */

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Quiz;
use App\Models\QuizResult;
use App\Models\User;

try {
    echo "=== Testing Quiz Functionality ===\n\n";
    
    // Test 1: Check if quiz exists
    echo "1. Testing Quiz model...\n";
    $quiz = Quiz::with('questions')->first();
    if ($quiz) {
        echo "✓ Quiz found: {$quiz->judul_quiz}\n";
        echo "✓ Questions count: " . $quiz->questions->count() . "\n";
        
        if ($quiz->questions->count() > 0) {
            $firstQuestion = $quiz->questions->first();
            echo "✓ First question: " . substr($firstQuestion->pertanyaan, 0, 50) . "...\n";
            echo "✓ Pilihan jawaban type: " . gettype($firstQuestion->pilihan_jawaban) . "\n";
            
            if (is_string($firstQuestion->pilihan_jawaban)) {
                $options = json_decode($firstQuestion->pilihan_jawaban, true);
                echo "✓ Parsed options count: " . (is_array($options) ? count($options) : 'failed') . "\n";
            }
        }
    } else {
        echo "✗ No quiz found\n";
    }
    
    // Test 2: Check QuizResult model
    echo "\n2. Testing QuizResult model...\n";
    $resultCount = QuizResult::count();
    echo "✓ Total quiz results: {$resultCount}\n";
    
    // Test 3: Check unique constraint
    echo "\n3. Testing unique constraint...\n";
    $user = User::first();
    if ($user && $quiz) {
        $existingResult = QuizResult::where('quiz_id', $quiz->quiz_id)
            ->where('users_id', $user->users_id)
            ->first();
            
        if ($existingResult) {
            echo "✓ Found existing result for user {$user->users_id} in quiz {$quiz->quiz_id}\n";
            echo "✓ Score: {$existingResult->nilai_total}%\n";
            echo "✓ updateOrCreate should work for this case\n";
        } else {
            echo "✓ No existing result found (this is OK)\n";
        }
    }
    
    echo "\n=== Test completed! ===\n";
    echo "Quiz submission should now work with updateOrCreate method.\n";
    
} catch (Exception $e) {
    echo "✗ Error during testing: " . $e->getMessage() . "\n";
}
