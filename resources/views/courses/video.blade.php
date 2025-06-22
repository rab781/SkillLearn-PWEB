@extends('layouts.app')

@section('title', $courseVideo->vidio->judul . ' - ' . $course->nama_course)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50">
    <!-- Video Header Section -->
    <div class="relative bg-gradient-to-r from-blue-600 via-purple-600 to-blue-800 text-white overflow-hidden py-8">
        <div class="absolute inset-0 bg-black opacity-20"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <nav class="flex mb-6" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('courses.index') }}" class="inline-flex items-center text-blue-200 hover:text-white transition-colors">
                            <i class="fas fa-home mr-2"></i>
                            Courses
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-blue-300 mx-2"></i>
                            <a href="{{ route('courses.show', $course->course_id) }}" class="text-blue-200 hover:text-white transition-colors">
                                {{ Str::limit($course->nama_course, 30) }}
                            </a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-blue-300 mx-2"></i>
                            <span class="text-white font-medium">{{ Str::limit($courseVideo->vidio->judul, 40) }}</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <!-- Video Title & Course Info -->
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                <div class="mb-4 lg:mb-0">
                    <h1 class="text-2xl lg:text-3xl font-bold mb-2">{{ $courseVideo->vidio->judul }}</h1>
                    <p class="text-blue-200 flex items-center">
                        <i class="fas fa-play-circle mr-2"></i>
                        {{ $course->nama_course }} - {{ $courseVideo->section->nama_section }}
                    </p>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-wrap gap-3">
                    <button onclick="toggleBookmarkVideo()"
                            class="inline-flex items-center px-4 py-2 bg-white/20 hover:bg-white/30 backdrop-blur-sm rounded-lg transition-all duration-300 transform hover:scale-105">
                        <i id="bookmark-icon" class="far fa-bookmark mr-2"></i>
                        <span class="hidden sm:inline">Bookmark</span>
                    </button>
                    <button onclick="shareVideo()"
                            class="inline-flex items-center px-4 py-2 bg-white/20 hover:bg-white/30 backdrop-blur-sm rounded-lg transition-all duration-300 transform hover:scale-105">
                        <i class="fas fa-share mr-2"></i>
                        <span class="hidden sm:inline">Share</span>
                    </button>
                    <button onclick="showQuickReview()"
                            class="inline-flex items-center px-4 py-2 bg-yellow-500/80 hover:bg-yellow-500 backdrop-blur-sm rounded-lg transition-all duration-300 transform hover:scale-105">
                        <i class="fas fa-lightbulb mr-2"></i>
                        <span class="hidden sm:inline">Review</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Video Player & Content - 3/4 width -->
            <div class="lg:col-span-3 space-y-6">
                <!-- Video Player -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <div class="relative">
                        <div class="aspect-video bg-gray-900">
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
                                        src="https://www.youtube.com/embed/{{ $videoId }}?enablejsapi=1&rel=0&autoplay=1&modestbranding=1"
                                        title="{{ $courseVideo->vidio->judul }}"
                                        frameborder="0"
                                        allowfullscreen
                                        class="w-full h-full">
                                </iframe>
                            @else
                                <div class="flex items-center justify-center bg-gray-800 text-white h-full">
                                    <div class="text-center p-8">
                                        <i class="fas fa-video text-6xl mb-4 text-gray-400"></i>
                                        <h3 class="text-xl font-semibold mb-2">Video tidak dapat ditampilkan</h3>
                                        <p class="text-gray-300 mb-4">Silakan klik tombol di bawah untuk menonton di YouTube</p>
                                        <a href="{{ $courseVideo->vidio->url }}" target="_blank"
                                           class="inline-flex items-center px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors">
                                            <i class="fab fa-youtube mr-2"></i> Tonton di YouTube
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Video Information -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $courseVideo->vidio->judul }}</h2>
                            <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600">
                                <span class="flex items-center">
                                    <i class="fas fa-clock mr-2 text-blue-500"></i>
                                    {{ $courseVideo->durasi_menit }} menit
                                </span>
                                <span class="flex items-center">
                                    <i class="fas fa-list mr-2 text-purple-500"></i>
                                    Video {{ $courseVideo->urutan_video }}
                                </span>
                                @if($courseVideo->catatan_admin)
                                    <span class="flex items-center">
                                        <i class="fas fa-sticky-note mr-2 text-yellow-500"></i>
                                        Catatan Instruktur
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Completion Toggle -->
                        <div class="mt-4 sm:mt-0">
                            <div class="flex items-center space-x-3 bg-gray-50 px-4 py-3 rounded-lg">
                                <span class="text-sm font-medium text-gray-700">Tandai selesai</span>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" id="markComplete" class="sr-only peer"
                                           {{ $videoProgress && $videoProgress->is_completed ? 'checked' : '' }}
                                           onchange="toggleVideoCompletion()">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Video Description -->
                    <div class="prose max-w-none">
                        @if($courseVideo->vidio->deskripsi)
                            <div class="text-gray-700 leading-relaxed">
                                {!! nl2br(e($courseVideo->vidio->deskripsi)) !!}
                            </div>
                        @else
                            <div class="text-gray-700 leading-relaxed">
                                <p>Video pembelajaran ini merupakan bagian dari course <strong>{{ $course->nama_course }}</strong>. Silakan tonton video dengan seksama dan jangan lupa untuk menandai sebagai selesai setelah menonton.</p>
                            </div>
                        @endif

                        @if($courseVideo->catatan_admin)
                            <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                                <h4 class="text-yellow-800 font-semibold mb-2 flex items-center">
                                    <i class="fas fa-sticky-note mr-2"></i>Catatan Instruktur
                                </h4>
                                <p class="text-yellow-700">{{ $courseVideo->catatan_admin }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Navigation Controls -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
                        <!-- Previous Video -->
                        <div>
                            @if($navigation['previous'])
                                <a href="{{ route('courses.video', ['courseId' => $course->course_id, 'videoId' => $navigation['previous']->course_video_id]) }}"
                                   class="inline-flex items-center px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-all duration-300 transform hover:scale-105">
                                    <i class="fas fa-chevron-left mr-2"></i>
                                    <div class="text-left">
                                        <div class="text-xs text-gray-500">Sebelumnya</div>
                                        <div class="font-medium">{{ Str::limit($navigation['previous']->vidio->judul, 25) }}</div>
                                    </div>
                                </a>
                            @else
                                <div class="text-gray-400 text-sm flex items-center">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    Video pertama
                                </div>
                            @endif
                        </div>

                        <!-- Next Video -->
                        <div>
                            @if($navigation['next'])
                                <a href="{{ route('courses.video', ['courseId' => $course->course_id, 'videoId' => $navigation['next']->course_video_id]) }}"
                                   class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-all duration-300 transform hover:scale-105">
                                    <div class="text-right">
                                        <div class="text-xs text-blue-200">Selanjutnya</div>
                                        <div class="font-medium">{{ Str::limit($navigation['next']->vidio->judul, 25) }}</div>
                                    </div>
                                    <i class="fas fa-chevron-right ml-2"></i>
                                </a>
                            @else
                                <div class="text-gray-400 text-sm flex items-center justify-end">
                                    <i class="fas fa-check-circle mr-2 text-green-500"></i>
                                    Video terakhir
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar - Course Navigation - 1/4 width -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden sticky top-8">
                    <!-- Sidebar Header -->
                    <div class="bg-gradient-to-r from-blue-500 to-purple-600 text-white p-6">
                        <h3 class="text-lg font-bold mb-4">Daftar Video</h3>

                        <!-- Progress Bar -->
                        @if($userProgress)
                            <div class="mb-2">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-sm text-blue-100">Progress</span>
                                    <span class="text-sm font-semibold">{{ number_format($userProgress->progress_percentage, 0) }}%</span>
                                </div>
                                <div class="w-full bg-white/20 rounded-full h-2">
                                    <div class="bg-white rounded-full h-2 transition-all duration-500" style="width: {{ $userProgress->progress_percentage }}%"></div>
                                </div>
                            </div>
                        @else
                            <div class="mb-2">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-sm text-blue-100">Progress</span>
                                    <span class="text-sm font-semibold">0%</span>
                                </div>
                                <div class="w-full bg-white/20 rounded-full h-2">
                                    <div class="bg-white rounded-full h-2 transition-all duration-500" style="width: 0%"></div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Course Sections -->
                    <div class="max-h-96 overflow-y-auto">
                        @foreach($course->sections as $section)
                            <div class="border-b border-gray-100 last:border-b-0">
                                <!-- Section Header -->
                                <div class="p-4 bg-gray-50 cursor-pointer hover:bg-gray-100 transition-colors"
                                     onclick="toggleSection('section-{{ $section->section_id }}')">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1">
                                            <h4 class="font-semibold text-gray-900 text-sm flex items-center">
                                                <i id="arrow-{{ $section->section_id }}" class="fas fa-chevron-down mr-2 transition-transform duration-200"></i>
                                                {{ $section->nama_section }}
                                            </h4>
                                            @if($userProgress)
                                                @php
                                                    $sectionVideoIds = $section->videos->pluck('vidio_vidio_id');
                                                    $completedInSection = $userProgress->videoProgress()
                                                        ->whereIn('vidio_vidio_id', $sectionVideoIds)
                                                        ->where('is_completed', true)
                                                        ->count();
                                                @endphp
                                                <span class="text-xs text-gray-500">{{ $completedInSection }}/{{ $section->videos->count() }} selesai</span>
                                            @else
                                                <span class="text-xs text-gray-500">0/{{ $section->videos->count() }} selesai</span>
                                            @endif
                                        </div>
                                        @if($userProgress)
                                            @php
                                                $isCompleted = $completedInSection === $section->videos->count() && $section->videos->count() > 0;
                                            @endphp
                                            @if($isCompleted)
                                                <i class="fas fa-check-circle text-green-500"></i>
                                            @endif
                                        @endif
                                    </div>
                                </div>

                                <!-- Section Videos -->
                                <div id="section-{{ $section->section_id }}" class="divide-y divide-gray-100">
                                    @foreach($section->videos as $video)
                                        <div class="video-item {{ $video->course_video_id == $courseVideo->course_video_id ? 'bg-blue-50 border-l-4 border-blue-500' : 'hover:bg-gray-50' }}">
                                            <a href="{{ route('courses.video', ['courseId' => $course->course_id, 'videoId' => $video->course_video_id]) }}"
                                               class="flex items-center p-4 group transition-colors">
                                                <div class="mr-3 flex-shrink-0">
                                                    @if($userProgress)
                                                        @php
                                                            $videoProgressItem = $userProgress->videoProgress()
                                                                ->where('vidio_vidio_id', $video->vidio_vidio_id)
                                                                ->first();
                                                        @endphp
                                                        @if($videoProgressItem && $videoProgressItem->is_completed)
                                                            <i class="fas fa-check-circle text-green-500 text-lg"></i>
                                                        @elseif($video->course_video_id == $courseVideo->course_video_id)
                                                            <i class="fas fa-play-circle text-blue-500 text-lg"></i>
                                                        @else
                                                            <i class="fas fa-circle text-gray-300 text-sm"></i>
                                                        @endif
                                                    @else
                                                        @if($video->course_video_id == $courseVideo->course_video_id)
                                                            <i class="fas fa-play-circle text-blue-500 text-lg"></i>
                                                        @else
                                                            <i class="fas fa-circle text-gray-300 text-sm"></i>
                                                        @endif
                                                    @endif
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <h5 class="text-gray-900 text-sm font-medium group-hover:text-blue-600 transition-colors">
                                                        {{ $video->vidio->judul }}
                                                    </h5>
                                                    <div class="flex items-center mt-1 space-x-2 text-xs text-gray-500">
                                                        <span>{{ $video->durasi_menit }} min</span>
                                                        @if($video->catatan_admin)
                                                            <i class="fas fa-sticky-note text-yellow-500" title="Ada catatan instruktur"></i>
                                                        @endif
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Mobile Floating Action Button -->
<div class="lg:hidden fixed bottom-6 right-6 z-50">
    <button onclick="toggleMobileSidebar()"
            class="bg-blue-600 hover:bg-blue-700 text-white p-4 rounded-full shadow-lg transition-all duration-300 transform hover:scale-110">
        <i class="fas fa-list"></i>
    </button>
</div>

<!-- Mobile Sidebar Overlay -->
<div id="mobileSidebarOverlay" class="lg:hidden fixed inset-0 bg-black bg-opacity-50 z-40 hidden">
    <div class="fixed inset-y-0 right-0 max-w-sm w-full bg-white shadow-xl transform translate-x-full transition-transform duration-300" id="mobileSidebar">
        <div class="h-full overflow-y-auto">
            <!-- Mobile Sidebar Header -->
            <div class="bg-gradient-to-r from-blue-500 to-purple-600 text-white p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold">Daftar Video</h3>
                    <button onclick="toggleMobileSidebar()" class="text-white hover:text-gray-200">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <!-- Progress Bar -->
                @if($userProgress)
                    <div class="mb-2">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm text-blue-100">Progress</span>
                            <span class="text-sm font-semibold">{{ number_format($userProgress->progress_percentage, 0) }}%</span>
                        </div>
                        <div class="w-full bg-white/20 rounded-full h-2">
                            <div class="bg-white rounded-full h-2 transition-all duration-500" style="width: {{ $userProgress->progress_percentage }}%"></div>
                        </div>
                    </div>
                @else
                    <div class="mb-2">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm text-blue-100">Progress</span>
                            <span class="text-sm font-semibold">0%</span>
                        </div>
                        <div class="w-full bg-white/20 rounded-full h-2">
                            <div class="bg-white rounded-full h-2 transition-all duration-500" style="width: 0%"></div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Mobile Course Sections -->
            <div class="divide-y divide-gray-100">
                @foreach($course->sections as $section)
                    <!-- Mobile Section Content (same as desktop) -->
                    <div class="border-b border-gray-100 last:border-b-0">
                        <div class="p-4 bg-gray-50 cursor-pointer" onclick="toggleSection('mobile-section-{{ $section->section_id }}')">
                            <!-- Same content as desktop sidebar -->
                        </div>
                        <div id="mobile-section-{{ $section->section_id }}">
                            <!-- Same video list as desktop -->
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Global variables
const videoId = {{ $courseVideo->course_video_id }};
const courseId = {{ $course->course_id }};
let watchStartTime = Date.now();
let isSidebarOpen = false;

// Video completion toggle with SweetAlert2
function toggleVideoCompletion() {
    const checkbox = document.getElementById('markComplete');
    const isCompleted = checkbox.checked;

    // Show loading
    Swal.fire({
        title: isCompleted ? 'Menandai sebagai selesai...' : 'Menandai sebagai belum selesai...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });    $.ajax({
        url: `/courses/${courseId}/video/${videoId}/complete`,
        type: 'POST',
        data: {
            is_completed: isCompleted,
            _token: window.csrfToken
        },
        success: function(data) {
            if (data.success) {
                if (isCompleted) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Video Selesai!',
                        text: 'Video telah ditandai sebagai selesai',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        // Auto-advance to next video if available
                        @if($navigation['next'])
                            Swal.fire({
                                icon: 'question',
                                title: 'Lanjut ke Video Berikutnya?',
                                text: 'Video berikutnya: {{ $navigation['next']->vidio->judul ?? 'Video Selanjutnya' }}',
                                showCancelButton: true,
                                confirmButtonText: 'Ya, Lanjut',
                                cancelButtonText: 'Tidak',
                                confirmButtonColor: '#3b82f6'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = "{{ route('courses.video', ['courseId' => $course->course_id, 'videoId' => $navigation['next']->course_video_id]) }}";
                                }
                            });
                        @endif
                    });
                } else {
                    Swal.fire({
                        icon: 'info',
                        title: 'Status Diperbarui',
                        text: 'Video ditandai sebagai belum selesai',
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
                
                // Update progress bar
                updateProgressDisplay(data.progress_percentage || 0);
            } else {
                throw new Error(data.message || 'Gagal memperbarui status');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
            // Revert checkbox state
            checkbox.checked = !isCompleted;
            
            let errorMessage = 'Terjadi kesalahan saat memperbarui status video';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }
            
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: errorMessage
            });
        }
    });
}

// Bookmark video function with SweetAlert2
function toggleBookmarkVideo() {
    const icon = document.getElementById('bookmark-icon');
    const iconMobile = document.getElementById('bookmark-icon-mobile');
    const isBookmarked = icon.classList.contains('fas');

    Swal.fire({
        title: isBookmarked ? 'Menghapus bookmark...' : 'Menambah bookmark...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    $.ajax({
        url: `/web/bookmark/video/${videoId}`,
        type: 'POST',
        data: {
            _token: window.csrfToken
        },
        success: function(data) {
            if (data.success) {
                if (data.bookmarked) {
                    icon.className = 'fas fa-bookmark text-yellow-400';
                    if (iconMobile) iconMobile.className = 'fas fa-bookmark';
                    showSuccess('Video berhasil di-bookmark!');
                } else {
                    icon.className = 'far fa-bookmark';
                    if (iconMobile) iconMobile.className = 'far fa-bookmark';
                    showSuccess('Bookmark video dihapus!');
                }
            } else {
                throw new Error(data.message || 'Gagal mengubah bookmark');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
            showError('Terjadi kesalahan saat mengubah bookmark');
        }
    });
}

// Share video function with SweetAlert2
function shareVideo() {
    const videoUrl = window.location.href;
    const videoTitle = `{{ $courseVideo->vidio->judul }}`;

    if (navigator.share) {
        navigator.share({
            title: videoTitle,
            text: `Lihat video pembelajaran: ${videoTitle}`,
            url: videoUrl
        }).catch(console.error);
    } else {
        // Fallback: copy to clipboard
        navigator.clipboard.writeText(videoUrl).then(() => {
            showSuccess('Link video berhasil disalin ke clipboard!');
        }).catch(() => {
            // Manual copy fallback
            Swal.fire({
                icon: 'info',
                title: 'Share Video',
                html: `
                    <p class="mb-3">Salin link berikut untuk berbagi video:</p>
                    <div class="input-group">
                        <input type="text" class="form-control" value="${videoUrl}" id="shareUrl" readonly>
                        <button class="btn btn-primary" onclick="copyShareUrl()">Salin</button>
                    </div>
                `,
                showConfirmButton: false,
                showCloseButton: true
            });
        });
    }
}

function copyShareUrl() {
    const input = document.getElementById('shareUrl');
    input.select();
    document.execCommand('copy');
    showSuccess('Link berhasil disalin!');
    Swal.close();
}

// Sidebar toggle for mobile
function toggleSidebar() {
    const sidebar = document.querySelector('.video-sidebar');
    if (window.innerWidth < 1024) {
        if (isSidebarOpen) {
            sidebar.style.transform = 'translateX(100%)';
            isSidebarOpen = false;
        } else {
            sidebar.style.transform = 'translateX(0)';
            isSidebarOpen = true;
        }
    }
}

// Section toggle
function toggleSection(sectionId) {
    const section = document.getElementById(sectionId);
    const arrow = document.getElementById('arrow-' + sectionId.replace('section-', ''));

    if (section.style.display === 'none' || section.style.display === '') {
        section.style.display = 'block';
        arrow.style.transform = 'rotate(180deg)';
    } else {
        section.style.display = 'none';
        arrow.style.transform = 'rotate(0deg)';
    }
}

// Update progress display
function updateProgressDisplay(percentage) {
    const progressBar = document.querySelector('.progress-bar-modern');
    const progressText = document.querySelector('.progress-bar-modern').closest('.video-sidebar').querySelector('.text-blue-400');

    if (progressBar) {
        progressBar.style.width = percentage + '%';
    }
    if (progressText) {
        progressText.textContent = Math.round(percentage) + '%';
    }
}

// Auto-advance to next video
function nextVideo() {
    @if($navigation['next'])
        window.location.href = "{{ route('courses.video', ['courseId' => $course->course_id, 'videoId' => $navigation['next']->course_video_id]) }}";
    @else
        showInfo('Ini adalah video terakhir dalam course');
    @endif
}

// Quick Review functionality
function showQuickReview() {
    @if($course->quickReviews->count() > 0)
        const reviews = @json($course->quickReviews);
        const randomReview = reviews[Math.floor(Math.random() * reviews.length)];

        Swal.fire({
            icon: 'info',
            title: '<i class="fas fa-lightbulb"></i> Quick Review',
            html: `
                <div class="text-left">
                    <h5 class="mb-3">${randomReview.judul}</h5>
                    <p>${randomReview.konten}</p>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Lanjut ke Video Berikutnya',
            cancelButtonText: 'Tutup',
            confirmButtonColor: '#3b82f6',
            width: '600px'
        }).then((result) => {
            if (result.isConfirmed) {
                nextVideo();
            }
        });
    @endif
}

// Track video watch time
function trackWatchTime() {
    const watchTime = Math.floor((Date.now() - watchStartTime) / 1000);

    fetch(`/courses/${courseId}/video/${videoId}/watch-time`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            watch_time: watchTime
        })
    }).catch(error => console.error('Watch time tracking error:', error));
}

// Auto-save watch time every 30 seconds
setInterval(trackWatchTime, 30000);

// Initialize sidebar state for mobile
document.addEventListener('DOMContentLoaded', function() {
    if (window.innerWidth < 1024) {
        const sidebar = document.querySelector('.video-sidebar');
        sidebar.style.transform = 'translateX(100%)';
        sidebar.style.position = 'fixed';
        sidebar.style.top = '0';
        sidebar.style.right = '0';
        sidebar.style.zIndex = '1050';
        sidebar.style.height = '100vh';
        sidebar.style.transition = 'transform 0.3s ease';
    }

    // Auto-expand current section
    const currentVideoItem = document.querySelector('.video-item.active');
    if (currentVideoItem) {
        const section = currentVideoItem.closest('.section-container');
        const sectionId = section.querySelector('[id^="section-"]').id;
        const videoList = document.getElementById(sectionId);
        const arrow = document.getElementById('arrow-' + sectionId.replace('section-', ''));

        if (videoList && arrow) {
            videoList.style.display = 'block';
            arrow.style.transform = 'rotate(180deg)';
        }
    }

    // Check bookmark status
    checkBookmarkStatus();

    // Add keyboard shortcuts help button
    addKeyboardShortcutsHelp();

    // Show quick review after video completion (optional)
    const completeCheckbox = document.getElementById('markComplete');
    if (completeCheckbox && completeCheckbox.checked) {
        setTimeout(() => {
            if (Math.random() < 0.3) { // 30% chance to show quick review
                showQuickReview();
            }
        }, 2000);
    }
});

// Check initial bookmark status
function checkBookmarkStatus() {
    $.ajax({
        url: `/web/bookmark/video/${videoId}/check`,
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            if (data.success) {
                const icon = document.getElementById('bookmark-icon');
                const iconMobile = document.getElementById('bookmark-icon-mobile');
                if (data.bookmarked) {
                    icon.className = 'fas fa-bookmark text-yellow-400';
                    if (iconMobile) iconMobile.className = 'fas fa-bookmark';
                } else {
                    icon.className = 'far fa-bookmark';
                    if (iconMobile) iconMobile.className = 'far fa-bookmark';
                }
            }
        },
        error: function(xhr, status, error) {
            console.error('Error checking bookmark status:', error);
        }
    });
}

// Handle page unload (save final watch time)
window.addEventListener('beforeunload', function() {
    trackWatchTime();
});

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Space bar to toggle completion
    if (e.code === 'Space' && e.target.tagName !== 'INPUT' && e.target.tagName !== 'TEXTAREA') {
        e.preventDefault();
        const checkbox = document.getElementById('markComplete');
        if (checkbox) {
            checkbox.checked = !checkbox.checked;
            toggleVideoCompletion();
        }
    }

    // Arrow keys for navigation
    if (e.code === 'ArrowLeft' && e.ctrlKey) {
        @if($navigation['previous'])
            window.location.href = "{{ route('courses.video', ['courseId' => $course->course_id, 'videoId' => $navigation['previous']->course_video_id]) }}";
        @endif
    }

    if (e.code === 'ArrowRight' && e.ctrlKey) {
        @if($navigation['next'])
            window.location.href = "{{ route('courses.video', ['courseId' => $course->course_id, 'videoId' => $navigation['next']->course_video_id]) }}";
        @endif
    }

    // B key for bookmark
    if (e.code === 'KeyB' && e.ctrlKey) {
        e.preventDefault();
        toggleBookmarkVideo();
    }

    // S key for share
    if (e.code === 'KeyS' && e.ctrlKey) {
        e.preventDefault();
        shareVideo();
    }

    // R key for quick review
    if (e.code === 'KeyR' && e.ctrlKey) {
        e.preventDefault();
        showQuickReview();
    }

    // Escape key to close sidebar on mobile
    if (e.code === 'Escape' && window.innerWidth < 1024 && isSidebarOpen) {
        toggleSidebar();
    }
});

// Show keyboard shortcuts help
function showKeyboardShortcuts() {
    Swal.fire({
        title: '<i class="fas fa-keyboard"></i> Keyboard Shortcuts',
        html: `
            <div class="text-left space-y-2 text-sm">
                <div><kbd class="px-2 py-1 bg-gray-200 rounded text-xs">Space</kbd> Toggle completion</div>
                <div><kbd class="px-2 py-1 bg-gray-200 rounded text-xs">Ctrl + ←</kbd> Previous video</div>
                <div><kbd class="px-2 py-1 bg-gray-200 rounded text-xs">Ctrl + →</kbd> Next video</div>
                <div><kbd class="px-2 py-1 bg-gray-200 rounded text-xs">Ctrl + B</kbd> Toggle bookmark</div>
                <div><kbd class="px-2 py-1 bg-gray-200 rounded text-xs">Ctrl + S</kbd> Share video</div>
                <div><kbd class="px-2 py-1 bg-gray-200 rounded text-xs">Ctrl + R</kbd> Quick review</div>
                <div><kbd class="px-2 py-1 bg-gray-200 rounded text-xs">Esc</kbd> Close sidebar (mobile)</div>
            </div>
        `,
        confirmButtonText: 'OK',
        width: '400px'
    });
}

// Add help button for keyboard shortcuts (you can add this to the header)
function addKeyboardShortcutsHelp() {
    const header = document.querySelector('.glass-card .flex.items-center.justify-between .flex.items-center.space-x-3');
    if (header) {
        const helpButton = document.createElement('div');
        helpButton.className = 'tooltip';
        helpButton.innerHTML = `
            <button onclick="showKeyboardShortcuts()"
                    class="p-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg transition-colors btn-animate">
                <i class="fas fa-keyboard"></i>
            </button>
            <span class="tooltiptext">Keyboard Shortcuts</span>
        `;
        header.appendChild(helpButton);
    }
}

// Auto-save progress periodically
function autoSaveProgress() {
    if (document.getElementById('markComplete')?.checked) {
        trackWatchTime();
    }
}

// Auto-save every 2 minutes
setInterval(autoSaveProgress, 120000);
</script>
@endpush
