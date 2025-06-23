<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Get a course to test
$course = App\Models\Course::with([
    'kategori',
    'sections.videos.vidio',
    'sections.quizzes.questions',
    'quizzes',
    'quizzes.questions'
])->active()->first();

if (!$course) {
    echo "No courses found in database.\n";
    exit(1);
}

echo "Testing Course: {$course->nama_course}\n";
echo "Course ID: {$course->course_id}\n";

// Test sections
echo "\nSections:\n";
if ($course->sections) {
    echo "- Found " . $course->sections->count() . " sections\n";
    foreach ($course->sections as $section) {
        echo "  Section {$section->urutan_section}: {$section->nama_section}\n";
        
        // Test videos
        if ($section->videos) {
            echo "    Videos: " . $section->videos->count() . "\n";
            foreach ($section->videos as $video) {
                echo "      Video {$video->urutan_video}: ";
                if ($video->vidio) {
                    echo $video->vidio->nama ?? 'Unnamed video';
                } else {
                    echo "No vidio data";
                }
                echo "\n";
            }
        } else {
            echo "    Videos: NULL or empty\n";
        }
        
        // Test quizzes
        if ($section->quizzes) {
            echo "    Quizzes: " . $section->quizzes->count() . "\n";
        } else {
            echo "    Quizzes: NULL or empty\n";
        }
    }
} else {
    echo "- No sections found (NULL)\n";
}

// Test quizzes
echo "\nCourse Quizzes:\n";
if ($course->quizzes) {
    echo "- Found " . $course->quizzes->count() . " quizzes\n";
} else {
    echo "- No quizzes found (NULL)\n";
}

echo "\nTest completed successfully - no 'count() on null' errors!\n";
