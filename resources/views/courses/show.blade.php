@extends('layouts.app')

@section('title', $course->nama_course . ' - SkillLearn')

<!-- Success/Error Notifications -->
@if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            showSuccess('{{ session('success') }}');
        });
    </script>
@endif

@if(session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            showError('{{ session('error') }}');
        });
    </script>
@endif

@if(session('warning'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            showWarning('{{ session('warning') }}');
        });
    </script>
@endif

@if(session('info'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            showInfo('{{ session('info') }}');
        });
    </script>
@endif

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50">
    <!-- Course Hero Section -->
    <div class="relative bg-gradient-to-r from-blue-600 via-purple-600 to-blue-800 text-white overflow-hidden">
        <div class="absolute inset-0 bg-black opacity-20"></div>
        <div class="absolute inset-0">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-600/90 to-purple-600/90"></div>
            <img src="{{ $course->gambar_course_url }}"
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
                    <div class="grid grid-cols-4 gap-6 mb-8">
                        <div class="text-center">
                            <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-play-circle text-2xl text-white"></i>
                            </div>
                            <div class="text-2xl font-bold text-white">{{ $course->total_video }}</div>
                            <div class="text-blue-200 text-sm">Videos</div>
                        </div>
                        <div class="text-center">
                            <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-question-circle text-2xl text-white"></i>
                            </div>
                            <div class="text-2xl font-bold text-white">{{ $course->total_quizzes }}</div>
                            <div class="text-blue-200 text-sm">Quizzes</div>
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
                            <p class="text-gray-600 text-sm">Course lengkap dengan {{ $course->total_video }} video pembelajaran
                                @if($course->total_quizzes > 0)
                                dan {{ $course->total_quizzes }} quiz
                                @endif
                            </p>
                        </div>

                        @auth
                            @if($userProgress)
                                <!-- Progress Bar -->
                                <div class="mb-6">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-sm font-medium text-gray-700">Progress</span>
                                        <span class="text-sm font-bold text-blue-600">{{ $userProgress->progress_percentage }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-3 mb-3">
                                        <div class="bg-gradient-to-r from-blue-500 to-purple-600 h-3 rounded-full transition-all duration-300"
                                             style="width: {{ $userProgress->progress_percentage }}%"></div>
                                    </div>

                                    @if($course->total_video > 0 || $course->total_quizzes > 0)
                                        <div class="space-y-2">
                                            @if($course->total_video > 0)
                                                @php
                                                    $completedVideos = \App\Models\UserVideoProgress::where('user_id', Auth::id())
                                                        ->where('course_id', $course->course_id)
                                                        ->where('is_completed', true)
                                                        ->count();
                                                    $videoProgress = ($completedVideos / $course->total_video) * 100;
                                                @endphp
                                                <div class="flex items-center justify-between text-xs">
                                                    <span class="text-gray-600">📹 Video Progress</span>
                                                    <div class="flex items-center">
                                                        <span class="mr-2">{{ $completedVideos }}/{{ $course->total_video }}</span>
                                                        <div class="w-20 h-1.5 bg-gray-200 rounded-full">
                                                            <div class="bg-green-500 h-1.5 rounded-full" style="width: {{ $videoProgress }}%"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                            @if($course->total_quizzes > 0)
                                                @php
                                                    $completedQuizzes = \App\Models\QuizResult::where('users_id', Auth::id())
                                                        ->whereIn('quiz_id', $course->quizzes->pluck('quiz_id'))
                                                        ->distinct('quiz_id')
                                                        ->count();
                                                    $quizProgress = ($completedQuizzes / $course->total_quizzes) * 100;
                                                @endphp
                                                <div class="flex items-center justify-between text-xs">
                                                    <span class="text-gray-600">🎯 Quiz Progress</span>
                                                    <div class="flex items-center">
                                                        <span class="mr-2">{{ $completedQuizzes }}/{{ $course->total_quizzes }}</span>
                                                        <div class="w-20 h-1.5 bg-gray-200 rounded-full">
                                                            <div class="bg-blue-500 h-1.5 rounded-full" style="width: {{ $quizProgress }}%"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
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
                            @auth
                                <button id="bookmark-btn" onclick="toggleBookmark({{ $course->course_id }})"
                                        class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 px-4 rounded-lg transition-colors flex items-center justify-center">
                                    <i id="bookmark-icon" class="far fa-bookmark mr-2"></i>
                                    <span id="bookmark-text">Bookmark Course</span>
                                </button>
                            @else
                                <button onclick="showInfo('Silakan login terlebih dahulu untuk bookmark course', 'Login Required').then(() => { window.location.href = '{{ route('login') }}'; })"
                                        class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 px-4 rounded-lg transition-colors flex items-center justify-center">
                                    <i class="far fa-bookmark mr-2"></i>
                                    Login untuk Bookmark
                                </button>
                            @endauth
                            <button onclick="shareService('{{ $course->nama_course }}', '{{ url()->current() }}')"
                                    class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 px-4 rounded-lg transition-colors flex items-center justify-center">
                                <i class="fas fa-share mr-2"></i>
                                Share Course
                            </button>
                        </div>
                    </div>
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
                            @php
                                $totalQuizzes = $course->sections->sum(function($section) {
                                    return $section->quizzes ? $section->quizzes->count() : 0;
                                });
                            @endphp
                            <p class="text-gray-600">
                                {{ $course->sections ? $course->sections->count() : 0 }} section dengan {{ $course->total_video }} video
                                @if($totalQuizzes > 0)
                                    dan {{ $totalQuizzes }} quiz
                                @endif
                            </p>
                        </div>
                    </div>

                    <!-- Course Sections -->
                    <div class="space-y-4">
                        @forelse($course->sections as $section)
                        @php
                            $sectionVideos = $section->videos ?? collect();
                            
                            // Initialize empty collection
                            $sectionQuizzes = collect();
                            
                            // Debug the section and its quizzes
                            // First try section->quizzes relationship
                            if ($section->relationLoaded('quizzes') && $section->quizzes->count() > 0) {
                                $sectionQuizzes = $section->quizzes;
                            }
                            // Also check course->quizzes if section quizzes is empty or if we need more
                            if (isset($course->quizzes) && $course->quizzes->count() > 0) {
                                // Filter quizzes by section_id and merge with existing
                                $courseQuizzes = $course->quizzes->where('section_id', $section->section_id);
                                if ($courseQuizzes->count() > 0) {
                                    // Merge with existing quizzes if any
                                    if ($sectionQuizzes->isEmpty()) {
                                        $sectionQuizzes = $courseQuizzes;
                                    } else {
                                        // Make sure we don't have duplicates
                                        $existingIds = $sectionQuizzes->pluck('quiz_id')->toArray();
                                        $newQuizzes = $courseQuizzes->whereNotIn('quiz_id', $existingIds);
                                        $sectionQuizzes = $sectionQuizzes->concat($newQuizzes);
                                    }
                                }
                            }
                            
                            $totalItems = $sectionVideos->count() + $sectionQuizzes->count();
                        @endphp
                        <div class="border border-gray-200 rounded-xl overflow-hidden hover:shadow-lg transition-shadow">
                            <button class="w-full px-6 py-4 text-left bg-gradient-to-r from-gray-50 to-blue-50 hover:from-blue-50 hover:to-purple-50 transition-colors flex items-center justify-between focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    onclick="toggleSection('section-{{ $section->section_id }}')">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center mr-4">
                                        <span class="font-semibold text-sm">{{ $section->urutan_section }}</span>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-gray-900">{{ $section->nama_section }}</h3>
                                        <p class="text-sm text-gray-600">
                                            {{ $sectionVideos->count() }} video
                                            @if($sectionQuizzes->count() > 0)
                                                • {{ $sectionQuizzes->count() }} quiz
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <i class="fas fa-chevron-down text-gray-400 transform transition-transform duration-200" id="icon-section-{{ $section->section_id }}"></i>
                            </button>

                            <div id="section-{{ $section->section_id }}" class="hidden">
                                <div class="px-6 py-4 bg-white">
                                    <div class="space-y-3">
                                        @php
                                            // Combine videos and quizzes, then sort by order
                                            $sectionItems = collect();

                                            // Add videos
                                            foreach($sectionVideos as $video) {
                                                $sectionItems->push([
                                                    'type' => 'video',
                                                    'order' => $video->urutan_video,
                                                    'data' => $video
                                                ]);
                                            }

                                            // Add quizzes
                                            foreach($sectionQuizzes as $quiz) {
                                                // Make sure quiz model has been properly loaded with relationships
                                                if (method_exists($quiz, 'load')) {
                                                    // Always load questions and results for better reliability
                                                    $quiz->load(['questions', 'results' => function($query) {
                                                        if (Auth::check()) {
                                                            $query->where('users_id', Auth::id());
                                                        }
                                                    }]);
                                                }
                                                
                                                $sectionItems->push([
                                                    'type' => 'quiz',
                                                    'order' => $quiz->urutan ?? 999,
                                                    'data' => $quiz
                                                ]);
                                            }

                                            // Sort by order
                                            $sectionItems = $sectionItems->sortBy('order');
                                        @endphp

                                        @forelse($sectionItems as $item)
                                            @if($item['type'] === 'video')
                                                @php $video = $item['data']; @endphp
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
                                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                                Video
                                                            </span>
                                                            @auth
                                                                @php 
                                                                    $videoProgress = $video->video_progress;
                                                                @endphp
                                                                @if($userProgress && $videoProgress && $videoProgress->is_completed)
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
                                            @elseif($item['type'] === 'quiz')
                                                @php
                                                    $quiz = $item['data'];
                                                    $userQuizResult = null;
                                                    if(Auth::check()) {
                                                        $userQuizResult = $quiz->results()->where('users_id', Auth::id())->latest()->first();
                                                    }
                                                @endphp
                                                <div class="flex items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors group">
                                                    <div class="w-10 h-10 bg-purple-100 group-hover:bg-purple-200 rounded-lg flex items-center justify-center mr-4">
                                                        <i class="fas fa-question-circle text-purple-600 text-sm"></i>
                                                    </div>
                                                    <div class="flex-1">
                                                        <h4 class="font-medium text-gray-900 group-hover:text-purple-700">{{ $quiz->judul_quiz }}</h4>
                                                        <div class="flex items-center space-x-4 mt-1">
                                                            <span class="text-sm text-gray-500">
                                                                <i class="fas fa-clock mr-1"></i>{{ $quiz->durasi_menit ?? 0 }} menit
                                                            </span>
                                                            <span class="text-sm text-gray-500">
                                                                @php
                                                                    // Better handling of questions count
                                                                    $questionsCount = 0;
                                                                    if(is_object($quiz->questions)) {
                                                                        $questionsCount = $quiz->questions->count();
                                                                    } elseif(is_array($quiz->questions)) {
                                                                        $questionsCount = count($quiz->questions);
                                                                    } elseif($quiz->relationLoaded('questions')) {
                                                                        $questionsCount = $quiz->questions->count();
                                                                    } elseif(method_exists($quiz, 'questions')) {
                                                                        $questionsCount = $quiz->questions()->count();
                                                                    }
                                                                @endphp
                                                                <i class="fas fa-list mr-1"></i>{{ $questionsCount }} soal
                                                            </span>
                                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                                Quiz
                                                            </span>
                                                            @auth
                                                                @if($userQuizResult)
                                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $userQuizResult->nilai_total >= 60 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                                        <i class="fas {{ $userQuizResult->nilai_total >= 60 ? 'fa-check' : 'fa-times' }} mr-1"></i>
                                                                        {{ $userQuizResult->nilai_total }}%
                                                                    </span>
                                                                @endif
                                                            @endauth
                                                        </div>
                                                        @if($quiz->deskripsi_quiz)
                                                            <p class="text-sm text-gray-600 mt-1">{{ Str::limit($quiz->deskripsi_quiz, 100) }}</p>
                                                        @endif
                                                    </div>
                                                    @auth
                                                        @if($userProgress)
                                                            <a href="{{ route('courses.quiz.show', [$course->course_id, $quiz->quiz_id]) }}"
                                                               class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm rounded-lg transition-colors">
                                                                {{ $userQuizResult ? 'Ulangi' : 'Mulai Quiz' }}
                                                            </a>
                                                        @endif
                                                    @endauth
                                                </div>
                                            @endif
                                        @empty
                                        <div class="text-center py-8">
                                            <i class="fas fa-folder-open text-4xl text-gray-300 mb-3"></i>
                                            <p class="text-gray-500">Belum ada konten di section ini</p>
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

                <!-- Course Highlights -->
                @php
                    $courseReviews = $course->quickReviews()->where('tipe_review', 'tengah_course')->where('is_active', 1)->get();
                @endphp
                @if($courseReviews->count() > 0)
                <div class="bg-white rounded-2xl shadow-xl p-8">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-lightbulb text-white text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">Course Highlights</h2>
                            <p class="text-gray-600">Poin-poin penting dalam course ini</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        @foreach($courseReviews as $review)
                        <div class="bg-gradient-to-r from-yellow-50 to-orange-50 border-l-4 border-yellow-500 rounded-lg p-6">
                            <h3 class="font-semibold text-gray-900 mb-2">{{ $review->judul_review }}</h3>
                            <div class="text-gray-700 prose prose-sm max-w-none">
                                {!! nl2br(e($review->konten_review)) !!}
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Quiz Performance Analytics -->
                @php
                    $courseQuizzes = $course->quizzes()->with(['results.user'])->get();
                    $allQuizResults = $courseQuizzes->flatMap(function($quiz) { return $quiz->results; });
                @endphp
                @if($courseQuizzes->isNotEmpty() && $allQuizResults->isNotEmpty())
                <div class="bg-white rounded-2xl shadow-xl p-8">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-chart-pie text-white text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">Quiz Performance Analytics</h2>
                            <p class="text-gray-600">Statistik performa quiz dari seluruh peserta</p>
                        </div>
                    </div>

                    <!-- Quiz Statistics Overview -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6 text-center">
                            <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-question-circle text-white"></i>
                            </div>
                            <div class="text-2xl font-bold text-blue-600">{{ $courseQuizzes->count() }}</div>
                            <div class="text-sm text-blue-700">Total Quiz</div>
                        </div>

                        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-6 text-center">
                            <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-users text-white"></i>
                            </div>
                            <div class="text-2xl font-bold text-green-600">{{ $allQuizResults->count() }}</div>
                            <div class="text-sm text-green-700">Total Attempts</div>
                        </div>

                        <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-6 text-center">
                            <div class="w-12 h-12 bg-purple-500 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-trophy text-white"></i>
                            </div>
                            <div class="text-2xl font-bold text-purple-600">{{ round($allQuizResults->avg('nilai_total'), 1) }}%</div>
                            <div class="text-sm text-purple-700">Rata-rata Nilai</div>
                        </div>

                        <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl p-6 text-center">
                            <div class="w-12 h-12 bg-orange-500 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-check-circle text-white"></i>
                            </div>
                            <div class="text-2xl font-bold text-orange-600">{{ round(($allQuizResults->where('nilai_total', '>=', 60)->count() / $allQuizResults->count()) * 100, 1) }}%</div>
                            <div class="text-sm text-orange-700">Pass Rate</div>
                        </div>
                    </div>

                    <!-- Individual Quiz Performance -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Performance per Quiz</h3>
                        @foreach($courseQuizzes as $quiz)
                            @php
                                $quizResults = $quiz->results;
                                $quizAvg = $quizResults->isNotEmpty() ? round($quizResults->avg('nilai_total'), 1) : 0;
                                $quizPassRate = $quizResults->isNotEmpty() ? round(($quizResults->where('nilai_total', '>=', 60)->count() / $quizResults->count()) * 100, 1) : 0;
                            @endphp
                            <div class="bg-gray-50 rounded-lg p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <div>
                                        <h4 class="font-medium text-gray-900">{{ $quiz->judul_quiz }}</h4>
                                        <p class="text-sm text-gray-600">{{ $quizResults->count() }} attempts</p>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-lg font-bold {{ $quizAvg >= 80 ? 'text-green-600' : ($quizAvg >= 60 ? 'text-yellow-600' : 'text-red-600') }}">
                                            {{ $quizAvg }}%
                                        </div>
                                        <div class="text-sm text-gray-500">Rata-rata</div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div class="bg-white rounded-lg p-4">
                                        <div class="flex items-center justify-between">
                                            <span class="text-sm text-gray-600">Pass Rate</span>
                                            <span class="font-semibold {{ $quizPassRate >= 70 ? 'text-green-600' : ($quizPassRate >= 50 ? 'text-yellow-600' : 'text-red-600') }}">
                                                {{ $quizPassRate }}%
                                            </span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                                            <div class="bg-gradient-to-r from-blue-500 to-purple-600 h-2 rounded-full transition-all duration-300"
                                                 style="width: {{ $quizPassRate }}%"></div>
                                        </div>
                                    </div>

                                    <div class="bg-white rounded-lg p-4">
                                        <div class="flex items-center justify-between">
                                            <span class="text-sm text-gray-600">Highest Score</span>
                                            <span class="font-semibold text-blue-600">{{ $quizResults->isNotEmpty() ? $quizResults->max('nilai_total') : 0 }}%</span>
                                        </div>
                                        <div class="flex items-center justify-between mt-1">
                                            <span class="text-sm text-gray-600">Lowest Score</span>
                                            <span class="font-semibold text-gray-600">{{ $quizResults->isNotEmpty() ? $quizResults->min('nilai_total') : 0 }}%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Student Feedback Section -->
                @auth
                <div class="bg-white rounded-2xl shadow-xl p-8">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-comments text-white text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">Feedback Course</h2>
                            <p class="text-gray-600">Bagikan pengalaman belajar Anda</p>
                        </div>
                    </div>

                    <!-- Existing Feedback Display -->
                    <div id="existing-feedback" class="hidden mb-6">
                        <div class="bg-gradient-to-r from-blue-50 to-purple-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="font-semibold text-gray-900">Feedback Anda</h3>
                                <div class="flex space-x-2">
                                    <button onclick="editFeedback()" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                                        <i class="fas fa-edit mr-1"></i>Edit
                                    </button>
                                    <button onclick="deleteFeedback()" class="text-red-600 hover:text-red-700 text-sm font-medium">
                                        <i class="fas fa-trash mr-1"></i>Hapus
                                    </button>
                                </div>
                            </div>
                            <div class="flex items-center mb-2">
                                <div id="existing-rating-stars" class="flex space-x-1 mr-3">
                                    <!-- Stars will be filled by JavaScript -->
                                </div>
                                <span id="existing-rating-text" class="text-sm text-gray-600"></span>
                            </div>
                            <p id="existing-feedback-text" class="text-gray-700"></p>
                            <div class="text-xs text-gray-500 mt-2">
                                Diberikan pada: <span id="existing-feedback-date"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Feedback Form -->
                    <div id="feedback-form-container">
                        <form id="feedback-form" class="space-y-6">
                            @csrf
                            <input type="hidden" name="course_id" value="{{ $course->course_id }}">
                            <input type="hidden" name="feedback_id" id="feedback-id">

                            <!-- Rating -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Rating Course *</label>
                                <div class="flex space-x-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        <button type="button" class="star-rating text-3xl text-gray-300 hover:text-yellow-500 transition-colors" data-rating="{{ $i }}">
                                            <i class="fas fa-star"></i>
                                        </button>
                                    @endfor
                                </div>
                                <input type="hidden" name="rating" id="rating-input" required>
                            </div>

                            <!-- Feedback Text -->
                            <div>
                                <label for="pesan" class="block text-sm font-medium text-gray-700 mb-2">Komentar *</label>
                                <textarea id="pesan" name="pesan" rows="4" required
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                                          placeholder="Bagikan pengalaman belajar Anda, saran, atau kritik konstruktif..."></textarea>
                            </div>

                            <div class="flex space-x-3">
                                <button type="submit" id="submit-feedback-btn"
                                        class="flex-1 bg-gradient-to-r from-purple-500 to-pink-600 hover:from-purple-600 hover:to-pink-700 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-300 transform hover:scale-105">
                                    <i class="fas fa-paper-plane mr-2"></i>
                                    <span id="submit-text">Kirim Feedback</span>
                                </button>

                                <button type="button" id="cancel-edit-btn" onclick="cancelEdit()"
                                        class="hidden px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg transition-colors">
                                    <i class="fas fa-times mr-2"></i>
                                    Batal
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                @endauth
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Instructor Info -->
                <div class="bg-white rounded-2xl shadow-xl p-6">
                    <div class="text-center">
                        <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-chalkboard-teacher text-2xl text-white"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">SkillLearn Team</h3>
                        <p class="text-gray-600 text-sm mb-4">Expert Instructor</p>
                        <div class="flex justify-center space-x-4 text-sm text-gray-500">
                            <span><i class="fas fa-graduation-cap mr-1"></i>{{ $course->kategori->kategori }}</span>
                        </div>
                    </div>
                </div>

                <!-- Related Courses -->
                <div class="bg-white rounded-2xl shadow-xl p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Course Lainnya</h3>
                    <div class="space-y-4">
                        @php
                            $relatedCourses = App\Models\Course::where('kategori_kategori_id', $course->kategori_kategori_id)
                                            ->where('course_id', '!=', $course->course_id)
                                            ->active()
                                            ->limit(3)
                                            ->get();
                        @endphp
                        @forelse($relatedCourses as $relatedCourse)
                        <div class="flex space-x-4 p-4 bg-gray-50 rounded-lg hover:bg-blue-50 transition-colors">
                            <img src="{{ $relatedCourse->gambar_course_url }}"
                                 class="w-16 h-12 object-cover rounded-lg"
                                 alt="{{ $relatedCourse->nama_course }}">
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-900 text-sm line-clamp-2">{{ Str::limit($relatedCourse->nama_course, 40) }}</h4>
                                <p class="text-xs text-gray-500 mt-1">{{ $relatedCourse->total_video }} videos</p>
                                <a href="{{ route('courses.show', $relatedCourse->course_id) }}"
                                   class="text-blue-600 hover:text-blue-700 text-xs font-medium">Lihat Detail →</a>
                            </div>
                        </div>
                        @empty
                        <p class="text-gray-500 text-sm">Belum ada course terkait</p>
                        @endforelse
                    </div>
                </div>

                {{-- <!-- Course Stats -->
                <div class="bg-white rounded-2xl shadow-xl p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Statistik Course</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Total Students</span>
                            <span class="font-semibold text-gray-900">{{ $course->userProgress->count() }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Completion Rate</span>
                            <span class="font-semibold text-green-600">
                                {{ $course->userProgress->count() > 0 ? round($course->userProgress->where('status', 'completed')->count() / $course->userProgress->count() * 100) : 0 }}%
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Level</span>
                            <span class="font-semibold capitalize text-blue-600">{{ $course->level }}</span>
                        </div>
                        @php
                            $courseQuizzes = $course->quizzes()->with(['results.user'])->get();
                            $totalQuizAttempts = $courseQuizzes->sum(function($quiz) { return $quiz->results->count(); });
                            $avgScore = $courseQuizzes->isNotEmpty() ?
                                round($courseQuizzes->sum(function($quiz) {
                                    return $quiz->results->avg('nilai_total') ?? 0;
                                }) / $courseQuizzes->count(), 1) : 0;
                        @endphp
                        @if($courseQuizzes->isNotEmpty())
                            <div class="border-t pt-4 mt-4">
                                <h4 class="font-semibold text-gray-800 mb-3 flex items-center">
                                    <i class="fas fa-chart-bar mr-2 text-blue-500"></i>
                                    Quiz Statistics
                                </h4>
                                <div class="space-y-3">
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600 text-sm">Total Quiz</span>
                                        <span class="font-semibold text-gray-900">{{ $courseQuizzes->count() }}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600 text-sm">Total Attempts</span>
                                        <span class="font-semibold text-gray-900">{{ $totalQuizAttempts }}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600 text-sm">Rata-rata Nilai</span>
                                        <span class="font-semibold {{ $avgScore >= 80 ? 'text-green-600' : ($avgScore >= 60 ? 'text-yellow-600' : 'text-red-600') }}">
                                            {{ $avgScore }}%
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Quiz Reports (Show if user has quiz results) -->
                @auth
                @if($courseQuizzes->isNotEmpty())
                    @php
                        $userQuizResults = collect();
                        foreach($courseQuizzes as $quiz) {
                            $userResult = $quiz->results()->where('users_id', auth()->id())->latest()->first();
                            if($userResult) {
                                $userQuizResults->push($userResult);
                            }
                        }
                    @endphp
                    @if($userQuizResults->isNotEmpty())
                    <div class="bg-white rounded-2xl shadow-xl p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold text-gray-900">Hasil Quiz Anda</h3>
                            <span class="text-sm text-gray-500">{{ $userQuizResults->count() }} dari {{ $courseQuizzes->count() }} quiz</span>
                        </div>

                        <!-- Quiz Results Summary -->
                        <div class="mb-6 p-4 bg-gradient-to-r from-blue-50 to-purple-50 rounded-lg">
                            <div class="grid grid-cols-2 gap-4 text-center">
                                <div>
                                    <div class="text-2xl font-bold text-blue-600">
                                        {{ round($userQuizResults->avg('nilai_total'), 1) }}%
                                    </div>
                                    <div class="text-sm text-gray-600">Rata-rata</div>
                                </div>
                                <div>
                                    <div class="text-2xl font-bold {{ $userQuizResults->where('nilai_total', '>=', 60)->count() == $userQuizResults->count() ? 'text-green-600' : 'text-orange-600' }}">
                                        {{ $userQuizResults->where('nilai_total', '>=', 60)->count() }}/{{ $userQuizResults->count() }}
                                    </div>
                                    <div class="text-sm text-gray-600">Lulus</div>
                                </div>
                            </div>
                        </div>

                        <!-- Individual Quiz Results -->
                        <div class="space-y-3 max-h-64 overflow-y-auto">
                            @foreach($userQuizResults->sortByDesc('created_at') as $result)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                <div class="flex-1">
                                    <h4 class="font-medium text-gray-900 text-sm">{{ Str::limit($result->quiz->judul_quiz, 25) }}</h4>
                                    <div class="flex items-center space-x-3 mt-1">
                                        <span class="text-xs text-gray-500">
                                            <i class="fas fa-calendar mr-1"></i>{{ $result->created_at->format('d/m/Y') }}
                                        </span>
                                        <span class="text-xs text-gray-500">
                                            <i class="fas fa-clock mr-1"></i>{{ $result->getDuration() }}m
                                        </span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-lg font-bold {{ $result->nilai_total >= 80 ? 'text-green-600' : ($result->nilai_total >= 60 ? 'text-yellow-600' : 'text-red-600') }}">
                                        {{ $result->nilai_total }}%
                                    </div>
                                    <div class="text-xs text-gray-500">{{ $result->jumlah_benar }}/{{ $result->total_soal }}</div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <!-- Show detailed quiz report button -->
                        <div class="mt-4 text-center">
                            <button onclick="showQuizReport()" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                                <i class="fas fa-chart-line mr-1"></i>Lihat Laporan Detail
                            </button>
                        </div>
                    </div>
                    @endif
                @endif
                @endauth --}}
            </div>
        </div>
    </div>
</div>

<!-- Alert Notifications -->
<div id="alert-container" class="fixed top-4 right-4 z-50 space-y-2">
    <!-- Alerts will be dynamically added here -->
</div>

@endsection

@push('scripts')
<script>
// Add CSRF token to window object for AJAX requests
window.csrfToken = "{{ csrf_token() }}";

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

// Bookmark functionality
function toggleBookmark(courseId) {
    @auth
        const bookmarkBtn = document.getElementById('bookmark-btn');
        const bookmarkIcon = document.getElementById('bookmark-icon');
        const bookmarkText = document.getElementById('bookmark-text');

        // Disable the button temporarily
        bookmarkBtn.disabled = true;
        bookmarkBtn.classList.add('opacity-75');

        showLoading('Memproses bookmark...');

        $.ajax({
            url: '{{ route("web.bookmark.course.store") }}',
            type: 'POST',
            data: {
                course_id: courseId,
                _token: '{{ csrf_token() }}'
            },
            success: function(data) {
                Swal.close();

                if (data.success) {
                    if (data.action === 'added') {
                        bookmarkIcon.className = 'fas fa-bookmark mr-2 text-yellow-500';
                        bookmarkText.textContent = 'Bookmarked';
                        bookmarkBtn.classList.add('bg-yellow-50', 'text-yellow-700');
                        bookmarkBtn.classList.remove('bg-gray-100', 'text-gray-700');
                        showSuccess(data.message);
                    } else {
                        bookmarkIcon.className = 'far fa-bookmark mr-2';
                        bookmarkText.textContent = 'Bookmark Course';
                        bookmarkBtn.classList.remove('bg-yellow-50', 'text-yellow-700');
                        bookmarkBtn.classList.add('bg-gray-100', 'text-gray-700');
                        showInfo(data.message);
                    }
                } else {
                    showError(data.message || 'Gagal memproses bookmark');
                }
            },
            error: function(xhr, status, error) {
                Swal.close();
                console.error('Error:', error);
                let errorMessage = 'Terjadi kesalahan saat memproses bookmark';

                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    errorMessage = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }

                showError(errorMessage);
            },
            complete: function() {
                // Re-enable button when request completes
                bookmarkBtn.disabled = false;
                bookmarkBtn.classList.remove('opacity-75');
            }
        });
    @else
        showInfo('Silakan login terlebih dahulu untuk bookmark course', 'Login Required').then(() => {
            window.location.href = '{{ route('login') }}';
        });
    @endauth
}

// Share functionality
function shareService(courseName, courseUrl) {
    if (navigator.share) {
        navigator.share({
            title: 'SkillLearn Course: ' + courseName,
            text: 'Lihat course menarik ini di SkillLearn!',
            url: courseUrl
        }).then(() => {
            showSuccess('Course berhasil dibagikan!');
        }).catch(err => {
            console.log('Error sharing:', err);
            fallbackShare(courseName, courseUrl);
        });
    } else {
        fallbackShare(courseName, courseUrl);
    }
}

function fallbackShare(courseName, courseUrl) {
    Swal.fire({
        title: '<i class="fas fa-share-alt text-blue-500"></i> Bagikan Course',
        html: `
            <div class="text-left space-y-4">
                <p class="text-gray-600 mb-4">Bagikan course "<strong>${courseName}</strong>" melalui:</p>
                <div class="grid grid-cols-2 gap-3">
                    <button onclick="shareToWhatsApp('${courseName}', '${courseUrl}')"
                            class="flex items-center justify-center p-3 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors">
                        <i class="fab fa-whatsapp mr-2"></i> WhatsApp
                    </button>
                    <button onclick="shareToFacebook('${courseUrl}')"
                            class="flex items-center justify-center p-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fab fa-facebook mr-2"></i> Facebook
                    </button>
                    <button onclick="shareToTwitter('${courseName}', '${courseUrl}')"
                            class="flex items-center justify-center p-3 bg-blue-400 text-white rounded-lg hover:bg-blue-500 transition-colors">
                        <i class="fab fa-twitter mr-2"></i> Twitter
                    </button>
                    <button onclick="copyToClipboard('${courseUrl}')"
                            class="flex items-center justify-center p-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                        <i class="fas fa-copy mr-2"></i> Copy Link
                    </button>
                </div>
            </div>
        `,
        showConfirmButton: false,
        showCancelButton: true,
        cancelButtonText: 'Tutup',
        width: '500px'
    });
}

function shareToWhatsApp(courseName, courseUrl) {
    const text = `Lihat course menarik "${courseName}" di SkillLearn! ${courseUrl}`;
    window.open(`https://wa.me/?text=${encodeURIComponent(text)}`, '_blank');
    Swal.close();
}

function shareToFacebook(courseUrl) {
    window.open(`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(courseUrl)}`, '_blank');
    Swal.close();
}

function shareToTwitter(courseName, courseUrl) {
    const text = `Lihat course menarik "${courseName}" di SkillLearn!`;
    window.open(`https://twitter.com/intent/tweet?text=${encodeURIComponent(text)}&url=${encodeURIComponent(courseUrl)}`, '_blank');
    Swal.close();
}

function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        showSuccess('Link berhasil disalin ke clipboard!');
        Swal.close();
    }).catch(err => {
        showError('Gagal menyalin link');
        console.error('Failed to copy: ', err);
    });
}

// Star rating functionality
document.addEventListener('DOMContentLoaded', function() {
    // Global AJAX setup for CSRF token
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': window.csrfToken
        }
    });

    const stars = document.querySelectorAll('.star-rating');
    const ratingInput = document.getElementById('rating-input');
    let currentFeedback = null;
    let isEditMode = false;

    // Check bookmark status on load
    @auth
        checkBookmarkStatus({{ $course->course_id }});
        // Load existing feedback
        loadUserFeedback({{ $course->course_id }});
    @endauth

    // Prevent any accidental GET requests to courses.start
    document.addEventListener('click', function(e) {
        const target = e.target.closest('a');
        if (target && target.href && target.href.includes('/courses/') && target.href.includes('/start')) {
            e.preventDefault();
            showError('Gunakan tombol "Mulai Pembelajaran" yang benar untuk memulai course ini.', 'Akses Tidak Valid');
            return false;
        }
    });

    if (stars.length > 0) {
        stars.forEach((star, index) => {
            star.addEventListener('click', function() {
                const rating = this.dataset.rating;
                ratingInput.value = rating;

                // Update star colors
                updateStarDisplay(rating);
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
    }

    // Load user's existing feedback
    function loadUserFeedback(courseId) {
        $.ajax({
            url: '{{ route("web.feedback.course", "") }}/' + courseId,
            type: 'GET',
            success: function(data) {
                if (data.success && data.has_feedback) {
                    currentFeedback = data.feedback;
                    displayExistingFeedback(currentFeedback);
                } else {
                    // Show feedback form
                    $('#feedback-form-container').show();
                }
            },
            error: function(xhr, status, error) {
                console.error('Error loading feedback:', error);
                // Show feedback form anyway
                $('#feedback-form-container').show();
            }
        });
    }

    // Display existing feedback
    function displayExistingFeedback(feedback) {
        $('#existing-feedback').show();
        $('#feedback-form-container').hide();

        // Display rating stars
        displayRatingStars('#existing-rating-stars', feedback.rating);
        $('#existing-rating-text').text(`(${feedback.rating}/5)`);

        // Display feedback text
        $('#existing-feedback-text').text(feedback.pesan);

        // Display date
        const feedbackDate = new Date(feedback.created_at).toLocaleDateString('id-ID', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
        $('#existing-feedback-date').text(feedbackDate);
    }

    // Display rating stars
    function displayRatingStars(container, rating) {
        const starsContainer = $(container);
        starsContainer.empty();

        for (let i = 1; i <= 5; i++) {
            const star = $('<i class="fas fa-star text-lg"></i>');
            if (i <= rating) {
                star.addClass('text-yellow-500');
            } else {
                star.addClass('text-gray-300');
            }
            starsContainer.append(star);
        }
    }

    // Update star display for form
    function updateStarDisplay(rating) {
        stars.forEach((s, i) => {
            if (i < rating) {
                s.classList.remove('text-gray-300');
                s.classList.add('text-yellow-500');
            } else {
                s.classList.remove('text-yellow-500');
                s.classList.add('text-gray-300');
            }
        });
    }

    // Edit feedback function
    window.editFeedback = function() {
        if (!currentFeedback) return;

        isEditMode = true;

        // Show form
        $('#existing-feedback').hide();
        $('#feedback-form-container').show();

        // Fill form with existing data
        $('#feedback-id').val(currentFeedback.feedback_id);
        $('#pesan').val(currentFeedback.pesan);
        $('#rating-input').val(currentFeedback.rating);

        // Update stars
        updateStarDisplay(currentFeedback.rating);

        // Update UI
        $('#submit-text').text('Update Feedback');
        $('#cancel-edit-btn').show();
        $('#submit-feedback-btn').removeClass('flex-1');
    };

    // Cancel edit function
    window.cancelEdit = function() {
        isEditMode = false;

        // Reset form
        $('#feedback-form')[0].reset();
        $('#feedback-id').val('');
        $('#rating-input').val('');

        // Reset stars
        stars.forEach(s => {
            s.classList.remove('text-yellow-500');
            s.classList.add('text-gray-300');
        });

        // Update UI
        $('#submit-text').text('Kirim Feedback');
        $('#cancel-edit-btn').hide();
        $('#submit-feedback-btn').addClass('flex-1');

        // Show existing feedback
        $('#feedback-form-container').hide();
        $('#existing-feedback').show();
    };

    // Delete feedback function
    window.deleteFeedback = function() {
        if (!currentFeedback) return;

        Swal.fire({
            title: 'Hapus Feedback?',
            text: 'Anda yakin ingin menghapus feedback ini? Tindakan ini tidak dapat dibatalkan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                showLoading('Menghapus feedback...');

                $.ajax({
                    url: '{{ route("web.feedback.destroy", "") }}/' + currentFeedback.feedback_id,
                    type: 'DELETE',
                    success: function(data) {
                        Swal.close();
                        if (data.success) {
                            showSuccess('Feedback berhasil dihapus!').then(() => {
                                // Reset state
                                currentFeedback = null;
                                $('#existing-feedback').hide();
                                $('#feedback-form-container').show();
                            });
                        } else {
                            showError(data.message || 'Gagal menghapus feedback');
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.close();
                        console.error('Error:', error);
                        showError('Terjadi kesalahan saat menghapus feedback');
                    }
                });
            }
        });
    };

    // Feedback form submission
    const feedbackForm = document.getElementById('feedback-form');
    if (feedbackForm) {
        feedbackForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const submitBtn = this.querySelector('button[type="submit"]');

            if (!ratingInput.value) {
                showWarning('Silakan berikan rating terlebih dahulu');
                return;
            }

            const pesan = $('#pesan').val();
            const courseId = {{ $course->course_id }};
            const rating = ratingInput.value;
            const feedbackId = $('#feedback-id').val();

            if (!pesan.trim()) {
                showWarning('Silakan berikan komentar');
                return;
            }

            submitBtn.disabled = true;
            const originalText = $('#submit-text').text();
            $('#submit-text').html('<i class="fas fa-spinner fa-spin mr-2"></i>' + (isEditMode ? 'Updating...' : 'Mengirim...'));

            const ajaxData = {
                course_id: courseId,
                rating: rating,
                pesan: pesan,
                _token: window.csrfToken
            };

            const ajaxOptions = {
                type: isEditMode ? 'PUT' : 'POST',
                url: isEditMode ?
                    '{{ route("web.feedback.update", "") }}/' + feedbackId :
                    '{{ route("web.feedback.store") }}',
                data: ajaxData,
                success: function(data) {
                    if (data.success) {
                        const message = isEditMode ? 'Feedback berhasil diupdate!' : 'Terima kasih atas feedback Anda!';
                        showSuccess(message).then(() => {
                            // Update current feedback
                            currentFeedback = data.feedback;
                            isEditMode = false;

                            // Reset form
                            feedbackForm.reset();
                            $('#feedback-id').val('');
                            $('#rating-input').val('');

                            // Reset stars
                            stars.forEach(s => {
                                s.classList.remove('text-yellow-500');
                                s.classList.add('text-gray-300');
                            });

                            // Update UI
                            $('#submit-text').text('Kirim Feedback');
                            $('#cancel-edit-btn').hide();
                            $('#submit-feedback-btn').addClass('flex-1');

                            // Show existing feedback
                            displayExistingFeedback(currentFeedback);
                        });
                    } else {
                        showError(data.message || 'Gagal mengirim feedback');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    let errorMessage = 'Terjadi kesalahan saat mengirim feedback';

                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        errorMessage = Object.values(xhr.responseJSON.errors).flat().join("<br>");
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }

                    showError(errorMessage);
                },
                complete: function() {
                    submitBtn.disabled = false;
                    $('#submit-text').text(originalText);
                }
            };

            $.ajax(ajaxOptions);
        });
    }
});

// Check bookmark status dengan AJAX dan error handling yang lebih baik
function checkBookmarkStatus(courseId) {
    // Mendefinisikan elemen-elemen UI yang perlu diupdate
    const bookmarkBtn = document.getElementById('bookmark-btn');
    const bookmarkIcon = document.getElementById('bookmark-icon');
    const bookmarkText = document.getElementById('bookmark-text');

    // Pastikan elemen-elemen ada di halaman
    if (!bookmarkBtn || !bookmarkIcon || !bookmarkText) {
        console.error('Bookmark elements not found on page');
        return;
    }

    // Tambahkan class loading untuk menunjukkan sedang memuat
    bookmarkBtn.classList.add('opacity-75');

    // Request AJAX ke API endpoint untuk check status bookmark
    $.ajax({
        url: "{{ route('web.bookmark.course.check', '') }}/" + courseId,
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            // Hapus class loading
            bookmarkBtn.classList.remove('opacity-75');

            if (data.success) {
                if (data.bookmarked) {
                    // Course telah di-bookmark, update UI
                    bookmarkIcon.className = 'fas fa-bookmark mr-2 text-yellow-500';
                    bookmarkText.textContent = 'Bookmarked';
                    bookmarkBtn.classList.add('bg-yellow-50', 'text-yellow-700');
                    bookmarkBtn.classList.remove('bg-gray-100', 'text-gray-700');
                } else {
                    // Course belum di-bookmark, pastikan UI menunjukkan status yang benar
                    bookmarkIcon.className = 'far fa-bookmark mr-2';
                    bookmarkText.textContent = 'Bookmark Course';
                    bookmarkBtn.classList.remove('bg-yellow-50', 'text-yellow-700');
                    bookmarkBtn.classList.add('bg-gray-100', 'text-gray-700');
                }
            }
        },
        error: function(xhr, status, error) {
            // Hapus class loading pada error
            bookmarkBtn.classList.remove('opacity-75');

            // Log error tetapi jangan tampilkan ke user karena ini background check
            console.error('Error checking bookmark status:', error);

            // Set default UI state pada error (not bookmarked)
            bookmarkIcon.className = 'far fa-bookmark mr-2';
            bookmarkText.textContent = 'Bookmark Course';
            bookmarkBtn.classList.remove('bg-yellow-50', 'text-yellow-700');
            bookmarkBtn.classList.add('bg-gray-100', 'text-gray-700');
        }
    });
}

// Show detailed quiz report modal
function showQuizReport() {
    @auth
        showLoading('Memuat laporan quiz...');

        $.ajax({
            url: '{{ route("courses.quiz-report", $course->course_id) }}',
            type: 'GET',
            success: function(data) {
                Swal.close();

                if (data.success) {
                    let reportHtml = `
                        <div class="text-left">
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold mb-3">Ringkasan Performa Quiz</h3>
                                <div class="grid grid-cols-3 gap-4 text-center mb-4">
                                    <div class="bg-blue-50 p-3 rounded-lg">
                                        <div class="text-2xl font-bold text-blue-600">${data.summary.total_attempts}</div>
                                        <div class="text-sm text-gray-600">Total Percobaan</div>
                                    </div>
                                    <div class="bg-green-50 p-3 rounded-lg">
                                        <div class="text-2xl font-bold text-green-600">${data.summary.average_score}%</div>
                                        <div class="text-sm text-gray-600">Rata-rata</div>
                                    </div>
                                    <div class="bg-purple-50 p-3 rounded-lg">
                                        <div class="text-2xl font-bold text-purple-600">${data.summary.passed_count}/${data.summary.total_attempts}</div>
                                        <div class="text-sm text-gray-600">Lulus</div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <h4 class="font-semibold mb-3">Detail Hasil Quiz:</h4>
                                <div class="space-y-3 max-h-64 overflow-y-auto">
                    `;

                    data.quiz_results.forEach(result => {
                        let gradeColor = result.nilai_total >= 80 ? 'green' : (result.nilai_total >= 60 ? 'yellow' : 'red');
                        let gradeIcon = result.nilai_total >= 60 ? 'check-circle' : 'times-circle';

                        reportHtml += `
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex-1">
                                    <div class="flex items-center">
                                        <i class="fas fa-${gradeIcon} text-${gradeColor}-500 mr-2"></i>
                                        <h5 class="font-medium">${result.quiz_title}</h5>
                                    </div>
                                    <div class="text-sm text-gray-600 mt-1">
                                        <span>Dikerjakan: ${result.date}</span> •
                                        <span>Durasi: ${result.duration} menit</span> •
                                        <span>Benar: ${result.jumlah_benar}/${result.total_soal}</span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-lg font-bold text-${gradeColor}-600">${result.nilai_total}%</div>
                                    <div class="text-xs text-gray-500">Grade: ${result.grade}</div>
                                </div>
                            </div>
                        `;
                    });

                    reportHtml += `
                                </div>
                            </div>
                        </div>
                    `;

                    Swal.fire({
                        title: '<i class="fas fa-chart-bar text-blue-500"></i> Laporan Quiz Detail',
                        html: reportHtml,
                        width: '700px',
                        showConfirmButton: false,
                        showCancelButton: true,
                        cancelButtonText: 'Tutup',
                        customClass: {
                            popup: 'text-left'
                        }
                    });
                } else {
                    showError(data.message || 'Gagal memuat laporan quiz');
                }
            },
            error: function(xhr, status, error) {
                Swal.close();
                console.error('Error:', error);
                showError('Terjadi kesalahan saat memuat laporan quiz');
            }
        });
    @else
        showInfo('Silakan login terlebih dahulu untuk melihat laporan quiz');
    @endauth
}
</script>
@endpush

<style>
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
