<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\CourseSection;
use App\Models\CourseVideo;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\QuizResult;
use App\Models\QuizAnswerDetail;
use App\Models\Kategori;
use App\Models\User;
use App\Models\UserCourseProgress;
use App\Models\UserVideoProgress;
use App\Models\Vidio;
use Illuminate\Support\Facades\DB;

class CourseQuizProgressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Dapatkan data user dan course
        $user = User::where('email', 'student@skilllearn.com')->first();
        $courses = Course::all();

        if (!$user || $courses->isEmpty()) {
            $this->command->info('❌ Seeder tidak dapat dijalankan: User atau Course tidak ditemukan');
            return;
        }

        foreach ($courses as $course) {
            $this->createUserProgress($user, $course);
            $this->createQuizResultsForUser($user, $course);
        }

        $this->command->info('✅ Data progress belajar dan quiz berhasil dibuat');
    }

    /**
     * Create progress records for a user in a course
     */
    private function createUserProgress($user, $course)
    {
        // Calculate total videos in course
        $totalVideos = CourseVideo::where('course_id', $course->course_id)->count();

        if ($totalVideos === 0) {
            return;
        }

        // Get sections and videos
        $sections = CourseSection::where('course_id', $course->course_id)
                                ->orderBy('urutan_section')
                                ->get();

        if ($sections->isEmpty()) {
            return;
        }

        // Randomly determine completed videos (between 30% and 80%)
        $videosCompleted = rand(
            (int)($totalVideos * 0.3),
            (int)($totalVideos * 0.8)
        );

        // Calculate progress
        $progressPct = ($videosCompleted / $totalVideos) * 100;

        // Get current section and video based on progress
        $currentSectionIndex = (int)(($progressPct / 100) * $sections->count());
        if ($currentSectionIndex >= $sections->count()) {
            $currentSectionIndex = $sections->count() - 1;
        }

        $currentSection = $sections[$currentSectionIndex];

        // Get current video
        $currentVideo = CourseVideo::where('section_id', $currentSection->section_id)
                                  ->orderBy('urutan_video')
                                  ->first();

        // Create or update course progress
        UserCourseProgress::updateOrCreate(
            [
                'user_id' => $user->users_id,
                'course_id' => $course->course_id
            ],
            [
                'current_section_id' => $currentSection->section_id,
                'current_video_id' => $currentVideo ? $currentVideo->vidio_vidio_id : null,
                'videos_completed' => $videosCompleted,
                'total_videos' => $totalVideos,
                'progress_percentage' => $progressPct,
                'started_at' => now()->subDays(rand(10, 30)),
                'completed_at' => $progressPct >= 100 ? now()->subDays(rand(0, 5)) : null,
                'status' => $progressPct >= 100 ? 'completed' : 'in_progress',
            ]
        );

        // Create video progress for some videos
        $videos = CourseVideo::where('course_id', $course->course_id)
                            ->orderBy('urutan_video')
                            ->limit($videosCompleted + 2) // +2 for incomplete ones
                            ->get();

        $count = 0;
        foreach ($videos as $video) {
            $isCompleted = $count < $videosCompleted;

            UserVideoProgress::updateOrCreate(
                [
                    'user_id' => $user->users_id,
                    'vidio_vidio_id' => $video->vidio_vidio_id,
                ],
                [
                    'course_id' => $course->course_id,
                    'watch_time_seconds' => $isCompleted ? $video->durasi_menit * 60 : rand(30, $video->durasi_menit * 30),
                    'total_duration_seconds' => $video->durasi_menit * 60,
                    'completion_percentage' => $isCompleted ? 100 : rand(10, 90),
                    'is_completed' => $isCompleted,
                    'completed_at' => $isCompleted ? now()->subDays(rand(0, 10)) : null,
                    'first_watched_at' => now()->subDays(rand(0, 5))->subHours(rand(0, 12)),
                ]
            );

            $count++;
        }
    }

    /**
     * Create quiz results for a user in a course
     */
    private function createQuizResultsForUser($user, $course)
    {
        $quizzes = Quiz::where('course_id', $course->course_id)->get();

        if ($quizzes->isEmpty()) {
            return;
        }

        foreach ($quizzes as $quiz) {
            // Skip some quizzes randomly to simulate incomplete progress
            if (rand(0, 2) === 0) {
                continue;
            }

            $questions = $quiz->questions;
            $totalQuestions = $questions->count();

            if ($totalQuestions === 0) {
                continue;
            }

            // Randomly determine how many correct answers (weighted toward good performance)
            $correctCount = rand(ceil($totalQuestions * 0.6), $totalQuestions);
            $wrongCount = $totalQuestions - $correctCount;

            // Calculate score based on correct answers
            $score = ($correctCount / $totalQuestions) * 100;

            // Create quiz result
            $quizResult = QuizResult::updateOrCreate(
                [
                    'quiz_id' => $quiz->quiz_id,
                    'users_id' => $user->users_id
                ],
                [
                    'nilai_total' => $score,
                    'jumlah_benar' => $correctCount,
                    'jumlah_salah' => $wrongCount,
                    'total_soal' => $totalQuestions,
                    'waktu_mulai' => now()->subDays(rand(1, 7))->subMinutes(rand(15, 30)),
                    'waktu_selesai' => now()->subDays(rand(1, 7)),
                    'detail_jawaban' => json_encode([
                        'total_benar' => $correctCount,
                        'total_salah' => $wrongCount,
                    ]),
                ]
            );

            // Create quiz answer details
            QuizAnswerDetail::where('result_quiz_id', $quizResult->result_quiz_id)->delete();

            $i = 0;
            foreach ($questions as $question) {
                $isCorrect = $i < $correctCount;

                QuizAnswerDetail::create([
                    'result_quiz_id' => $quizResult->result_quiz_id,
                    'quiz_id' => $quiz->quiz_id,
                    'urutan_pertanyaan' => $question->urutan_pertanyaan,
                    'pertanyaan' => $question->pertanyaan,
                    'jawaban_user' => $isCorrect ? $question->jawaban_benar : 'Jawaban salah contoh',
                    'jawaban_benar' => $question->jawaban_benar,
                    'is_correct' => $isCorrect,
                    'skor_pertanyaan' => $isCorrect ? 100 : 0,
                ]);

                $i++;
            }
        }
    }
}
