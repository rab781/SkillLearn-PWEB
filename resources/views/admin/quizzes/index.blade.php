@extends('layouts.admin')

@section('title', 'Quiz Management - ' . $course->nama_course)

@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
.quiz-management-container {
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
}

/* Override potential Tailwind conflicts */
.quiz-management-container * {
    box-sizing: border-box;
}

/* Page Header */
.quiz-page-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    color: white !important;
    border-radius: 12px !important;
    padding: 32px !important;
    margin-bottom: 32px !important;
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.2) !important;
}

.quiz-page-header .breadcrumb {
    background: rgba(255, 255, 255, 0.1) !important;
    border-radius: 8px !important;
    padding: 12px 16px !important;
    margin-bottom: 0 !important;
}

.quiz-page-header .breadcrumb a {
    color: rgba(255, 255, 255, 0.9) !important;
    text-decoration: none !important;
}

.quiz-page-header .breadcrumb-item.active {
    color: rgba(255, 255, 255, 0.8) !important;
}

.quiz-page-header h1 {
    color: white !important;
    margin-bottom: 0 !important;
}

.quiz-page-header p {
    color: rgba(255, 255, 255, 0.85) !important;
    margin-bottom: 0 !important;
}

.quiz-page-header .header-icon {
    background: rgba(255, 255, 255, 0.2) !important;
    border-radius: 50% !important;
    padding: 8px !important;
    margin-right: 16px !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
}

/* Quiz Cards */
.quiz-card {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%) !important;
    border-radius: 16px !important;
    border: 1px solid rgba(102, 126, 234, 0.1) !important;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.1) !important;
    transition: all 0.3s ease !important;
    overflow: hidden !important;
    position: relative !important;
    height: 100% !important;
}

.quiz-card::before {
    content: '' !important;
    position: absolute !important;
    top: 0 !important;
    left: 0 !important;
    right: 0 !important;
    height: 4px !important;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
}

.quiz-card:hover {
    transform: translateY(-8px) !important;
    box-shadow: 0 12px 35px rgba(102, 126, 234, 0.2) !important;
    border-color: rgba(102, 126, 234, 0.3) !important;
}

.quiz-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    color: #ffffff !important;
    padding: 24px !important;
    position: relative !important;
}

.quiz-header h5 {
    font-size: 20px !important;
    font-weight: 700 !important;
    margin-bottom: 8px !important;
    color: white !important;
}

.quiz-header p {
    font-size: 14px !important;
    opacity: 0.85 !important;
    margin-bottom: 0 !important;
}

.quiz-status-badge {
    position: absolute !important;
    top: 16px !important;
    right: 16px !important;
    padding: 8px 16px !important;
    border-radius: 20px !important;
    font-size: 12px !important;
    font-weight: 600 !important;
    backdrop-filter: blur(15px) !important;
    border: 1px solid rgba(255, 255, 255, 0.2) !important;
}

.quiz-status-badge.bg-success {
    background: linear-gradient(135deg, rgba(76, 175, 80, 0.9), rgba(69, 160, 73, 0.9)) !important;
    color: white !important;
}

.quiz-status-badge.bg-secondary {
    background: linear-gradient(135deg, rgba(108, 117, 125, 0.9), rgba(90, 98, 104, 0.9)) !important;
    color: white !important;
}

/* Quiz Stats */
.quiz-stats {
    display: flex !important;
    flex-wrap: wrap !important;
    gap: 12px !important;
    margin-top: 16px !important;
}

.quiz-stat {
    display: flex !important;
    align-items: center !important;
    gap: 6px !important;
    padding: 8px 12px !important;
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.2), rgba(255, 255, 255, 0.1)) !important;
    border-radius: 20px !important;
    font-size: 12px !important;
    font-weight: 500 !important;
    color: rgba(255, 255, 255, 0.95) !important;
    backdrop-filter: blur(15px) !important;
    border: 1px solid rgba(255, 255, 255, 0.15) !important;
    transition: all 0.3s ease !important;
}

.quiz-stat:hover {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.3), rgba(255, 255, 255, 0.2)) !important;
    transform: scale(1.05) !important;
}

.quiz-content {
    padding: 20px !important;
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%) !important;
}

.quiz-meta {
    display: flex !important;
    justify-content: space-between !important;
    align-items: center !important;
    margin-bottom: 16px !important;
    padding: 12px !important;
    background: linear-gradient(135deg, #e9ecef 0%, #f8f9fa 100%) !important;
    border-radius: 12px !important;
    border: 1px solid rgba(102, 126, 234, 0.1) !important;
}

.quiz-meta small {
    display: flex !important;
    align-items: center !important;
    gap: 4px !important;
    color: #6c757d !important;
    font-weight: 500 !important;
}

.quiz-actions {
    padding: 16px !important;
    border-top: 1px solid #dee2e6 !important;
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%) !important;
    display: flex !important;
    gap: 12px !important;
}

/* Buttons */
.quiz-btn {
    padding: 12px 24px !important;
    border-radius: 12px !important;
    font-weight: 600 !important;
    font-size: 14px !important;
    transition: all 0.3s ease !important;
    border: none !important;
    cursor: pointer !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 8px !important;
    text-decoration: none !important;
    position: relative !important;
    overflow: hidden !important;
    min-height: 44px !important;
    white-space: nowrap !important;
}

.quiz-btn i {
    font-size: 16px !important;
    line-height: 1 !important;
}

.quiz-btn:hover {
    transform: translateY(-2px) !important;
    text-decoration: none !important;
}

.quiz-btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    color: white !important;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3) !important;
}

.quiz-btn-primary:hover {
    color: white !important;
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4) !important;
}

.quiz-btn-success {
    background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%) !important;
    color: white !important;
    box-shadow: 0 4px 15px rgba(76, 175, 80, 0.3) !important;
}

.quiz-btn-success:hover {
    color: white !important;
    box-shadow: 0 6px 20px rgba(76, 175, 80, 0.4) !important;
}

.quiz-btn-warning {
    background: linear-gradient(135deg, #FF9800 0%, #F57C00 100%) !important;
    color: white !important;
    box-shadow: 0 4px 15px rgba(255, 152, 0, 0.3) !important;
}

.quiz-btn-warning:hover {
    color: white !important;
    box-shadow: 0 6px 20px rgba(255, 152, 0, 0.4) !important;
}

.quiz-btn-danger {
    background: linear-gradient(135deg, #F44336 0%, #D32F2F 100%) !important;
    color: white !important;
    box-shadow: 0 4px 15px rgba(244, 67, 54, 0.3) !important;
}

.quiz-btn-danger:hover {
    color: white !important;
    box-shadow: 0 6px 20px rgba(244, 67, 54, 0.4) !important;
}

.quiz-btn-secondary {
    background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%) !important;
    color: white !important;
    box-shadow: 0 4px 15px rgba(108, 117, 125, 0.3) !important;
}

.quiz-btn-secondary:hover {
    color: white !important;
    box-shadow: 0 6px 20px rgba(108, 117, 125, 0.4) !important;
}

/* Form Builder */
.quiz-form-builder {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%) !important;
    border-radius: 16px !important;
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.15) !important;
    border: 1px solid rgba(102, 126, 234, 0.1) !important;
    overflow: hidden !important;
}

.quiz-form-section {
    padding: 32px !important;
    border-bottom: 1px solid rgba(102, 126, 234, 0.1) !important;
}

.quiz-form-section:last-child {
    border-bottom: none !important;
}

.quiz-form-section h4 {
    color: #667eea !important;
    margin-bottom: 24px !important;
    font-weight: 700 !important;
    display: flex !important;
    align-items: center !important;
    gap: 8px !important;
}

/* Form Inputs */
.quiz-input-group {
    position: relative !important;
    margin-bottom: 24px !important;
}

.quiz-input-group input,
.quiz-input-group textarea,
.quiz-input-group select {
    width: 100% !important;
    padding: 16px 20px !important;
    border: 2px solid #e2e8f0 !important;
    border-radius: 12px !important;
    font-size: 15px !important;
    transition: all 0.3s ease !important;
    background: #ffffff !important;
    font-family: inherit !important;
}

.quiz-input-group input:focus,
.quiz-input-group textarea:focus,
.quiz-input-group select:focus {
    border-color: #667eea !important;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.15) !important;
    outline: none !important;
    background: linear-gradient(135deg, #ffffff 0%, #f8fdff 100%) !important;
}

.quiz-input-group label {
    position: absolute !important;
    left: 20px !important;
    top: 16px !important;
    background: white !important;
    padding: 0 8px !important;
    color: #718096 !important;
    font-size: 15px !important;
    transition: all 0.3s ease !important;
    pointer-events: none !important;
    font-weight: 500 !important;
}

.quiz-input-group input:focus + label,
.quiz-input-group textarea:focus + label,
.quiz-input-group select:focus + label,
.quiz-input-group input:not(:placeholder-shown) + label,
.quiz-input-group textarea:not(:placeholder-shown) + label,
.quiz-input-group select:not([value=""]) + label,
.quiz-input-group .has-value + label {
    top: -8px !important;
    font-size: 12px !important;
    color: #667eea !important;
    font-weight: 600 !important;
}

/* Empty State */
.quiz-empty-state {
    text-align: center !important;
    padding: 64px 32px !important;
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%) !important;
    border-radius: 16px !important;
    border: 2px dashed rgba(102, 126, 234, 0.3) !important;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.1) !important;
}

.quiz-empty-state i {
    font-size: 64px !important;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    -webkit-background-clip: text !important;
    -webkit-text-fill-color: transparent !important;
    background-clip: text !important;
    margin-bottom: 24px !important;
}

.quiz-empty-state h4 {
    color: #667eea !important;
    margin-bottom: 16px !important;
    font-weight: 600 !important;
}

.quiz-empty-state p {
    color: #6c757d !important;
    margin-bottom: 32px !important;
}

/* Alert */
.quiz-alert-success {
    background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%) !important;
    border-color: #4CAF50 !important;
    color: #155724 !important;
    border-radius: 12px !important;
    box-shadow: 0 2px 10px rgba(76, 175, 80, 0.2) !important;
}

/* Animation */
.quiz-fade-in {
    animation: quizFadeIn 0.5s ease-in-out !important;
}

@keyframes quizFadeIn {
    from {
        opacity: 0 !important;
        transform: translateY(20px) !important;
    }
    to {
        opacity: 1 !important;
        transform: translateY(0) !important;
    }
}

/* Responsive */
@media (max-width: 768px) {
    .quiz-stats {
        gap: 8px !important;
    }

    .quiz-stat {
        font-size: 11px !important;
        padding: 6px 10px !important;
    }

    .quiz-actions {
        flex-direction: column !important;
        gap: 8px !important;
    }

    .quiz-btn {
        justify-content: center !important;
        width: 100% !important;
    }

    .quiz-page-header {
        padding: 24px !important;
    }

    .quiz-form-section {
        padding: 24px !important;
    }
}
</style>
@endpush

@section('content')
<div class="quiz-management-container">
<div class="container-fluid px-4 py-3">
    <!-- Page Header -->
    <div class="quiz-page-header quiz-fade-in mb-4">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <nav aria-label="breadcrumb" class="mb-3">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.courses.index') }}">
                                <i class="fas fa-graduation-cap me-1"></i>Courses
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.courses.show', $course->course_id) }}">
                                {{ Str::limit($course->nama_course, 30) }}
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Quiz Management</li>
                    </ol>
                </nav>
                <div class="d-flex align-items-center mb-2">
                    <div class="header-icon">
                        <i class="fas fa-question-circle fa-lg"></i>
                    </div>
                    <div>
                        <h1 class="h3 mb-0">Quiz Management</h1>
                        <p class="mb-0">
                            Kelola quiz untuk course: <strong>{{ $course->nama_course }}</strong>
                        </p>
                    </div>
                </div>
            </div>
            <button class="quiz-btn quiz-btn-primary" onclick="showQuizBuilder()">
                <i class="fas fa-plus"></i>
                <span>Buat Quiz Baru</span>
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert quiz-alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Quiz Builder (Hidden by default) -->
    <div id="quizBuilderCard" class="card quiz-form-builder mb-4 quiz-fade-in" style="display: none;">
        <div class="quiz-form-section">
            <h4>
                <i class="fas fa-plus-circle text-primary"></i>
                Buat Quiz Baru
            </h4>
            <form id="quizForm">
                @csrf
                <input type="hidden" id="quizId" name="quiz_id">

                <div class="row">
                    <div class="col-md-8">
                        <div class="quiz-input-group">
                            <input type="text" id="judulQuiz" name="judul_quiz" placeholder=" " required>
                            <label for="judulQuiz">Judul Quiz</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="quiz-input-group">
                            <select id="tipeQuiz" name="tipe_quiz" required>
                                <option value="">Pilih Tipe</option>
                                <option value="setelah_video">Setelah Video</option>
                                <option value="setelah_section">Setelah Section</option>
                                <option value="akhir_course">Akhir Course</option>
                            </select>
                            <label for="tipeQuiz">Tipe Quiz</label>
                        </div>
                    </div>
                </div>

                <div class="quiz-input-group">
                    <textarea id="deskripsiQuiz" name="deskripsi_quiz" rows="3" placeholder=" "></textarea>
                    <label for="deskripsiQuiz">Deskripsi Quiz (opsional)</label>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="quiz-input-group">
                            <input type="number" id="durasiQuiz" name="durasi_menit" min="1" max="180" value="30" placeholder=" " required>
                            <label for="durasiQuiz">Durasi (menit)</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check mt-4">
                            <input type="checkbox" class="form-check-input" id="isActive" name="is_active" value="1" checked>
                            <label class="form-check-label fw-medium" for="isActive">
                                <i class="fas fa-toggle-on text-success me-1"></i>Aktifkan quiz
                            </label>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-3 mt-4">
                    <button type="button" class="quiz-btn quiz-btn-success" onclick="saveQuiz()">
                        <i class="fas fa-save"></i>
                        <span>Simpan Quiz</span>
                    </button>
                    <button type="button" class="quiz-btn quiz-btn-secondary" onclick="cancelQuizBuilder()">
                        <i class="fas fa-times"></i>
                        <span>Batal</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Quiz List -->
    <div class="row">
        @if($quizzes->count() > 0)
            @foreach($quizzes as $quiz)
            <div class="col-lg-6 col-xl-4 mb-4">
                <div class="quiz-card quiz-fade-in">
                    <div class="quiz-header position-relative">
                        <span class="quiz-status-badge {{ $quiz->is_active ? 'bg-success' : 'bg-secondary' }}">
                            <i class="fas fa-{{ $quiz->is_active ? 'check' : 'pause' }} me-1"></i>
                            {{ $quiz->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>

                        <h5 class="mb-2 fw-bold">{{ $quiz->judul_quiz }}</h5>
                        <p class="mb-0 opacity-90">
                            {{ $quiz->deskripsi_quiz ? Str::limit($quiz->deskripsi_quiz, 80) : 'Tidak ada deskripsi' }}
                        </p>

                        <div class="quiz-stats">
                            <div class="quiz-stat">
                                <i class="fas fa-clock"></i>
                                <span>{{ $quiz->durasi_menit }} menit</span>
                            </div>
                            <div class="quiz-stat">
                                <i class="fas fa-tag"></i>
                                <span>{{ ucwords(str_replace('_', ' ', $quiz->tipe_quiz)) }}</span>
                            </div>
                            <div class="quiz-stat">
                                <i class="fas fa-question-circle"></i>
                                <span>{{ $quiz->questions->count() }} soal</span>
                            </div>
                        </div>
                    </div>

                    <div class="quiz-content">
                        <div class="quiz-meta">
                            <small>
                                <i class="fas fa-users"></i>
                                {{ $quiz->results->groupBy('user_id')->count() }} peserta
                            </small>
                            <small>
                                @if($quiz->results->count() > 0)
                                    <i class="fas fa-chart-line"></i>
                                    Rata-rata: {{ number_format($quiz->results->avg('score'), 1) }}%
                                @else
                                    <i class="fas fa-chart-line"></i>
                                    Belum ada hasil
                                @endif
                            </small>
                        </div>

                        @if($quiz->deskripsi_quiz && strlen($quiz->deskripsi_quiz) > 80)
                        <div class="mt-2">
                            <p class="text-muted small mb-0">{{ $quiz->deskripsi_quiz }}</p>
                        </div>
                        @endif
                    </div>

                    <div class="quiz-actions">
                        <a href="{{ route('admin.courses.quizzes.questions.index', $quiz->quiz_id) }}"
                           class="quiz-btn quiz-btn-primary flex-fill text-center">
                            <i class="fas fa-edit"></i>
                            <span>Kelola Soal</span>
                        </a>

                        <button class="quiz-btn quiz-btn-warning" onclick="editQuiz({{ $quiz->quiz_id }})"
                                title="Edit Quiz">
                            <i class="fas fa-cog"></i>
                        </button>

                        <button class="quiz-btn quiz-btn-danger" onclick="deleteQuiz({{ $quiz->quiz_id }})"
                                title="Hapus Quiz">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        @else
            <div class="col-12">
                <div class="quiz-empty-state quiz-fade-in">
                    <i class="fas fa-question-circle"></i>
                    <h4>Belum Ada Quiz</h4>
                    <p>Mulai buat quiz pertama untuk course ini dan tingkatkan pembelajaran siswa!</p>
                    <button class="quiz-btn quiz-btn-primary" onclick="showQuizBuilder()">
                        <i class="fas fa-plus"></i> Buat Quiz Pertama
                    </button>
                </div>
            </div>
        @endif
    </div>
</div>
</div>
@endsection

@push('scripts')
<script>
let currentEditingQuiz = null;

// Show quiz builder
function showQuizBuilder() {
    const builderCard = document.getElementById('quizBuilderCard');
    builderCard.style.display = 'block';
    builderCard.scrollIntoView({ behavior: 'smooth' });

    // Reset form
    resetQuizForm();
}

// Cancel quiz builder
function cancelQuizBuilder() {
    const builderCard = document.getElementById('quizBuilderCard');
    builderCard.style.display = 'none';
    resetQuizForm();
    currentEditingQuiz = null;
}

// Reset quiz form
function resetQuizForm() {
    document.getElementById('quizForm').reset();
    document.getElementById('quizId').value = '';
    document.getElementById('durasiQuiz').value = '30';
    document.getElementById('isActive').checked = true;
}

// Save quiz
function saveQuiz() {
    const form = document.getElementById('quizForm');

    // Validate form
    if (!validateQuizForm()) {
        return;
    }

    const quizData = {
        judul_quiz: document.getElementById('judulQuiz').value.trim(),
        deskripsi_quiz: document.getElementById('deskripsiQuiz').value.trim(),
        tipe_quiz: document.getElementById('tipeQuiz').value,
        durasi_menit: parseInt(document.getElementById('durasiQuiz').value),
        is_active: document.getElementById('isActive').checked,
        _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    };

    const quizId = document.getElementById('quizId').value;
    const url = quizId ?
        `/admin/courses/quiz/${quizId}` :
        `/admin/courses/{{ $course->course_id }}/quizzes`;
    const method = quizId ? 'PUT' : 'POST';

    // Show loading
    Swal.fire({
        title: quizId ? 'Mengupdate Quiz...' : 'Membuat Quiz...',
        text: 'Mohon tunggu sebentar',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': quizData._token
        },
        body: JSON.stringify(quizData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: data.message || (quizId ? 'Quiz berhasil diupdate!' : 'Quiz berhasil dibuat!'),
                showConfirmButton: true,
                confirmButtonText: 'OK'
            }).then(() => {
                if (!quizId && data.quiz_id) {
                    // Jika quiz baru dibuat, tanya apakah ingin menambah pertanyaan
                    Swal.fire({
                        title: 'Quiz Berhasil Dibuat!',
                        text: 'Apakah Anda ingin menambahkan pertanyaan sekarang?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#667eea',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, Tambah Pertanyaan',
                        cancelButtonText: 'Nanti Saja'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = `/admin/quiz/${data.quiz_id}/questions`;
                        } else {
                            window.location.reload();
                        }
                    });
                } else {
                    window.location.reload();
                }
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: data.message || 'Gagal menyimpan quiz'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Terjadi kesalahan saat menyimpan quiz'
        });
    });
}

// Validate quiz form
function validateQuizForm() {
    const judul = document.getElementById('judulQuiz').value.trim();
    const tipe = document.getElementById('tipeQuiz').value;
    const durasi = document.getElementById('durasiQuiz').value;

    if (!judul) {
        Swal.fire({
            icon: 'warning',
            title: 'Validasi Error',
            text: 'Judul quiz harus diisi!'
        });
        document.getElementById('judulQuiz').focus();
        return false;
    }

    if (!tipe) {
        Swal.fire({
            icon: 'warning',
            title: 'Validasi Error',
            text: 'Tipe quiz harus dipilih!'
        });
        document.getElementById('tipeQuiz').focus();
        return false;
    }

    if (!durasi || durasi < 1 || durasi > 180) {
        Swal.fire({
            icon: 'warning',
            title: 'Validasi Error',
            text: 'Durasi quiz harus antara 1-180 menit!'
        });
        document.getElementById('durasiQuiz').focus();
        return false;
    }

    return true;
}

// Edit quiz
function editQuiz(quizId) {
    // Fetch quiz data
    fetch(`/admin/courses/quiz/${quizId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const quiz = data.quiz;

                // Show quiz builder
                showQuizBuilder();

                // Fill form with quiz data
                document.getElementById('quizId').value = quiz.quiz_id;
                document.getElementById('judulQuiz').value = quiz.judul_quiz;
                document.getElementById('deskripsiQuiz').value = quiz.deskripsi_quiz || '';
                document.getElementById('tipeQuiz').value = quiz.tipe_quiz;
                document.getElementById('durasiQuiz').value = quiz.durasi_menit;
                document.getElementById('isActive').checked = quiz.is_active;

                currentEditingQuiz = quizId;

                // Trigger label animation
                triggerLabelAnimation();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Gagal mengambil data quiz'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Terjadi kesalahan saat mengambil data quiz'
            });
        });
}

// Delete quiz
function deleteQuiz(quizId) {
    Swal.fire({
        title: 'Hapus Quiz?',
        text: 'Quiz dan semua pertanyaannya akan dihapus permanen. Tindakan ini tidak dapat dibatalkan!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: 'Menghapus Quiz...',
                text: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch(`/admin/courses/quiz/${quizId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Quiz berhasil dihapus!'
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Gagal menghapus quiz'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Terjadi kesalahan saat menghapus quiz'
                });
            });
        }
    });
}

// Trigger label animation for filled inputs
function triggerLabelAnimation() {
    const inputs = document.querySelectorAll('.quiz-input-group input, .quiz-input-group textarea, .quiz-input-group select');
    inputs.forEach(input => {
        if (input.value) {
            input.classList.add('has-value');
        }
    });
}

// Add event listeners for modern input animations
document.addEventListener('DOMContentLoaded', function() {
    const inputs = document.querySelectorAll('.quiz-input-group input, .quiz-input-group textarea, .quiz-input-group select');

    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (this.value) {
                this.classList.add('has-value');
            } else {
                this.classList.remove('has-value');
            }
        });

        input.addEventListener('focus', function() {
            this.classList.add('has-value');
        });
    });
});
</script>
@endpush
