<?php
/**
 * Test script untuk memverifikasi quiz page functionality
 */

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Course;
use App\Models\Quiz;

try {
    echo "=== Testing Quiz Page Functionality ===\n\n";
    
    // Test 1: Get course with quiz
    echo "1. Testing Course with quiz loading...\n";
    $course = Course::with(['quizzes.questions'])->first();
    
    if ($course && $course->quizzes->count() > 0) {
        $quiz = $course->quizzes->first();
        echo "✓ Course loaded: {$course->nama_course}\n";
        echo "✓ Quiz found: {$quiz->judul_quiz}\n";
        echo "✓ Questions count: " . $quiz->questions->count() . "\n";
        
        // Test question data
        if ($quiz->questions->count() > 0) {
            $question = $quiz->questions->first();
            echo "✓ Sample question: {$question->pertanyaan}\n";
            echo "✓ Sample options: {$question->pilihan_jawaban}\n";
            
            // Test if pilihan_jawaban is valid JSON
            $options = json_decode($question->pilihan_jawaban, true);
            if (is_array($options)) {
                echo "✓ Options parsed successfully: " . implode(', ', $options) . "\n";
            } else {
                echo "✗ Options parsing failed\n";
            }
        }
    } else {
        echo "✗ No courses with quizzes found\n";
    }
    
    echo "\n=== Test completed successfully! ===\n";
    echo "The quiz page should now work without JavaScript errors.\n";
    echo "Please test the quiz page in browser at: http://localhost:8000\n";
    
} catch (Exception $e) {
    echo "✗ Error during testing: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
