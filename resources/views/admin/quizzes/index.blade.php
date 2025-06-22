@extends('layouts.admin')

@section('title', 'Quiz Management - ' . $course->nama_course)

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.courses.index') }}">Courses</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.courses.show', $course->course_id) }}">{{ $course->nama_course }}</a></li>
                    <li class="breadcrumb-item active">Quiz Management</li>
                </ol>
            </nav>
            <h1 class="h3 mb-0 text-gray-800">Quiz Management</h1>
            <p class="text-muted">Kelola quiz untuk course: <strong>{{ $course->nama_course }}</strong></p>
        </div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addQuizModal">
            <i class="fas fa-plus"></i> Tambah Quiz
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Quiz List -->
    <div class="card shadow">
        <div class="card-header">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Quiz</h6>
        </div>
        <div class="card-body">
            @if($quizzes->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Judul Quiz</th>
                                <th>Tipe</th>
                                <th>Durasi</th>
                                <th>Status</th>
                                <th>Peserta</th>
                                <th>Rata-rata Skor</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($quizzes as $quiz)
                            <tr>
                                <td>
                                    <strong>{{ $quiz->judul_quiz }}</strong>
                                    <br>
                                    <small class="text-muted">{!! Str::limit(strip_tags($quiz->deskripsi_quiz), 50) !!}</small>
                                </td>
                                <td>
                                    <span class="badge 
                                        @switch($quiz->tipe_quiz)
                                            @case('setelah_video') bg-info @break
                                            @case('setelah_section') bg-warning @break
                                            @case('akhir_course') bg-success @break
                                            @default bg-secondary
                                        @endswitch
                                    ">
                                        {{ ucwords(str_replace('_', ' ', $quiz->tipe_quiz)) }}
                                    </span>
                                </td>
                                <td>{{ $quiz->durasi_menit }} menit</td>
                                <td>
                                    <span class="badge {{ $quiz->is_active ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $quiz->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td>{{ $quiz->results->count() }} peserta</td>
                                <td>
                                    @if($quiz->results->count() > 0)
                                        {{ number_format($quiz->results->avg('skor'), 1) }}%
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-sm btn-primary" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editQuizModal"
                                                data-quiz-id="{{ $quiz->quiz_id }}"
                                                data-quiz-title="{{ $quiz->judul_quiz }}"
                                                data-quiz-description="{{ $quiz->deskripsi_quiz }}"
                                                data-quiz-type="{{ $quiz->tipe_quiz }}"
                                                data-quiz-duration="{{ $quiz->durasi_menit }}"
                                                data-quiz-content="{{ $quiz->konten_quiz }}"
                                                data-quiz-active="{{ $quiz->is_active ? 1 : 0 }}"
                                                title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="{{ route('admin.courses.quiz.destroy', $quiz->quiz_id) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-danger" 
                                                    onclick="return confirm('Yakin ingin menghapus quiz ini?')"
                                                    title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-question-circle fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Belum ada quiz untuk course ini. Tambahkan quiz pertama!</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Add Quiz Modal -->
<div class="modal fade" id="addQuizModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('admin.courses.quiz.store', $course->course_id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Quiz Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="judul_quiz" class="form-label">Judul Quiz</label>
                        <input type="text" class="form-control" id="judul_quiz" name="judul_quiz" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="deskripsi_quiz" class="form-label">Deskripsi Quiz</label>
                        <textarea class="form-control" id="deskripsi_quiz" name="deskripsi_quiz" rows="3"></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tipe_quiz" class="form-label">Tipe Quiz</label>
                                <select class="form-select" id="tipe_quiz" name="tipe_quiz" required>
                                    <option value="">-- Pilih Tipe --</option>
                                    <option value="setelah_video">Setelah Video</option>
                                    <option value="setelah_section">Setelah Section</option>
                                    <option value="akhir_course">Akhir Course</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="durasi_menit" class="form-label">Durasi (menit)</label>
                                <input type="number" class="form-control" id="durasi_menit" name="durasi_menit" min="1" value="10" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="konten_quiz" class="form-label">Konten Quiz (JSON)</label>
                        <textarea class="form-control" id="konten_quiz" name="konten_quiz" rows="8" placeholder='{"questions": [{"question": "Apa itu Laravel?", "options": ["Framework PHP", "Database", "Server"], "correct": 0}]}' required></textarea>
                        <div class="form-text">
                            Format JSON untuk pertanyaan quiz. 
                            <a href="#" onclick="showQuizExample()">Lihat contoh format</a>
                        </div>
                    </div>
                    
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" checked>
                        <label class="form-check-label" for="is_active">Aktifkan quiz</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Quiz</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Quiz Modal -->
<div class="modal fade" id="editQuizModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="editQuizForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Quiz</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_judul_quiz" class="form-label">Judul Quiz</label>
                        <input type="text" class="form-control" id="edit_judul_quiz" name="judul_quiz" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_deskripsi_quiz" class="form-label">Deskripsi Quiz</label>
                        <textarea class="form-control" id="edit_deskripsi_quiz" name="deskripsi_quiz" rows="3"></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_tipe_quiz" class="form-label">Tipe Quiz</label>
                                <select class="form-select" id="edit_tipe_quiz" name="tipe_quiz" required>
                                    <option value="setelah_video">Setelah Video</option>
                                    <option value="setelah_section">Setelah Section</option>
                                    <option value="akhir_course">Akhir Course</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_durasi_menit" class="form-label">Durasi (menit)</label>
                                <input type="number" class="form-control" id="edit_durasi_menit" name="durasi_menit" min="1" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_konten_quiz" class="form-label">Konten Quiz (JSON)</label>
                        <textarea class="form-control" id="edit_konten_quiz" name="konten_quiz" rows="8" required></textarea>
                    </div>
                    
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="edit_is_active" name="is_active" value="1">
                        <label class="form-check-label" for="edit_is_active">Aktifkan quiz</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update Quiz</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Handle edit quiz modal
$('#editQuizModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var quizId = button.data('quiz-id');
    var modal = $(this);
    
    // Set form action
    modal.find('#editQuizForm').attr('action', '/admin/courses/quiz/' + quizId);
    
    // Populate form fields
    modal.find('#edit_judul_quiz').val(button.data('quiz-title'));
    modal.find('#edit_deskripsi_quiz').val(button.data('quiz-description'));
    modal.find('#edit_tipe_quiz').val(button.data('quiz-type'));
    modal.find('#edit_durasi_menit').val(button.data('quiz-duration'));
    modal.find('#edit_konten_quiz').val(button.data('quiz-content'));
    modal.find('#edit_is_active').prop('checked', button.data('quiz-active') == 1);
});

function showQuizExample() {
    const example = {
        "questions": [
            {
                "question": "Apa itu Laravel?",
                "options": ["Framework PHP", "Database", "Programming Language"],
                "correct": 0
            },
            {
                "question": "Siapa yang membuat Laravel?",
                "options": ["Taylor Otwell", "Mark Zuckerberg", "Linus Torvalds"],
                "correct": 0
            }
        ]
    };
    
    alert('Contoh format JSON Quiz:\n\n' + JSON.stringify(example, null, 2));
}
</script>
@endpush
