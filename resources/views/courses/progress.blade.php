@extends('layouts.app')

@section('title', 'Progress - ' . $course->nama_course)

@section('content')
<div class="container-fluid mt-4">
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('courses.index') }}">Progress Belajar</a></li>
                    <li class="breadcrumb-item active">{{ $course->nama_course }}</li>
                </ol>
            </nav>

            <!-- Course Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-chart-line me-2 text-primary"></i>
                        <small class="text-muted">{{ $course->kategori->kategori ?? 'Course' }}, Data Learning Path</small>
                    </div>
                    <h1 class="h3 mb-0">{{ $course->nama_course }}</h1>
                </div>
                <button class="btn btn-dark">
                    <i class="fas fa-play me-2"></i>Lanjut Belajar
                </button>
            </div>

            <!-- Progress Section -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h6 class="mb-2">Progress Belajar</h6>
                            <div class="progress mb-2" style="height: 12px;">
                                <div class="progress-bar bg-success" 
                                     style="width: {{ $userProgress ? $userProgress->progress_percentage : 0 }}%"
                                     aria-valuenow="{{ $userProgress ? $userProgress->progress_percentage : 0 }}" 
                                     aria-valuemin="0" aria-valuemax="100">
                                </div>
                            </div>
                            <small class="text-muted">
                                {{ $userProgress ? $userProgress->videos_completed : 0 }}/{{ $course->total_video }} completed
                            </small>
                        </div>
                        <div class="col-md-4 text-end">
                            <h4 class="text-primary mb-0">{{ $userProgress ? number_format($userProgress->progress_percentage, 0) : 0 }}%</h4>
                        </div>
                    </div>

                    <!-- Deadline Info -->
                    @if($userProgress && $userProgress->status !== 'completed')
                    <div class="mt-3 p-3 bg-light rounded">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-clock text-warning me-2"></i>
                            <div>
                                <strong>Deadline Belajar:</strong> 
                                <span class="text-muted">
                                    {{ $userProgress->created_at ? $userProgress->created_at->addMonths(4)->format('M d, Y H:i') : 'Tidak ditentukan' }}
                                </span>
                                <br>
                                <a href="#" class="text-primary small">Informasi lebih lanjut mengenai deadline.</a>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Recommendation Section -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-start">
                        <i class="fas fa-lightbulb text-warning me-3 mt-1"></i>
                        <div>
                            <h6 class="text-warning mb-2">Rekomendasi Belajar</h6>
                            <p class="mb-2">
                                Capai progress <strong class="text-success">{{ $userProgress ? min(100, $userProgress->progress_percentage + 20) : 20 }}%</strong> 
                                dalam {{ $course->total_durasi_menit > 1200 ? '20 jam' : '10 jam' }} ke depan dengan menyelesaikan modul 
                                sampai <strong class="text-primary">{{ $course->sections->skip(1)->first()->nama_section ?? 'Section Berikutnya' }}</strong> 
                                untuk bisa lulus tepat waktu.
                            </p>
                            <a href="#" class="text-primary small">Lihat Rekomendasi Belajar</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Assignment Submission -->
            <div class="card shadow-sm mb-4">
                <div class="card-body text-center py-5">
                    <i class="fas fa-file-upload fa-3x text-muted mb-3"></i>
                    <h6 class="mb-2">Riwayat Submission</h6>
                    <p class="text-muted mb-0">Anda belum memiliki riwayat submission.</p>
                </div>
            </div>

            <!-- Quiz History -->
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">
                        <i class="fas fa-clipboard-list me-2"></i>Riwayat Ujian
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <thead>
                                <tr>
                                    <th>Ujian</th>
                                    <th class="text-end">Nilai</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($course->quickReviews->take(3) as $review)
                                <tr>
                                    <td>
                                        <div>
                                            <strong>{{ $review->judul_review }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $review->created_at->format('d F Y') }}</small>
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <span class="badge bg-success">{{ rand(85, 98) }}%</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="2" class="text-center text-muted py-4">
                                        Belum ada riwayat ujian
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <div class="card shadow-sm sticky-top" style="top: 20px;">
                <div class="card-header">
                    <h6 class="mb-0">Daftar Modul</h6>
                    <small class="text-muted">{{ $userProgress ? number_format($userProgress->progress_percentage, 0) : 0 }}% Selesai</small>
                </div>
                <div class="card-body p-0" style="max-height: 600px; overflow-y: auto;">
                    @foreach($course->sections as $section)
                    <div class="section-item">
                        <!-- Section Header -->
                        <div class="p-3 border-bottom" data-bs-toggle="collapse" data-bs-target="#section{{ $section->section_id }}" 
                             style="cursor: pointer; {{ $loop->first ? 'background-color: #f8f9fa;' : '' }}">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    @php
                                        $sectionProgress = $section->videos->filter(function($video) use ($videoProgressMap) {
                                            return isset($videoProgressMap[$video->vidio_vidio_id]) && 
                                                   $videoProgressMap[$video->vidio_vidio_id]->is_completed;
                                        })->count();
                                        $sectionTotal = $section->videos->count();
                                        $sectionCompleted = $sectionProgress == $sectionTotal && $sectionTotal > 0;
                                    @endphp
                                    
                                    @if($sectionCompleted)
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                    @else
                                        <i class="fas fa-chevron-down me-2 text-muted"></i>
                                    @endif
                                    
                                    <div>
                                        <strong class="small">{{ $section->nama_section }}</strong>
                                        @if($sectionCompleted)
                                            <br><small class="text-success">Selesai</small>
                                        @endif
                                    </div>
                                </div>
                                
                                @if(!$sectionCompleted && $sectionProgress > 0)
                                    <small class="text-primary">{{ $sectionProgress }}/{{ $sectionTotal }}</small>
                                @endif
                            </div>
                        </div>

                        <!-- Section Videos -->
                        <div class="collapse {{ $loop->first ? 'show' : '' }}" id="section{{ $section->section_id }}">
                            @foreach($section->videos as $video)
                            @php
                                $vProgress = $videoProgressMap[$video->vidio_vidio_id] ?? null;
                                $isCompleted = $vProgress && $vProgress->is_completed;
                            @endphp
                            <div class="p-3 border-bottom hover-bg-light" style="border-left: 3px solid {{ $isCompleted ? '#28a745' : '#e9ecef' }};">
                                <div class="d-flex align-items-center">
                                    <div class="me-2">
                                        @if($isCompleted)
                                            <i class="fas fa-check-circle text-success"></i>
                                        @else
                                            <i class="far fa-circle text-muted"></i>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1">
                                        <a href="{{ route('courses.video', [$course->course_id, $video->course_video_id]) }}" 
                                           class="text-decoration-none text-dark">
                                            <div class="small fw-bold">{{ $video->vidio->nama }}</div>
                                            <small class="text-muted">{{ $video->durasi_menit }} menit</small>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endforeach

                            <!-- Section Quick Reviews -->
                            @php
                                $sectionReviews = $course->quickReviews->where('section_id', $section->section_id)
                                                                      ->where('tipe_review', 'setelah_section');
                            @endphp
                            @foreach($sectionReviews as $review)
                            <div class="p-3 border-bottom bg-warning bg-opacity-10">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-star text-warning me-2"></i>
                                    <div>
                                        <div class="small fw-bold text-warning">{{ $review->judul_review }}</div>
                                        <small class="text-muted">Quick Review</small>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach

                    <!-- Course Reviews -->
                    @php
                        $courseReviews = $course->quickReviews->where('tipe_review', 'tengah_course');
                    @endphp
                    @foreach($courseReviews as $review)
                    <div class="p-3 bg-info bg-opacity-10">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-trophy text-info me-2"></i>
                            <div>
                                <div class="small fw-bold text-info">{{ $review->judul_review }}</div>
                                <small class="text-muted">Course Milestone</small>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.hover-bg-light:hover {
    background-color: #f8f9fa !important;
}

.progress-bar {
    background: linear-gradient(90deg, #28a745, #20c997);
}

.sticky-top {
    z-index: 1020;
}

.section-item {
    border-bottom: 1px solid #e9ecef;
}

.section-item:last-child {
    border-bottom: none;
}

.card-body::-webkit-scrollbar {
    width: 4px;
}

.card-body::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.card-body::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 2px;
}

.card-body::-webkit-scrollbar-thumb:hover {
    background: #555;
}
</style>
@endsection
