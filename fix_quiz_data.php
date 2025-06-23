<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Fixing Quiz Data ===" . PHP_EOL;

// Remove invalid course quiz entry
App\Models\CourseQuiz::where('quiz_id', 7)->delete();
echo "Removed invalid course quiz entry" . PHP_EOL;

// Add valid quiz to course
$courseQuiz = App\Models\CourseQuiz::create([
    'course_id' => 1,
    'quiz_id' => 1, // Valid quiz ID
    'position' => 'start',
    'reference_id' => null,
    'position_order' => 1
]);

echo "Added Quiz 1 to Course 1 at start position" . PHP_EOL;

// Add another quiz
$courseQuiz2 = App\Models\CourseQuiz::create([
    'course_id' => 1,
    'quiz_id' => 2, // Valid quiz ID
    'position' => 'end',
    'reference_id' => null,
    'position_order' => 2
]);

echo "Added Quiz 2 to Course 1 at end position" . PHP_EOL;

echo "=== Updated Course Quizzes ===" . PHP_EOL;
$courseQuizzes = App\Models\CourseQuiz::with(['quiz'])->where('course_id', 1)->get();
foreach ($courseQuizzes as $cq) {
    $quizTitle = $cq->quiz ? $cq->quiz->judul_quiz : 'N/A';
    echo "Course: {$cq->course_id} - Quiz: {$cq->quiz_id} ({$quizTitle}) - Position: {$cq->position}" . PHP_EOL;
}
