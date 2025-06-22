@extends('layouts.app')

@section('title', $course->nama_course . ' - SkillLearn')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50">
    <!-- Course Hero Section -->
    <div class="relative bg-gradient-to-r from-blue-600 via-purple-600 to-blue-800 text-white overflow-hidden">
        <div class="absolute inset-0 bg-black opacity-20"></div>
        <div class="absolute inset-0">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-600/90 to-purple-600/90"></div>
            <img src="{{ $course->gambar_course ?: 'https://via.placeholder.com/1200x600/4f46e5/ffffff?text=' . urlencode($course->nama_course) }}"
                 class="w-full h-full object-cover opacity-30"
                 alt="{{ $course->nama_course }}">
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <!-- Breadcrumb -->
            <nav class="flex mb-8" aria-label="Breadcrumb">
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
                            <span class="text-white font-medium">{{ Str::limit($course->nama_course, 50) }}</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Course Info -->
                <div class="lg:col-span-2">
                    <!-- Category & Level Badges -->
                    <div class="flex flex-wrap gap-3 mb-6">
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-white/20 backdrop-blur-sm text-white border border-white/30">
                            <i class="fas fa-tag mr-2"></i>{{ $course->kategori->kategori ?? 'N/A' }}
                        </span>
                        @switch($course->level)
                            @case('pemula')
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-green-500/90 text-white">
                                    <i class="fas fa-seedling mr-2"></i>Pemula
                                </span>
                                @break
                            @case('menengah')
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-yellow-500/90 text-black">
                                    <i class="fas fa-chart-line mr-2"></i>Menengah
                                </span>
                                @break
                            @case('lanjut')
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-red-500/90 text-white">
                                    <i class="fas fa-rocket mr-2"></i>Lanjut
                                </span>
                                @break
                        @endswitch
                    </div>

                    <h1 class="text-4xl lg:text-5xl font-bold mb-6 leading-tight">{{ $course->nama_course }}</h1>
                    <p class="text-xl text-blue-100 mb-8 leading-relaxed">{{ $course->deskripsi_course }}</p>

                    <!-- Course Stats -->
                    <div class="grid grid-cols-3 gap-6 mb-8">
                        <div class="text-center">
                            <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-play-circle text-2xl text-white"></i>
                            </div>
                            <div class="text-2xl font-bold text-white">{{ $course->total_video }}</div>
                            <div class="text-blue-200 text-sm">Videos</div>
                        </div>
                        <div class="text-center">
                            <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-clock text-2xl text-white"></i>
                            </div>
                            <div class="text-2xl font-bold text-white">{{ $course->total_durasi_menit ?? 0 }}</div>
                            <div class="text-blue-200 text-sm">Menit</div>
                        </div>
                        <div class="text-center">
                            <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-users text-2xl text-white"></i>
                            </div>
                            <div class="text-2xl font-bold text-white">{{ $course->userProgress->count() }}</div>
                            <div class="text-blue-200 text-sm">Students</div>
                        </div>
                    </div>
                </div>

                <!-- Course Action Card -->
                <div class="lg:col-span-1">
                    <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-2xl p-6 sticky top-8">
                        <div class="text-center mb-6">
                            <div class="w-24 h-24 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-graduation-cap text-3xl text-white"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Mulai Pembelajaran</h3>
                            <p class="text-gray-600 text-sm">Course lengkap dengan {{ $course->total_video }} video pembelajaran</p>
                        </div>

                        @auth
                            @if($userProgress)
                                <!-- Progress Bar -->
                                <div class="mb-6">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-sm font-medium text-gray-700">Progress</span>
                                        <span class="text-sm font-bold text-blue-600">{{ $userProgress->progress_percentage }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-3">
                                        <div class="bg-gradient-to-r from-blue-500 to-purple-600 h-3 rounded-full transition-all duration-300"
                                             style="width: {{ $userProgress->progress_percentage }}%"></div>
                                    </div>
                                </div>

                                <!-- Continue Learning Button -->
                                <form action="{{ route('courses.start', $course->course_id) }}" method="POST" class="w-full">
                                    @csrf
                                    <button type="submit" class="w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-bold py-4 px-6 rounded-xl transition-all duration-300 transform hover:scale-105 flex items-center justify-center shadow-lg">
                                        <i class="fas fa-play mr-3"></i>
                                        {{ $userProgress->status === 'not_started' ? 'Mulai Sekarang' : 'Lanjut Belajar' }}
                                    </button>
                                </form>
                            @else
                                <!-- Start Learning Button -->
                                <form action="{{ route('courses.start', $course->course_id) }}" method="POST" class="w-full">
                                    @csrf
                                    <button type="submit" class="w-full bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white font-bold py-4 px-6 rounded-xl transition-all duration-300 transform hover:scale-105 flex items-center justify-center shadow-lg">
                                        <i class="fas fa-rocket mr-3"></i>
                                        Mulai Pembelajaran
                                    </button>
                                </form>
                            @endif
                        @else
                            <!-- Login Required -->
                            <a href="{{ route('login') }}"
                               class="w-full bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white font-bold py-4 px-6 rounded-xl transition-all duration-300 transform hover:scale-105 flex items-center justify-center shadow-lg">
                                <i class="fas fa-sign-in-alt mr-3"></i>
                                Login untuk Belajar
                            </a>
                        @endauth

                        <!-- Additional Actions -->
                        <div class="mt-4 space-y-3">
                            <button class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 px-4 rounded-lg transition-colors flex items-center justify-center">
                                <i class="fas fa-bookmark mr-2"></i>
                                Bookmark Course
                            </button>
                            <button class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 px-4 rounded-lg transition-colors flex items-center justify-center">
                                <i class="fas fa-share mr-2"></i>
                                Share Course
                            </button>
                        </div>
                    </div>
                </div>
            </div>
    <!-- Main Content Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Course Content -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Course Curriculum -->
                <div class="bg-white rounded-2xl shadow-xl p-8">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-list-alt text-white text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">Kurikulum Course</h2>
                            <p class="text-gray-600">{{ $course->sections->count() }} section dengan {{ $course->total_video }} video pembelajaran</p>
                        </div>
                    </div>

                    <!-- Course Sections -->
                    <div class="space-y-4">
                        @forelse($course->sections as $section)
                        <div class="border border-gray-200 rounded-xl overflow-hidden hover:shadow-lg transition-shadow">
                            <button class="w-full px-6 py-4 text-left bg-gradient-to-r from-gray-50 to-blue-50 hover:from-blue-50 hover:to-purple-50 transition-colors flex items-center justify-between focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    onclick="toggleSection('section-{{ $section->section_id }}')">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center mr-4">
                                        <span class="font-semibold text-sm">{{ $section->urutan_section }}</span>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-gray-900">{{ $section->nama_section }}</h3>
                                        <p class="text-sm text-gray-600">{{ $section->videos->count() }} video</p>
                                    </div>
                                </div>
                                <i class="fas fa-chevron-down text-gray-400 transform transition-transform duration-200" id="icon-section-{{ $section->section_id }}"></i>
                            </button>

                            <div id="section-{{ $section->section_id }}" class="hidden">
                                <div class="px-6 py-4 bg-white">
                                    <div class="space-y-3">
                                        @forelse($section->videos as $video)
                                        <div class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-blue-50 transition-colors group">
                                            <div class="w-10 h-10 bg-blue-100 group-hover:bg-blue-200 rounded-lg flex items-center justify-center mr-4">
                                                <i class="fas fa-play text-blue-600 text-sm"></i>
                                            </div>
                                            <div class="flex-1">
                                                <h4 class="font-medium text-gray-900 group-hover:text-blue-700">{{ $video->vidio->judul ?? 'Video ' . $video->urutan_video }}</h4>
                                                <div class="flex items-center space-x-4 mt-1">
                                                    <span class="text-sm text-gray-500">
                                                        <i class="fas fa-clock mr-1"></i>{{ $video->durasi_menit ?? 0 }} menit
                                                    </span>
                                                    @auth
                                                        @if($userProgress && $video->video_progress && $video->video_progress->is_completed)
                                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                                <i class="fas fa-check mr-1"></i>Selesai
                                                            </span>
                                                        @endif
                                                    @endauth
                                                </div>
                                            </div>
                                            @auth
                                                @if($userProgress)
                                                    <a href="{{ route('courses.video', [$course->course_id, $video->course_video_id]) }}"
                                                       class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition-colors">
                                                        Tonton
                                                    </a>
                                                @endif
                                            @endauth
                                        </div>
                                        @empty
                                        <div class="text-center py-8">
                                            <i class="fas fa-video text-4xl text-gray-300 mb-3"></i>
                                            <p class="text-gray-500">Belum ada video di section ini</p>
                                        </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-12">
                            <i class="fas fa-folder-open text-6xl text-gray-300 mb-4"></i>
                            <h3 class="text-xl font-semibold text-gray-500 mb-2">Belum Ada Konten</h3>
                            <p class="text-gray-400">Course ini sedang dalam tahap pengembangan</p>
                        </div>
                        @endforelse
                    </div>
                </div>
                                                <i class="fas fa-redo"></i> Ulangi Course
                                            </button>
                                        </form>
                                        <a href="{{ route('courses.progress', $course->course_id) }}"
                                           class="btn btn-outline-primary btn-lg">
                                            <i class="fas fa-trophy"></i> Lihat Progress
                                        </a>
                                    @else
                                        <form action="{{ route('courses.start', $course->course_id) }}" method="POST" class="d-inline" onsubmit="console.log('Submitting continue form')">
                                            @csrf

                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar - Course Content -->
        <div class="col-lg-4">
            <div class="sticky-top" style="top: 1rem;">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-list"></i> Daftar Modul
                        </h5>
                        <small class="opacity-75">{{ $course->sections->count() }} sections, {{ $course->total_video }} videos</small>
                    </div>
                    <div class="card-body p-0" style="max-height: 70vh; overflow-y: auto;">
                        @foreach($course->sections as $section)
                            <div class="section-item border-bottom">
                                <div class="p-3">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <h6 class="mb-0 fw-bold text-primary">
                                            <i class="fas fa-folder"></i> {{ $section->nama_section }}
                                        </h6>
                                        <span class="badge bg-light text-dark">{{ $section->videos->count() }} videos</span>
                                    </div>
                                    @if($section->deskripsi_section)
                                        <small class="text-muted d-block mb-2">{{ $section->deskripsi_section }}</small>
                                    @endif

                                    <!-- Section Videos -->
                                    <div class="video-list">
                                        @foreach($section->videos as $courseVideo)
                                            <div class="video-item d-flex align-items-center py-2 px-2 rounded hover-bg">
                                                <div class="me-3">
                                                    @if($userProgress)
                                                        {{-- Gunakan relasi atau data yang sudah disiapkan controller --}}
                                                        @if(isset($courseVideo->video_progress))
                                                            @if($courseVideo->video_progress->is_completed)
                                                                <i class="fas fa-check-circle text-success"></i>
                                                            @elseif($courseVideo->video_progress->completion_percentage > 0)
                                                                <i class="fas fa-play-circle text-warning"></i>
                                                            @else
                                                                <i class="fas fa-play-circle text-muted"></i>
                                                            @endif
                                                        @else
                                                            <i class="fas fa-play-circle text-muted"></i>
                                                        @endif
                                                    @else
                                                        <i class="fas fa-play-circle text-muted"></i>
                                                    @endif
                                                </div>
                                                <div class="flex-grow-1">
                                                    @if($userProgress)
                                                        <a href="{{ route('courses.video', ['courseId' => $course->course_id, 'videoId' => $courseVideo->course_video_id]) }}"
                                                           class="text-decoration-none">
                                                            <div class="fw-medium">{{ $courseVideo->vidio->judul }}</div>
                                                            <small class="text-muted">{{ $courseVideo->vidio->durasi_menit }} menit</small>
                                                        </a>
                                                    @else
                                                        <div class="fw-medium text-muted">{{ $courseVideo->vidio->judul }}</div>
                                                        <small class="text-muted">{{ $courseVideo->vidio->durasi_menit }} menit</small>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>            </div>
        </div>
    </div>
</div>

<!-- Additional Course Information Section -->
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <!-- Course Highlights Section -->
            @php
                $courseReviews = $course->quickReviews->where('tipe_review', 'tengah_course');
            @endphp
            @if($courseReviews->count() > 0)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-gradient bg-warning text-white">
                    <h5 class="mb-0 d-flex align-items-center">
                        <i class="fas fa-lightbulb me-2"></i>Course Highlights
                    </h5>
                    <small class="opacity-75">Poin-poin penting yang akan Anda pelajari</small>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @foreach($courseReviews as $review)
                        <div class="col-md-6">
                            <div class="card border-warning bg-warning bg-opacity-10 h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-start">
                                        <div class="rounded-circle bg-warning bg-opacity-25 p-2 me-3">
                                            <i class="fas fa-star text-warning"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-semibold text-warning mb-2">{{ $review->judul_review }}</h6>
                                            <div class="text-dark small">{!! $review->konten_review !!}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif            <!-- Course Content Details -->
            <div class="card border-0 shadow-sm">
                <div class="card-header gradient-bg text-white">
                    <h5 class="mb-0 d-flex align-items-center">
                        <i class="fas fa-book-open me-2"></i> Konten Pembelajaran Detail
                    </h5>
                    <small class="opacity-75">{{ $course->sections->count() }} sections dengan {{ $course->total_video }} video pembelajaran</small>
                </div>
                <div class="card-body p-0">
                    <!-- Course Sections Accordion -->
                    <div class="accordion accordion-flush" id="sectionsAccordion">
                        @foreach($course->sections as $section)
                        <div class="accordion-item border-0">
                            <h2 class="accordion-header">
                                <button class="accordion-button fw-semibold {{ $loop->first ? '' : 'collapsed' }}"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#collapse{{ $section->section_id }}">
                                    <div class="d-flex justify-content-between w-100 align-items-center">
                                        <div class="d-flex align-items-center">
                                            <span class="badge bg-primary rounded-pill me-3">{{ $section->urutan_section }}</span>
                                            <div>
                                                <strong class="text-primary">{{ $section->nama_section }}</strong>
                                                <div class="small text-muted mt-1">
                                                    <i class="fas fa-play-circle me-1"></i>{{ $section->videos->count() }} videos
                                                    <i class="fas fa-clock ms-2 me-1"></i>{{ $section->getTotalDuration() ?? '0' }} menit
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </button>
                            </h2>
                            <div id="collapse{{ $section->section_id }}"
                                 class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}"
                                 data-bs-parent="#sectionsAccordion">
                                <div class="accordion-body bg-light">
                                    @if($section->deskripsi_section)
                                        <div class="alert alert-light border-primary mb-3">
                                            <i class="fas fa-info-circle text-primary me-2"></i>
                                            {{ $section->deskripsi_section }}
                                        </div>
                                    @endif

                                    <!-- Videos in this section -->
                                    @if($section->videos->count() > 0)
                                        <div class="row g-3">
                                            @foreach($section->videos as $courseVideo)
                                            <div class="col-12">
                                                <div class="card border-0 shadow-sm video-card hover-shadow">
                                                    <div class="card-body p-3">
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <div class="d-flex align-items-center flex-grow-1">
                                                                <div class="me-3">
                                                                    <span class="badge bg-gradient bg-primary rounded-pill">{{ $courseVideo->urutan_video }}</span>
                                                                </div>
                                                                <div class="flex-grow-1">
                                                                    <h6 class="mb-1 fw-semibold">{{ $courseVideo->vidio->judul ?? $courseVideo->vidio->nama ?? 'Video Title' }}</h6>
                                                                    <div class="d-flex align-items-center text-muted small">
                                                                        <i class="fas fa-clock me-1"></i>
                                                                        <span class="me-3">{{ $courseVideo->vidio->durasi_menit ?? $courseVideo->durasi_menit ?? 0 }} menit</span>

                                                                        @if($userProgress && $userProgress->status !== 'not_started')
                                                                            {{-- Menggunakan relasi yang sudah ada daripada query langsung --}}
                                                                            @if(isset($courseVideo->video_progress) && $courseVideo->video_progress->is_completed)
                                                                                <span class="badge bg-success">
                                                                                    <i class="fas fa-check-circle me-1"></i>Selesai
                                                                                </span>
                                                                            @elseif(isset($courseVideo->video_progress) && $courseVideo->video_progress->completion_percentage > 0)
                                                                                <span class="badge bg-warning">
                                                                                    <i class="fas fa-play-circle me-1"></i>{{ number_format($courseVideo->video_progress->completion_percentage, 0) }}%
                                                                                </span>
                                                                            @else
                                                                                <span class="badge bg-light text-dark">
                                                                                    <i class="fas fa-circle me-1"></i>Belum dimulai
                                                                                </span>
                                                                            @endif
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            @if($userProgress)
                                                            <div class="ms-3">
                                                                <a href="{{ route('courses.video', [$course->course_id, $courseVideo->course_video_id]) }}"
                                                                   class="btn btn-primary btn-sm">
                                                                    <i class="fas fa-play me-1"></i>Tonton
                                                                </a>
                                                            </div>
                                                            @else
                                                            <div class="ms-3">
                                                                <button class="btn btn-outline-secondary btn-sm" disabled>
                                                                    <i class="fas fa-lock me-1"></i>Locked
                                                                </button>
                                                            </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>

                                        <!-- Section Reviews -->
                                        @php
                                            $sectionReviews = $course->quickReviews->where('section_id', $section->section_id)->where('tipe_review', 'setelah_section');
                                        @endphp
                                        @if($sectionReviews->count() > 0)
                                            <div class="mt-4">
                                                <div class="card border-warning bg-warning bg-opacity-10">
                                                    <div class="card-body p-3">
                                                        <h6 class="text-warning mb-3">
                                                            <i class="fas fa-star me-2"></i>Quick Review - Section {{ $section->urutan_section }}
                                                        </h6>
                                                        <div class="row g-2">
                                                            @foreach($sectionReviews as $review)
                                                            <div class="col-md-6">
                                                                <div class="p-2 bg-white rounded border border-warning border-opacity-50">
                                                                    <small class="fw-semibold text-warning">{{ $review->judul_review }}</small>
                                                                </div>
                                                            </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @else
                                        <div class="text-center py-5">
                                            <div class="mb-3">
                                                <i class="fas fa-video fa-3x text-muted opacity-50"></i>
                                            </div>
                                            <h6 class="text-muted">Belum ada video di section ini</h6>
                                            <p class="text-muted small mb-0">Video akan segera ditambahkan</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    @if($course->sections->count() === 0)
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                    <i class="fas fa-graduation-cap fa-2x text-primary"></i>
                                </div>
                            </div>
                            <h5 class="text-primary mb-2">Course dalam Pengembangan</h5>
                            <p class="text-muted mb-0">Konten pembelajaran sedang disiapkan dengan kualitas terbaik untuk Anda!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Debugging and JavaScript Enhancements -->
<script>
// Debug: Pastikan semua form course start menggunakan POST method
document.addEventListener('DOMContentLoaded', function() {
    const courseForms = document.querySelectorAll('form[action*="start"]');
    courseForms.forEach(function(form) {
        console.log('Course start form method:', form.method); // Should be 'post'

        // Pastikan method adalah POST
        if (form.method.toLowerCase() !== 'post') {
            console.error('Form method is not POST!', form);
            form.method = 'POST';
        }

        // Add event listener untuk debugging
        form.addEventListener('submit', function(e) {
            console.log('Form submitting with method:', this.method);
            console.log('Form action:', this.action);
        });
    });
});
</script>

<style>
/* Enhanced styling for course show page */
.object-cover {
    object-fit: cover;
}

.stat-box {
    padding: 1rem;
    text-align: center;
}

.hover-bg:hover {
    background-color: #f8f9fa;
}

.video-item {
    transition: background-color 0.2s ease;
    margin: 2px 0;
}

.section-item:last-child {
    border-bottom: none;
}

.sticky-top {
    z-index: 1020;
}

.gradient-bg {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.accordion-button:not(.collapsed) {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
}

.accordion-button {
    transition: all 0.3s ease;
    border: none;
    box-shadow: none;
}

.accordion-button:focus {
    box-shadow: none;
    border: none;
}

.video-card {
    transition: all 0.3s ease;
    border: 1px solid rgba(102, 126, 234, 0.1) !important;
}

.hover-shadow:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.15) !important;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #5a67d8 0%, #6b46c1 100%);
    transform: translateY(-1px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
}

.badge.bg-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
}

.badge.bg-success {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
}

.badge.bg-warning {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%) !important;
}

.card-header.bg-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
}

.card-header.bg-gradient.bg-warning {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%) !important;
}

.accordion-item {
    border: 1px solid rgba(102, 126, 234, 0.1);
    margin-bottom: 0.5rem;
    border-radius: 0.5rem;
    overflow: hidden;
}

.accordion-body.bg-light {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%) !important;
}

.text-primary {
    color: #667eea !important;
}

.border-primary {
    border-color: rgba(102, 126, 234, 0.3) !important;
}

.fade-in {
    animation: fadeIn 0.6s ease-in-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Responsive improvements */
@media (max-width: 991.98px) {
    .sticky-top {
        position: relative !important;
        top: auto !important;
    }
}

@media (max-width: 768px) {
    .video-card .card-body {
        padding: 1rem !important;
    }

    .video-card .btn {
        font-size: 0.8rem;
        padding: 0.25rem 0.5rem;
    }
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.prose p {
    margin-bottom: 0.5rem;
}
</style>
@endsection

@push('scripts')
<script>
// Toggle section accordion
function toggleSection(sectionId) {
    const section = document.getElementById(sectionId);
    const icon = document.getElementById('icon-' + sectionId);

    if (section.classList.contains('hidden')) {
        section.classList.remove('hidden');
        icon.classList.add('rotate-180');
    } else {
        section.classList.add('hidden');
        icon.classList.remove('rotate-180');
    }
}

// Star rating functionality
document.addEventListener('DOMContentLoaded', function() {
    const stars = document.querySelectorAll('.star-rating');
    const ratingInput = document.getElementById('rating-input');

    stars.forEach((star, index) => {
        star.addEventListener('click', function() {
            const rating = this.dataset.rating;
            ratingInput.value = rating;

            // Update star colors
            stars.forEach((s, i) => {
                if (i < rating) {
                    s.classList.remove('text-gray-300');
                    s.classList.add('text-yellow-500');
                } else {
                    s.classList.remove('text-yellow-500');
                    s.classList.add('text-gray-300');
                }
            });
        });

        star.addEventListener('mouseenter', function() {
            const rating = this.dataset.rating;
            stars.forEach((s, i) => {
                if (i < rating) {
                    s.classList.add('text-yellow-400');
                }
            });
        });

        star.addEventListener('mouseleave', function() {
            stars.forEach(s => {
                s.classList.remove('text-yellow-400');
            });
        });
    });

    // Feedback form submission
    const feedbackForm = document.getElementById('feedback-form');
    if (feedbackForm) {
        feedbackForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');

            if (!ratingInput.value) {
                alert('Silakan berikan rating terlebih dahulu');
                return;
            }

            if (!formData.get('komentar').trim()) {
                alert('Silakan berikan komentar');
                return;
            }

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mengirim...';

            fetch('/api/feedback', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Terima kasih atas feedback Anda!');
                    this.reset();
                    ratingInput.value = '';
                    stars.forEach(s => {
                        s.classList.remove('text-yellow-500');
                        s.classList.add('text-gray-300');
                    });
                } else {
                    alert('Gagal mengirim feedback: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengirim feedback');
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-paper-plane mr-2"></i>Kirim Feedback';
            });
        });
    }
});
</script>
@endpush
