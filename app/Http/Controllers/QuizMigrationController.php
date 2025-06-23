<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\QuizQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuizMigrationController extends Controller
{
    public function migrateOldQuizzes()
    {
        $quizzesWithJsonQuestions = Quiz::whereNotNull('konten_quiz')
                                      ->whereJsonLength('konten_quiz', '>', 0)
                                      ->get();

        $migrated = 0;
        $errors = [];

        foreach ($quizzesWithJsonQuestions as $quiz) {
            try {
                DB::beginTransaction();

                $content = $quiz->konten_quiz;

                // Check if it's already parsed JSON or string
                if (is_string($content)) {
                    $questions = json_decode($content, true);
                } else {
                    $questions = $content;
                }

                if (!is_array($questions)) {
                    $errors[] = "Quiz ID {$quiz->quiz_id}: Invalid JSON format";
                    continue;
                }

                $questionOrder = 1;
                foreach ($questions as $questionData) {
                    // Handle different JSON structures
                    $pertanyaan = $questionData['question'] ?? $questionData['pertanyaan'] ?? '';
                    $options = $questionData['options'] ?? $questionData['pilihan'] ?? [];
                    $correctAnswer = $questionData['correct'] ?? $questionData['jawaban_benar'] ?? '';

                    if (empty($pertanyaan) || empty($options)) {
                        continue;
                    }

                    // Ensure options are in the right format
                    if (is_array($options) && !empty($options)) {
                        // Convert associative array to indexed array if needed
                        if (array_keys($options) !== range(0, count($options) - 1)) {
                            $options = array_values($options);
                        }
                    }

                    QuizQuestion::create([
                        'quiz_id' => $quiz->quiz_id,
                        'pertanyaan' => $pertanyaan,
                        'options' => $options,
                        'jawaban_benar' => $correctAnswer,
                        'urutan_pertanyaan' => $questionOrder,
                    ]);

                    $questionOrder++;
                }

                // Clear the old JSON content after successful migration
                $quiz->update(['konten_quiz' => null]);

                DB::commit();
                $migrated++;

            } catch (\Exception $e) {
                DB::rollback();
                $errors[] = "Quiz ID {$quiz->quiz_id}: " . $e->getMessage();
            }
        }

        return response()->json([
            'success' => true,
            'migrated' => $migrated,
            'total_quizzes' => $quizzesWithJsonQuestions->count(),
            'errors' => $errors
        ]);
    }

    public function checkQuizMigrationStatus()
    {
        $totalQuizzes = Quiz::count();
        $quizzesWithJsonContent = Quiz::whereNotNull('konten_quiz')
                                    ->whereJsonLength('konten_quiz', '>', 0)
                                    ->count();
        $quizzesWithQuestions = Quiz::whereHas('questions')->count();
        $quizzesNeedingMigration = Quiz::whereNotNull('konten_quiz')
                                     ->whereJsonLength('konten_quiz', '>', 0)
                                     ->whereDoesntHave('questions')
                                     ->count();

        return response()->json([
            'total_quizzes' => $totalQuizzes,
            'quizzes_with_json_content' => $quizzesWithJsonContent,
            'quizzes_with_questions' => $quizzesWithQuestions,
            'quizzes_needing_migration' => $quizzesNeedingMigration,
            'migration_needed' => $quizzesNeedingMigration > 0
        ]);
    }
}
