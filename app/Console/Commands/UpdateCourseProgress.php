<?php

namespace App\Console\Commands;

use App\Models\Course;
use App\Models\QuizResult;
use App\Models\UserCourseProgress;
use App\Models\UserVideoProgress;
use Illuminate\Console\Command;

class UpdateCourseProgress extends Command
{
    protected $signature = 'course:update-progress {--user=} {--course=}';
    protected $description = 'Update progress percentages for all course enrollments with the new calculation formula';

    public function handle()
    {
        $userIdFilter = $this->option('user');
        $courseIdFilter = $this->option('course');

        $query = UserCourseProgress::with(['course.sections.videos', 'course.quizzes']);

        if ($userIdFilter) {
            $query->where('user_id', $userIdFilter);
            $this->info("Updating progress for user ID: {$userIdFilter}");
        }

        if ($courseIdFilter) {
            $query->where('course_id', $courseIdFilter);
            $this->info("Updating progress for course ID: {$courseIdFilter}");
        }

        $progressRecords = $query->get();
        $total = $progressRecords->count();

        if ($total === 0) {
            $this->info('No progress records found matching the criteria');
            return 0;
        }

        $this->info("Updating progress for {$total} records...");
        $bar = $this->output->createProgressBar($total);
        $bar->start();

        $updated = 0;
        foreach ($progressRecords as $progress) {
            $course = $progress->course;
            $userId = $progress->user_id;
            $courseId = $progress->course_id;

            if (!$course || !$course->sections) {
                $bar->advance();
                continue;
            }

            // Video progress calculation
            $totalVideos = $course->sections->flatMap->videos->count();
            $videoProgress = 0;

            if ($totalVideos > 0) {
                $completedVideos = UserVideoProgress::where('user_id', $userId)
                    ->where('course_id', $courseId)
                    ->where('is_completed', true)
                    ->count();

                $videoProgress = ($completedVideos / $totalVideos) * 100;
            }

            // Quiz progress calculation
            $quizProgress = 0;
            $totalQuizzes = $course->quizzes->count();

            if ($totalQuizzes > 0) {
                $completedQuizzes = QuizResult::where('users_id', $userId)
                    ->whereIn('quiz_id', $course->quizzes->pluck('quiz_id'))
                    ->distinct('quiz_id')
                    ->count();

                $quizProgress = ($completedQuizzes / $totalQuizzes) * 100;
            }

            // Calculate overall progress as the average of video and quiz progress
            $overallProgress = 0;
            $divisor = 0;

            if ($totalVideos > 0) {
                $overallProgress += $videoProgress;
                $divisor++;
            }

            if ($totalQuizzes > 0) {
                $overallProgress += $quizProgress;
                $divisor++;
            }

            $completionPercentage = $divisor > 0 ? $overallProgress / $divisor : 0;

            $status = 'not_started';
            if ($completionPercentage > 0 && $completionPercentage < 100) {
                $status = 'in_progress';
            } elseif ($completionPercentage >= 100) {
                $status = 'completed';
            }

            $oldPercentage = $progress->progress_percentage;

            $progress->update([
                'progress_percentage' => $completionPercentage,
                'status' => $status,
                'completed_at' => $status === 'completed' ? now() : null,
            ]);

            if ($oldPercentage != $completionPercentage) {
                $updated++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Progress update completed. {$updated}/{$total} records were updated.");

        return 1;
    }
}
