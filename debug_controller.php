<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Test the CourseController methods that were problematic
$courseController = new App\Http\Controllers\CourseController();

// Test show method
echo "Testing CourseController::show method...\n";
try {
    $course = App\Models\Course::active()->first();
    if ($course) {
        echo "Testing with course ID: {$course->course_id}\n";
        
        // Simulate the show method logic without HTTP request/response
        $courseWithRelations = App\Models\Course::with([
            'kategori',
            'sections.videos.vidio',
            'sections.quizzes.questions',
            'quizzes',
            'quizzes.questions'
        ])->active()->findOrFail($course->course_id);
        
        echo "Course loaded successfully: {$courseWithRelations->nama_course}\n";
        
        // Test the problematic code sections
        if ($courseWithRelations->sections) {
            echo "Sections count: " . $courseWithRelations->sections->count() . "\n";
        } else {
            echo "Sections: NULL\n";
        }
        
        if ($courseWithRelations->quizzes) {
            echo "Quizzes count: " . $courseWithRelations->quizzes->count() . "\n";
        } else {
            echo "Quizzes: NULL\n";
        }
        
    } else {
        echo "No active courses found.\n";
    }
} catch (Exception $e) {
    echo "Error in show method: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}

// Test start method logic
echo "\nTesting CourseController::start method logic...\n";
try {
    $course = App\Models\Course::active()->first();
    if ($course) {
        echo "Testing first video retrieval for course: {$course->course_id}\n";
        
        // Test the fixed logic
        $firstSection = $course->sections()
            ->orderBy('urutan_section')
            ->first();
        
        $firstVideo = null;
        if ($firstSection) {
            $firstVideo = $firstSection->videos()
                ->orderBy('urutan_video')
                ->first();
            echo "First video found: " . ($firstVideo ? $firstVideo->course_video_id : 'NULL') . "\n";
        } else {
            echo "No sections found - this would have caused the original error!\n";
        }
    }
} catch (Exception $e) {
    echo "Error in start method: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}

echo "\nAll tests completed successfully!\n";
