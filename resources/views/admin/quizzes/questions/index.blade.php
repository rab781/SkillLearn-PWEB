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
<meta name="csrf-token" content="{{ csrf_token() }}">
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

    <!-- Question Builder (Hidden by default) -->
    <div id="questionBuilderCard" class="card form-builder mb-4 fade-in" style="display: none;">
        <div class="form-section">
            <h4>
                <i class="fas fa-plus-circle text-success"></i>
                Tambah Pertanyaan Baru
            </h4>
            <div id="error-messages" class="alert alert-danger" style="display: none;"></div>
            <form id="questionForm" autocomplete="off">
                @csrf
                <input type="hidden" id="questionId" name="question_id">

                <div class="input-group-modern">
                    <textarea id="questionText" name="pertanyaan" rows="3" placeholder=" " required></textarea>
                    <label for="questionText">Pertanyaan</label>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold mb-3">
                        <i class="fas fa-list me-2 text-primary"></i>Pilihan Jawaban
                    </label>
                    <div id="answerOptions" class="mb-3">
                        <!-- Answer options will be dynamically added here -->
                    </div>
                    <button type="button" class="btn-modern btn-primary-modern btn-sm" onclick="addAnswerOption()">
                        <i class="fas fa-plus"></i>
                        <span>Tambah Pilihan</span>
                    </button>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="input-group-modern">
                            <select id="questionWeight" name="bobot_nilai" required>
                                <option value="">Pilih bobot nilai</option>
                                <option value="1">1 - Sangat Mudah</option>
                                <option value="2">2 - Mudah</option>
                                <option value="3">3 - Sedang</option>
                                <option value="4">4 - Sulit</option>
                                <option value="5">5 - Sangat Sulit</option>
                            </select>
                            <label for="questionWeight">Bobot Nilai</label>
                        </div>
                    </div>
                    <div class="col-md-6 d-flex align-items-end">
                        <div class="d-flex gap-3 w-100">
                            <button type="button" class="btn-modern btn-success-modern flex-fill" onclick="saveQuestion()">
                                <i class="fas fa-save"></i>
                                <span>Simpan Pertanyaan</span>
                            </button>
                            <button type="button" class="btn-modern btn-secondary" onclick="cancelEditQuestion()">
                                <i class="fas fa-times"></i>
                                <span>Batal</span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Question List -->
    <div class="card form-builder fade-in">
        <div class="form-section">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4>
                    <i class="fas fa-list text-info"></i>
                    Daftar Pertanyaan ({{ $questions->count() }})
                </h4>
                @if($questions->count() > 0)
                    <small class="text-muted">
                        <i class="fas fa-arrows-alt me-1"></i>Drag untuk mengubah urutan
                    </small>
                @endif
            </div>

            @if($questions->count() > 0)
                <div id="sortable-questions">
                    @foreach($questions->sortBy('urutan_pertanyaan') as $question)
                    <div class="question-card fade-in" data-question-id="{{ $question->question_id }}">
                        <div class="question-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-grip-vertical handle me-3" title="Drag untuk mengubah urutan"></i>
                                    <div>
                                        <h6 class="mb-1 fw-bold">Pertanyaan {{ $question->urutan_pertanyaan }}</h6>
                                        <div class="d-flex gap-2">
                                            <span class="badge bg-light text-dark">
                                                Bobot: {{ $question->bobot_nilai }}
                                            </span>
                                            <span class="badge bg-light text-dark">
                                                {{ count(json_decode($question->pilihan_jawaban, true) ?? []) }} pilihan
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <span class="question-number">{{ $question->urutan_pertanyaan }}</span>
                            </div>
                        </div>

                        <div class="question-content">
                            <h6 class="mb-3 text-dark">{{ $question->pertanyaan }}</h6>

                            <div class="answer-list">
                                @php
                                    $options = json_decode($question->pilihan_jawaban, true) ?? [];
                                    $optionLabels = range('A', chr(65 + count($options) - 1));
                                @endphp
                                @foreach($options as $index => $option)
                                <div class="answer-option {{ $optionLabels[$index] === $question->jawaban_benar ? 'correct' : '' }}">
                                    <div class="answer-indicator {{ $optionLabels[$index] === $question->jawaban_benar ? 'correct' : 'incorrect' }}">
                                        {{ $optionLabels[$index] }}
                                    </div>
                                    <div class="flex-1">
                                        {{ $option }}
                                        @if($optionLabels[$index] === $question->jawaban_benar)
                                            <i class="fas fa-check-circle text-success float-end"></i>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="question-actions">
                            <button class="btn-modern btn-warning-modern btn-sm" onclick="editQuestion({{ $question->question_id }})">
                                <i class="fas fa-edit"></i>
                                <span>Edit</span>
                            </button>
                            <button class="btn-modern btn-danger-modern btn-sm" onclick="deleteQuestion({{ $question->question_id }})">
                                <i class="fas fa-trash"></i>
                                <span>Hapus</span>
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-question-circle"></i>
                    <h4>Belum Ada Pertanyaan</h4>
                    <p>Mulai tambahkan pertanyaan untuk quiz ini dan buat pembelajaran lebih interaktif!</p>
                    <button class="btn-modern btn-primary-modern" onclick="showQuestionBuilder()">
                        <i class="fas fa-plus"></i>
                        <span>Tambah Pertanyaan Pertama</span>
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
let currentEditingQuestion = null;
let answerOptionCount = 0;
const quiz_id = {{ $quiz->quiz_id }};

document.addEventListener('DOMContentLoaded', function() {
    // Setup CSRF token for all AJAX requests
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (csrfToken) {
        console.log('CSRF token set globally');
    } else {
        console.warn('CSRF token not found!');
    }

    // Initialize SortableJS
    const sortableQuestionsList = document.getElementById('sortable-questions');
    if (sortableQuestionsList) {
        new Sortable(sortableQuestionsList, {
            handle: '.handle',
            animation: 200,
            ghostClass: 'sortable-placeholder',
            onEnd: function (evt) {
                updateQuestionOrder();
            }
        });
    }
});

function showQuestionBuilder() {
    document.getElementById('questionBuilderCard').style.display = 'block';
    document.getElementById('questionBuilderCard').scrollIntoView({ behavior: 'smooth' });

    // Reset form
    resetQuestionForm();

    // Hide error messages
    hideErrorMessages();

    // Add initial answer options
    answerOptionCount = 0;
    addAnswerOption();
    addAnswerOption();

    // Focus on the question text field
    setTimeout(() => {
        document.getElementById('questionText').focus();
    }, 300);
}

function resetQuestionForm() {
    document.getElementById('questionForm').reset();
    document.getElementById('questionId').value = '';
    document.getElementById('answerOptions').innerHTML = '';
    currentEditingQuestion = null;
    answerOptionCount = 0;
}

function addAnswerOption() {
    const container = document.getElementById('answerOptions');
    const optionIndex = answerOptionCount;
    const optionLabel = String.fromCharCode(65 + optionIndex); // A, B, C, D...

    const optionHtml = `
        <div class="answer-option-builder mb-3" data-option="${optionLabel}">
            <div class="input-group-modern">
                <input type="text" name="pilihan_jawaban[]" placeholder=" " required>
                <label>Pilihan ${optionLabel}</label>
            </div>
            <div class="form-check mt-2">
                <input class="form-check-input" type="radio" name="jawaban_benar" value="${optionLabel}" id="correct_${optionLabel}">
                <label class="form-check-label" for="correct_${optionLabel}">
                    Jawaban yang benar
                </label>
            </div>
            ${optionIndex > 1 ? `
                <button type="button" class="btn-modern btn-danger-modern btn-sm mt-2" onclick="removeAnswerOption('${optionLabel}')">
                    <i class="fas fa-trash"></i>
                    <span>Hapus</span>
                </button>
            ` : ''}
        </div>
    `;

    container.insertAdjacentHTML('beforeend', optionHtml);
    answerOptionCount++;
}

function removeAnswerOption(optionLabel) {
    const optionElement = document.querySelector(`[data-option="${optionLabel}"]`);
    if (optionElement) {
        optionElement.remove();
    }
}

function saveQuestion() {
    // Clear any previous error messages
    document.getElementById('error-messages').style.display = 'none';
    document.getElementById('error-messages').innerHTML = '';

    const form = document.getElementById('questionForm');
    const formData = new FormData(form);

    // Check CSRF token first
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (!csrfToken) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'CSRF token not found. Please refresh the page and try again.',
            confirmButtonColor: '#8B5CF6',
        });
        return;
    }

    // Validate form
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    // Check if at least one correct answer is selected
    const correctAnswer = formData.get('jawaban_benar');
    if (!correctAnswer) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Pilih jawaban yang benar!',
            confirmButtonColor: '#8B5CF6',
        });
        return;
    }

    // Check for empty answer options
    const answerOptions = formData.getAll('pilihan_jawaban[]');
    const emptyOptions = answerOptions.filter(option => !option.trim());
    if (emptyOptions.length > 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Pilihan Jawaban Kosong',
            text: 'Semua pilihan jawaban harus diisi.',
            confirmButtonColor: '#8B5CF6',
        });
        return;
    }

    const questionId = document.getElementById('questionId').value;
    const url = questionId ?
        `/admin/courses/quiz-questions/${questionId}` :
        `/admin/courses/quizzes/${quiz_id}/questions`;

    console.log('Saving question to URL:', url);
    console.log('Quiz ID:', quiz_id);
    console.log('Question ID:', questionId || 'New Question');

    const method = questionId ? 'PUT' : 'POST';

    // Convert FormData to JSON object properly
    const data = {
        pertanyaan: formData.get('pertanyaan'),
        bobot_nilai: formData.get('bobot_nilai'),
        jawaban_benar: formData.get('jawaban_benar'),
        pilihan_jawaban: answerOptions,
    };

    if (questionId) {
        data.question_id = questionId;
    }

    // Show loading
    Swal.fire({
        title: 'Menyimpan...',
        text: 'Mohon tunggu sebentar',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    console.log('Sending data:', data);
    console.log('CSRF token:', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 'Not found');

    // Ensure CSRF token is included
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (!csrfToken) {
        console.error('CSRF token not found!');
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'CSRF token not found. Please refresh the page and try again.',
            confirmButtonColor: '#8B5CF6',
        });
        return;
    }

    fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => {
        console.log('Response status:', response.status);
        console.log('Response headers:', [...response.headers.entries()]);

        if (!response.ok) {
            return response.json().then(errorData => {
                console.error('Server error response:', errorData);
                throw new Error(errorData.message || 'Terjadi kesalahan pada server');
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: data.message,
                confirmButtonColor: '#8B5CF6',
            }).then(() => {
                location.reload();
            });
        } else {
            throw new Error(data.message || 'Terjadi kesalahan');
        }
    })
    .catch(error => {
        console.error('Error saving question:', error);

        // Close any loading dialog
        Swal.close();

        if (error.errors) {
            // Handle validation errors
            showErrorMessages(error.errors);

            // Scroll to error messages
            document.getElementById('error-messages').scrollIntoView({ behavior: 'smooth' });
        } else {
            // Get more details about the error
            let errorMessage = 'Terjadi kesalahan saat menyimpan pertanyaan.';

            if (error.message) {
                errorMessage += '<br>Detail: ' + error.message;
            }

            // Show detailed error
            document.getElementById('error-messages').innerHTML = errorMessage;
            document.getElementById('error-messages').style.display = 'block';
            document.getElementById('error-messages').scrollIntoView({ behavior: 'smooth' });

            // Show general error
            Swal.fire({
                icon: 'error',
                title: 'Gagal Menyimpan Pertanyaan',
                html: errorMessage + '<br><br>Silahkan periksa form anda dan coba lagi.',
                confirmButtonColor: '#8B5CF6',
            });
        }
    });
}

function editQuestion(questionId) {
    // Show loading
    Swal.fire({
        title: 'Memuat...',
        text: 'Mengambil data pertanyaan',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    // Fetch question data
    fetch(`/admin/courses/quiz-questions/${questionId}/edit`, {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        console.log('Edit response status:', response.status);
        if (!response.ok) {
            throw new Error('Tidak dapat mengambil data pertanyaan');
        }
        return response.json();
    })
    .then(data => {
        if (data.success && data.question) {
            // Close the loading dialog
            Swal.close();

            // Set question ID for edit mode
            document.getElementById('questionId').value = questionId;

            // Set question text
            document.getElementById('questionText').value = data.question.pertanyaan;

            // Set question weight
            document.getElementById('questionWeight').value = data.question.bobot_nilai;

            // Clear existing answer options
            document.getElementById('answerOptions').innerHTML = '';

            // Reset counter
            answerOptionCount = 0;

            // Add answer options from the question data
            try {
                const options = data.question.pilihan_jawaban;
                const correctAnswer = data.question.jawaban_benar;

                console.log('Options data:', options);

                if (Array.isArray(options) && options.length > 0) {
                    options.forEach((option, index) => {
                        const optionLabel = String.fromCharCode(65 + index);
                        addAnswerOptionWithValue(option, optionLabel === correctAnswer);
                    });
                } else if (typeof options === 'object' && options !== null) {
                    // Handle case where options might be an object with keys A, B, C, etc.
                    Object.keys(options).forEach((key, index) => {
                        const optionLabel = String.fromCharCode(65 + index);
                        addAnswerOptionWithValue(options[key], optionLabel === correctAnswer);
                    });
                } else {
                    throw new Error('Invalid options format');
                }
            } catch (e) {
                console.error('Error processing answer options:', e);
                // Add default empty options if error
                addAnswerOption();
                addAnswerOption();
            }

            // Show the form
            document.getElementById('questionBuilderCard').style.display = 'block';
            document.getElementById('questionBuilderCard').scrollIntoView({ behavior: 'smooth' });

            // Set current editing question
            currentEditingQuestion = questionId;
        } else {
            throw new Error(data.message || 'Tidak dapat memuat data pertanyaan');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.message || 'Terjadi kesalahan saat memuat data pertanyaan',
            confirmButtonColor: '#8B5CF6',
        });
    });
}

function addAnswerOptionWithValue(optionValue, isCorrect) {
    const container = document.getElementById('answerOptions');
    const optionIndex = answerOptionCount;
    const optionLabel = String.fromCharCode(65 + optionIndex); // A, B, C, D...

    const optionHtml = `
        <div class="answer-option-builder mb-3" data-option="${optionLabel}">
            <div class="input-group-modern">
                <input type="text" name="pilihan_jawaban[]" placeholder=" " value="${optionValue}" required>
                <label>Pilihan ${optionLabel}</label>
            </div>
            <div class="form-check mt-2">
                <input class="form-check-input" type="radio" name="jawaban_benar" value="${optionLabel}" id="correct_${optionLabel}" ${isCorrect ? 'checked' : ''}>
                <label class="form-check-label" for="correct_${optionLabel}">
                    Jawaban yang benar
                </label>
            </div>
            ${optionIndex > 1 ? `
                <button type="button" class="btn-modern btn-danger-modern btn-sm mt-2" onclick="removeAnswerOption('${optionLabel}')">
                    <i class="fas fa-trash"></i>
                    <span>Hapus</span>
                </button>
            ` : ''}
        </div>
    `;

    container.insertAdjacentHTML('beforeend', optionHtml);
    answerOptionCount++;
}

function deleteQuestion(questionId) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Pertanyaan ini akan dihapus permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/admin/courses/quiz/questions/${questionId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Terhapus!', data.message, 'success')
                        .then(() => location.reload());
                } else {
                    throw new Error(data.message);
                }
            })
            .catch(error => {
                Swal.fire('Error!', error.message, 'error');
            });
        }
    });
}

function cancelEditQuestion() {
    document.getElementById('questionBuilderCard').style.display = 'none';
    resetQuestionForm();
}

function updateQuestionOrder() {
    const questions = document.querySelectorAll('#sortable-questions .question-card');
    const orderData = [];

    questions.forEach((question, index) => {
        const questionId = question.getAttribute('data-question-id');
        orderData.push({
            question_id: questionId,
            urutan_pertanyaan: index + 1
        });

        // Update visual order number
        const numberElement = question.querySelector('.question-number');
        if (numberElement) {
            numberElement.textContent = index + 1;
        }
    });

    // Send order update to server
    fetch(`/admin/courses/quiz-questions/update-order`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ questions: orderData })
    })
    .catch(error => {
        console.error('Error updating order:', error);
    });
}

function previewQuiz() {
    // Get all questions for preview
    const questions = [];
    document.querySelectorAll('.question-card').forEach((card, index) => {
        // Get question text
        const questionText = card.querySelector('h6.mb-3').textContent;
        const questionNumber = index + 1;

        // Get options
        const options = [];
        card.querySelectorAll('.answer-option').forEach((option) => {
            const optionText = option.querySelector('.flex-1').textContent.trim();
            const isCorrect = option.classList.contains('correct');
            options.push({ text: optionText, isCorrect });
        });

        questions.push({
            number: questionNumber,
            text: questionText,
            options
        });
    });

    // If no questions, show error
    if (questions.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Tidak ada pertanyaan',
            text: 'Tambahkan pertanyaan terlebih dahulu untuk melihat preview quiz.',
            confirmButtonColor: '#8B5CF6',
        });
        return;
    }

    // Build preview HTML
    let previewHtml = `
        <div class="quiz-preview-container">
            <h5 class="mb-4">{{ $quiz->judul_quiz }}</h5>
    `;

    questions.forEach(q => {
        previewHtml += `
            <div class="question-preview mb-4">
                <h6 class="mb-3">${q.number}. ${q.text}</h6>
                <div class="options-list">
        `;

        q.options.forEach((opt, idx) => {
            const optionLabel = String.fromCharCode(65 + idx);
            previewHtml += `
                <div class="option-preview ${opt.isCorrect ? 'option-correct' : ''}">
                    <span class="option-label">${optionLabel}</span>
                    <span class="option-text">${opt.text}</span>
                    ${opt.isCorrect ? '<i class="fas fa-check-circle text-success float-end"></i>' : ''}
                </div>
            `;
        });

        previewHtml += `
                </div>
            </div>
        `;
    });

    previewHtml += `</div>`;

    // Show preview in modal
    Swal.fire({
        title: 'Preview Quiz',
        html: previewHtml,
        width: '800px',
        customClass: {
            container: 'quiz-preview-modal',
            popup: 'quiz-preview-popup',
        },
        showCloseButton: true,
        confirmButtonColor: '#8B5CF6',
        confirmButtonText: 'Tutup Preview'
    });

    // Add styles for preview
    const style = document.createElement('style');
    style.textContent = `
        .quiz-preview-popup {
            max-width: 800px;
            width: 100%;
        }
        .quiz-preview-container {
            text-align: left;
            padding: 20px 0;
        }
        .question-preview {
            border-bottom: 1px solid #e9ecef;
            padding-bottom: 20px;
        }
        .question-preview:last-child {
            border-bottom: none;
        }
        .options-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .option-preview {
            display: flex;
            align-items: center;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 8px;
            position: relative;
        }
        .option-correct {
            background: #d1e7dd;
            border: 1px solid #198754;
        }
        .option-label {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            background: #e9ecef;
            border-radius: 50%;
            margin-right: 12px;
            font-weight: bold;
        }
        .option-correct .option-label {
            background: #198754;
            color: white;
        }
    `;
    document.head.appendChild(style);
}

function showErrorMessages(errors) {
    const errorContainer = document.getElementById('error-messages');
    errorContainer.innerHTML = '';
    errorContainer.style.display = 'block';

    // Add error header
    const errorHeader = document.createElement('div');
    errorHeader.innerHTML = '<strong><i class="fas fa-exclamation-triangle me-2"></i>Ada kesalahan dalam form:</strong>';
    errorContainer.appendChild(errorHeader);

    if (typeof errors === 'string') {
        errorContainer.innerHTML += `<p class="mt-2"><i class="fas fa-exclamation-circle me-2"></i>${errors}</p>`;
        return;
    }

    const errorList = document.createElement('ul');
    errorList.classList.add('mb-0', 'mt-2');
    errorList.style.paddingLeft = '20px';

    if (typeof errors === 'object') {
        // If errors is an object with error arrays
        Object.keys(errors).forEach(field => {
            errors[field].forEach(message => {
                const item = document.createElement('li');
                item.textContent = message;
                errorList.appendChild(item);
            });
        });
    }

    errorContainer.appendChild(errorList);
}

function hideErrorMessages() {
    const errorContainer = document.getElementById('error-messages');
    errorContainer.innerHTML = '';
    errorContainer.style.display = 'none';
}
</script>
@endpush
