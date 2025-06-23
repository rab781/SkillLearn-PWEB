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

        return view('admin.quizzes.questions.index', compact('quiz'));
    }

    /**
     * Store a new question for a quiz
     */
    public function store(Request $request, $quizId)
    {
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
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        // Get the next order number
        $latestOrder = $quiz->questions()->max('urutan_pertanyaan') ?? 0;

        // Create the question
        $question = QuizQuestion::create([
            'quiz_id' => $quiz->quiz_id,
            'urutan_pertanyaan' => $latestOrder + 1,
            'pertanyaan' => $request->pertanyaan,
            'pilihan_jawaban' => $request->pilihan_jawaban,
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
    }

    /**
     * Update a quiz question
     */
    public function update(Request $request, $questionId)
    {
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
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        $question->update([
            'pertanyaan' => $request->pertanyaan,
            'pilihan_jawaban' => $request->pilihan_jawaban,
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
    }

    /**
     * Delete a quiz question
     */
    public function destroy(Request $request, $questionId)
    {
        $question = QuizQuestion::findOrFail($questionId);

        $question->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Pertanyaan quiz berhasil dihapus!'
            ]);
        }

        return redirect()->route('admin.courses.quizzes.questions.index', $question->quiz_id)
            ->with('success', 'Pertanyaan quiz berhasil dihapus!');
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
}
