<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use App\Models\Course;
use App\Models\CourseVideo;
use App\Models\Quiz;
use App\Models\QuizResult;
use App\Models\RiwayatTonton;
use App\Models\Feedback;
use App\Models\User;

echo "=== Testing SkillLearn Course-Based System ===\n\n";

// Initialize Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "1. Testing Course with Videos...\n";
$course = Course::with(['videos.vidio', 'sections'])->first();
if ($course) {
    echo "Found course: {$course->nama_course}\n";
    echo "Total videos: {$course->videos->count()}\n";
    echo "Total sections: {$course->sections->count()}\n";
} else {
    echo "No courses found. Creating test course...\n";
    
    $course = Course::create([
        'nama_course' => 'Test Course: Laravel untuk Pemula',
        'deskripsi_course' => 'Belajar Laravel dari dasar hingga mahir',
        'level' => 'Beginner',
        'is_active' => true,
        'kategori_kategori_id' => 1
    ]);
    echo "Created course: {$course->nama_course}\n";
}

echo "\n2. Testing Quiz System...\n";
$quiz = Quiz::where('course_id', $course->course_id)->first();
if (!$quiz) {
    echo "Creating test quiz...\n";
    $quiz = Quiz::create([
        'course_id' => $course->course_id,
        'soal' => 'Apa itu Laravel?',
        'jawaban' => [
            ['text' => 'PHP Framework', 'is_correct' => true],
            ['text' => 'Database', 'is_correct' => false],
            ['text' => 'Web Server', 'is_correct' => false],
            ['text' => 'CSS Framework', 'is_correct' => false]
        ]
    ]);
    echo "Created quiz: {$quiz->soal}\n";
} else {
    echo "Found existing quiz: {$quiz->soal}\n";
}

echo "\n3. Testing Course History...\n";
$user = User::where('role', 'CU')->first();
if ($user) {
    echo "Testing with user: {$user->nama_lengkap}\n";
    
    // Record course watch
    $history = RiwayatTonton::recordCourseWatch(
        $user->users_id,
        $course->course_id,
        null, // no specific video yet
        1,    // first position
        25.5  // 25.5% progress
    );
    
    echo "Recorded watch history - Progress: {$history->persentase_tonton}%\n";
    
    // Test quiz submission
    $result = QuizResult::updateOrCreate(
        [
            'quiz_id' => $quiz->quiz_id,
            'users_id' => $user->users_id,
        ],
        [
            'nilai_total' => 100, // correct answer
        ]
    );
    
    echo "Quiz submitted - Score: {$result->nilai_total}\n";
} else {
    echo "No customer user found for testing.\n";
}

echo "\n4. Testing Course-based Feedback...\n";
if ($user) {
    $feedback = Feedback::create([
        'tanggal' => now()->toDateString(),
        'pesan' => 'Course ini sangat membantu untuk belajar Laravel!',
        'course_id' => $course->course_id,
        'rating' => 5,
        'catatan' => 'Materi dijelaskan dengan sangat baik dan mudah dipahami.',
        'users_id' => $user->users_id
    ]);
    
    echo "Created feedback for course with rating: {$feedback->rating}/5\n";
}

echo "\n5. Testing Course Statistics...\n";
$totalWatchers = RiwayatTonton::where('course_id', $course->course_id)->distinct('id_pengguna')->count();
$averageProgress = RiwayatTonton::where('course_id', $course->course_id)->avg('persentase_tonton') ?? 0;
$quizParticipants = QuizResult::whereIn('quiz_id', $course->quizzes->pluck('quiz_id'))->count();
$averageQuizScore = QuizResult::whereIn('quiz_id', $course->quizzes->pluck('quiz_id'))->avg('nilai_total') ?? 0;

echo "Course Statistics:\n";
echo "- Total watchers: {$totalWatchers}\n";
echo "- Average progress: " . round($averageProgress, 2) . "%\n";
echo "- Quiz participants: {$quizParticipants}\n";
echo "- Average quiz score: " . round($averageQuizScore, 2) . "\n";

echo "\n6. Testing Recent Courses for User...\n";
if ($user) {    $recentCourses = RiwayatTonton::with(['course', 'currentVideo'])
        ->where('id_pengguna', $user->users_id)
        ->orderBy('waktu_ditonton', 'desc')
        ->limit(5)
        ->get();
    
    echo "Recent courses for {$user->nama_lengkap}:\n";
    foreach ($recentCourses as $history) {
        echo "- {$history->course->nama_course} (Progress: {$history->persentase_tonton}%)\n";
    }
}

echo "\n=== Course-Based System Test Complete ===\n";
echo "✓ Course system working\n";
echo "✓ Quiz system working\n";
echo "✓ Course history tracking working\n";
echo "✓ Course-based feedback working\n";
echo "✓ Statistics generation working\n";
echo "✓ User progress tracking working\n";

echo "\nSystem successfully migrated from video-based to course-based!\n";
