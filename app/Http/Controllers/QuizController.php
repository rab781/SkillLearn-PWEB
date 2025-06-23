<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\QuizResult;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class QuizController extends Controller
{
    /**
     * Display quizzes for a course
     */
    public function index($courseId)
    {
        $course = Course::find($courseId);
        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Course tidak ditemukan'
            ], 404);
        }

        $quizzes = Quiz::where('course_id', $courseId)->get();

        return response()->json([
            'success' => true,
            'course' => $course,
            'quizzes' => $quizzes
        ]);
    }

    /**
     * Store a new quiz (Admin only)
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_id' => 'required|exists:courses,course_id',
            'soal' => 'required|string|max:500',
            'jawaban' => 'required|array|min:2',
            'jawaban.*.text' => 'required|string',
            'jawaban.*.is_correct' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Ensure only one correct answer
        $correctAnswers = collect($request->jawaban)->where('is_correct', true);
        if ($correctAnswers->count() !== 1) {
            return response()->json([
                'success' => false,
                'message' => 'Harus ada tepat satu jawaban yang benar'
            ], 422);
        }

        $quiz = Quiz::create([
            'course_id' => $request->course_id,
            'soal' => $request->soal,
            'jawaban' => $request->jawaban,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Quiz berhasil dibuat',
            'quiz' => $quiz
        ], 201);
    }

    /**
     * Display a specific quiz
     */
    public function show($id)
    {
        $quiz = Quiz::with(['course', 'questions', 'results'])->find($id);

        if (!$quiz) {
            return response()->json([
                'success' => false,
                'message' => 'Quiz tidak ditemukan'
            ], 404);
        }

        // Check if this is an admin request (has admin middleware or admin role)
        $isAdmin = auth()->check() && (auth()->user()->role === 'admin' || request()->is('admin/*'));

        if ($isAdmin) {
            // Return full quiz data for admin
            return response()->json([
                'success' => true,
                'quiz' => $quiz
            ]);
        }

        // Hide correct answers from response (for taking quiz)
        $quizData = $quiz->toArray();
        if (isset($quizData['jawaban'])) {
            $quizData['jawaban'] = collect($quiz->jawaban)->map(function($answer) {
                return ['text' => $answer['text']];
            })->toArray();
        }

        return response()->json([
            'success' => true,
            'quiz' => $quizData
        ]);
    }

    /**
     * Update a quiz (Admin only)
     */
    public function update(Request $request, $id)
    {
        $quiz = Quiz::find($id);

        if (!$quiz) {
            return response()->json([
                'success' => false,
                'message' => 'Quiz tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'soal' => 'required|string|max:500',
            'jawaban' => 'required|array|min:2',
            'jawaban.*.text' => 'required|string',
            'jawaban.*.is_correct' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Ensure only one correct answer
        $correctAnswers = collect($request->jawaban)->where('is_correct', true);
        if ($correctAnswers->count() !== 1) {
            return response()->json([
                'success' => false,
                'message' => 'Harus ada tepat satu jawaban yang benar'
            ], 422);
        }

        $quiz->update([
            'soal' => $request->soal,
            'jawaban' => $request->jawaban,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Quiz berhasil diupdate',
            'quiz' => $quiz
        ]);
    }

    /**
     * Delete a quiz (Admin only)
     */
    public function destroy($id)
    {
        $quiz = Quiz::find($id);

        if (!$quiz) {
            return response()->json([
                'success' => false,
                'message' => 'Quiz tidak ditemukan'
            ], 404);
        }

        $quiz->delete();

        return response()->json([
            'success' => true,
            'message' => 'Quiz berhasil dihapus'
        ]);
    }

    /**
     * Submit quiz answer
     */
    public function submitAnswer(Request $request, $id)
    {
        $quiz = Quiz::find($id);

        if (!$quiz) {
            return response()->json([
                'success' => false,
                'message' => 'Quiz tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'jawaban' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        /** @var User $user */
        $user = Auth::user();

        $isCorrect = $quiz->checkAnswer($request->jawaban);
        $score = $isCorrect ? 100 : 0;

        $result = QuizResult::updateOrCreate(
            [
                'quiz_id' => $quiz->quiz_id,
                'users_id' => $user->users_id,
            ],
            [
                'nilai_total' => $score,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Jawaban berhasil disimpan',
            'result' => [
                'is_correct' => $isCorrect,
                'score' => $score,
                'correct_answer' => $quiz->getCorrectAnswer()['text']
            ]
        ]);
    }

    /**
     * Get user's quiz results for a course
     */
    public function getUserResults($courseId)
    {
        /** @var User $user */
        $user = Auth::user();

        $course = Course::with('quizzes')->find($courseId);
        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Course tidak ditemukan'
            ], 404);
        }

        $quizIds = $course->quizzes->pluck('quiz_id');
        $results = QuizResult::with('quiz')
            ->whereIn('quiz_id', $quizIds)
            ->where('users_id', $user->users_id)
            ->get();

        $totalQuizzes = $course->quizzes->count();
        $completedQuizzes = $results->count();
        $averageScore = $results->avg('nilai_total') ?? 0;

        return response()->json([
            'success' => true,
            'course' => $course,
            'results' => $results,
            'summary' => [
                'total_quizzes' => $totalQuizzes,
                'completed_quizzes' => $completedQuizzes,
                'completion_rate' => $totalQuizzes > 0 ? round(($completedQuizzes / $totalQuizzes) * 100, 2) : 0,
                'average_score' => round($averageScore, 2)
            ]
        ]);
    }

    /**
     * Get course quiz statistics (Admin only)
     */
    public function getCourseStatistics($courseId)
    {
        $course = Course::with('quizzes')->find($courseId);
        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Course tidak ditemukan'
            ], 404);
        }

        $quizIds = $course->quizzes->pluck('quiz_id');
        $allResults = QuizResult::with(['quiz', 'user'])
            ->whereIn('quiz_id', $quizIds)
            ->get();

        $totalParticipants = $allResults->pluck('users_id')->unique()->count();
        $averageScore = $allResults->avg('nilai_total') ?? 0;
        $passedCount = $allResults->where('nilai_total', '>=', 60)->count();

        $statistics = [
            'course' => $course,
            'total_quizzes' => $course->quizzes->count(),
            'total_participants' => $totalParticipants,
            'average_score' => round($averageScore, 2),
            'pass_rate' => $allResults->count() > 0 ? round(($passedCount / $allResults->count()) * 100, 2) : 0,
            'quiz_details' => $course->quizzes->map(function($quiz) {
                $results = $quiz->userAnswers;
                return [
                    'quiz' => $quiz,
                    'participants' => $results->count(),
                    'average_score' => round($results->avg('nilai_total') ?? 0, 2),
                    'pass_rate' => $results->count() > 0 ? round($results->where('nilai_total', '>=', 60)->count() / $results->count() * 100, 2) : 0
                ];
            })
        ];

        return response()->json([
            'success' => true,
            'statistics' => $statistics
        ]);
    }

    /**
     * Display quiz management page for admin
     */
    public function adminIndex($courseId)
    {
        $course = Course::with(['kategori'])->findOrFail($courseId);
        $quizzes = Quiz::where('course_id', $courseId)
            ->with(['results'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.quizzes.index', compact('course', 'quizzes'));
    }

    /**
     * Store a new quiz for admin
     */
    public function adminStore(Request $request, $courseId)
    {
        $validator = Validator::make($request->all(), [
            'judul_quiz' => 'required|string|max:150',
            'deskripsi_quiz' => 'nullable|string',
            'tipe_quiz' => 'required|in:setelah_video,setelah_section,akhir_course',
            'durasi_menit' => 'required|integer|min:1|max:180',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        $quiz = Quiz::create([
            'course_id' => $courseId,
            'judul_quiz' => $request->judul_quiz,
            'deskripsi_quiz' => $request->deskripsi_quiz,
            'tipe_quiz' => $request->tipe_quiz,
            'durasi_menit' => $request->durasi_menit,
            'is_active' => $request->boolean('is_active', true),
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Quiz berhasil dibuat!',
                'quiz_id' => $quiz->quiz_id,
                'quiz' => $quiz
            ]);
        }

        return redirect()->route('admin.courses.quizzes', $courseId)
            ->with('success', 'Quiz berhasil dibuat!');
    }

    /**
     * Update quiz for admin
     */
    public function adminUpdate(Request $request, $quizId)
    {
        $quiz = Quiz::findOrFail($quizId);

        $validator = Validator::make($request->all(), [
            'judul_quiz' => 'required|string|max:150',
            'deskripsi_quiz' => 'nullable|string',
            'tipe_quiz' => 'required|in:setelah_video,setelah_section,akhir_course',
            'durasi_menit' => 'required|integer|min:1|max:180',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        $quiz->update([
            'judul_quiz' => $request->judul_quiz,
            'deskripsi_quiz' => $request->deskripsi_quiz,
            'tipe_quiz' => $request->tipe_quiz,
            'durasi_menit' => $request->durasi_menit,
            'is_active' => $request->boolean('is_active', $quiz->is_active),
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Quiz berhasil diupdate!',
                'quiz' => $quiz
            ]);
        }

        return redirect()->route('admin.courses.quizzes', $quiz->course_id)
            ->with('success', 'Quiz berhasil diupdate!');
    }

    /**
     * Delete quiz for admin
     */
    public function adminDestroy(Request $request, $quizId)
    {
        $quiz = Quiz::findOrFail($quizId);
        $courseId = $quiz->course_id;

        // Delete related results and questions first
        $quiz->results()->delete();
        $quiz->questions()->delete();
        $quiz->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Quiz berhasil dihapus!'
            ]);
        }

        return redirect()->route('admin.courses.quizzes', $courseId)
            ->with('success', 'Quiz berhasil dihapus!');
    }

    /**
     * Get quizzes for video completion
     */
    public function getVideoQuizzes($courseId, $videoId)
    {
        $quizzes = Quiz::where('course_id', $courseId)
            ->where('vidio_vidio_id', $videoId)
            ->where('tipe_quiz', 'setelah_video')
            ->where('is_active', true)
            ->orderBy('urutan')
            ->get();

        // Check if user has already taken these quizzes
        $userId = Auth::id();
        $quizzesWithResults = $quizzes->map(function($quiz) use ($userId) {
            $userResult = QuizResult::where('quiz_id', $quiz->quiz_id)
                ->where('user_id', $userId)
                ->first();

            $quiz->user_completed = !is_null($userResult);
            $quiz->user_score = $userResult ? $userResult->score : null;

            return $quiz;
        });

        return response()->json([
            'success' => true,
            'quizzes' => $quizzesWithResults,
            'has_incomplete_quizzes' => $quizzesWithResults->where('user_completed', false)->count() > 0
        ]);
    }

    /**
     * Get quiz data for AJAX modal (with questions and options)
     */
    public function getQuizData($quizId)
    {
        /** @var User $user */
        $user = Auth::user();

        $quiz = Quiz::with(['questions', 'course'])->find($quizId);

        if (!$quiz) {
            return response()->json([
                'success' => false,
                'message' => 'Quiz tidak ditemukan'
            ], 404);
        }

        // Check if user has already completed this quiz
        $existingResult = QuizResult::where('quiz_id', $quizId)
            ->where('users_id', $user->users_id)
            ->first();

        if ($existingResult) {
            // Return quiz result if already completed
            return response()->json([
                'success' => true,
                'quiz' => [
                    'quiz_id' => $quiz->quiz_id,
                    'judul_quiz' => $quiz->judul_quiz,
                    'deskripsi_quiz' => $quiz->deskripsi_quiz,
                ],
                'result' => $existingResult,
                'completed' => true
            ]);
        }

        // Format questions for frontend
        $questions = $quiz->questions->map(function ($question) {
            $pilihan = is_array($question->pilihan_jawaban)
                ? $question->pilihan_jawaban
                : json_decode($question->pilihan_jawaban, true);

            return [
                'question_id' => $question->question_id,
                'pertanyaan' => $question->pertanyaan,
                'options' => $pilihan ?: [],
                'urutan_pertanyaan' => $question->urutan_pertanyaan
            ];
        })->sortBy('urutan_pertanyaan')->values();

        return response()->json([
            'success' => true,
            'quiz' => [
                'quiz_id' => $quiz->quiz_id,
                'judul_quiz' => $quiz->judul_quiz,
                'deskripsi_quiz' => $quiz->deskripsi_quiz,
                'durasi_menit' => $quiz->durasi_menit,
                'questions' => $questions
            ],
            'completed' => false
        ]);
    }

    /**
     * Submit quiz answers for AJAX quiz (supports multiple questions)
     */
    public function submitQuizAnswers(Request $request, $courseId, $quizId)
    {
        $validator = Validator::make($request->all(), [
            'answers' => 'required|array',
            'answers.*' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Format jawaban tidak valid',
                'errors' => $validator->errors()
            ], 422);
        }

        /** @var User $user */
        $user = Auth::user();

        $quiz = Quiz::with('questions')->where('quiz_id', $quizId)
            ->where('course_id', $courseId)
            ->first();

        if (!$quiz) {
            return response()->json([
                'success' => false,
                'message' => 'Quiz tidak ditemukan'
            ], 404);
        }

        // Check if user already completed this quiz (allow retries, just log attempt)
        $previousAttempts = QuizResult::where('quiz_id', $quizId)
            ->where('users_id', $user->users_id)
            ->count();

        // Log attempt count for reference (commented out to avoid import issue)
        // \Log::info("User {$user->users_id} attempting quiz {$quizId}, attempt #" . ($previousAttempts + 1));

        $answers = $request->input('answers');
        $totalQuestions = $quiz->questions->count();
        $correctCount = 0;
        $wrongCount = 0;
        $answerDetails = [];

        // Check each answer
        foreach ($quiz->questions as $question) {
            $questionId = $question->question_id;
            $userAnswer = $answers[$questionId] ?? null;
            $correctAnswer = $question->jawaban_benar;
            $isCorrect = $userAnswer === $correctAnswer;

            if ($isCorrect) {
                $correctCount++;
            } else {
                $wrongCount++;
            }

            $answerDetails[] = [
                'question_id' => $questionId,
                'user_answer' => $userAnswer,
                'correct_answer' => $correctAnswer,
                'is_correct' => $isCorrect,
                'bobot_nilai' => $question->bobot_nilai ?? 1
            ];
        }

        // Calculate total score
        $totalScore = $totalQuestions > 0 ? ($correctCount / $totalQuestions) * 100 : 0;

        // Save result (use updateOrCreate to handle unique constraint)
        $result = QuizResult::updateOrCreate(
            [
                'quiz_id' => $quizId,
                'users_id' => $user->users_id,
            ],
            [
                'nilai_total' => round($totalScore, 2),
                'jumlah_benar' => $correctCount,
                'jumlah_salah' => $wrongCount,
                'total_soal' => $totalQuestions,
                'waktu_mulai' => now(),
                'waktu_selesai' => now(),
                'detail_jawaban' => json_encode($answerDetails)
            ]
        );

        // Find next lesson (video or quick review)
        $course = Course::with(['videos', 'quickReviews', 'quizzes'])->find($courseId);
        $nextLesson = null;

        // Get all lessons in order (videos, quick reviews, quizzes)
        $allLessons = collect();
        
        // Add videos with safer URL generation
        if ($course->videos && $course->videos->count() > 0) {
            foreach ($course->videos as $video) {
                try {
                    $videoUrl = route('courses.video', ['courseId' => $courseId, 'videoId' => $video->video_id]);
                    $allLessons->push([
                        'id' => $video->video_id,
                        'type' => 'video',
                        'title' => $video->judul_video ?? 'Video',
                        'order' => $video->urutan_video ?? 999,
                        'url' => $videoUrl
                    ]);
                } catch (\Exception $e) {
                    // Skip this video if URL generation fails
                    continue;
                }
            }
        }

        // Add quick reviews with safer URL generation
        if ($course->quickReviews && $course->quickReviews->count() > 0) {
            foreach ($course->quickReviews as $review) {
                try {
                    $reviewUrl = route('courses.quick-review', ['courseId' => $courseId, 'reviewId' => $review->review_id]);
                    $allLessons->push([
                        'id' => $review->review_id,
                        'type' => 'review',
                        'title' => $review->judul_review ?? 'Quick Review',
                        'order' => $review->urutan_review ?? 999,
                        'url' => $reviewUrl
                    ]);
                } catch (\Exception $e) {
                    // Skip this review if URL generation fails
                    continue;
                }
            }
        }

        // Add quizzes with safer URL generation
        if ($course->quizzes && $course->quizzes->count() > 0) {
            foreach ($course->quizzes as $quizItem) {
                try {
                    $quizUrl = route('courses.quiz.show', ['courseId' => $courseId, 'quizId' => $quizItem->quiz_id]);
                    $allLessons->push([
                        'id' => $quizItem->quiz_id,
                        'type' => 'quiz',
                        'title' => $quizItem->judul_quiz ?? 'Quiz',
                        'order' => $quizItem->urutan_quiz ?? 999,
                        'url' => $quizUrl
                    ]);
                } catch (\Exception $e) {
                    // Skip this quiz if URL generation fails
                    continue;
                }
            }
        }

        // Sort lessons by order and find current quiz position
        $sortedLessons = $allLessons->sortBy('order')->values();
        $currentQuizIndex = $sortedLessons->search(function ($lesson) use ($quizId) {
            return $lesson['type'] === 'quiz' && $lesson['id'] == $quizId;
        });

        // Get next lesson if exists
        if ($currentQuizIndex !== false && $currentQuizIndex < $sortedLessons->count() - 1) {
            $nextLesson = $sortedLessons[$currentQuizIndex + 1];
        } else {
            // If no next lesson found, provide course overview as fallback
            $nextLesson = [
                'type' => 'course',
                'title' => 'Kembali ke Course',
                'url' => route('courses.show', $courseId)
            ];
        }

        return response()->json([
            'success' => true,
            'message' => 'Quiz berhasil diselesaikan',
            'result' => [
                'quiz_result_id' => $result->result_quiz_id,
                'nilai_total' => $result->nilai_total,
                'jumlah_benar' => $correctCount,
                'jumlah_salah' => $wrongCount,
                'total_soal' => $totalQuestions,
                'is_passed' => $result->nilai_total >= 60,
                'next_lesson' => $nextLesson
            ]
        ]);
    }

    /**
     * Display quiz page for taking the quiz
     */
    public function showQuizPage($courseId, $quizId)
    {
        try {
            $course = \App\Models\Course::findOrFail($courseId);
            $quiz = Quiz::with(['questions', 'course', 'section'])
                ->where('quiz_id', $quizId)
                ->where('course_id', $courseId)
                ->firstOrFail();

            $userId = Auth::id();

            // Check if user has started the course
            $userProgress = \App\Models\UserCourseProgress::where('user_id', $userId)
                ->where('course_id', $courseId)
                ->first();

            if (!$userProgress) {
                return redirect()->route('courses.show', $courseId)
                    ->with('error', 'Anda harus memulai course terlebih dahulu sebelum mengerjakan quiz.');
            }

            // Get user's previous quiz attempts
            $previousResults = \App\Models\QuizResult::where('quiz_id', $quizId)
                ->where('users_id', $userId)
                ->orderBy('created_at', 'desc')
                ->get();

            $bestScore = $previousResults->max('nilai_total') ?? 0;
            $attemptCount = $previousResults->count();

            // Check if quiz has questions
            if ($quiz->questions->isEmpty()) {
                return redirect()->route('courses.show', $courseId)
                    ->with('error', 'Quiz ini belum memiliki soal.');
            }

            return view('courses.quiz', compact(
                'course',
                'quiz', 
                'userProgress',
                'previousResults',
                'bestScore',
                'attemptCount'
            ));

        } catch (\Exception $e) {
            return redirect()->route('courses.show', $courseId)
                ->with('error', 'Quiz tidak ditemukan atau terjadi kesalahan.');
        }
    }
}
