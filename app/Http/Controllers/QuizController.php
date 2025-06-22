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
        $quiz = Quiz::with('course')->find($id);
        
        if (!$quiz) {
            return response()->json([
                'success' => false,
                'message' => 'Quiz tidak ditemukan'
            ], 404);
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
        $request->validate([
            'judul_quiz' => 'required|string|max:150',
            'deskripsi_quiz' => 'nullable|string',
            'tipe_quiz' => 'required|in:setelah_video,setelah_section,akhir_course',
            'durasi_menit' => 'required|integer|min:1|max:120',
            'konten_quiz' => 'required|string',
            'is_active' => 'nullable|boolean',
        ]);

        // Validate JSON content
        $content = json_decode($request->konten_quiz, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return back()->withErrors(['konten_quiz' => 'Format JSON tidak valid']);
        }

        $quiz = Quiz::create([
            'course_id' => $courseId,
            'judul_quiz' => $request->judul_quiz,
            'deskripsi_quiz' => $request->deskripsi_quiz,
            'tipe_quiz' => $request->tipe_quiz,
            'durasi_menit' => $request->durasi_menit,
            'konten_quiz' => $request->konten_quiz,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.courses.quizzes', $courseId)
            ->with('success', 'Quiz berhasil dibuat!');
    }

    /**
     * Update quiz for admin
     */
    public function adminUpdate(Request $request, $quizId)
    {
        $quiz = Quiz::findOrFail($quizId);
        
        $request->validate([
            'judul_quiz' => 'required|string|max:150',
            'deskripsi_quiz' => 'nullable|string',
            'tipe_quiz' => 'required|in:setelah_video,setelah_section,akhir_course',
            'durasi_menit' => 'required|integer|min:1|max:120',
            'konten_quiz' => 'required|string',
            'is_active' => 'nullable|boolean',
        ]);

        // Validate JSON content
        $content = json_decode($request->konten_quiz, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return back()->withErrors(['konten_quiz' => 'Format JSON tidak valid']);
        }

        $quiz->update([
            'judul_quiz' => $request->judul_quiz,
            'deskripsi_quiz' => $request->deskripsi_quiz,
            'tipe_quiz' => $request->tipe_quiz,
            'durasi_menit' => $request->durasi_menit,
            'konten_quiz' => $request->konten_quiz,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.courses.quizzes', $quiz->course_id)
            ->with('success', 'Quiz berhasil diupdate!');
    }

    /**
     * Delete quiz for admin
     */
    public function adminDestroy($quizId)
    {
        $quiz = Quiz::findOrFail($quizId);
        $courseId = $quiz->course_id;
        
        // Delete related results first
        $quiz->results()->delete();
        $quiz->delete();

        return redirect()->route('admin.courses.quizzes', $courseId)
            ->with('success', 'Quiz berhasil dihapus!');
    }
}
