@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    <h2 class="text-3xl font-bold text-center text-blue-600 mb-6">Daftar Soal Quiz</h2>

    <div class="flex justify-end mb-4">
        <button onclick="openAddQuizModal()" class=" text-gray-800   px-4 py-2 rounded-lg hover:bg-blue-700 border border-blue-600  hover:text-white transition duration-300">
            Tambah Soal
        </button>
    </div>

    <table class="table-auto w-full bg-white shadow-md rounded-lg overflow-hidden">
        <thead class="bg-gradient-to-r from-purple-600 to-blue-600 text-white">
            <tr>
                <th class="px-4 py-2 text-left">No</th>
                <th class="px-4 py-2 text-left">Soal</th>
                <th class="px-4 py-2 text-left">Pilihan</th>
                <th class="px-4 py-2 text-left">Jawaban Benar</th>
                <th class="px-4 py-2 text-left">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($quizzes as $quiz)
                <tr class="border-b hover:bg-gray-100">
                    <td class="px-4 py-2">{{ $loop->iteration }}</td>
                    <td class="px-4 py-2">{{ $quiz->soal }}</td>
                    <td class="px-4 py-2">
                        @php
                            $pilihan = is_array($quiz->pilihan) ? $quiz->pilihan : json_decode($quiz->pilihan, true);
                        @endphp
                        {{ implode(', ', $pilihan) }}
                    </td>
                    <td class="px-4 py-2">{{ $quiz->jawaban_benar }}</td>
                    <td class="px-4 py-2">
                        {{-- <button onclick="editQuiz({{ $quiz->id }})" class="bg-yellow-500 text-white px-2 py-1 rounded hover:bg-yellow-600">
                            Edit
                        </button> --}}
                        <form action="{{ route('quiz.destroy', $quiz->id) }}" method="POST" onsubmit="return confirm('Hapus soal ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal Tambah Soal Quiz -->
<div id="addQuizModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white p-8 rounded-lg max-w-md w-full mx-4">
        <h3 class="text-lg font-semibold mb-4 text-blue-600">Tambah Soal Quiz</h3>
        <form id="addQuizForm" method="POST" action="{{ route('admin.quiz.index') }} class="space-y-4">
            @csrf
            <input type="hidden" name="vidio_id" value="{{ $vidio_id }}">
            <div>
                <label class="block text-sm font-medium text-gray-700">Soal</label>
                <textarea name="soal" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md"></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Pilihan Jawaban</label>
                <div class="grid grid-cols-1 gap-2">
                    <input type="text" name="pilihan[]" required class="block w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="Pilihan A">
                    <input type="text" name="pilihan[]" required class="block w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="Pilihan B">
                    <input type="text" name="pilihan[]" required class="block w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="Pilihan C">
                    <input type="text" name="pilihan[]" required class="block w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="Pilihan D">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Jawaban Benar</label>
                <input type="text" name="jawaban_benar" required class="block w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="Contoh: A">
            </div>
            <div class="flex justify-end space-x-2">
                <button type="button" onclick="closeAddQuizModal()" class="px-4 py-2 text-gray-600 hover:text-gray-800">Batal</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openAddQuizModal() {
    document.getElementById('addQuizModal').classList.remove('hidden');
    document.getElementById('addQuizModal').classList.add('flex');
}
function closeAddQuizModal() {
    document.getElementById('addQuizModal').classList.add('hidden');
    document.getElementById('addQuizModal').classList.remove('flex');
}
</script>
