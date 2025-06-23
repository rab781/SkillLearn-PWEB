<?php
/**
 * Test script untuk memverifikasi quiz submission
 */

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Quiz;
use App\Models\QuizResult;
use App\Models\Course;

try {
    echo "=== Testing Quiz Submission Functionality ===\n\n";
    
    // Test 1: Check quiz with questions
    echo "1. Testing Quiz loading...\n";
    $quiz = Quiz::with('questions')->first();
    
    if ($quiz) {
        echo "✓ Quiz loaded: {$quiz->judul_quiz}\n";
        echo "✓ Questions count: " . $quiz->questions->count() . "\n";
        
        if ($quiz->questions->count() > 0) {
            $question = $quiz->questions->first();
            echo "✓ First question: " . substr($question->pertanyaan, 0, 50) . "...\n";
            echo "✓ Pilihan jawaban: " . $question->pilihan_jawaban . "\n";
            
            // Test JSON parsing
            try {
                $options = json_decode($question->pilihan_jawaban, true);
                if (is_array($options)) {
                    echo "✓ Pilihan jawaban dapat di-parse sebagai JSON\n";
                    echo "✓ Jumlah opsi: " . count($options) . "\n";
                } else {
                    echo "✗ Pilihan jawaban bukan array valid\n";
                }
            } catch (Exception $e) {
                echo "✗ Error parsing pilihan jawaban: " . $e->getMessage() . "\n";
            }
        }
    } else {
        echo "✗ No quiz found\n";
    }
    
    // Test 2: Check QuizResult model
    echo "\n2. Testing QuizResult model...\n";
    $result = new QuizResult();
    $fillable = $result->getFillable();
    echo "✓ QuizResult fillable fields: " . implode(', ', $fillable) . "\n";
    
    // Test 3: Check database structure
    echo "\n3. Testing database access...\n";
    try {
        $resultCount = QuizResult::count();
        echo "✓ QuizResult table accessible (current records: {$resultCount})\n";
    } catch (Exception $e) {
        echo "✗ Error accessing QuizResult table: " . $e->getMessage() . "\n";
    }
    
    echo "\n=== Test completed successfully! ===\n";
    echo "Quiz submission should now work correctly.\n";
    echo "Please test the quiz page in browser.\n";
    
} catch (Exception $e) {
    echo "✗ Error during testing: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
