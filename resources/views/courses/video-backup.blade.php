@extends('layouts.app')

@section('title', $courseVideo->vidio->judul . ' - ' . $course->nama_course)

@section('content')
<div class="container-fluid p-0" style="background-color: #1a1a1a;">
    <div class="row g-0">
        <!-- Main Video Area -->
        <div class="col-lg-9" style="background-color: #1a1a1a;">
            <!-- Video Header -->
            <div class="p-3 bg-dark text-white">
                <div class="d-flex align-items-center">
                    <a href="{{ route('courses.show', $course->course_id) }}" class="text-white text-decoration-none me-3">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <h6 class="mb-0">{{ $course->nama_course }}</h6>
                </div>
            </div>

            <!-- Video Player -->
            <div class="position-relative">
                <div class="ratio ratio-16x9">
                    @php
                        // Extract YouTube video ID from URL
                        $videoId = '';
                        $url = $courseVideo->vidio->url;
                        
                        if (preg_match('/[\\?\\&]v=([^\\?\\&]+)/', $url, $matches)) {
                            $videoId = $matches[1];
                        } elseif (preg_match('/youtu\\.be\\/([^\\?\\&]+)/', $url, $matches)) {
                            $videoId = $matches[1];
                        }
                    @endphp
                    
                    @if($videoId)
                        <iframe id="videoPlayer" 
                                src="https://www.youtube.com/embed/{{ $videoId }}?enablejsapi=1&rel=0&autoplay=1" 
                                title="{{ $courseVideo->vidio->judul }}"
                                frameborder="0" 
                                allowfullscreen
                                class="w-100 h-100">
                        </iframe>
                    @else
                        <div class="d-flex align-items-center justify-content-center bg-dark text-white h-100">
                            <div class="text-center">
                                <i class="fas fa-video fa-3x mb-3"></i>
                                <h5>Video tidak dapat ditampilkan</h5>
                                <p>Silakan klik link di bawah untuk menonton di YouTube</p>
                                <a href="{{ $courseVideo->vidio->url }}" target="_blank" class="btn btn-primary">
                                    <i class="fab fa-youtube"></i> Tonton di YouTube
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Video Content -->
            <div class="bg-dark text-white p-4">
                <!-- Training Header -->
                <div class="mb-4">
                    <h4 class="text-white fw-bold mb-2">Latihan: {{ $courseVideo->vidio->judul }}</h4>
                    
                    <!-- Video Description/Content -->
                    <div class="video-description text-light">
                        @if($courseVideo->vidio->deskripsi)
                            <p>{{ $courseVideo->vidio->deskripsi }}</p>
                        @else
                            <p>Apakah "modal" Anda untuk melakukan pengumpulan data sudah siap? Syukur jika semuanya sudah siap, tetapi untuk memuat kembali pengetahuan tersebut mari kita sedikit <em>recall</em> materi pada materi sebelumnya.</p>
                            
                            <p>Kesimpulan dari materi sebelumnya adalah teori terkait data collecting. Data collecting adalah fondasi yang akan menentukan seberapa sukses model machine learning Anda. Mengumpulkan data dari sumber yang relevan dalam jumlah yang cukup adalah kunci untuk membangun model yang kuat dan andal.</p>
                            
                            <p>Jadi, pastikan Anda tidak terburu-buru dalam materi ini—luangkan waktu untuk mengumpulkan dan memahami data Anda karena ini adalah investasi terbaik yang bisa Anda lakukan untuk kesuksesan sebuah model.</p>
                            
                            <p>Ngomong-ngomong investasi tentunya akan lebih baik jika kita juga menambahkan "modal" baru agar menghasilkan output yang lebih maksimal. <em>Nah</em>, pada materi ini, kita akan melakukan praktik pengumpulan data dan memperkenalkan konsep-konsep yang lebih maksimal.</p>
                        @endif
                    </div>
                </div>

                <!-- Navigation -->
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        @if($navigation['previous'])
                            <a href="{{ route('courses.video', ['courseId' => $course->course_id, 'videoId' => $navigation['previous']->course_video_id]) }}" 
                               class="btn btn-outline-light me-3">
                                <i class="fas fa-chevron-left"></i> {{ $navigation['previous']->vidio->judul }}
                            </a>
                        @endif
                    </div>
                    
                    <div class="text-center">
                        <small class="text-muted">{{ $courseVideo->section->nama_section }}</small>
                    </div>

                    <div class="d-flex align-items-center">
                        @if($navigation['next'])
                            <span class="text-muted me-3">Tandai modul ini telah selesai</span>
                            <div class="form-check form-switch me-3">
                                <input class="form-check-input" type="checkbox" id="markComplete" 
                                       {{ $videoProgress && $videoProgress->is_completed ? 'checked' : '' }}
                                       onchange="toggleVideoCompletion()">
                            </div>
                            <i class="fas fa-chevron-right text-primary"></i>
                        @endif
                    </div>
                </div>
            </div>
        </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Video Info -->
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h3 class="mb-2">{{ $courseVideo->vidio->nama }}</h3>
                            <div class="mb-3">
                                <span class="badge bg-primary">{{ $courseVideo->section->nama_section }}</span>
                                <span class="badge bg-info">Video {{ $courseVideo->urutan_video }}</span>
                                <span class="badge bg-secondary">{{ $courseVideo->durasi_menit }} menit</span>
                            </div>
                        </div>
                        
                        <div class="text-end">
                            @if($videoProgress && $videoProgress->is_completed)
                                <div class="text-success mb-2">
                                    <i class="fas fa-check-circle"></i> Selesai
                                </div>
                            @endif
                            
                            <button id="markCompleteBtn" class="btn btn-success" 
                                    {{ $videoProgress && $videoProgress->is_completed ? 'disabled' : '' }}>
                                <i class="fas fa-check"></i> 
                                {{ $videoProgress && $videoProgress->is_completed ? 'Selesai' : 'Tandai Selesai' }}
                            </button>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    @if($videoProgress && $videoProgress->completion_percentage > 0)
                    <div class="mb-3">
                        <label class="form-label small text-muted">Progress menonton:</label>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar" 
                                 style="width: {{ $videoProgress->completion_percentage }}%"
                                 id="watchProgress">
                            </div>
                        </div>
                        <small class="text-muted">{{ number_format($videoProgress->completion_percentage, 1) }}%</small>
                    </div>
                    @endif

                    <!-- Admin Notes -->
                    @if($courseVideo->catatan_admin)
                    <div class="alert alert-info">
                        <strong>Catatan:</strong> {{ $courseVideo->catatan_admin }}
                    </div>
                    @endif

                    <!-- Video Description -->
                    <div class="mt-3">
                        <h6>Deskripsi Video:</h6>
                        <p class="text-muted">{{ $courseVideo->vidio->deskripsi }}</p>
                    </div>
                </div>
            </div>

            <!-- Video Navigation -->
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            @if($navigation['previous'])
                                <a href="{{ route('courses.video', [$course->course_id, $navigation['previous']->course_video_id]) }}" 
                                   class="btn btn-outline-secondary">
                                    <i class="fas fa-chevron-left"></i> Video Sebelumnya
                                </a>
                            @endif
                        </div>
                        <div class="col-6 text-end">
                            @if($navigation['next'])
                                <a href="{{ route('courses.video', [$course->course_id, $navigation['next']->course_video_id]) }}" 
                                   class="btn btn-primary" id="nextVideoBtn">
                                    Video Selanjutnya <i class="fas fa-chevron-right"></i>
                                </a>
                            @else
                                <a href="{{ route('courses.progress', $course->course_id) }}" 
                                   class="btn btn-success">
                                    <i class="fas fa-trophy"></i> Lihat Progress Course
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Reviews for this video -->
            @if($quickReviews->count() > 0)
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-star text-warning"></i> Quick Review
                    </h5>
                </div>
                <div class="card-body">
                    @foreach($quickReviews as $review)
                    <div class="alert alert-warning">
                        <h6 class="alert-heading">{{ $review->judul_review }}</h6>
                        <div>{!! $review->konten_review !!}</div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-3">
            <!-- Course Progress -->
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h6 class="mb-0">Course Progress</h6>
                </div>
                <div class="card-body">
                    <div class="progress mb-2" style="height: 10px;">
                        <div class="progress-bar" 
                             style="width: {{ $userProgress->progress_percentage }}%">
                        </div>
                    </div>
                    <small class="text-muted">
                        {{ $userProgress->videos_completed }}/{{ $userProgress->total_videos }} videos 
                        ({{ number_format($userProgress->progress_percentage, 1) }}%)
                    </small>
                </div>
            </div>

            <!-- Course Content -->
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="mb-0">{{ $course->nama_course }}</h6>
                </div>
                <div class="card-body p-0" style="max-height: 600px; overflow-y: auto;">
                    @foreach($course->sections as $section)
                    <div class="border-bottom">
                        <div class="p-3 bg-light">
                            <strong class="small">{{ $section->urutan_section }}. {{ $section->nama_section }}</strong>
                        </div>
                        @foreach($section->videos as $video)
                        <div class="p-2 {{ $video->course_video_id == $courseVideo->course_video_id ? 'bg-primary text-white' : '' }}">
                            <div class="d-flex align-items-center">
                                <div class="me-2">
                                    @php
                                        $vProgress = \App\Models\UserVideoProgress::where('user_id', auth()->id())
                                            ->where('vidio_vidio_id', $video->vidio_vidio_id)
                                            ->where('course_id', $course->course_id)
                                            ->first();
                                    @endphp
                                    
                                    @if($vProgress && $vProgress->is_completed)
                                        <i class="fas fa-check-circle text-success"></i>
                                    @elseif($video->course_video_id == $courseVideo->course_video_id)
                                        <i class="fas fa-play-circle"></i>
                                    @else
                                        <i class="far fa-circle text-muted"></i>
                                    @endif
                                </div>
                                <div class="flex-grow-1">
                                    <a href="{{ route('courses.video', [$course->course_id, $video->course_video_id]) }}" 
                                       class="text-decoration-none {{ $video->course_video_id == $courseVideo->course_video_id ? 'text-white' : 'text-dark' }}">
                                        <small>{{ $video->urutan_video }}. {{ Str::limit($video->vidio->nama, 40) }}</small>
                                    </a>
                                    <br>
                                    <small class="text-muted">{{ $video->durasi_menit }} min</small>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.ratio iframe {
    border-radius: 0.375rem;
}

.progress-bar {
    background: linear-gradient(90deg, #007bff, #0056b3);
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

@push('scripts')
<script>
let watchTimeInterval;
let currentWatchTime = 0;
let videoDuration = 0;

$(document).ready(function() {
    // Start tracking watch time
    startWatchTimeTracking();
    
    // Mark complete button handler
    $('#markCompleteBtn').click(function() {
        if (!$(this).hasClass('disabled')) {
            markVideoComplete();
        }
    });
});

function startWatchTimeTracking() {
    // Update watch time every 10 seconds
    watchTimeInterval = setInterval(function() {
        currentWatchTime += 10;
        updateWatchTime();
    }, 10000);
}

function updateWatchTime() {
    $.ajax({
        url: '{{ route("courses.video.watch-time", [$course->course_id, $courseVideo->course_video_id]) }}',
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            watch_time: currentWatchTime,
            total_duration: videoDuration
        },
        success: function(response) {
            if (response.success) {
                // Update progress bar
                $('#watchProgress').css('width', response.completion_percentage + '%');
                
                // Auto-mark as complete if 90% watched
                if (response.is_completed && !$('#markCompleteBtn').hasClass('disabled')) {
                    $('#markCompleteBtn').html('<i class="fas fa-check"></i> Selesai').addClass('disabled');
                    showSuccessMessage('Video otomatis ditandai selesai!');
                }
            }
        }
    });
}

function markVideoComplete() {
    $.ajax({
        url: '{{ route("courses.video.complete", [$course->course_id, $courseVideo->course_video_id]) }}',
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                $('#markCompleteBtn').html('<i class="fas fa-check"></i> Selesai').addClass('disabled');
                showSuccessMessage(response.message);
                
                // Update sidebar progress
                updateSidebarProgress();
                
                // Show section reviews if any
                if (response.has_section_reviews && response.section_reviews.length > 0) {
                    showSectionReviews(response.section_reviews);
                }
                
                // Enable next video button
                $('#nextVideoBtn').removeClass('disabled');
            }
        }
    });
}

function updateSidebarProgress() {
    // Mark current video as completed in sidebar
    location.reload(); // Simple reload to update all progress indicators
}

function showSectionReviews(reviews) {
    // Show section review modal or alert
    let reviewsHtml = '<div class="alert alert-info"><h6>🎉 Selamat! Anda telah menyelesaikan section ini!</h6>';
    reviews.forEach(function(review) {
        reviewsHtml += '<h6>' + review.judul_review + '</h6>';
        reviewsHtml += '<div>' + review.konten_review + '</div>';
    });
    reviewsHtml += '</div>';
    
    // Insert after video info card
    $('.card.shadow.mb-4').eq(1).after(reviewsHtml);
}

function showSuccessMessage(message) {
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: message,
        timer: 2000,
        showConfirmButton: false
    });
}

// Clean up interval when leaving page
$(window).on('beforeunload', function() {
    if (watchTimeInterval) {
        clearInterval(watchTimeInterval);
    }
});
</script>
@endpush
