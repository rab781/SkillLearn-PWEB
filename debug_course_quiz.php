<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Quiz Details for Course 1 ===" . PHP_EOL;

$course = App\Models\Course::with([
    'courseQuizzes.quiz.questions'
])->find(1);

echo "Course: {$course->nama_course}" . PHP_EOL;
echo "Course Quizzes Count: " . $course->courseQuizzes->count() . PHP_EOL;

foreach ($course->courseQuizzes as $courseQuiz) {
    echo "--- CourseQuiz ID: {$courseQuiz->id} ---" . PHP_EOL;
    echo "Quiz ID: {$courseQuiz->quiz_id}" . PHP_EOL;
    echo "Position: {$courseQuiz->position}" . PHP_EOL;
    echo "Reference ID: {$courseQuiz->reference_id}" . PHP_EOL;
    
    if ($courseQuiz->quiz) {
        echo "Quiz Title: {$courseQuiz->quiz->judul_quiz}" . PHP_EOL;
        echo "Quiz Description: {$courseQuiz->quiz->deskripsi_quiz}" . PHP_EOL;
        echo "Quiz Active: " . ($courseQuiz->quiz->is_active ? 'Yes' : 'No') . PHP_EOL;
        echo "Questions Count: " . $courseQuiz->quiz->questions->count() . PHP_EOL;
    } else {
        echo "Quiz: NOT FOUND!" . PHP_EOL;
    }
    echo PHP_EOL;
}

// Check position filtering
echo "=== Position Filtering ===" . PHP_EOL;
echo "Start position: " . $course->courseQuizzes->where('position', 'start')->count() . PHP_EOL;
echo "End position: " . $course->courseQuizzes->where('position', 'end')->count() . PHP_EOL;
echo "After video: " . $course->courseQuizzes->where('position', 'after_video')->count() . PHP_EOL;
echo "After section: " . $course->courseQuizzes->where('position', 'after_section')->count() . PHP_EOL;
