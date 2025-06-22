<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Vidio;
use App\Models\DetailQuiz;
use App\Models\ResultQuiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{

    // ADMIN: List semua soal quiz untuk video tertentu
    public function index($vidio_id)
    {
        $quizzes = Quiz::where('vidio_id', $vidio_id)->get();
        return view('quiz.qadmin', compact('quizzes', 'vidio_id'));
    }

    public function showQuiz($vidio_id)
    {
        $quizzes = Quiz::where('vidio_id', $vidio_id)->get();
        return view('quiz.quser', compact('quizzes', 'vidio_id'));
    }

    // ADMIN: Simpan soal baru
    public function store(Request $request)
    {
        $request->validate([
            'vidio_id' => 'required|exists:vidio,vidio_id', // Pastikan vidio_id valid
            'soal' => 'required',
            'pilihan' => 'required|array|min:4',
            'jawaban_benar' => 'required',
        ]);

        $data = $request->only(['vidio_id', 'soal', 'jawaban_benar']);
        $data['pilihan'] = json_encode($request->pilihan);

        Quiz::create($data);

        // return response()->json(['success' => true]);
        return redirect()->back()->with('success', 'Soal berhasil ditambahkan');
    }

    // ADMIN: Hapus soal
    public function destroy($id)
    {
        $quiz = Quiz::findOrFail($id);
        $vidio_id = $quiz->vidio_id;
        $quiz->delete();
        // return response()->json(['success' => true]);
        return redirect()->route('admin.quiz.index', $vidio_id)->with('success', 'Soal berhasil dihapus');
    }

    // USER: Tampilkan quiz untuk dikerjakan
    public function showQuizForUser($vidio_id)
    {
        $quizzes = Quiz::where('vidio_id', $vidio_id)->get();
        return view('quiz.quser', compact('quizzes', 'vidio_id'));
    }

    // USER: Submit jawaban quiz
public function submitQuiz(Request $request, $vidio_id)
{
    $user = Auth::user();
    if (!$user) {
        return response()->json(['success' => false, 'message' => 'Anda harus login untuk mengerjakan quiz.']);
    }

    $quizzes = Quiz::where('vidio_id', $vidio_id)->get();
    $jumlahBenar = 0;
    $totalSoal = $quizzes->count();

    foreach ($quizzes as $quiz) {
        $jawabanUser = $request->input('jawaban')[$quiz->id] ?? '';
        if ($jawabanUser == $quiz->jawaban_benar) {
            $jumlahBenar++;
        }

        DetailQuiz::updateOrCreate(
            [
                'quiz_id' => $quiz->id,
                'users_id' => $user->id,
            ],
            [
                'jawaban_user' => $jawabanUser,
                'nilai' => $jawabanUser == $quiz->jawaban_benar ? 1 : 0,
            ]
        );
    }

    $nilaiTotal = ($jumlahBenar / $totalSoal) * 100;

    ResultQuiz::updateOrCreate(
        [
            'vidio_id' => $vidio_id,
            'users_id' => $user->id,
        ],
        [
            'nilai_total' => $nilaiTotal,
        ]
    );

    return response()->json(['success' => true, 'nilai_total' => $nilaiTotal]);
}

}
