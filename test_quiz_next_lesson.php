<?php
// Test script to check quiz next lesson functionality

require_once __DIR__ . '/vendor/autoload.php';

use App\Models\Course;
use App\Models\Quiz;
use App\Models\CourseVideo;
use App\Models\QuickReview;

// Initialize Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

try {
    echo "Testing quiz next lesson functionality...\n\n";

    // Get a course with videos and quizzes
    $course = Course::with(['videos', 'quickReviews', 'quizzes'])->first();
    
    if (!$course) {
        echo "No course found.\n";
        exit;
    }
    
    echo "Course: {$course->nama_course}\n";
    echo "Videos: " . ($course->videos ? $course->videos->count() : 0) . "\n";
    echo "Quick Reviews: " . ($course->quickReviews ? $course->quickReviews->count() : 0) . "\n";
    echo "Quizzes: " . ($course->quizzes ? $course->quizzes->count() : 0) . "\n\n";

    // Test route generation for videos
    if ($course->videos && $course->videos->count() > 0) {
        $firstVideo = $course->videos->first();
        echo "First video: {$firstVideo->judul_video}\n";
        try {
            $videoUrl = route('courses.video', ['courseId' => $course->course_id, 'videoId' => $firstVideo->video_id]);
            echo "Video URL: {$videoUrl}\n";
        } catch (Exception $e) {
            echo "Error generating video URL: " . $e->getMessage() . "\n";
        }
    }

    // Test route generation for quizzes
    if ($course->quizzes && $course->quizzes->count() > 0) {
        $firstQuiz = $course->quizzes->first();
        echo "First quiz: {$firstQuiz->judul_quiz}\n";
        try {
            $quizUrl = route('courses.quiz.show', ['courseId' => $course->course_id, 'quizId' => $firstQuiz->quiz_id]);
            echo "Quiz URL: {$quizUrl}\n";
        } catch (Exception $e) {
            echo "Error generating quiz URL: " . $e->getMessage() . "\n";
        }
    }

    // Test lesson ordering
    $allLessons = collect();
    
    // Add videos
    if ($course->videos) {
        foreach ($course->videos as $video) {
            $allLessons->push([
                'id' => $video->video_id,
                'type' => 'video',
                'title' => $video->judul_video,
                'order' => $video->urutan_video ?? 999,
            ]);
        }
    }

    // Add quizzes
    if ($course->quizzes) {
        foreach ($course->quizzes as $quiz) {
            $allLessons->push([
                'id' => $quiz->quiz_id,
                'type' => 'quiz',
                'title' => $quiz->judul_quiz,
                'order' => $quiz->urutan_quiz ?? 999,
            ]);
        }
    }

    $sortedLessons = $allLessons->sortBy('order')->values();
    
    echo "\nLesson order:\n";
    foreach ($sortedLessons as $index => $lesson) {
        echo ($index + 1) . ". {$lesson['type']}: {$lesson['title']} (order: {$lesson['order']})\n";
    }

    echo "\nTest completed successfully!\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
