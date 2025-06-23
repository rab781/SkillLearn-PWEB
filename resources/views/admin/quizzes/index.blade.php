@extends('layouts.admin')

@section('title', 'Quiz Management - ' . $course->nama_course)

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
/* Modern Quiz Management Styling */
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

.quiz-builder {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    border: 2px dashed #e9ecef;
    border-radius: var(--border-radius);
    transition: var(--transition);
    box-shadow: var(--shadow-light);
}

.quiz-builder:hover {
    border-color: #667eea;
    background: linear-gradient(135deg, #ffffff 0%, #e7f1ff 100%);
    transform: translateY(-2px);
}

.quiz-card {
    background: white;
    border-radius: var(--border-radius);
    border: 1px solid #e9ecef;
    transition: var(--transition);
    overflow: hidden;
    box-shadow: var(--shadow-light);
    height: 100%;
}

.quiz-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-medium);
    border-color: #667eea;
}

.quiz-header {
    background: var(--primary-gradient);
    color: white;
    padding: 1.5rem;
    position: relative;
}

.quiz-status-badge {
    position: absolute;
    top: 1rem;
    right: 1rem;
    padding: 0.4rem 0.8rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    backdrop-filter: blur(10px);
}

.quiz-stats {
    display: flex;
    gap: 1.5rem;
    margin-top: 1rem;
    flex-wrap: wrap;
}

.quiz-stat {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: rgba(255,255,255,0.95);
    font-size: 0.85rem;
    background: rgba(255,255,255,0.1);
    padding: 0.4rem 0.8rem;
    border-radius: 20px;
    backdrop-filter: blur(10px);
}

.quiz-actions {
    padding: 1.25rem;
    border-top: 1px solid #f1f3f4;
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    display: flex;
    gap: 0.75rem;
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

.quiz-content {
    padding: 1.25rem;
    flex: 1;
}

.quiz-meta {
    display: flex;
    justify-content: between;
    align-items: center;
    margin-bottom: 1rem;
    padding: 0.75rem;
    background: #f8f9fa;
    border-radius: 8px;
}

.quiz-meta small {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    color: #6c757d;
    font-weight: 500;
}

.fade-in {
    animation: fadeIn 0.5s ease-in-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

@media (max-width: 768px) {
    .quiz-stats {
        gap: 1rem;
    }

    .quiz-stat {
        font-size: 0.8rem;
        padding: 0.3rem 0.6rem;
    }

    .quiz-actions {
        flex-direction: column;
    }

    .btn-modern {
        justify-content: center;
    }
}

.btn-modern:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.btn-primary-modern {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.btn-success-modern {
    background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
    color: white;
}

.btn-warning-modern {
    background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%);
    color: white;
}

.btn-danger-modern {
    background: linear-gradient(135deg, #f44336 0%, #d32f2f 100%);
    color: white;
}
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-3">
    <!-- Page Header -->
    <div class="page-header fade-in mb-4">
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
                            <a href="{{ route('admin.courses.show', $course->course_id) }}" class="text-decoration-none">
                                {{ Str::limit($course->nama_course, 30) }}
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Quiz Management</li>
                    </ol>
                </nav>
                <div class="d-flex align-items-center mb-2">
                    <div class="bg-primary text-white rounded-circle p-2 me-3">
                        <i class="fas fa-question-circle fa-lg"></i>
                    </div>
                    <div>
                        <h1 class="h3 mb-0 text-gray-800">Quiz Management</h1>
                        <p class="text-muted mb-0">
                            Kelola quiz untuk course: <strong>{{ $course->nama_course }}</strong>
                        </p>
                    </div>
                </div>
            </div>
            <button class="btn-modern btn-primary-modern" onclick="showQuizBuilder()">
                <i class="fas fa-plus"></i>
                <span>Buat Quiz Baru</span>
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Quiz Builder (Hidden by default) -->
    <div id="quizBuilderCard" class="card form-builder mb-4 fade-in" style="display: none;">
        <div class="form-section">
            <h4>
                <i class="fas fa-plus-circle text-primary"></i>
                Buat Quiz Baru
            </h4>
            <form id="quizForm">
                @csrf
                <input type="hidden" id="quizId" name="quiz_id">

                <div class="row">
                    <div class="col-md-8">
                        <div class="input-group-modern">
                            <input type="text" id="judulQuiz" name="judul_quiz" placeholder=" " required>
                            <label for="judulQuiz">Judul Quiz</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="input-group-modern">
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

                <div class="input-group-modern">
                    <textarea id="deskripsiQuiz" name="deskripsi_quiz" rows="3" placeholder=" "></textarea>
                    <label for="deskripsiQuiz">Deskripsi Quiz (opsional)</label>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="input-group-modern">
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
                    <button type="button" class="btn-modern btn-success-modern" onclick="saveQuiz()">
                        <i class="fas fa-save"></i>
                        <span>Simpan Quiz</span>
                    </button>
                    <button type="button" class="btn-modern btn-secondary" onclick="cancelQuizBuilder()">
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
                <div class="quiz-card fade-in">
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
                           class="btn-modern btn-primary-modern flex-fill text-center">
                            <i class="fas fa-edit"></i>
                            <span>Kelola Soal</span>
                        </a>

                        <button class="btn-modern btn-warning-modern" onclick="editQuiz({{ $quiz->quiz_id }})"
                                title="Edit Quiz">
                            <i class="fas fa-cog"></i>
                        </button>

                        <button class="btn-modern btn-danger-modern" onclick="deleteQuiz({{ $quiz->quiz_id }})"
                                title="Hapus Quiz">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        @else
            <div class="col-12">
                <div class="empty-state fade-in">
                    <i class="fas fa-question-circle"></i>
                    <h4>Belum Ada Quiz</h4>
                    <p>Mulai buat quiz pertama untuk course ini dan tingkatkan pembelajaran siswa!</p>
                    <button class="btn-modern btn-primary-modern" onclick="showQuizBuilder()">
                        <i class="fas fa-plus"></i> Buat Quiz Pertama
                    </button>
                </div>
            </div>
        @endif
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
    const inputs = document.querySelectorAll('.input-group-modern input, .input-group-modern textarea, .input-group-modern select');
    inputs.forEach(input => {
        if (input.value) {
            input.classList.add('has-value');
        }
    });
}

// Add event listeners for modern input animations
document.addEventListener('DOMContentLoaded', function() {
    const inputs = document.querySelectorAll('.input-group-modern input, .input-group-modern textarea, .input-group-modern select');

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
