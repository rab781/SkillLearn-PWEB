<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Database Data Check ===" . PHP_EOL;
echo "Courses: " . App\Models\Course::count() . PHP_EOL;
echo "Quizzes: " . App\Models\Quiz::count() . PHP_EOL;
echo "CourseQuizzes: " . App\Models\CourseQuiz::count() . PHP_EOL;
echo "Quiz Questions: " . App\Models\QuizQuestion::count() . PHP_EOL;

// List courses
echo PHP_EOL . "=== Courses ===" . PHP_EOL;
$courses = App\Models\Course::select('course_id', 'nama_course')->limit(5)->get();
foreach ($courses as $course) {
    echo "ID: {$course->course_id} - {$course->nama_course}" . PHP_EOL;
}

// List quizzes
echo PHP_EOL . "=== Quizzes ===" . PHP_EOL;
$quizzes = App\Models\Quiz::select('quiz_id', 'judul_quiz', 'course_id')->limit(5)->get();
foreach ($quizzes as $quiz) {
    echo "ID: {$quiz->quiz_id} - {$quiz->judul_quiz} (Course: {$quiz->course_id})" . PHP_EOL;
}

// List course quizzes
echo PHP_EOL . "=== Course Quizzes ===" . PHP_EOL;
$courseQuizzes = App\Models\CourseQuiz::with(['quiz'])->limit(5)->get();
foreach ($courseQuizzes as $cq) {
    $quizTitle = $cq->quiz ? $cq->quiz->judul_quiz : 'N/A';
    echo "Course: {$cq->course_id} - Quiz: {$cq->quiz_id} ({$quizTitle}) - Position: {$cq->position}" . PHP_EOL;
}
