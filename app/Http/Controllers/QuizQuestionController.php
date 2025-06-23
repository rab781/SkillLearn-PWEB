<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class QuizQuestionController extends Controller
{
    /**
     * Display the list of questions for a quiz
     */
    public function index($quizId)
    {
        $quiz = Quiz::with(['course', 'questions'])->findOrFail($quizId);
        $questions = $quiz->questions()->orderBy('urutan_pertanyaan')->get();

        return view('admin.quizzes.questions.index', compact('quiz', 'questions'));
    }

    /**
     * Store a new question for a quiz
     */
    public function store(Request $request, $quizId)
    {
        try {
            $quiz = Quiz::findOrFail($quizId);

            $validator = Validator::make($request->all(), [
                'pertanyaan' => 'required|string|max:1000',
                'pilihan_jawaban' => 'required|array|min:2',
                'pilihan_jawaban.*' => 'required|string|max:500',
                'jawaban_benar' => 'required|string|in:A,B,C,D,E,F',
                'bobot_nilai' => 'required|integer|min:1|max:5',
            ]);

            if ($validator->fails()) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $validator->errors()->first(),
                        'errors' => $validator->errors()
                    ], 422);
                }
                return back()->withErrors($validator)->withInput();
            }

            // Ensure no empty answer options
            $hasEmptyOptions = collect($request->pilihan_jawaban)->contains(function($value) {
                return empty(trim($value));
            });

            if ($hasEmptyOptions) {
                return response()->json([
                    'success' => false,
                    'message' => 'Semua pilihan jawaban harus diisi'
                ], 422);
            }

            // Get the next order number
            $latestOrder = $quiz->questions()->max('urutan_pertanyaan') ?? 0;

            // Create the question
            $question = QuizQuestion::create([
                'quiz_id' => $quiz->quiz_id,
                'urutan_pertanyaan' => $latestOrder + 1,
                'pertanyaan' => $request->pertanyaan,
                'pilihan_jawaban' => json_encode($request->pilihan_jawaban),
                'jawaban_benar' => $request->jawaban_benar,
                'bobot_nilai' => $request->bobot_nilai,
                'is_active' => true,
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pertanyaan quiz berhasil ditambahkan!',
                    'question' => $question
                ]);
            }

            return redirect()->route('admin.courses.quizzes.questions.index', $quizId)
                ->with('success', 'Pertanyaan quiz berhasil ditambahkan!');

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }

            return back()->withErrors('Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Update a quiz question
     */
    public function update(Request $request, $questionId)
    {
        try {
            $question = QuizQuestion::findOrFail($questionId);

            $validator = Validator::make($request->all(), [
                'pertanyaan' => 'required|string|max:1000',
                'pilihan_jawaban' => 'required|array|min:2',
                'pilihan_jawaban.*' => 'required|string|max:500',
                'jawaban_benar' => 'required|string|in:A,B,C,D,E,F',
                'bobot_nilai' => 'required|integer|min:1|max:5',
            ]);

            if ($validator->fails()) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $validator->errors()->first(),
                        'errors' => $validator->errors()
                    ], 422);
                }
                return back()->withErrors($validator)->withInput();
            }

            // Ensure no empty answer options
            $hasEmptyOptions = collect($request->pilihan_jawaban)->contains(function($value) {
                return empty(trim($value));
            });

            if ($hasEmptyOptions) {
                return response()->json([
                    'success' => false,
                    'message' => 'Semua pilihan jawaban harus diisi'
                ], 422);
            }

            $question->update([
                'pertanyaan' => $request->pertanyaan,
                'pilihan_jawaban' => json_encode($request->pilihan_jawaban),
                'jawaban_benar' => $request->jawaban_benar,
                'bobot_nilai' => $request->bobot_nilai,
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pertanyaan quiz berhasil diperbarui!',
                    'question' => $question
                ]);
            }

            return redirect()->route('admin.courses.quizzes.questions.index', $question->quiz_id)
                ->with('success', 'Pertanyaan quiz berhasil diperbarui!');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }

            return back()->withErrors('Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Delete a quiz question
     */
    public function destroy(Request $request, $questionId)
    {
        try {
            $question = QuizQuestion::findOrFail($questionId);
            $quizId = $question->quiz_id;

            $question->delete();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pertanyaan quiz berhasil dihapus!'
                ]);
            }

            return redirect()->route('admin.courses.quizzes.questions.index', $quizId)
                ->with('success', 'Pertanyaan quiz berhasil dihapus!');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }

            return back()->withErrors('Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Reorder quiz questions
     */
    public function reorder(Request $request, $quizId)
    {
        $quiz = Quiz::findOrFail($quizId);

        $validator = Validator::make($request->all(), [
            'questions' => 'required|array',
            'questions.*.question_id' => 'required|integer|exists:quiz_questions,question_id',
            'questions.*.urutan_pertanyaan' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        foreach ($request->questions as $questionData) {
            $question = QuizQuestion::find($questionData['question_id']);
            if ($question && $question->quiz_id == $quizId) {
                $question->urutan_pertanyaan = $questionData['urutan_pertanyaan'];
                $question->save();
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Urutan pertanyaan berhasil diubah!'
        ]);
    }

    /**
     * Update question order via AJAX
     */
    public function updateOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'questions' => 'required|array',
            'questions.*.question_id' => 'required|integer|exists:quiz_questions,question_id',
            'questions.*.urutan_pertanyaan' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            foreach ($request->questions as $questionData) {
                QuizQuestion::where('question_id', $questionData['question_id'])
                    ->update(['urutan_pertanyaan' => $questionData['urutan_pertanyaan']]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Urutan pertanyaan berhasil diubah!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengubah urutan pertanyaan'
            ], 500);
        }
    }

    /**
     * Get question data for editing
     */
    public function edit(Request $request, $questionId)
    {
        try {
            $question = QuizQuestion::findOrFail($questionId);

            // Format data for response
            $formattedQuestion = [
                'question_id' => $question->question_id,
                'quiz_id' => $question->quiz_id,
                'pertanyaan' => $question->pertanyaan,
                'pilihan_jawaban' => json_decode($question->pilihan_jawaban, true),
                'jawaban_benar' => $question->jawaban_benar,
                'bobot_nilai' => $question->bobot_nilai,
                'urutan_pertanyaan' => $question->urutan_pertanyaan,
                'is_active' => $question->is_active,
            ];

            return response()->json([
                'success' => true,
                'message' => 'Data pertanyaan berhasil dimuat',
                'question' => $formattedQuestion
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Pertanyaan tidak ditemukan'
            ], 404);
        }
    }
}
