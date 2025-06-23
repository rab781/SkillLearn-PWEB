@extends('layouts.app')

@section('title', $courseVideo->vidio->judul . ' - ' . $course->nama_course)

@push('styles')
<style>
.quiz-item {
    transition: all 0.3s ease;
}

.quiz-item:hover {
    transform: translateX(2px);
}

.quiz-option {
    transition: all 0.2s ease;
}

.quiz-option:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

.quiz-option input[type="radio"]:checked + span {
    font-weight: 600;
    color: #4f46e5;
}

/* Custom styles for SweetAlert2 Quiz */
.quiz-popup {
    border-radius: 16px !important;
}

.quiz-html-container {
    padding: 0 !important;
    margin: 0 !important;
}

/* SweetAlert2 footer styling */
.swal2-footer {
    border-top: 1px solid #e5e7eb !important;
    background-color: #f9fafb !important;
    padding: 1rem !important;
}

/* Override SweetAlert2 button styles */
.swal2-footer button {
    border: none !important;
    border-radius: 8px !important;
    padding: 8px 16px !important;
    font-size: 14px !important;
    font-weight: 500 !important;
    transition: all 0.2s ease !important;
    cursor: pointer !important;
}

.swal2-footer button:hover {
    transform: translateY(-1px) !important;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06) !important;
}

/* Quiz indicator badges */
.quiz-indicator {
    background: linear-gradient(45deg, #8b5cf6, #a855f7);
    color: white;
    font-size: 0.75rem;
    padding: 2px 6px;
    border-radius: 12px;
    font-weight: 600;
}

/* Final quiz styling */
.final-quiz-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

/* Mobile responsive adjustments */
@media (max-width: 768px) {
    .quiz-item {
        padding: 12px;
    }

    .quiz-item h5 {
        font-size: 13px;
    }

    .quiz-item .text-xs {
        font-size: 11px;
    }
}

.quiz-option {
    transition: all 0.2s ease;
}

.quiz-option:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

.quiz-option input[type="radio"]:checked + span {
    font-weight: 600;
    color: #4f46e5;
}

/* Custom styles for SweetAlert2 Quiz */
.quiz-popup {
    border-radius: 16px !important;
}

.quiz-html-container {
    padding: 0 !important;
    margin: 0 !important;
}

/* SweetAlert2 footer styling */
.swal2-footer {
    border-top: 1px solid #e5e7eb !important;
    background-color: #f9fafb !important;
    padding: 1rem !important;
}

/* Override SweetAlert2 button styles */
.swal2-footer button {
    border: none !important;
    border-radius: 8px !important;
    padding: 8px 16px !important;
    font-size: 14px !important;
    font-weight: 500 !important;
    transition: all 0.2s ease !important;
    cursor: pointer !important;
}

.swal2-footer button:hover {
    transform: translateY(-1px) !important;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06) !important;
}

/* Quiz indicator badges */
.quiz-indicator {
    background: linear-gradient(45deg, #8b5cf6, #a855f7);
    color: white;
    font-size: 0.75rem;
    padding: 2px 6px;
    border-radius: 12px;
    font-weight: 600;
}

/* Final quiz styling */
.final-quiz-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

/* Section collapse animation */
.section-videos {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease;
}

.section-videos.expanded {
    max-height: 2000px;
}

/* Progress ring animation */
@keyframes progress-ring {
    0% { stroke-dasharray: 0 100; }
}

.progress-ring {
    animation: progress-ring 1s ease-out;
}

/* Hover effects for video items */
.video-item:hover {
    background: linear-gradient(45deg, #f3f4f6, #e5e7eb);
}

.video-item.current {
    background: linear-gradient(45deg, #dbeafe, #bfdbfe);
    border-left: 4px solid #3b82f6;
}

/* Mobile responsive adjustments */
@media (max-width: 768px) {
    .quiz-item {
        padding: 12px;
    }

    .quiz-item h5 {
        font-size: 13px;
    }

    .quiz-item .text-xs {
        font-size: 11px;
    }
}
</style>
@endpush

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
                    <button id="bookmark-btn" onclick="toggleBookmarkVideo()"
                            class="inline-flex items-center px-4 py-2 bg-white/20 hover:bg-white/30 backdrop-blur-sm rounded-lg transition-all duration-300 transform hover:scale-105">
                        <i id="bookmark-icon" class="far fa-bookmark mr-2"></i>
                        <span class="hidden sm:inline" id="bookmark-text">Bookmark</span>
                    </button>
                    <button onclick="shareVideo()"
                            class="inline-flex items-center px-4 py-2 bg-white/20 hover:bg-white/30 backdrop-blur-sm rounded-lg transition-all duration-300 transform hover:scale-105">
                        <i class="fas fa-share mr-2"></i>
                        <span class="hidden sm:inline">Share</span>
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
                                <!-- Direct YouTube Embed -->
                                <iframe id="youtube-iframe"
                                        src="https://www.youtube.com/embed/{{ $videoId }}?enablejsapi=1&autoplay=1&controls=1&rel=0&modestbranding=1"
                                        class="w-full h-full"
                                        frameborder="0"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        allowfullscreen>
                                </iframe>
                                <!-- Fallback for API tracking -->
                                <div id="youtube-player" style="display:none;"></div>
                            @else
                                <div class="flex items-center justify-center bg-gray-800 text-white h-full">
                                    <div class="text-center p-8">
                                        <i class="fas fa-video text-6xl mb-4 text-gray-400"></i>
                                        <h3 class="text-xl font-semibold mb-2">Video tidak dapat ditampilkan</h3>
                                        <p class="text-gray-300 mb-4">URL video tidak valid atau tidak didukung</p>
                                        <a href="{{ $courseVideo->vidio->url }}" target="_blank"
                                           class="inline-flex items-center px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors">
                                            <i class="fab fa-youtube mr-2"></i> Buka di YouTube
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
                                                <span class="text-xs text-gray-500">{{ $completedInSection }}/{{ $section->videos ? $section->videos->count() : 0 }} selesai</span>
                                            @else
                                                <span class="text-xs text-gray-500">0/{{ $section->videos ? $section->videos->count() : 0 }} selesai</span>
                                            @endif
                                        </div>
                                        @if($userProgress)
                                            @php
                                                $sectionVideoCount = $section->videos ? $section->videos->count() : 0;
                                                $isCompleted = $completedInSection === $sectionVideoCount && $sectionVideoCount > 0;
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
                                                    <div class="flex flex-wrap items-center mt-1 gap-2 text-xs text-gray-500">
                                                        <span class="flex items-center">
                                                            <i class="fas fa-clock mr-1"></i>{{ $video->durasi_menit }} min
                                                        </span>
                                                        @if($video->catatan_admin)
                                                            <span class="flex items-center text-yellow-600">
                                                                <i class="fas fa-sticky-note mr-1"></i>Catatan
                                                            </span>
                                                        @endif
                                                        @php
                                                            // Check for quizzes after this video
                                                            $videoQuizzes = $course->quizzes->where('vidio_vidio_id', $video->vidio_vidio_id)
                                                                ->where('tipe_quiz', 'setelah_video');
                                                        @endphp
                                                        @if($videoQuizzes->count() > 0)
                                                            <span class="flex items-center text-purple-600">
                                                                <i class="fas fa-question-circle mr-1"></i>Quiz ({{ $videoQuizzes->count() }})
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    @endforeach

                                    @php
                                        // Show section quizzes at the end of section videos
                                        $sectionQuizzes = $course->quizzes->where('section_id', $section->section_id)
                                            ->where('tipe_quiz', 'setelah_section');
                                    @endphp
                                    @if($sectionQuizzes->count() > 0)
                                        @foreach($sectionQuizzes as $quiz)
                                            <div class="quiz-item bg-purple-50 border-l-4 border-purple-500">
                                                <div class="flex items-center p-4 cursor-pointer hover:bg-purple-100 transition-colors"
                                                     onclick="openQuizFromSidebar({{ $quiz->quiz_id }}, '{{ $quiz->judul_quiz }}')">
                                                    <div class="mr-3 flex-shrink-0">
                                        @if(Auth::check())
                                            @php
                                                $quizResult = \App\Models\QuizResult::where('users_id', Auth::id())
                                                    ->where('quiz_id', $quiz->quiz_id)
                                                    ->first();
                                            @endphp
                                            @if($quizResult)
                                                @if($quizResult->nilai_total >= 60)
                                                    <i class="fas fa-check-circle text-green-500 text-lg"></i>
                                                @else
                                                    <i class="fas fa-exclamation-circle text-amber-500 text-lg"></i>
                                                @endif
                                            @else
                                                <i class="fas fa-question-circle text-purple-500 text-lg"></i>
                                            @endif
                                        @else
                                            <i class="fas fa-question-circle text-purple-500 text-lg"></i>
                                        @endif
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <h5 class="text-purple-900 text-sm font-medium">
                                                            {{ $quiz->judul_quiz }}
                                                        </h5>
                                                        <div class="flex flex-wrap items-center mt-1 gap-2 text-xs text-purple-600">
                                                            <span class="flex items-center">
                                                                <i class="fas fa-clock mr-1"></i>{{ $quiz->durasi_menit ?? 0 }} min
                                                            </span>
                                                            <span class="flex items-center">
                                                                <i class="fas fa-list mr-1"></i>{{ $quiz->questions->count() }} soal
                                                            </span>
                                                            @if($userProgress)
                                                                @php
                                                                    $quizResult = \App\Models\QuizResult::where('users_id', Auth::id())
                                                                        ->where('quiz_id', $quiz->quiz_id)
                                                                        ->first();
                                                                @endphp
                                                                @if($quizResult)
                                                                    <span class="px-2 py-1 rounded-full text-xs font-medium
                                                                        {{ $quizResult->nilai_total >= 60 ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800' }}">
                                                                        {{ number_format($quizResult->nilai_total, 0) }}%
                                                                    </span>
                                                                @else
                                                                    <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded-full text-xs font-medium">
                                                                        Belum dikerjakan
                                                                    </span>
                                                                @endif
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        @endforeach

                        @php
                            // Show final course quizzes at the end
                            $finalQuizzes = $course->quizzes->where('tipe_quiz', 'akhir_course');
                        @endphp
                        @if($finalQuizzes->count() > 0)
                            <div class="border-t-2 border-gradient-to-r from-purple-500 to-indigo-600">
                                <div class="p-4 bg-gradient-to-r from-purple-50 to-indigo-50">
                                    <h4 class="font-semibold text-purple-900 text-sm flex items-center mb-2">
                                        <i class="fas fa-graduation-cap mr-2"></i>
                                        Final Course Quiz
                                    </h4>
                                    <p class="text-xs text-purple-700">Selesaikan untuk menyelesaikan course</p>
                                </div>
                                <div class="divide-y divide-purple-100">
                                    @foreach($finalQuizzes as $quiz)
                                        <div class="quiz-item bg-gradient-to-r from-purple-50 to-indigo-50 border-l-4 border-purple-600">
                                            <div class="flex items-center p-4 cursor-pointer hover:from-purple-100 hover:to-indigo-100 transition-colors"
                                                 onclick="openQuizFromSidebar({{ $quiz->quiz_id }}, '{{ $quiz->judul_quiz }}')">
                                                <div class="mr-3 flex-shrink-0">
                                                    @if($userProgress)
                                                        @php
                                                            $quizResult = \App\Models\QuizResult::where('users_id', Auth::id())
                                                                ->where('quiz_id', $quiz->quiz_id)
                                                                ->first();
                                                        @endphp
                                                        @if($quizResult)
                                                            @if($quizResult->nilai_total >= 60)
                                                                <i class="fas fa-trophy text-yellow-500 text-lg"></i>
                                                            @else
                                                                <i class="fas fa-exclamation-circle text-amber-500 text-lg"></i>
                                                            @endif
                                                        @else
                                                            <i class="fas fa-graduation-cap text-purple-600 text-lg"></i>
                                                        @endif
                                                    @else
                                                        <i class="fas fa-graduation-cap text-purple-600 text-lg"></i>
                                                    @endif
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <h5 class="text-purple-900 text-sm font-semibold">
                                                        {{ $quiz->judul_quiz }}
                                                    </h5>
                                                    <div class="flex flex-wrap items-center mt-1 gap-2 text-xs text-purple-700">
                                                        <span class="flex items-center">
                                                            <i class="fas fa-clock mr-1"></i>{{ $quiz->durasi_menit ?? 0 }} min
                                                        </span>
                                                        <span class="flex items-center">
                                                            <i class="fas fa-list mr-1"></i>{{ $quiz->questions->count() }} soal
                                                        </span>
                                                        @if($userProgress)
                                                            @php
                                                                $quizResult = null;
                                                                if (Auth::check()) {
                                                                    $quizResult = \App\Models\QuizResult::where('users_id', Auth::id())
                                                                        ->where('quiz_id', $quiz->quiz_id)
                                                                        ->first();
                                                                }
                                                            @endphp
                                                            @if($quizResult)
                                                                <span class="px-2 py-1 rounded-full text-xs font-medium
                                                                    {{ $quizResult->nilai_total >= 60 ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800' }}">
                                                                    {{ number_format($quizResult->nilai_total, 0) }}%
                                                                </span>
                                                            @else
                                                                <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded-full text-xs font-medium">
                                                                    Belum dikerjakan
                                                                </span>
                                                            @endif
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
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
                    <div class="border-b border-gray-100 last:border-b-0">
                        <div class="p-4 bg-gray-50 cursor-pointer" onclick="toggleSection('mobile-section-{{ $section->section_id }}')">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="font-semibold text-gray-800 text-sm">{{ $section->judul_section }}</h4>
                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ $section->videos ? $section->videos->count() : 0 }} video
                                        @php
                                            $sectionQuizzes = $section->quizzes ?? collect();
                                        @endphp
                                        @if($sectionQuizzes->count() > 0)
                                            • {{ $sectionQuizzes->count() }} quiz
                                        @endif
                                    </p>
                                </div>
                                <i class="fas fa-chevron-down transition-transform duration-200" id="arrow-mobile-{{ $section->section_id }}"></i>
                            </div>
                        </div>
                        <div id="mobile-section-{{ $section->section_id }}" style="display: none;">
                            <div class="divide-y divide-gray-50">
                                @if($section->videos && $section->videos->count() > 0)
                                    @foreach($section->videos as $sectionVideo)
                                        <div class="video-item p-4 cursor-pointer transition-colors
                                            {{ $sectionVideo->course_video_id == $courseVideo->course_video_id ? 'bg-blue-50 border-l-4 border-blue-500' : 'hover:bg-gray-50' }}"
                                             onclick="window.location.href='{{ route('courses.video', ['courseId' => $course->course_id, 'videoId' => $sectionVideo->course_video_id]) }}'">
                                            <div class="flex items-center">
                                                <div class="mr-3 flex-shrink-0">
                                                    @if($userProgress)
                                                        @php
                                                            $isVideoCompleted = \App\Models\UserVideoProgress::where('user_id', Auth::id())
                                                                ->where('vidio_vidio_id', $sectionVideo->vidio_vidio_id)
                                                                ->where('course_id', $course->course_id)
                                                                ->where('is_completed', true)
                                                                ->exists();
                                                        @endphp
                                                        @if($isVideoCompleted)
                                                            <i class="fas fa-check-circle text-green-500 text-lg"></i>
                                                        @elseif($sectionVideo->course_video_id == $courseVideo->course_video_id)
                                                            <i class="fas fa-play-circle text-blue-500 text-lg"></i>
                                                        @else
                                                            <i class="fas fa-play-circle text-gray-400 text-lg"></i>
                                                        @endif
                                                    @else
                                                        @if($sectionVideo->course_video_id == $courseVideo->course_video_id)
                                                            <i class="fas fa-play-circle text-blue-500 text-lg"></i>
                                                        @else
                                                            <i class="fas fa-play-circle text-gray-400 text-lg"></i>
                                                        @endif
                                                    @endif
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <h5 class="text-gray-800 text-sm font-medium leading-tight mb-1">
                                                        {{ $sectionVideo->vidio->judul }}
                                                    </h5>
                                                    <div class="flex items-center text-xs text-gray-500">
                                                        <i class="fas fa-clock mr-1"></i>
                                                        {{ $sectionVideo->vidio->durasi_menit ?? $sectionVideo->durasi_menit ?? 0 }} menit
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif

                                @if($section->quizzes->count() > 0)
                                    @foreach($section->quizzes as $quiz)
                                        <div class="quiz-item p-4 cursor-pointer transition-colors hover:bg-purple-50 border-l-4 border-purple-500"
                                             onclick="openQuizFromSidebar({{ $quiz->quiz_id }}, '{{ $quiz->judul_quiz }}')">
                                            <div class="flex items-center">
                                                <div class="mr-3 flex-shrink-0">
                                                    @if($userProgress)
                                                        @php
                                                            $quizResult = \App\Models\QuizResult::where('users_id', Auth::id())
                                                                ->where('quiz_id', $quiz->quiz_id)
                                                                ->first();
                                                        @endphp
                                                        @if($quizResult)
                                                            @if($quizResult->nilai_total >= 60)
                                                                <i class="fas fa-check-circle text-green-500 text-lg"></i>
                                                            @else
                                                                <i class="fas fa-exclamation-circle text-amber-500 text-lg"></i>
                                                            @endif
                                                        @else
                                                            <i class="fas fa-clipboard-question text-purple-600 text-lg"></i>
                                                        @endif
                                                    @else
                                                        <i class="fas fa-clipboard-question text-purple-600 text-lg"></i>
                                                    @endif
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <h5 class="text-purple-900 text-sm font-medium leading-tight mb-1">
                                                        {{ $quiz->judul_quiz }}
                                                    </h5>
                                                    <div class="flex items-center text-xs text-purple-700">
                                                        <i class="fas fa-clock mr-1"></i>{{ $quiz->durasi_menit ?? 0 }} min
                                                        <span class="mx-2">•</span>
                                                        <i class="fas fa-list mr-1"></i>{{ $quiz->questions->count() }} soal
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach

                @php
                    // Show final course quizzes at the end
                    $finalQuizzes = $course->quizzes->where('tipe_quiz', 'akhir_course');
                @endphp
                @if($finalQuizzes->count() > 0)
                    <div class="border-t-2 border-gradient-to-r from-purple-500 to-indigo-600">
                        <div class="p-4 bg-gradient-to-r from-purple-50 to-indigo-50">
                            <h4 class="font-semibold text-purple-900 text-sm flex items-center mb-2">
                                <i class="fas fa-graduation-cap mr-2"></i>
                                Final Course Quiz
                            </h4>
                            <p class="text-xs text-purple-700">Complete to finish the course</p>
                        </div>
                        <div class="divide-y divide-purple-100">
                            @foreach($finalQuizzes as $quiz)
                                <div class="quiz-item p-4 cursor-pointer transition-colors hover:bg-purple-50 border-l-4 border-purple-600"
                                     onclick="openQuizFromSidebar({{ $quiz->quiz_id }}, '{{ $quiz->judul_quiz }}')">
                                    <div class="flex items-center">
                                        <div class="mr-3 flex-shrink-0">
                                            @if($userProgress)
                                                @php
                                                    $quizResult = \App\Models\QuizResult::where('users_id', Auth::id())
                                                        ->where('quiz_id', $quiz->quiz_id)
                                                        ->first();
                                                @endphp
                                                @if($quizResult)
                                                    @if($quizResult->nilai_total >= 60)
                                                        <i class="fas fa-trophy text-yellow-500 text-lg"></i>
                                                    @else
                                                        <i class="fas fa-exclamation-circle text-amber-500 text-lg"></i>
                                                    @endif
                                                @else
                                                    <i class="fas fa-graduation-cap text-purple-600 text-lg"></i>
                                                @endif
                                            @else
                                                <i class="fas fa-graduation-cap text-purple-600 text-lg"></i>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h5 class="text-purple-900 text-sm font-medium leading-tight mb-1">
                                                {{ $quiz->judul_quiz }}
                                            </h5>
                                            <div class="flex items-center text-xs text-purple-700">
                                                <i class="fas fa-clock mr-1"></i>{{ $quiz->durasi_menit ?? 0 }} min
                                                <span class="mx-2">•</span>
                                                <i class="fas fa-list mr-1"></i>{{ $quiz->questions->count() }} soal
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- SweetAlert2 for notifications -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- YouTube Player API -->
<script src="https://www.youtube.com/iframe_api"></script>

<script>
// Global variables
const videoId = {{ $courseVideo->course_video_id }};
const courseId = {{ $course->course_id }};
const youtubeVideoId = '{{ $videoId ?? '' }}';
let watchStartTime = Date.now();
let isSidebarOpen = false;

// Set CSRF token
window.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// YouTube Player variables
let youtubePlayer;
let youtubeWatchStartTime = 0;
let totalWatchTime = 0;
let isYouTubeTracking = false;

// Helper functions for SweetAlert2
function showLoading(message = 'Memproses...') {
    Swal.fire({
        title: message,
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        willOpen: () => {
            Swal.showLoading();
        }
    });
}

function showSuccess(title, text = '') {
    return Swal.fire({
        icon: 'success',
        title: title,
        text: text,
        timer: 2000,
        showConfirmButton: false
    });
}

function showError(message) {
    Swal.fire({
        icon: 'error',
        title: 'Error!',
        text: message
    });
}

function showInfo(message) {
    Swal.fire({
        icon: 'info',
        title: 'Info',
        text: message
    });
}

// YouTube Player API Ready
function onYouTubeIframeAPIReady() {
    console.log('YouTube API Ready');
    @if($videoId)
        try {
            youtubePlayer = new YT.Player('youtube-player', {
                height: '100%',
                width: '100%',
                videoId: '{{ $videoId }}',
                playerVars: {
                    'autoplay': 0,
                    'controls': 1,
                    'rel': 0,
                    'modestbranding': 1,
                    'enablejsapi': 1
                },
                events: {
                    'onReady': onPlayerReady,
                    'onStateChange': onPlayerStateChange,
                    'onError': onPlayerError
                }
            });
        } catch (error) {
            console.error('Error initializing YouTube player:', error);
        }
    @endif
}

function onPlayerReady(event) {
    console.log('YouTube player ready');
    // Set player size to fit container
    const container = document.getElementById('youtube-player');
    if (container) {
        container.style.width = '100%';
        container.style.height = '100%';
    }
}

function onPlayerStateChange(event) {
    switch(event.data) {
        case YT.PlayerState.PLAYING:
            if (!isYouTubeTracking) {
                youtubeWatchStartTime = Date.now();
                isYouTubeTracking = true;
                console.log('Started tracking YouTube watch time');
            }
            break;
        case YT.PlayerState.PAUSED:
        case YT.PlayerState.ENDED:
            if (isYouTubeTracking) {
                const currentWatchDuration = Math.floor((Date.now() - youtubeWatchStartTime) / 1000 / 60); // in minutes
                totalWatchTime += currentWatchDuration;
                isYouTubeTracking = false;

                // Send watch time to server
                if (currentWatchDuration > 0) {
                    updateYouTubeWatchTime(currentWatchDuration);
                }

                console.log(`YouTube watch session: ${currentWatchDuration} minutes, Total: ${totalWatchTime} minutes`);
            }

            // Auto-complete if watched 80% or more
            if (event.data === YT.PlayerState.ENDED) {
                const videoDuration = youtubePlayer.getDuration();
                const currentTime = youtubePlayer.getCurrentTime();
                const watchPercentage = (currentTime / videoDuration) * 100;

                if (watchPercentage >= 80 && !document.getElementById('markComplete').checked) {
                    document.getElementById('markComplete').checked = true;
                    toggleVideoCompletion();
                    showSuccess('Video otomatis ditandai selesai karena sudah ditonton hingga akhir!');
                }
            }
            break;
    }
}

function onPlayerError(event) {
    const playerContainer = document.querySelector('.aspect-video.bg-gray-900');
    let errorMessage = 'Terjadi kesalahan saat memuat video';

    switch(event.data) {
        case 2:
            errorMessage = 'Video ID tidak valid';
            break;
        case 5:
            errorMessage = 'Video tidak dapat diputar dalam pemutar HTML5';
            break;
        case 100:
            errorMessage = 'Video tidak ditemukan atau telah dihapus';
            break;
        case 101:
        case 150:
            errorMessage = 'Video tidak dapat diputar karena pembatasan pemilik';
            break;
        default:
            errorMessage = 'Terjadi kesalahan yang tidak diketahui';
    }

    console.error('YouTube Player Error:', event.data, errorMessage);

    if (playerContainer) {
        playerContainer.innerHTML = `
            <div class="flex items-center justify-center bg-gray-800 text-white h-full">
                <div class="text-center p-8">
                    <i class="fas fa-exclamation-triangle text-6xl mb-4 text-red-400"></i>
                    <h3 class="text-xl font-semibold mb-2">Error Memuat Video</h3>
                    <p class="text-gray-300 mb-4">${errorMessage}</p>
                    <div class="space-y-2">
                        <button onclick="location.reload()"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition-colors mr-2">
                            <i class="fas fa-redo mr-2"></i> Refresh Halaman
                        </button>
                        <button onclick="window.history.back()"
                                class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i> Kembali
                        </button>
                    </div>
                </div>
            </div>
        `;
    }

    showError(errorMessage);
}

// Update YouTube watch time to server
function updateYouTubeWatchTime(duration) {
    fetch(`{{ route('courses.video.watch-time', [$course->course_id, $courseVideo->vidio->vidio_id]) }}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.csrfToken || document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            duration: duration,
            current_time: youtubePlayer ? Math.floor(youtubePlayer.getCurrentTime()) : 0,
            video_duration: youtubePlayer ? Math.floor(youtubePlayer.getDuration()) : {{ $courseVideo->durasi_menit * 60 }},
            source: 'youtube'
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('YouTube watch time updated successfully');
        }
    })
    .catch(error => {
        console.error('Error updating YouTube watch time:', error);
    });
}

// Periodically save YouTube watch time (every 2 minutes)
let youtubeWatchTimeInterval = setInterval(() => {
    if (isYouTubeTracking && youtubePlayer && youtubePlayer.getPlayerState() === YT.PlayerState.PLAYING) {
        const currentWatchDuration = Math.floor((Date.now() - youtubeWatchStartTime) / 1000 / 60);
        if (currentWatchDuration >= 2) { // At least 2 minutes watched
            totalWatchTime += currentWatchDuration;
            updateYouTubeWatchTime(currentWatchDuration);
            youtubeWatchStartTime = Date.now(); // Reset start time
        }
    }
}, 120000); // Every 2 minutes

// Cleanup on page unload
window.addEventListener('beforeunload', () => {
    if (isYouTubeTracking && youtubeWatchStartTime > 0) {
        const finalWatchDuration = Math.floor((Date.now() - youtubeWatchStartTime) / 1000 / 60);
        if (finalWatchDuration > 0) {
            // Use sendBeacon for reliable final update
            const data = new FormData();
            data.append('duration', finalWatchDuration);
            data.append('current_time', youtubePlayer ? Math.floor(youtubePlayer.getCurrentTime()) : 0);
            data.append('video_duration', youtubePlayer ? Math.floor(youtubePlayer.getDuration()) : {{ $courseVideo->durasi_menit * 60 }});
            data.append('source', 'youtube');
            data.append('_token', window.csrfToken || document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            navigator.sendBeacon(`{{ route('courses.video.watch-time', [$course->course_id, $courseVideo->vidio->vidio_id]) }}`, data);
        }
    }
    clearInterval(youtubeWatchTimeInterval);
});

// Video completion toggle with SweetAlert2
function toggleVideoCompletion() {
    const checkbox = document.getElementById('markComplete');
    const isCompleted = checkbox.checked;

    // Show loading
    showLoading(isCompleted ? 'Menandai sebagai selesai...' : 'Menandai sebagai belum selesai...');

    fetch(`{{ route('courses.video.complete', [$course->course_id, $courseVideo->course_video_id]) }}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.csrfToken || document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            is_completed: isCompleted
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            if (isCompleted) {
                showSuccess('Video Selesai!', 'Video telah ditandai sebagai selesai').then(() => {
                    // Check for quizzes after completing video
                    checkAndShowVideoQuiz();

                    // Auto-advance to next video if available and no quiz to take
                    @if($navigation['next'])
                        setTimeout(() => {
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
                        }, 1000);
                    @endif
                });
            } else {
                showSuccess('Marking berhasil dihapus');
            }

            // Update progress display if available
            updateProgressDisplay(data.progress_percentage);
        } else {
            showError(data.message || 'Failed to update video status');
            // Revert checkbox state
            checkbox.checked = !isCompleted;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showError('Terjadi kesalahan saat mengupdate status video');
        // Revert checkbox state
        checkbox.checked = !isCompleted;
    });
}

// Check bookmark status
function checkVideoBookmark() {
    const icon = document.getElementById('bookmark-icon');
    const bookmarkText = document.getElementById('bookmark-text');

    fetch(`/web/bookmark/check/{{ $course->course_id }}`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.bookmarked) {
            icon.className = 'fas fa-bookmark text-yellow-400 mr-2';
            if (bookmarkText) bookmarkText.textContent = 'Bookmarked';
        } else {
            icon.className = 'far fa-bookmark mr-2';
            if (bookmarkText) bookmarkText.textContent = 'Bookmark';
        }
    })
    .catch(error => {
        console.error('Error checking bookmark status:', error);
    });
}

// Bookmark video function with SweetAlert2
function toggleBookmarkVideo() {
    const icon = document.getElementById('bookmark-icon');
    const bookmarkText = document.getElementById('bookmark-text');
    const isBookmarked = icon.classList.contains('fas');

    showLoading(isBookmarked ? 'Menghapus bookmark...' : 'Menambah bookmark...');

    fetch(`/web/bookmark/course`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.csrfToken
        },
        body: JSON.stringify({
            course_id: {{ $course->course_id }}
        })
    })
    .then(response => response.json())
    .then(data => {
        Swal.close();

        if (data.success) {
            if (data.action === 'added') {
                icon.className = 'fas fa-bookmark text-yellow-400 mr-2';
                if (bookmarkText) bookmarkText.textContent = 'Bookmarked';
                showSuccess('Course berhasil di-bookmark!');
            } else {
                icon.className = 'far fa-bookmark mr-2';
                if (bookmarkText) bookmarkText.textContent = 'Bookmark';
                showSuccess('Bookmark course dihapus!');
            }
        } else {
            showError(data.message || 'Gagal mengubah bookmark');
        }
    })
    .catch(error => {
        Swal.close();
        console.error('Error:', error);
        showError('Terjadi kesalahan saat mengubah bookmark');
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
function toggleMobileSidebar() {
    const overlay = document.getElementById('mobileSidebarOverlay');
    const sidebar = document.getElementById('mobileSidebar');

    if (overlay.classList.contains('hidden')) {
        // Show sidebar
        overlay.classList.remove('hidden');
        setTimeout(() => {
            sidebar.style.transform = 'translateX(0)';
        }, 10);
    } else {
        // Hide sidebar
        sidebar.style.transform = 'translateX(100%)';
        setTimeout(() => {
            overlay.classList.add('hidden');
        }, 300);
    }
}

// Section toggle
function toggleSection(sectionId) {
    const section = document.getElementById(sectionId);
    const arrowId = 'arrow-' + sectionId.replace('section-', '').replace('mobile-', '');
    const arrow = document.getElementById(arrowId);

    if (section) {
        if (section.style.display === 'none' || section.style.display === '') {
            section.style.display = 'block';
            if (arrow) arrow.style.transform = 'rotate(180deg)';
        } else {
            section.style.display = 'none';
            if (arrow) arrow.style.transform = 'rotate(0deg)';
        }
    }
}

// Update progress display
function updateProgressDisplay(percentage) {
    if (percentage === undefined) return;

    // Update desktop progress bar
    const desktopProgressBar = document.querySelector('.bg-gradient-to-r.from-blue-500.to-purple-600');
    const desktopProgressText = document.querySelector('.text-blue-100');

    if (desktopProgressBar) {
        desktopProgressBar.style.width = percentage + '%';
    }
    if (desktopProgressText && desktopProgressText.textContent.includes('%')) {
        desktopProgressText.textContent = Math.round(percentage) + '%';
    }

    // Update mobile progress bar
    const mobileProgressBar = document.querySelector('#mobileSidebar .bg-white.rounded-full.h-2');
    const mobileProgressText = document.querySelector('#mobileSidebar .text-sm.font-semibold');

    if (mobileProgressBar) {
        mobileProgressBar.style.width = percentage + '%';
    }
    if (mobileProgressText) {
        mobileProgressText.textContent = Math.round(percentage) + '%';
    }
}

// Quiz functions
function openQuizFromSidebar(quizId, quizTitle) {
    Swal.fire({
        icon: 'question',
        title: 'Mulai Quiz',
        text: `Apakah Anda siap untuk mengerjakan quiz: ${quizTitle}?`,
        showCancelButton: true,
        confirmButtonText: 'Ya, Mulai Quiz',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#3b82f6'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = `/courses/{{ $course->course_id }}/quiz/${quizId}`;
        }
    });
}

function checkAndShowVideoQuiz() {
    // Check if there are video-specific quizzes
    @if($courseVideo->quizzes && $courseVideo->quizzes->count() > 0)
        const quizzes = @json($courseVideo->quizzes);
        if (quizzes.length > 0) {
            const quiz = quizzes[0]; // Take first quiz

            Swal.fire({
                icon: 'info',
                title: 'Quiz Tersedia',
                text: `Ada quiz untuk video ini: ${quiz.judul_quiz}. Mau mengerjakan sekarang?`,
                showCancelButton: true,
                confirmButtonText: 'Ya, Kerjakan Quiz',
                cancelButtonText: 'Nanti Saja',
                confirmButtonColor: '#3b82f6'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `/courses/{{ $course->course_id }}/quiz/${quiz.quiz_id}`;
                }
            });
        }
    @endif
}

// Auto-advance to next video
function nextVideo() {
    @if($navigation['next'])
        window.location.href = "{{ route('courses.video', ['courseId' => $course->course_id, 'videoId' => $navigation['next']->course_video_id]) }}";
    @else
        showInfo('Ini adalah video terakhir dalam course');
    @endif
}

function previousVideo() {
    @if($navigation['previous'])
        window.location.href = "{{ route('courses.video', ['courseId' => $course->course_id, 'videoId' => $navigation['previous']->course_video_id]) }}";
    @else
        showInfo('Ini adalah video pertama dalam course');
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

// Improved video player initialization
function initializeVideoPlayer() {
    const iframe = document.getElementById('youtube-iframe');
    const playerContainer = document.querySelector('.aspect-video.bg-gray-900');

    if (!iframe || !playerContainer) {
        console.warn('Video player elements not found');
        return;
    }

    // Add loading indicator
    if (!iframe.src || iframe.src === 'about:blank') {
        playerContainer.innerHTML = `
            <div class="flex items-center justify-center bg-gray-800 text-white h-full">
                <div class="text-center p-8">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-white mx-auto mb-4"></div>
                    <h3 class="text-lg font-semibold mb-2">Memuat Video...</h3>
                    <p class="text-gray-300">Mohon tunggu sebentar</p>
                </div>
            </div>
        `;
    }

    // Handle iframe load event
    iframe.addEventListener('load', function() {
        console.log('Video iframe loaded successfully');
    });

    iframe.addEventListener('error', function() {
        console.error('Video iframe failed to load');
        playerContainer.innerHTML = `
            <div class="flex items-center justify-center bg-gray-800 text-white h-full">
                <div class="text-center p-8">
                    <i class="fas fa-exclamation-triangle text-6xl mb-4 text-red-400"></i>
                    <h3 class="text-xl font-semibold mb-2">Gagal Memuat Video</h3>
                    <p class="text-gray-300 mb-4">Video tidak dapat dimuat. Silakan coba refresh halaman.</p>
                    <button onclick="location.reload()"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition-colors">
                        <i class="fas fa-redo mr-2"></i> Refresh Halaman
                    </button>
                </div>
            </div>
        `;
    });
}

// Initialize video player when page loads
document.addEventListener('DOMContentLoaded', function() {
    // Initialize CSRF token
    window.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Check bookmark status
    if (typeof checkVideoBookmark === 'function') {
        checkVideoBookmark();
    }

    // Close mobile sidebar when overlay is clicked
    const overlay = document.getElementById('mobileSidebarOverlay');
    if (overlay) {
        overlay.addEventListener('click', function(e) {
            if (e.target === overlay) {
                toggleMobileSidebar();
            }
        });
    }

    // Initialize section states (show first section by default)
    const firstSectionDesktop = document.querySelector('[id^="section-"]');
    const firstSectionMobile = document.querySelector('[id^="mobile-section-"]');

    if (firstSectionDesktop) {
        firstSectionDesktop.style.display = 'block';
        const sectionId = firstSectionDesktop.id.replace('section-', '');
        const arrow = document.getElementById('arrow-' + sectionId);
        if (arrow) arrow.style.transform = 'rotate(180deg)';
    }

    if (firstSectionMobile) {
        firstSectionMobile.style.display = 'block';
        const sectionId = firstSectionMobile.id.replace('mobile-section-', '');
        const arrow = document.getElementById('arrow-mobile-' + sectionId);
        if (arrow) arrow.style.transform = 'rotate(180deg)';
    }

    // Add keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Space or K to play/pause (if YouTube player is available)
        if ((e.code === 'Space' || e.code === 'KeyK') && youtubePlayer && typeof youtubePlayer.getPlayerState === 'function') {
            e.preventDefault();
            const state = youtubePlayer.getPlayerState();
            if (state === YT.PlayerState.PLAYING) {
                youtubePlayer.pauseVideo();
            } else if (state === YT.PlayerState.PAUSED) {
                youtubePlayer.playVideo();
            }
        }

        // J for previous video
        if (e.code === 'KeyJ' && !e.ctrlKey && !e.altKey) {
            e.preventDefault();
            previousVideo();
        }

        // L for next video
        if (e.code === 'KeyL' && !e.ctrlKey && !e.altKey) {
            e.preventDefault();
            nextVideo();
        }

        // M for mark complete
        if (e.code === 'KeyM' && !e.ctrlKey && !e.altKey) {
            e.preventDefault();
            const checkbox = document.getElementById('markComplete');
            if (checkbox) {
                checkbox.checked = !checkbox.checked;
                toggleVideoCompletion();
            }
        }

        // B for bookmark
        if (e.code === 'KeyB' && !e.ctrlKey && !e.altKey) {
            e.preventDefault();
            toggleBookmarkVideo();
        }

        // S for share
        if (e.code === 'KeyS' && !e.ctrlKey && !e.altKey) {
            e.preventDefault();
            shareVideo();
        }

        // Escape to close mobile sidebar
        if (e.code === 'Escape') {
            const overlay = document.getElementById('mobileSidebarOverlay');
            if (overlay && !overlay.classList.contains('hidden')) {
                toggleMobileSidebar();
            }
        }
    });

    // Add a delay to ensure YouTube API is loaded
    setTimeout(() => {
        if (typeof onYouTubeIframeAPIReady === 'function') {
            console.log('Initializing YouTube player...');
        }
    }, 500);

    console.log('Video page initialized successfully');
});
</script>
@endpush
