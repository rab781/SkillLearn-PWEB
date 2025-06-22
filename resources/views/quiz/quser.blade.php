{{-- filepath: resources/views/quiz/quser.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold text-center mb-6">Quiz Video</h2>

    @if($quizzes->isEmpty())
        <div class="text-center py-12">
            <div class="text-6xl mb-4">📄</div>
            <p class="text-gray-500 text-lg mb-2">Belum ada soal untuk quiz ini</p>
            <p class="text-gray-400 text-sm">Tunggu admin menambahkan soal, ya!</p>
        </div>
    @else
        <form id="quizForm" class="bg-white shadow-md rounded-lg p-6">
            @csrf
            @foreach($quizzes as $quiz)
                <div class="mb-6">
                    <p class="text-lg font-semibold mb-2">{{ $loop->iteration }}. {{ $quiz->soal }}</p>
                    @php
                        $pilihan = is_array($quiz->pilihan) ? $quiz->pilihan : json_decode($quiz->pilihan, true);
                    @endphp
                    @foreach($pilihan as $idx => $pil)
                        <label class="block">
                            <input type="radio" name="jawaban[{{ $quiz->id }}]" value="{{ $pil }}" required class="mr-2">
                            {{ chr(65 + $idx) }}. {{ $pil }}
                        </label>
                    @endforeach
                </div>
            @endforeach
            <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white py-2 px-4 rounded-lg hover:from-blue-700 hover:to-purple-600">Submit</button>
        </form>
    @endif
</div>

<!-- Pop-up untuk hasil quiz -->
<!-- Pop-up untuk hasil quiz -->
<div id="resultPopup" class="fixed inset-0 bg-gray-800 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white p-8 rounded-lg max-w-md w-full mx-4 text-center">
        <h3 id="resultMessage" class="text-xl font-bold mb-4"></h3>
        <p id="resultScore" class="text-lg mb-6"></p>
        <button onclick="closeResultPopup()" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Selesai</button>
    </div>
</div>
{{-- <div id="resultPopup" class="fixed inset-0 bg-gray-800 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white p-8 rounded-lg max-w-md w-full mx-4 text-center">
        <h3 id="resultMessage" class="text-xl font-bold mb-4"></h3>
        <p id="resultScore" class="text-lg mb-6"></p>
        <button onclick="closeResultPopup()" class="px-4 py-2 mt-8 bg-gradient-to-r from-blue-500 to-purple-500 text-white rounded hover:bg-blue-700">Selesai</button>
    </div>
</div> --}}
@endsection

@push('scripts')
<script>
    document.getElementById('quizForm').addEventListener('submit', async function(e) {
    e.preventDefault(); // Mencegah form submit secara default (reload halaman)
    const formData = new FormData(this);

    try {
        const response = await fetch("{{ route('quiz.submit', $vidio_id) }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: formData,
        });
        const result = await response.json();
        console.log(result); // Debug response JSON
        if (result.success) {
            showResultPopup(result.nilai_total); // Tampilkan pop-up
        } else {
            alert('Terjadi kesalahan saat menyimpan hasil quiz.');
        }
    } catch (error) {
        alert('Terjadi kesalahan sistem.');
    }
});
// document.getElementById('quizForm').addEventListener('submit', async function(e) {
//     e.preventDefault();
//     const formData = new FormData(this);
//     try {
//         const response = await fetch("{{ route('quiz.submit', $vidio_id) }}", {
//             method: 'POST',
//             headers: {
//                 'X-CSRF-TOKEN': '{{ csrf_token() }}',
//                 console.log('{{ csrf_token() }}'); // Debug CSRF token
//             },
//             body: formData,
//         });
//         const result = await response.json();
//         console.log(result);
//         if (result.success) {
//             showResultPopup(result.nilai_total);
//         } else {
//             alert('Terjadi kesalahan dengan hasil quiz.');
//         }
//     } catch (error) {
//         alert('Terjadi kesalahan sistem.');
//     }
// });

function showResultPopup(nilaiTotal) {
    const resultPopup = document.getElementById('resultPopup');
    const resultMessage = document.getElementById('resultMessage');
    const resultScore = document.getElementById('resultScore');

    // Tentukan pesan berdasarkan nilai total
    if (nilaiTotal >= 20 && nilaiTotal <= 50) {
        resultMessage.textContent = 'Belajar lebih semangat lagi!';
    } else if (nilaiTotal >= 60 && nilaiTotal <= 90) {
        resultMessage.textContent = 'Usahamu sudah bagus!';
    } else if (nilaiTotal === 100) {
        resultMessage.textContent = 'Luar biasa usahamu!';
    }

    resultScore.textContent = `Nilai Anda: ${nilaiTotal}`;
    resultPopup.classList.remove('hidden');
    resultPopup.classList.add('flex');
}

function closeResultPopup() {
    const resultPopup = document.getElementById('resultPopup');
    resultPopup.classList.add('hidden');
    resultPopup.classList.remove('flex');
}
</script>

