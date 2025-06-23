@extends('layouts.admin')

@section('title', 'Manage Quiz Questions - ' . $quiz->judul_quiz)

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.css" rel="stylesheet">
<style>
/* Modern Question Management Styling */
:root {
    --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --success-gradient: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
    --warning-gradient: linear-gradient(135deg, #ff9800 0%, #f57c00 100%);
    --danger-gradient: linear-gradient(135deg, #f44336 0%, #d32f2f 100%);
    --shadow-light: 0 2px 10px rgba(0,0,0,0.08);
    --shadow-medium: 0 8px 25px rgba(0,0,0,0.15);
    --border-radius: 12px;
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.page-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: var(--border-radius);
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: var(--shadow-light);
}

.question-builder {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    border: 2px dashed #e9ecef;
    border-radius: var(--border-radius);
    transition: var(--transition);
    box-shadow: var(--shadow-light);
}

.question-builder:hover {
    border-color: #667eea;
    background: linear-gradient(135deg, #ffffff 0%, #e7f1ff 100%);
    transform: translateY(-2px);
}

.question-card {
    background: white;
    border-radius: var(--border-radius);
    border: 1px solid #e9ecef;
    transition: var(--transition);
    overflow: hidden;
    box-shadow: var(--shadow-light);
    margin-bottom: 1.5rem;
}

.question-card:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-medium);
    border-color: #667eea;
}

.question-header {
    background: var(--primary-gradient);
    color: white;
    padding: 1.25rem 1.5rem;
    position: relative;
}

.question-content {
    padding: 1.5rem;
}

.answer-option {
    background: white;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    padding: 1rem;
    margin-bottom: 0.75rem;
    transition: var(--transition);
    cursor: pointer;
    position: relative;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.answer-option:hover {
    border-color: #667eea;
    box-shadow: 0 2px 8px rgba(102, 126, 234, 0.15);
    transform: translateY(-1px);
}

.answer-option.correct {
    border-color: #198754;
    background: linear-gradient(135deg, #d1e7dd 0%, #f8fff9 100%);
}

.answer-option.correct::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 4px;
    background: var(--success-gradient);
    border-radius: 0 10px 10px 0;
}

.answer-indicator {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    font-weight: 600;
    flex-shrink: 0;
}

.answer-indicator.correct {
    background: var(--success-gradient);
    color: white;
}

.answer-indicator.incorrect {
    background: #e9ecef;
    color: #6c757d;
}

.handle {
    cursor: grab;
    color: #6c757d;
    font-size: 1.2rem;
    padding: 0.5rem;
    border-radius: 6px;
    transition: var(--transition);
}

.handle:hover {
    background: #f8f9fa;
    color: #495057;
}

.handle:active {
    cursor: grabbing;
}

.sortable-placeholder {
    background: linear-gradient(135deg, #e9ecef 0%, #f8f9fa 100%);
    border: 2px dashed #adb5bd;
    height: 60px;
    opacity: 0.7;
    border-radius: var(--border-radius);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6c757d;
    font-style: italic;
}

.sortable-placeholder::before {
    content: 'Drop pertanyaan di sini';
}

.form-builder {
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-medium);
    border: 1px solid #e9ecef;
}

.form-section {
    padding: 2rem;
    border-bottom: 1px solid #f1f3f4;
}

.form-section:last-child {
    border-bottom: none;
}

.form-section h4 {
    color: #2d3748;
    margin-bottom: 1.5rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.input-group-modern {
    position: relative;
    margin-bottom: 1.5rem;
}

.input-group-modern input,
.input-group-modern textarea,
.input-group-modern select {
    width: 100%;
    padding: 1rem 1.25rem;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    font-size: 0.95rem;
    transition: var(--transition);
    background: #ffffff;
    font-family: inherit;
}

.input-group-modern input:focus,
.input-group-modern textarea:focus,
.input-group-modern select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    outline: none;
    background: #ffffff;
}

.input-group-modern label {
    position: absolute;
    left: 1.25rem;
    top: 1rem;
    background: white;
    padding: 0 0.5rem;
    color: #718096;
    font-size: 0.95rem;
    transition: var(--transition);
    pointer-events: none;
    font-weight: 500;
}

.input-group-modern input:focus + label,
.input-group-modern textarea:focus + label,
.input-group-modern select:focus + label,
.input-group-modern input:not(:placeholder-shown) + label,
.input-group-modern textarea:not(:placeholder-shown) + label,
.input-group-modern select:not([value=""]) + label {
    top: -0.5rem;
    font-size: 0.8rem;
    color: #667eea;
    font-weight: 600;
}

.btn-modern {
    padding: 0.875rem 1.75rem;
    border-radius: 10px;
    font-weight: 600;
    font-size: 0.9rem;
    transition: var(--transition);
    border: none;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    text-decoration: none;
    position: relative;
    overflow: hidden;
}

.btn-modern::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: rgba(255,255,255,0.2);
    transition: var(--transition);
}

.btn-modern:hover::before {
    left: 0;
}

.btn-modern:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-medium);
}

.btn-modern:active {
    transform: translateY(0);
}

.btn-primary-modern {
    background: var(--primary-gradient);
    color: white;
}

.btn-success-modern {
    background: var(--success-gradient);
    color: white;
}

.btn-warning-modern {
    background: var(--warning-gradient);
    color: white;
}

.btn-danger-modern {
    background: var(--danger-gradient);
    color: white;
}

.btn-secondary {
    background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
    color: white;
}

.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    border-radius: var(--border-radius);
    border: 2px dashed #e9ecef;
}

.empty-state i {
    font-size: 4rem;
    color: #cbd5e0;
    margin-bottom: 1.5rem;
}

.empty-state h4 {
    color: #4a5568;
    margin-bottom: 1rem;
}

.empty-state p {
    color: #718096;
    margin-bottom: 2rem;
}

.question-actions {
    padding: 1.25rem;
    border-top: 1px solid #f1f3f4;
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    display: flex;
    gap: 0.75rem;
    justify-content: flex-end;
}

.question-number {
    background: rgba(255,255,255,0.2);
    color: white;
    padding: 0.4rem 0.8rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    position: absolute;
    top: 1rem;
    right: 1rem;
}

.fade-in {
    animation: fadeIn 0.5s ease-in-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.answer-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.answer-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem;
    background: #f8f9fa;
    border-radius: 8px;
    margin-bottom: 0.5rem;
    transition: var(--transition);
}

.answer-item:hover {
    background: #e9ecef;
}

@media (max-width: 768px) {
    .question-actions {
        flex-direction: column;
    }
    
    .btn-modern {
        justify-content: center;
    }
    
    .form-section {
        padding: 1.5rem;
    }
}
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="page-header fade-in">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <nav aria-label="breadcrumb" class="mb-3">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.courses.index') }}" class="text-decoration-none">
                                <i class="fas fa-graduation-cap me-1"></i>Courses
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.courses.show', $quiz->course->course_id) }}" class="text-decoration-none">
                                {{ Str::limit($quiz->course->nama_course, 25) }}
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.courses.quizzes', $quiz->course->course_id) }}" class="text-decoration-none">
                                Quiz Management
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Kelola Soal</li>
                    </ol>
                </nav>
                <div class="d-flex align-items-center mb-2">
                    <div class="bg-primary text-white rounded-circle p-2 me-3">
                        <i class="fas fa-edit fa-lg"></i>
                    </div>
                    <div>
                        <h1 class="h3 mb-0 text-gray-800">Quiz Question Manager</h1>
                        <p class="text-muted mb-0">
                            Kelola pertanyaan untuk: <strong>{{ $quiz->judul_quiz }}</strong>
                        </p>
                    </div>
                </div>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <button class="btn-modern btn-secondary" onclick="previewQuiz()">
                    <i class="fas fa-eye"></i> 
                    <span>Preview Quiz</span>
                </button>
                <button class="btn-modern btn-primary-modern" onclick="showQuestionBuilder()">
                    <i class="fas fa-plus"></i> 
                    <span>Tambah Soal</span>
                </button>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Quiz Info Card -->
    <div class="card form-builder mb-4 fade-in">
        <div class="form-section">
            <h4>
                <i class="fas fa-info-circle text-primary"></i>
                Informasi Quiz
            </h4>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="fw-bold text-muted small">JUDUL QUIZ</label>
                        <p class="mb-1 h5 text-primary">{{ $quiz->judul_quiz }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold text-muted small">DESKRIPSI</label>
                        <p class="mb-1 text-muted">{{ $quiz->deskripsi_quiz ?: 'Tidak ada deskripsi' }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="fw-bold text-muted small">DURASI & TIPE</label>
                        <div class="d-flex gap-3">
                            <span class="badge bg-primary fs-6">
                                <i class="fas fa-clock me-1"></i>{{ $quiz->durasi_menit }} menit
                            </span>
                            <span class="badge bg-info fs-6">
                                <i class="fas fa-tag me-1"></i>{{ ucwords(str_replace('_', ' ', $quiz->tipe_quiz)) }}
                            </span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold text-muted small">STATUS & STATISTIK</label>
                        <div class="d-flex gap-3">
                            <span class="badge {{ $quiz->is_active ? 'bg-success' : 'bg-secondary' }} fs-6">
                                <i class="fas fa-{{ $quiz->is_active ? 'check' : 'pause' }} me-1"></i>
                                {{ $quiz->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                            <span class="badge bg-dark fs-6">
                                <i class="fas fa-question-circle me-1"></i>{{ $questions->count() }} soal
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold text-muted">Tipe Quiz:</label>
                        <span class="badge fs-6 ms-2
                            @switch($quiz->tipe_quiz)
                                @case('setelah_video') bg-info @break
                                @case('setelah_section') bg-warning @break
                                @case('akhir_course') bg-success @break
                                @default bg-secondary
                            @endswitch
                        ">
                            {{ ucwords(str_replace('_', ' ', $quiz->tipe_quiz)) }}
                        </span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="fw-bold text-muted">Durasi:</label>
                        <p class="mb-1">
                            <i class="fas fa-clock text-warning me-1"></i>
                            {{ $quiz->durasi_menit }} menit
                        </p>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold text-muted">Status:</label>
                        <span class="badge fs-6 ms-2 {{ $quiz->is_active ? 'bg-success' : 'bg-secondary' }}">
                            <i class="fas fa-{{ $quiz->is_active ? 'check' : 'times' }} me-1"></i>
                            {{ $quiz->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold text-muted">Jumlah Pertanyaan:</label>
                        <p class="mb-1">
                            <i class="fas fa-list-ol text-primary me-1"></i>
                            <span class="fs-5 fw-bold">{{ $quiz->questions->count() }}</span> pertanyaan
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Question Builder (Hidden by default) -->
    <div id="questionBuilderCard" class="card form-builder mb-4 fade-in" style="display: none;">
        <div class="form-section">
            <h4>
                <i class="fas fa-plus-circle text-success"></i>
                Tambah Pertanyaan Baru
            </h4>
            <form id="questionForm">
                @csrf
                {{-- Ini akan diisi jika sedang mengedit pertanyaan --}}
                <input type="hidden" id="questionId" name="question_id">

                <div class="mb-4">
                    <label for="questionText" class="form-label fw-bold">
                        <i class="fas fa-question me-2 text-primary"></i>Pertanyaan
                    </label>
                    <textarea class="form-control form-control-lg" id="questionText" name="pertanyaan"
                              rows="3" placeholder="Masukkan pertanyaan quiz di sini..." required></textarea>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">
                        <i class="fas fa-list me-2 text-primary"></i>Pilihan Jawaban
                    </label>
                    <div id="answerOptions">
                        </div>
                    <button type="button" class="btn btn-outline-primary btn-sm mt-2" onclick="addAnswerOption()">
                        <i class="fas fa-plus me-1"></i>Tambah Pilihan
                    </button>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <label for="questionWeight" class="form-label fw-bold">
                            <i class="fas fa-weight me-2 text-primary"></i>Bobot Nilai
                        </label>
                        <select class="form-select" id="questionWeight" name="bobot_nilai" required>
                            <option value="">Pilih bobot nilai</option>
                            <option value="1">1 - Sangat Mudah</option>
                            <option value="2">2 - Mudah</option>
                            <option value="3">3 - Sedang</option>
                            <option value="4">4 - Sulit</option>
                            <option value="5">5 - Sangat Sulit</option>
                        </select>
                    </div>
                    <div class="col-md-6 d-flex align-items-end">
                        <div class="d-flex gap-2 w-100">
                            <button type="button" class="btn btn-success flex-fill" onclick="saveQuestion()">
                                <i class="fas fa-save me-2"></i>Simpan Pertanyaan
                            </button>
                            <button type="button" class="btn btn-secondary" onclick="cancelEditQuestion()">
                                <i class="fas fa-times me-2"></i>Batal
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow border-0">
        <div class="card-header bg-gradient-info text-white d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold">
                <i class="fas fa-list me-2"></i>Daftar Pertanyaan
            </h6>
            @if($quiz->questions->count() > 0)
                <small class="opacity-75">
                    <i class="fas fa-hand-rock me-1"></i>Drag untuk mengubah urutan
                </small>
            @endif
        </div>
        <div class="card-body">
            @if($quiz->questions->count() > 0)
                {{-- Wrapper untuk SortableJS --}}
                <div id="sortable-questions">
                    @foreach($quiz->questions->sortBy('urutan_pertanyaan') as $question)
                    <div class="question-card card mb-3 border-left-primary" data-question-id="{{ $question->question_id }}">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-grip-vertical handle text-muted me-3" title="Drag untuk mengubah urutan"></i>
                                    {{-- Nomor urut pertanyaan akan di-update oleh JS setelah drag --}}
                                    <span class="badge bg-primary question-number fs-6">{{ $question->urutan_pertanyaan }}</span>
                                    <span class="badge bg-secondary ms-2">Bobot: {{ $question->bobot_nilai }}</span>
                                </div>
                                <div class="dropdown">
                                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-cog"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#" onclick="editQuestion({{ $question->question_id }})">
                                            <i class="fas fa-edit me-2"></i>Edit</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item text-danger" href="#" onclick="deleteQuestion({{ $question->question_id }})">
                                            <i class="fas fa-trash me-2"></i>Hapus</a></li>
                                    </ul>
                                </div>
                            </div>

                            <h6 class="card-title mb-3">{{ $question->pertanyaan }}</h6>

                            <div class="row">
                                @php
                                    // Pastikan pilihan_jawaban adalah array, atau decode dari JSON
                                    $options = is_array($question->pilihan_jawaban) ? $question->pilihan_jawaban : json_decode($question->pilihan_jawaban, true);
                                    $optionLabels = range('A', chr(65 + count($options) - 1)); // A, B, C, D...
                                @endphp
                                @foreach($options as $index => $option)
                                <div class="col-md-6 mb-2">
                                    <div class="answer-option p-3 border rounded {{ $optionLabels[$index] === $question->jawaban_benar ? 'correct' : '' }}">
                                        <strong>{{ $optionLabels[$index] }}.</strong> {{ $option }}
                                        @if($optionLabels[$index] === $question->jawaban_benar)
                                            <i class="fas fa-check-circle text-success float-end"></i>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-question-circle fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Belum Ada Pertanyaan</h5>
                    <p class="text-muted mb-4">Mulai tambahkan pertanyaan untuk quiz ini.</p>
                    <button class="btn btn-primary" onclick="showQuestionBuilder()">
                        <i class="fas fa-plus me-2"></i>Tambah Pertanyaan Pertama
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- SweetAlert2 dan SortableJS perlu di-load --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>

<script>
let currentEditingQuestion = null;
let answerOptionCount = 0; // Untuk melacak berapa banyak pilihan jawaban yang ada

document.addEventListener('DOMContentLoaded', function() {
    // Inisialisasi SortableJS
    const sortableQuestionsList = document.getElementById('sortable-questions');
    if (sortableQuestionsList) {
        new Sortable(sortableQuestionsList, {
            handle: '.handle', // Elemen yang bisa di-drag
            animation: 150,
            ghostClass: 'sortable-placeholder', // Kelas untuk placeholder saat drag
            onEnd: function (evt) {
                // Panggil fungsi untuk mengupdate urutan setelah drag selesai
                updateQuestionOrder();
            }
        });
    }
});

// Fungsi bantuan untuk menampilkan pesan SweetAlert
function showSuccess(message) {
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: message,
        showConfirmButton: false,
        timer: 1500
    });
}

function showError(message) {
    Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: message,
        confirmButtonColor: '#dc3545'
    });
}

function showInfo(message, title = 'Info') {
    return Swal.fire({
        icon: 'info',
        title: title,
        text: message,
        confirmButtonColor: '#007bff'
    });
}

function showLoading(message) {
    Swal.fire({
        title: message,
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
}

function showDeleteConfirm(text, title = 'Konfirmasi Hapus') {
    return Swal.fire({
        title: title,
        text: text,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    });
}

// Menampilkan form pembuat pertanyaan
function showQuestionBuilder() {
    const builderCard = document.getElementById('questionBuilderCard');
    builderCard.style.display = 'block';
    builderCard.scrollIntoView({ behavior: 'smooth' }); // Scroll ke form

    // Reset form dan tambahkan 2 pilihan awal
    resetQuestionForm();
    addAnswerOption(); // Tambah pilihan A
    addAnswerOption(); // Tambah pilihan B
}

// Menyembunyikan form pembuat pertanyaan dan meresetnya
function cancelEditQuestion() {
    const builderCard = document.getElementById('questionBuilderCard');
    builderCard.style.display = 'none';
    resetQuestionForm();
    currentEditingQuestion = null; // Reset status edit
}

// Mereset semua field di form pertanyaan
function resetQuestionForm() {
    document.getElementById('questionForm').reset();
    document.getElementById('questionId').value = ''; // Kosongkan ID pertanyaan
    document.getElementById('answerOptions').innerHTML = ''; // Hapus semua pilihan jawaban
    answerOptionCount = 0; // Reset hitungan pilihan
}

// Menambahkan pilihan jawaban baru ke form
function addAnswerOption(text = '', isCorrect = false) {
    answerOptionCount++;
    // Menggunakan kode ASCII untuk label A, B, C, dst.
    const optionLabel = String.fromCharCode(64 + answerOptionCount);

    const optionHtml = `
        <div class="answer-option-item mb-3 p-3 border rounded ${isCorrect ? 'border-success bg-success-subtle' : 'border-secondary-subtle'}" data-option-id="${answerOptionCount}">
            <div class="d-flex align-items-center">
                <div class="form-check me-3">
                    <input class="form-check-input" type="radio" name="correct_answer"
                           value="${optionLabel}" id="correct_${answerOptionCount}" ${isCorrect ? 'checked' : ''}>
                    <label class="form-check-label fw-bold" for="correct_${answerOptionCount}">
                        ${optionLabel}.
                    </label>
                </div>
                <input type="text" class="form-control me-2" name="option_text[]"
                       placeholder="Masukkan pilihan ${optionLabel}" value="${text}" required>
                <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeAnswerOption(${answerOptionCount})"
                        ${answerOptionCount <= 2 ? 'disabled' : ''}>
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    `;

    document.getElementById('answerOptions').insertAdjacentHTML('beforeend', optionHtml);
    updateRemoveButtons(); // Perbarui status tombol hapus
}

// Menghapus pilihan jawaban dari form
function removeAnswerOption(optionId) {
    const optionElement = document.querySelector(`[data-option-id="${optionId}"]`);
    if (optionElement) {
        optionElement.remove();
        updateOptionLabels(); // Perbarui label A, B, C dan ID
        updateRemoveButtons(); // Perbarui status tombol hapus
    }
}

// Memperbarui label A, B, C, dst. setelah penambahan/penghapusan pilihan
function updateOptionLabels() {
    const options = document.querySelectorAll('.answer-option-item');
    answerOptionCount = 0; // Reset hitungan

    options.forEach((option, index) => {
        answerOptionCount++;
        const optionLabel = String.fromCharCode(65 + index); // A, B, C, D, E

        // Update data-option-id (penting untuk fungsi removeAnswerOption)
        option.setAttribute('data-option-id', answerOptionCount);

        // Update label radio button dan input placeholder
        option.querySelector('.form-check-label').textContent = `${optionLabel}.`;
        option.querySelector('.form-check-input').value = optionLabel;
        option.querySelector('.form-check-input').id = `correct_${answerOptionCount}`;
        option.querySelector('.form-check-label').setAttribute('for', `correct_${answerOptionCount}`);
        option.querySelector('input[name="option_text[]"]').placeholder = `Masukkan pilihan ${optionLabel}`;

        // Update handler tombol hapus
        const removeBtn = option.querySelector('button[onclick*="removeAnswerOption"]');
        if (removeBtn) {
            removeBtn.setAttribute('onclick', `removeAnswerOption(${answerOptionCount})`);
        }
    });
}

// Mengatur status disable tombol hapus (minimal 2 pilihan)
function updateRemoveButtons() {
    const options = document.querySelectorAll('.answer-option-item');
    options.forEach(option => {
        const removeBtn = option.querySelector('button[onclick*="removeAnswerOption"]');
        if (removeBtn) {
            removeBtn.disabled = options.length <= 2;
        }
    });
}

// Menyimpan atau mengupdate pertanyaan
function saveQuestion() {
    const form = document.getElementById('questionForm');

    // Validasi form
    if (!validateQuestionForm()) {
        return;
    }

    // Mengumpulkan pilihan jawaban
    const optionTexts = [];
    document.querySelectorAll('input[name="option_text[]"]').forEach(input => {
        // Hanya tambahkan jika tidak kosong
        if (input.value.trim()) {
            optionTexts.push(input.value.trim());
        }
    });

    const correctAnswerRadio = document.querySelector('input[name="correct_answer"]:checked');
    if (!correctAnswerRadio) {
        showError('Pilih jawaban yang benar!');
        return;
    }
    const correctAnswerLabel = correctAnswerRadio.value; // Misal: 'A', 'B'

    // Siapkan data untuk dikirim ke backend
    const questionData = {
        pertanyaan: document.getElementById('questionText').value,
        pilihan_jawaban: optionTexts, // Kirim sebagai array
        jawaban_benar: correctAnswerLabel, // Kirim label (A, B, C)
        bobot_nilai: parseInt(document.getElementById('questionWeight').value),
        _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content') // Ambil CSRF token
    };

    const questionId = document.getElementById('questionId').value;
    const quizId = "{{ $quiz->quiz_id }}"; // Ambil quiz_id dari Blade

    // Tentukan URL dan metode HTTP (POST untuk baru, PUT untuk edit)
    const url = questionId ?
        `/admin/courses/quiz/questions/${questionId}` : // Untuk update
        `/admin/courses/quiz/${quizId}/questions`;      // Untuk store baru
    const method = questionId ? 'PUT' : 'POST';

    showLoading(questionId ? 'Mengupdate pertanyaan...' : 'Menyimpan pertanyaan...');

    fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': questionData._token // Penting untuk Laravel
        },
        body: JSON.stringify(questionData) // Kirim data sebagai JSON string
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccess(data.message || 'Pertanyaan berhasil disimpan!');
            // Reload halaman untuk melihat perubahan
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            showError(data.message || 'Gagal menyimpan pertanyaan');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showError('Terjadi kesalahan saat menyimpan pertanyaan');
    });
}

// Validasi input form pertanyaan
function validateQuestionForm() {
    const questionText = document.getElementById('questionText').value.trim();
    const weight = document.getElementById('questionWeight').value;
    const options = document.querySelectorAll('input[name="option_text[]"]');
    const correctAnswer = document.querySelector('input[name="correct_answer"]:checked');

    if (!questionText) {
        showError('Pertanyaan harus diisi!');
        return false;
    }

    if (!weight) {
        showError('Bobot nilai harus dipilih!');
        return false;
    }

    let validOptions = 0;
    options.forEach(option => {
        if (option.value.trim()) validOptions++;
    });

    if (validOptions < 2) {
        showError('Minimal harus ada 2 pilihan jawaban!');
        return false;
    }

    if (!correctAnswer) {
        showError('Pilih jawaban yang benar!');
        return false;
    }

    return true;
}

// Mengisi form dengan data pertanyaan yang akan diedit
function editQuestion(questionId) {
    // Cari elemen kartu pertanyaan di DOM berdasarkan ID
    const questionCard = document.querySelector(`[data-question-id="${questionId}"]`);
    if (!questionCard) {
        showError('Pertanyaan tidak ditemukan untuk diedit.');
        return;
    }

    // Ambil data dari kartu pertanyaan
    const questionText = questionCard.querySelector('.card-title').textContent.trim();
    const weightBadge = questionCard.querySelector('.badge.bg-secondary');
    // Ekstrak bobot nilai (contoh: "Bobot: 3" menjadi "3")
    const weight = weightBadge ? weightBadge.textContent.replace('Bobot: ', '').trim() : '';

    const optionsElements = questionCard.querySelectorAll('.answer-option');
    const optionsData = [];
    let correctOptionLabel = '';

    optionsElements.forEach(optionEl => {
        // Ambil label (A, B, C) dan teks pilihan
        const label = optionEl.querySelector('strong').textContent.replace('.', '').trim();
        // Ambil text content tanpa icon dan strong element
        const fullText = optionEl.textContent.trim();
        const text = fullText.replace(label + '.', '').trim();
        
        optionsData.push({ label: label, text: text });
        if (optionEl.classList.contains('correct')) {
            correctOptionLabel = label;
        }
    });

    // Atur ID pertanyaan yang sedang diedit
    currentEditingQuestion = questionId;

    // Tampilkan form builder
    showQuestionBuilder();

    // Isi form dengan data yang diambil
    document.getElementById('questionId').value = questionId;
    document.getElementById('questionText').value = questionText;
    document.getElementById('questionWeight').value = weight;

    // Hapus pilihan yang ada dan tambahkan yang baru dari data pertanyaan
    document.getElementById('answerOptions').innerHTML = '';
    answerOptionCount = 0; // Reset hitungan

    optionsData.forEach(option => {
        addAnswerOption(option.text, option.label === correctOptionLabel);
    });

    // Pastikan tombol hapus diperbarui
    updateRemoveButtons();

    // Scroll ke form builder
    document.getElementById('questionBuilderCard').scrollIntoView({ behavior: 'smooth' });
}


// Menghapus pertanyaan
function deleteQuestion(questionId) {
    showDeleteConfirm('Pertanyaan akan dihapus secara permanen. Tindakan ini tidak dapat dibatalkan.', 'Hapus Pertanyaan?')
    .then((result) => {
        if (result.isConfirmed) {
            showLoading('Menghapus pertanyaan...');

            fetch(`/admin/courses/quiz/questions/${questionId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccess('Pertanyaan berhasil dihapus!');
                    setTimeout(() => {
                        window.location.reload(); // Reload halaman setelah berhasil
                    }, 1500);
                } else {
                    showError(data.message || 'Gagal menghapus pertanyaan');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showError('Terjadi kesalahan saat menghapus pertanyaan');
            });
        }
    });
}

// Memperbarui urutan pertanyaan setelah drag-and-drop
function updateQuestionOrder() {
    const questionCards = document.querySelectorAll('#sortable-questions .question-card');
    const questionOrder = [];

    questionCards.forEach((card, index) => {
        const questionId = card.getAttribute('data-question-id');
        questionOrder.push({
            question_id: questionId,
            urutan_pertanyaan: index + 1 // Urutan dimulai dari 1
        });

        // Update nomor urut yang ditampilkan di kartu
        card.querySelector('.question-number').textContent = index + 1;
    });

    // Kirim permintaan AJAX untuk memperbarui urutan di server
    fetch(`/admin/courses/quiz/{{ $quiz->quiz_id }}/questions/reorder`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ questions: questionOrder })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Tampilkan notifikasi kecil di pojok kanan atas
            const toast = document.createElement('div');
            toast.className = 'toast-notification';
            toast.innerHTML = '<i class="fas fa-check me-2"></i>Urutan pertanyaan diperbarui';
            document.body.appendChild(toast);

            setTimeout(() => {
                toast.remove();
            }, 3000);
        } else {
            showError(data.message || 'Gagal mengupdate urutan pertanyaan');
        }
    })
    .catch(error => {
        console.error('Error updating order:', error);
        showError('Terjadi kesalahan saat mengupdate urutan pertanyaan');
    });
}

// Fungsi untuk melihat pratinjau kuis (opsional, sesuaikan rute Anda)
function previewQuiz() {
    // Ini hanyalah contoh, sesuaikan dengan rute pratinjau kuis Anda di frontend
    showInfo('Preview quiz akan menampilkan quiz seperti yang dilihat siswa (membutuhkan implementasi di sisi siswa).', 'Preview Quiz').then(() => {
        // Contoh: window.open(`/quiz/${quizId}/preview`, '_blank');
        // window.open(`/courses/{{ $quiz->course->course_id }}/quiz/{{ $quiz->quiz_id }}`, '_blank');
        Swal.fire({
            icon: 'info',
            title: 'Fitur Belum Tersedia',
            text: 'Fungsionalitas pratinjau kuis di sisi siswa perlu diimplementasikan terlebih dahulu di frontend Anda.'
        });
    });
}
</script>
@endpush
