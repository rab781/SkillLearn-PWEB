@extends('layouts.admin')

@section('title', 'Detail Course - ' . $course->nama_course)

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>
<style>
    .sortable-ghost {
        opacity: 0.4;
        transform: rotate(3deg);
    }
    
    .sortable-chosen {
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
        transform: scale(1.02);
        z-index: 1000;
    }
    
    .sortable-drag {
        opacity: 0.8;
        transform: rotate(5deg);
    }
    
    .drag-handle {
        cursor: grab;
        color: #6B7280;
        transition: color 0.2s;
    }
    
    .drag-handle:hover {
        color: #374151;
    }
    
    .drag-handle:active {
        cursor: grabbing;
    }
    
    .drop-zone {
        min-height: 60px;
        border: 2px dashed #d1d5db;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6b7280;
        font-size: 0.875rem;
        margin: 16px 0;
        cursor: pointer;
        transition: all 0.3s ease;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    }
    
    .drop-zone:hover {
        border-color: #3b82f6;
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        color: #1d4ed8;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
    }
    
    .quiz-item {
        background: linear-gradient(135deg, #8b5cf6 0%, #a78bfa 100%);
        border-radius: 16px;
        padding: 20px;
        margin: 12px 0;
        color: white;
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: move;
        transition: all 0.3s ease;
        border: 1px solid rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
    }
    
    .quiz-item:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 35px rgba(139, 92, 246, 0.4);
    }
    
    .add-quiz-btn {
        background: linear-gradient(135deg, #8b5cf6 0%, #a78bfa 100%);
        color: white;
        border: none;
        border-radius: 10px;
        padding: 10px 16px;
        font-size: 0.875rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 14px 0 rgba(139, 92, 246, 0.3);
    }
    
    .add-quiz-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(139, 92, 246, 0.4);
        background: linear-gradient(135deg, #7c3aed 0%, #8b5cf6 100%);
    }

    .section-item {
        transition: all 0.3s ease;
        background: white;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .section-item:hover {
        box-shadow: 0 10px 25px -3px rgba(0, 0, 0, 0.1);
        transform: translateY(-1px);
    }

    .video-item {
        transition: all 0.3s ease;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border: 1px solid #e2e8f0;
    }

    .video-item:hover {
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        border-color: #3b82f6;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
    }

    .action-btn {
        padding: 8px 12px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.875rem;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
        border: none;
        cursor: pointer;
    }

    .action-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .action-btn-primary {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        color: white;
    }

    .action-btn-success {
        background: linear-gradient(135deg, #10b981 0%, #047857 100%);
        color: white;
    }

    .action-btn-danger {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
    }

    .action-btn-warning {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
    }

    .quiz-edit-btn {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.3);
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 0.8rem;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .quiz-edit-btn:hover {
        background: rgba(255, 255, 255, 0.3);
        color: white;
        transform: translateY(-1px);
    }

    .quiz-delete-btn {
        background: rgba(239, 68, 68, 0.8);
        color: white;
        border: 1px solid rgba(239, 68, 68, 0.3);
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 0.8rem;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .quiz-delete-btn:hover {
        background: rgba(220, 38, 38, 0.9);
        transform: translateY(-1px);
    }
</style>
@endpush

@section('content')
<!-- Page Header -->
<div class="mb-8">
    <nav class="flex mb-4" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('admin.courses.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 transition-colors">
                    <i class="fas fa-graduation-cap mr-2"></i>
                    Courses
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                    <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">{{ $course->nama_course }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
        <div>
            <h1 class="text-4xl font-bold text-gray-900 mb-2">{{ $course->nama_course }}</h1>
            <div class="flex items-center space-x-4">
                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium
                    @if($course->is_active) bg-green-100 text-green-800 @else bg-gray-100 text-gray-800 @endif">
                    <i class="fas fa-{{ $course->is_active ? 'check-circle' : 'pause-circle' }} mr-2"></i>
                    {{ $course->is_active ? 'Aktif' : 'Nonaktif' }}
                </span>
                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium
                    @switch($course->level)
                        @case('pemula') bg-green-100 text-green-800 @break
                        @case('menengah') bg-yellow-100 text-yellow-800 @break
                        @case('lanjut') bg-red-100 text-red-800 @break
                    @endswitch">
                    <i class="fas fa-signal mr-2"></i>
                    {{ ucfirst($course->level) }}
                </span>
                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                    <i class="fas fa-folder mr-2"></i>
                    {{ $course->kategori->kategori ?? 'N/A' }}
                </span>
            </div>
        </div>

        <div class="flex space-x-3 mt-6 sm:mt-0">
            <a href="{{ route('admin.courses.edit', $course->course_id) }}"
               class="action-btn action-btn-warning">
                <i class="fas fa-edit"></i> Edit Course
            </a>
            <button onclick="toggleCourseStatus({{ $course->course_id }}, {{ $course->is_active ? 'false' : 'true' }})"
                    class="action-btn {{ $course->is_active ? 'action-btn-danger' : 'action-btn-success' }}">
                <i class="fas fa-{{ $course->is_active ? 'pause' : 'play' }}"></i>
                {{ $course->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
            </button>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="mb-8 bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-xl shadow-lg">
        <div class="flex items-center">
            <i class="fas fa-check-circle mr-3 text-green-500"></i>
            {{ session('success') }}
        </div>
    </div>
@endif

<!-- Main Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
    <!-- Sidebar - Course Info & Actions -->
    <div class="lg:col-span-1">
        <!-- Course Information Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8">
            @if($course->gambar_course)
                <div class="aspect-video overflow-hidden">
                    <img src="{{ Storage::url($course->gambar_course) }}"
                         alt="{{ $course->nama_course }}"
                         class="w-full h-full object-cover">
                </div>
            @endif

            <div class="p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                    Informasi Course
                </h3>

                <div class="space-y-4">
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-gray-600 font-medium">Total Video:</span>
                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-semibold">
                            {{ $course->total_video }} videos
                        </span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-gray-600 font-medium">Total Durasi:</span>
                        <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm font-semibold">
                            {{ $course->total_durasi_menit }} menit
                        </span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-gray-600 font-medium">Siswa:</span>
                        <span class="bg-emerald-100 text-emerald-800 px-3 py-1 rounded-full text-sm font-semibold">
                            {{ $course->userProgress->count() }} enrolled
                        </span>
                    </div>
                </div>

                @if($course->deskripsi_course)
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h4 class="font-semibold text-gray-900 mb-2">Deskripsi:</h4>
                        <p class="text-gray-600 text-sm leading-relaxed">{{ $course->deskripsi_course }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Quick Actions Card -->
        <div class="bg-white rounded-2xl shadow-xl p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                <i class="fas fa-lightning-bolt mr-2 text-yellow-500"></i>
                Quick Actions
            </h3>

            <div class="space-y-3">
                <button onclick="showAddSectionDialog()"
                        class="w-full bg-gradient-to-r from-green-500 to-emerald-500 text-black py-3 px-4 rounded-xl hover:from-green-600 hover:to-emerald-600 transition-all duration-200 shadow-lg flex items-center justify-center">
                    <i class="fas fa-plus mr-2"></i> Tambah Section
                </button>

                {{-- <button onclick="showAddReviewDialog()"
                        class="w-full bg-gradient-to-r from-blue-500 to-cyan-500 text-white py-3 px-4 rounded-xl hover:from-blue-600 hover:to-cyan-600 transition-all duration-200 shadow-lg flex items-center justify-center">
                    <i class="fas fa-star mr-2"></i> Tambah Quick Review
                </button> --}}

                <a href="{{ route('admin.courses.quizzes', $course->course_id) }}"
                   class="w-full bg-gradient-to-r from-purple-500 to-pink-500 text-white py-3 px-4 rounded-xl hover:from-purple-600 hover:to-pink-600 transition-all duration-200 shadow-lg flex items-center justify-center">
                    <i class="fas fa-question-circle mr-2"></i> Kelola Quiz
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content - Course Sections & Reviews -->
    <div class="lg:col-span-3">
        <!-- Course Content -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8">
            <div class="bg-gradient-to-r from-blue-500 to-purple-600 px-6 py-4">
                <h3 class="text-xl font-bold text-white flex items-center">
                    <i class="fas fa-list mr-3"></i>
                    Course Content ({{ $course->sections->count() }} Sections)
                </h3>
            </div>

            <div class="p-6">
                @if($course->sections->count() > 0)
                    <!-- Display quizzes at start of course -->
                    @if($course->courseQuizzes->where('position', 'start')->count() > 0)
                        @foreach($course->courseQuizzes->where('position', 'start') as $courseQuiz)
                        <div class="quiz-item mb-4" data-quiz-id="{{ $courseQuiz->quiz->quiz_id }}">
                            <div class="flex items-center">
                                <div class="drag-handle mr-3">
                                    <i class="fas fa-grip-vertical"></i>
                                </div>
                                <div class="flex-1">
                                    <h5 class="font-semibold">
                                        <i class="fas fa-question-circle mr-2"></i>
                                        {{ $courseQuiz->quiz->judul_quiz }}
                                    </h5>
                                    <p class="text-sm text-purple-200 mt-1">{{ $courseQuiz->position_label }}</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('admin.courses.quizzes.questions.index', $courseQuiz->quiz->quiz_id) }}" 
                                   class="bg-white bg-opacity-20 text-white px-3 py-1 rounded text-sm hover:bg-opacity-30">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <button onclick="removeQuizFromCourse({{ $courseQuiz->id }})" 
                                        class="bg-red-500 bg-opacity-80 text-white px-3 py-1 rounded text-sm hover:bg-red-600">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        @endforeach
                    @endif
                    
                    <!-- Add Quiz at Start of Course -->
                    <div class="drop-zone quiz-drop-zone" data-position="start">
                        <i class="fas fa-plus-circle mr-2"></i>
                        <span>Tambah Quiz di Awal Course</span>
                    </div>
                    
                    <div id="sections-container" class="space-y-4">
                        @foreach($course->sections as $section)
                        <div class="section-item border border-gray-200 rounded-xl overflow-hidden" data-section-id="{{ $section->section_id }}">
                            <div class="bg-gray-50 px-6 py-4 cursor-pointer hover:bg-gray-100 transition-colors"
                                 onclick="toggleSection({{ $section->section_id }})">
                                <div class="flex justify-between items-center">
                                    <div class="flex items-center">
                                        <div class="drag-handle mr-3" onclick="event.stopPropagation()">
                                            <i class="fas fa-grip-vertical text-xl"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-gray-900 text-lg">
                                                <span class="section-order">{{ $section->urutan_section }}</span>. {{ $section->nama_section }}
                                            </h4>
                                            <p class="text-gray-600 text-sm mt-1">
                                                <i class="fas fa-play-circle mr-1"></i>
                                                {{ $section->videos->count() }} videos
                                            </p>
                                        </div>
                                    </div>
                                    <i id="arrow-{{ $section->section_id }}" class="fas fa-chevron-down text-gray-400 transition-transform"></i>
                                </div>
                            </div>

                            <div id="section-{{ $section->section_id }}" class="hidden">
                                <div class="px-6 py-4">
                                    @if($section->deskripsi_section)
                                        <p class="text-gray-600 mb-4 italic">{{ $section->deskripsi_section }}</p>
                                    @endif

                                    @if($section->videos->count() > 0)
                                        <div id="videos-container-{{ $section->section_id }}" class="space-y-3 mb-4">
                                            @foreach($section->videos as $courseVideo)
                                            <div class="video-item bg-gray-50 rounded-lg p-4 flex justify-between items-center" data-video-id="{{ $courseVideo->course_video_id }}">
                                                <div class="flex items-center flex-1">
                                                    <div class="drag-handle mr-3">
                                                        <i class="fas fa-grip-vertical"></i>
                                                    </div>
                                                    <div class="flex-1">
                                                        <h5 class="font-semibold text-gray-900">
                                                            <span class="video-order">{{ $courseVideo->urutan_video }}</span>. {{ $courseVideo->vidio->nama }}
                                                        </h5>
                                                        <div class="flex items-center text-sm text-gray-600 mt-1">
                                                            <i class="fas fa-clock mr-1"></i>
                                                            {{ $courseVideo->durasi_menit }} menit
                                                            @if($courseVideo->catatan_admin)
                                                                <span class="mx-2">•</span>
                                                                <i class="fas fa-sticky-note mr-1 text-blue-500"></i>
                                                                {{ Str::limit($courseVideo->catatan_admin, 30) }}
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="flex space-x-2">
                                                    <button class="add-quiz-btn" onclick="showAddQuizDialog('after_video', {{ $courseVideo->course_video_id }})">
                                                        <i class="fas fa-plus"></i>
                                                        Quiz
                                                    </button>
                                                    <a href="{{ $courseVideo->vidio->url }}" target="_blank"
                                                       class="bg-blue-500 text-white px-3 py-2 rounded-lg hover:bg-blue-600 transition-colors">
                                                        <i class="fas fa-play"></i>
                                                    </a>
                                                    <button onclick="removeVideo({{ $course->course_id }}, {{ $courseVideo->course_video_id }})"
                                                            class="bg-red-500 text-white px-3 py-2 rounded-lg hover:bg-red-600 transition-colors">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                                             <!-- Display quizzes after this video -->
                            @if($course->courseQuizzes->where('position', 'after_video')->where('reference_id', $courseVideo->course_video_id)->count() > 0)
                                @foreach($course->courseQuizzes->where('position', 'after_video')->where('reference_id', $courseVideo->course_video_id) as $courseQuiz)
                                <div class="quiz-item ml-8" data-quiz-id="{{ $courseQuiz->quiz->quiz_id }}">
                                    <div class="flex items-center">
                                        <div class="flex-1">
                                            <h5 class="font-semibold">
                                                <i class="fas fa-question-circle mr-2"></i>
                                                {{ $courseQuiz->quiz->judul_quiz }}
                                            </h5>
                                            <p class="text-sm text-purple-200 mt-1">{{ $courseQuiz->position_label }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('admin.courses.quizzes.questions.index', $courseQuiz->quiz->quiz_id) }}" 
                                           class="bg-white bg-opacity-20 text-white px-3 py-1 rounded text-sm hover:bg-opacity-30">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <button onclick="removeQuizFromCourse({{ $courseQuiz->id }})" 
                                                class="bg-red-500 bg-opacity-80 text-white px-3 py-1 rounded text-sm hover:bg-red-600">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                                @endforeach
                            @endif
                                            @endforeach
                                        </div>
                                    @endif

                                    <div class="flex space-x-3 mb-4">
                                        <button onclick="showAddVideoDialog({{ $section->section_id }}, '{{ $section->nama_section }}')"
                                                class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors flex items-center">
                                            <i class="fas fa-plus mr-2"></i> Tambah Video
                                        </button>
                                        <button onclick="removeSection({{ $course->course_id }}, {{ $section->section_id }})"
                                                class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition-colors flex items-center">
                                            <i class="fas fa-trash mr-2"></i> Hapus Section
                                        </button>
                                        <button class="add-quiz-btn" onclick="showAddQuizDialog('after_section', {{ $section->section_id }})">
                                            <i class="fas fa-plus mr-2"></i>
                                            Quiz Setelah Section
                                        </button>
                                    </div>                    
                    <!-- Display quizzes after this section -->
                    @if($course->courseQuizzes->where('position', 'after_section')->where('reference_id', $section->section_id)->count() > 0)
                        @foreach($course->courseQuizzes->where('position', 'after_section')->where('reference_id', $section->section_id) as $courseQuiz)
                        <div class="quiz-item" data-quiz-id="{{ $courseQuiz->quiz->quiz_id }}">
                            <div class="flex items-center">
                                <div class="flex-1">
                                    <h5 class="font-semibold">
                                        <i class="fas fa-question-circle mr-2"></i>
                                        {{ $courseQuiz->quiz->judul_quiz }}
                                    </h5>
                                    <p class="text-sm text-purple-200 mt-1">{{ $courseQuiz->position_label }}</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('admin.courses.quizzes.questions.index', $courseQuiz->quiz->quiz_id) }}" 
                                   class="bg-white bg-opacity-20 text-white px-3 py-1 rounded text-sm hover:bg-opacity-30">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <button onclick="removeQuizFromCourse({{ $courseQuiz->id }})" 
                                        class="bg-red-500 bg-opacity-80 text-white px-3 py-1 rounded text-sm hover:bg-red-600">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        @endforeach
                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- Add Quiz Between Sections -->
                        @if(!$loop->last)
                        <div class="drop-zone quiz-drop-zone" data-position="between_sections" data-after-section="{{ $section->section_id }}">
                            <i class="fas fa-plus-circle mr-2"></i>
                            <span>Tambah Quiz di Sini</span>
                        </div>
                        @endif
                        
                        @endforeach
                    </div>
                    
                    <!-- Display quizzes at end of course -->
                    @if($course->courseQuizzes->where('position', 'end')->count() > 0)
                        @foreach($course->courseQuizzes->where('position', 'end') as $courseQuiz)
                        <div class="quiz-item mt-6" data-quiz-id="{{ $courseQuiz->quiz->quiz_id }}">
                            <div class="flex items-center">
                                <div class="flex-1">
                                    <h5 class="font-semibold">
                                        <i class="fas fa-question-circle mr-2"></i>
                                        {{ $courseQuiz->quiz->judul_quiz }}
                                    </h5>
                                    <p class="text-sm text-purple-200 mt-1">{{ $courseQuiz->position_label }}</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('admin.courses.quizzes.questions.index', $courseQuiz->quiz->quiz_id) }}" 
                                   class="bg-white bg-opacity-20 text-white px-3 py-1 rounded text-sm hover:bg-opacity-30">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <button onclick="removeQuizFromCourse({{ $courseQuiz->id }})" 
                                        class="bg-red-500 bg-opacity-80 text-white px-3 py-1 rounded text-sm hover:bg-red-600">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        @endforeach
                    @endif
                    
                    <!-- Add Quiz at End of Course -->
                    <div class="drop-zone quiz-drop-zone mt-6" data-position="end">
                        <i class="fas fa-plus-circle mr-2"></i>
                        <span>Tambah Quiz di Akhir Course</span>
                    </div>
                @else
                    <div class="text-center py-12">
                        <i class="fas fa-folder-open text-6xl text-gray-300 mb-4"></i>
                        <h4 class="text-xl font-semibold text-gray-500 mb-2">Belum Ada Section</h4>
                        <p class="text-gray-400 mb-6">Tambahkan section pertama untuk memulai!</p>
                        <button onclick="showAddSectionDialog()"
                                class="bg-gradient-to-r from-blue-500 to-purple-600 text-white px-6 py-3 rounded-xl hover:from-blue-600 hover:to-purple-700 transition-all duration-200 shadow-lg">
                            <i class="fas fa-plus mr-2"></i> Tambah Section
                        </button>
                    </div>
                @endif
            </div>
        </div>

        {{-- <!-- Quick Reviews -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="bg-gradient-to-r from-yellow-500 to-orange-500 px-6 py-4">
                <h3 class="text-xl font-bold text-white flex items-center">
                    <i class="fas fa-star mr-3"></i>
                    Quick Reviews ({{ $course->quickReviews->count() }})
                </h3>
            </div>

            <div class="p-6">
                @if($course->quickReviews->count() > 0)
                    <div class="space-y-4">
                        @foreach($course->quickReviews as $review)
                        <div class="border border-gray-200 rounded-xl p-4 hover:shadow-md transition-shadow">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <h4 class="font-bold text-gray-900 text-lg">{{ $review->judul_review }}</h4>
                                    <div class="flex items-center space-x-3 mt-2">
                                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-semibold">
                                            {{ $review->getTypeLabel() }}
                                        </span>
                                        <span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-xs font-semibold">
                                            Urutan: {{ $review->urutan_review }}
                                        </span>
                                    </div>
                                    <p class="text-gray-600 mt-3">{!! Str::limit(strip_tags($review->konten_review), 150) !!}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-star text-4xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500">Belum ada quick review.</p>
                    </div>
                @endif
            </div>
        </div> --}}
    </div>
</div>

@endsection

@push('scripts')
<script>
// Initialize drag and drop functionality
document.addEventListener('DOMContentLoaded', function() {
    initializeDragAndDrop();
    initializeQuizDropZones();
});

// Initialize drag and drop for sections and videos
function initializeDragAndDrop() {
    // Make sections sortable
    const sectionsContainer = document.getElementById('sections-container');
    if (sectionsContainer) {
        new Sortable(sectionsContainer, {
            animation: 150,
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            dragClass: 'sortable-drag',
            handle: '.drag-handle',
            onEnd: function(evt) {
                updateSectionOrder();
            }
        });
    }

    // Make videos within each section sortable
    document.querySelectorAll('[id^="videos-container-"]').forEach(container => {
        new Sortable(container, {
            animation: 150,
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            dragClass: 'sortable-drag',
            handle: '.drag-handle',
            onEnd: function(evt) {
                const sectionId = container.id.replace('videos-container-', '');
                updateVideoOrder(sectionId);
            }
        });
    });
}

// Initialize quiz drop zones
function initializeQuizDropZones() {
    document.querySelectorAll('.quiz-drop-zone').forEach(zone => {
        zone.addEventListener('click', function() {
            const position = this.dataset.position;
            const afterSection = this.dataset.afterSection;
            const afterVideo = this.dataset.afterVideo;
            
            showAddQuizDialog(position, afterSection || afterVideo);
        });
    });
}

// Update section order after drag and drop
function updateSectionOrder() {
    const sections = document.querySelectorAll('.section-item');
    const sectionOrder = [];
    
    sections.forEach((section, index) => {
        const sectionId = section.dataset.sectionId;
        const newOrder = index + 1;
        
        // Update visual order number
        const orderSpan = section.querySelector('.section-order');
        if (orderSpan) {
            orderSpan.textContent = newOrder;
        }
        
        sectionOrder.push({
            section_id: sectionId,
            order: newOrder
        });
    });
    
    // Send update to server
    fetch(`/admin/courses/{{ $course->course_id }}/sections/reorder`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ sections: sectionOrder })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccessToast('Urutan section berhasil diperbarui');
        } else {
            showErrorToast('Gagal memperbarui urutan section');
            location.reload(); // Reload to restore original order
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showErrorToast('Terjadi kesalahan saat memperbarui urutan');
        location.reload();
    });
}

// Update video order after drag and drop
function updateVideoOrder(sectionId) {
    const videos = document.querySelectorAll(`#videos-container-${sectionId} .video-item`);
    const videoOrder = [];
    
    videos.forEach((video, index) => {
        const videoId = video.dataset.videoId;
        const newOrder = index + 1;
        
        // Update visual order number
        const orderSpan = video.querySelector('.video-order');
        if (orderSpan) {
            orderSpan.textContent = newOrder;
        }
        
        videoOrder.push({
            video_id: videoId,
            order: newOrder
        });
    });
    
    // Send update to server
    fetch(`/admin/courses/{{ $course->course_id }}/videos/reorder`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ 
            section_id: sectionId, 
            videos: videoOrder 
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccessToast('Urutan video berhasil diperbarui');
        } else {
            showErrorToast('Gagal memperbarui urutan video');
            location.reload(); // Reload to restore original order
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showErrorToast('Terjadi kesalahan saat memperbarui urutan');
        location.reload();
    });
}

// Show add quiz dialog
function showAddQuizDialog(position, referenceId = null) {
    let title = 'Tambah Quiz';
    let positionText = '';
    
    switch(position) {
        case 'start':
            positionText = 'di awal course';
            break;
        case 'end':
            positionText = 'di akhir course';
            break;
        case 'after_section':
            positionText = 'setelah section ini';
            break;
        case 'after_video':
            positionText = 'setelah video ini';
            break;
        case 'between_sections':
            positionText = 'di antara section';
            break;
    }
    
    Swal.fire({
        title: `<i class="fas fa-question-circle text-purple-500"></i> ${title}`,
        html: `
            <div class="text-left">
                <div class="mb-4 p-3 bg-purple-50 rounded-lg">
                    <p class="text-sm text-purple-700">
                        <i class="fas fa-info-circle mr-2"></i>
                        Quiz akan ditempatkan ${positionText}
                    </p>
                </div>
                
                <!-- Quiz Selection Tabs -->
                <div class="mb-4">
                    <div class="flex border-b border-gray-200">
                        <button type="button" id="tab-existing" class="flex-1 py-2 px-4 text-sm font-medium text-center border-b-2 border-purple-500 text-purple-600 bg-purple-50" onclick="switchQuizTab('existing')">
                            <i class="fas fa-list mr-2"></i>Pilih Quiz Existing
                        </button>
                        <button type="button" id="tab-new" class="flex-1 py-2 px-4 text-sm font-medium text-center border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300" onclick="switchQuizTab('new')">
                            <i class="fas fa-plus mr-2"></i>Buat Quiz Baru
                        </button>
                    </div>
                </div>
                
                <!-- Existing Quiz Tab Content -->
                <div id="existing-quiz-content" class="quiz-tab-content">
                    <div class="mb-4">
                        <label for="swal-quiz_id" class="block text-sm font-medium text-gray-700 mb-2">Pilih Quiz</label>
                        <select id="swal-quiz_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <option value="">-- Pilih Quiz --</option>
                        </select>
                        <small class="text-gray-500 mt-1">Quiz yang sudah digunakan di course ini akan dimark</small>
                    </div>
                </div>
                
                <!-- New Quiz Tab Content -->
                <div id="new-quiz-content" class="quiz-tab-content hidden">
                    <div class="space-y-4">
                        <div>
                            <label for="swal-new_quiz_title" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-heading mr-1"></i>Judul Quiz
                            </label>
                            <input type="text" id="swal-new_quiz_title" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent" placeholder="Masukkan judul quiz...">
                        </div>
                        
                        <div>
                            <label for="swal-new_quiz_description" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-align-left mr-1"></i>Deskripsi Quiz
                            </label>
                            <textarea id="swal-new_quiz_description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent" placeholder="Deskripsi singkat tentang quiz ini..."></textarea>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="swal-new_quiz_duration" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-clock mr-1"></i>Durasi (menit)
                                </label>
                                <input type="number" id="swal-new_quiz_duration" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent" placeholder="30" min="1" max="180" value="30">
                            </div>
                            
                            <div>
                                <label for="swal-new_quiz_type" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-tag mr-1"></i>Tipe Quiz
                                </label>
                                <select id="swal-new_quiz_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                    <option value="setelah_video">Setelah Video</option>
                                    <option value="setelah_section">Setelah Section</option>
                                    <option value="akhir_course">Akhir Course</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="bg-blue-50 p-3 rounded-lg">
                            <p class="text-sm text-blue-700">
                                <i class="fas fa-lightbulb mr-2"></i>
                                <strong>Tips:</strong> Setelah quiz dibuat, Anda bisa menambahkan pertanyaan melalui halaman detail quiz.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonColor: '#8B5CF6',
        cancelButtonColor: '#6B7280',
        confirmButtonText: '<i class="fas fa-plus mr-2"></i>Tambah Quiz',
        cancelButtonText: 'Batal',
        width: '700px',
        focusConfirm: false,
        didOpen: () => {
            loadQuizOptions();
        },
        preConfirm: () => {
            const activeTab = document.querySelector('.quiz-tab-content:not(.hidden)').id;
            
            if (activeTab === 'existing-quiz-content') {
                const quizId = document.getElementById('swal-quiz_id').value;
                if (!quizId) {
                    Swal.showValidationMessage('Pilih quiz yang akan ditambahkan!');
                    return false;
                }
                return { 
                    type: 'existing', 
                    quizId: quizId, 
                    position: position, 
                    referenceId: referenceId 
                };
            } else {
                const title = document.getElementById('swal-new_quiz_title').value.trim();
                const description = document.getElementById('swal-new_quiz_description').value.trim();
                const duration = document.getElementById('swal-new_quiz_duration').value;
                const quizType = document.getElementById('swal-new_quiz_type').value;
                
                if (!title) {
                    Swal.showValidationMessage('Judul quiz harus diisi!');
                    return false;
                }
                
                if (!duration || duration < 1) {
                    Swal.showValidationMessage('Durasi quiz harus minimal 1 menit!');
                    return false;
                }
                
                return { 
                    type: 'new', 
                    title: title,
                    description: description,
                    duration: parseInt(duration),
                    quizType: quizType,
                    position: position, 
                    referenceId: referenceId 
                };
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            addQuizToCourse(result.value);
        }
    });
}

// Switch between quiz tabs
function switchQuizTab(tabName) {
    // Update tab buttons
    document.getElementById('tab-existing').className = 'flex-1 py-2 px-4 text-sm font-medium text-center border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300';
    document.getElementById('tab-new').className = 'flex-1 py-2 px-4 text-sm font-medium text-center border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300';
    
    // Update content
    document.getElementById('existing-quiz-content').classList.add('hidden');
    document.getElementById('new-quiz-content').classList.add('hidden');
    
    if (tabName === 'existing') {
        document.getElementById('tab-existing').className = 'flex-1 py-2 px-4 text-sm font-medium text-center border-b-2 border-purple-500 text-purple-600 bg-purple-50';
        document.getElementById('existing-quiz-content').classList.remove('hidden');
    } else {
        document.getElementById('tab-new').className = 'flex-1 py-2 px-4 text-sm font-medium text-center border-b-2 border-purple-500 text-purple-600 bg-purple-50';
        document.getElementById('new-quiz-content').classList.remove('hidden');
    }
}

// Load quiz options
function loadQuizOptions() {
    fetch(`/admin/courses/{{ $course->course_id }}/available-quizzes`)
        .then(response => response.json())
        .then(data => {
            const select = document.getElementById('swal-quiz_id');
            select.innerHTML = '<option value="">-- Pilih Quiz --</option>';
            
            data.quizzes.forEach(quiz => {
                const option = document.createElement('option');
                option.value = quiz.quiz_id;
                option.textContent = quiz.nama_quiz;
                if (quiz.is_used) {
                    option.textContent += ' (sudah digunakan)';
                    option.disabled = true;
                }
                select.appendChild(option);
            });
        })
        .catch(error => {
            console.error('Error loading quizzes:', error);
            showErrorToast('Gagal memuat daftar quiz');
        });
}

// Add quiz to course
function addQuizToCourse(data) {
    let payload = {
        position: data.position,
        reference_id: data.referenceId
    };
    
    if (data.type === 'existing') {
        payload.quiz_id = data.quizId;
    } else if (data.type === 'new') {
        payload.quiz_title = data.title;
        payload.quiz_description = data.description;
        payload.quiz_duration = data.duration;
        payload.quiz_type = data.quizType;
    }
    
    Swal.fire({
        title: 'Memproses Quiz...',
        text: data.type === 'new' ? 'Membuat quiz baru dan menambahkan ke course...' : 'Menambahkan quiz ke course...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    fetch(`/admin/courses/{{ $course->course_id }}/quizzes/add`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(payload)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: data.message || 'Quiz berhasil ditambahkan ke course!',
                showConfirmButton: true,
                confirmButtonText: 'OK'
            }).then(() => {
                if (data.quiz_id && data.type === 'new') {
                    // Jika quiz baru dibuat, tanya apakah ingin menambah pertanyaan sekarang
                    Swal.fire({
                        title: 'Quiz Berhasil Dibuat!',
                        text: 'Apakah Anda ingin menambahkan pertanyaan ke quiz ini sekarang?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#8B5CF6',
                        cancelButtonColor: '#6B7280',
                        confirmButtonText: 'Ya, Tambah Pertanyaan',
                        cancelButtonText: 'Nanti Saja'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.open(`/admin/courses/quiz/${data.quiz_id}/questions`, '_blank');
                        }
                        location.reload();
                    });
                } else {
                    location.reload();
                }
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message || 'Gagal menambahkan quiz ke course'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Terjadi kesalahan saat menambahkan quiz'
        });
    });
}

// Remove quiz from course
function removeQuizFromCourse(courseQuizId) {
    Swal.fire({
        title: 'Hapus Quiz?',
        text: 'Quiz akan dihapus dari course ini. Quiz itu sendiri tidak akan dihapus.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/admin/courses/{{ $course->course_id }}/quizzes/${courseQuizId}/remove`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccessToast('Quiz berhasil dihapus dari course');
                    location.reload();
                } else {
                    showErrorToast(data.message || 'Gagal menghapus quiz');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showErrorToast('Terjadi kesalahan saat menghapus quiz');
            });
        }
    });
}

// Toast notifications
function showSuccessToast(message) {
    Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: message,
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
    });
}

function showErrorToast(message) {
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: message,
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
    });
}

// Toggle course status
function toggleCourseStatus(courseId, newStatus) {
    const title = newStatus === 'true' ? 'Aktifkan Course?' : 'Nonaktifkan Course?';
    const text = newStatus === 'true' ? 'Course akan dapat diakses oleh siswa.' : 'Course akan disembunyikan dari siswa.';
    const confirmText = newStatus === 'true' ? 'Ya, Aktifkan' : 'Ya, Nonaktifkan';

    Swal.fire({
        title: title,
        text: text,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: newStatus === 'true' ? '#10B981' : '#EF4444',
        cancelButtonColor: '#6B7280',
        confirmButtonText: confirmText,
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            showLoading('Memproses...');

            // Create form and submit
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/courses/${courseId}/toggle-status`;

            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            form.appendChild(csrfToken);
            document.body.appendChild(form);
            form.submit();
        }
    });
}

// Toggle section visibility
function toggleSection(sectionId) {
    const section = document.getElementById(`section-${sectionId}`);
    const arrow = document.getElementById(`arrow-${sectionId}`);

    if (section.classList.contains('hidden')) {
        section.classList.remove('hidden');
        arrow.classList.add('transform', 'rotate-180');
    } else {
        section.classList.add('hidden');
        arrow.classList.remove('transform', 'rotate-180');
    }
}

// Show add section dialog
function showAddSectionDialog() {
    Swal.fire({
        title: '<i class="fas fa-plus text-green-500"></i> Tambah Section Baru',
        html: `
            <div class="text-left">
                <div class="mb-4">
                    <label for="swal-nama_section" class="block text-sm font-medium text-gray-700 mb-2">Nama Section</label>
                    <input type="text" id="swal-nama_section" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Masukkan nama section..." required>
                </div>
                <div class="mb-4">
                    <label for="swal-deskripsi_section" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Section (opsional)</label>
                    <textarea id="swal-deskripsi_section" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Deskripsi section..."></textarea>
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonColor: '#10B981',
        cancelButtonColor: '#6B7280',
        confirmButtonText: '<i class="fas fa-save mr-2"></i>Tambah Section',
        cancelButtonText: 'Batal',
        focusConfirm: false,
        preConfirm: () => {
            const namaSection = document.getElementById('swal-nama_section').value;
            const deskripsiSection = document.getElementById('swal-deskripsi_section').value;

            if (!namaSection) {
                Swal.showValidationMessage('Nama section harus diisi!');
                return false;
            }

            return { namaSection, deskripsiSection };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Create form and submit
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/courses/{{ $course->course_id }}/sections`;

            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const namaInput = document.createElement('input');
            namaInput.type = 'hidden';
            namaInput.name = 'nama_section';
            namaInput.value = result.value.namaSection;

            const deskripsiInput = document.createElement('input');
            deskripsiInput.type = 'hidden';
            deskripsiInput.name = 'deskripsi_section';
            deskripsiInput.value = result.value.deskripsiSection;

            form.appendChild(csrfToken);
            form.appendChild(namaInput);
            form.appendChild(deskripsiInput);
            document.body.appendChild(form);
            form.submit();
        }
    });
}

// Show add video dialog with YouTube URL support
function showAddVideoDialog(sectionId, sectionName) {
    Swal.fire({
        title: `<i class="fas fa-video text-blue-500"></i> Tambah Video ke ${sectionName}`,
        html: `
            <div class="text-left">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Metode Input Video</label>
                    <div class="flex space-x-4 mb-4">
                        <label class="flex items-center">
                            <input type="radio" name="video_method" value="existing" checked class="mr-2" onchange="toggleVideoMethod()">
                            <span>Pilih dari Video yang Ada</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="video_method" value="youtube" class="mr-2" onchange="toggleVideoMethod()">
                            <span>Input URL YouTube Baru</span>
                        </label>
                    </div>
                </div>

                <!-- Existing Video Selection -->
                <div id="existing-video-section" class="mb-4">
                    <label for="swal-vidio_vidio_id" class="block text-sm font-medium text-gray-700 mb-2">Pilih Video</label>
                    <select id="swal-vidio_vidio_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">-- Pilih Video --</option>
                        @foreach($availableVideos as $video)
                            <option value="{{ $video->vidio_id }}">{{ $video->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- YouTube URL Input -->
                <div id="youtube-video-section" class="mb-4" style="display: none;">
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                        <h4 class="text-yellow-800 font-semibold mb-2 flex items-center">
                            <i class="fab fa-youtube mr-2"></i>Input Video YouTube
                        </h4>
                        <p class="text-yellow-700 text-sm">Masukkan URL YouTube, durasi akan otomatis diambil dari video.</p>
                    </div>

                    <label for="swal-youtube_url" class="block text-sm font-medium text-gray-700 mb-2">URL YouTube</label>
                    <input type="url" id="swal-youtube_url"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="https://www.youtube.com/watch?v=...">

                    <label for="swal-video_title" class="block text-sm font-medium text-gray-700 mb-2 mt-4">Judul Video</label>
                    <input type="text" id="swal-video_title"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Masukkan judul video...">

                    <label for="swal-video_description" class="block text-sm font-medium text-gray-700 mb-2 mt-4">Deskripsi Video (opsional)</label>
                    <textarea id="swal-video_description" rows="2"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              placeholder="Deskripsi video..."></textarea>

                    <div class="mt-4 flex items-center space-x-2">
                        <button type="button" onclick="fetchYouTubeData()"
                                class="px-3 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm">
                            <i class="fab fa-youtube mr-1"></i>Ambil Data YouTube
                        </button>
                        <span id="youtube-status" class="text-sm text-gray-500"></span>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="swal-durasi_menit" class="block text-sm font-medium text-gray-700 mb-2">Durasi (menit)</label>
                    <input type="number" id="swal-durasi_menit" min="1"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Durasi dalam menit...">
                    <small class="text-gray-500">Akan otomatis terisi jika menggunakan YouTube URL</small>
                </div>
                <div class="mb-4">
                    <label for="swal-catatan_admin" class="block text-sm font-medium text-gray-700 mb-2">Catatan Admin (opsional)</label>
                    <textarea id="swal-catatan_admin" rows="2"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              placeholder="Catatan untuk video ini..."></textarea>
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonColor: '#3B82F6',
        cancelButtonColor: '#6B7280',
        confirmButtonText: '<i class="fas fa-save mr-2"></i>Tambah Video',
        cancelButtonText: 'Batal',
        width: '600px',
        focusConfirm: false,
        preConfirm: () => {
            const method = document.querySelector('input[name="video_method"]:checked').value;

            if (method === 'existing') {
                const vidioId = document.getElementById('swal-vidio_vidio_id').value;
                const durasi = document.getElementById('swal-durasi_menit').value;
                const catatan = document.getElementById('swal-catatan_admin').value;

                if (!vidioId) {
                    Swal.showValidationMessage('Video harus dipilih!');
                    return false;
                }
                if (!durasi || durasi < 1) {
                    Swal.showValidationMessage('Durasi harus diisi dan minimal 1 menit!');
                    return false;
                }

                return { method, vidioId, durasi, catatan };

            } else {
                const youtubeUrl = document.getElementById('swal-youtube_url').value;
                const videoTitle = document.getElementById('swal-video_title').value;
                const videoDescription = document.getElementById('swal-video_description').value;
                const durasi = document.getElementById('swal-durasi_menit').value;
                const catatan = document.getElementById('swal-catatan_admin').value;

                if (!youtubeUrl) {
                    Swal.showValidationMessage('URL YouTube harus diisi!');
                    return false;
                }
                if (!videoTitle) {
                    Swal.showValidationMessage('Judul video harus diisi!');
                    return false;
                }
                if (!durasi || durasi < 1) {
                    Swal.showValidationMessage('Durasi harus diisi dan minimal 1 menit!');
                    return false;
                }

                return { method, youtubeUrl, videoTitle, videoDescription, durasi, catatan };
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            showLoading('Menambah video...');

            // Create form and submit
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/courses/{{ $course->course_id }}/videos`;

            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const sectionInput = document.createElement('input');
            sectionInput.type = 'hidden';
            sectionInput.name = 'section_id';
            sectionInput.value = sectionId;

            const durasiInput = document.createElement('input');
            durasiInput.type = 'hidden';
            durasiInput.name = 'durasi_menit';
            durasiInput.value = result.value.durasi;

            const catatanInput = document.createElement('input');
            catatanInput.type = 'hidden';
            catatanInput.name = 'catatan_admin';
            catatanInput.value = result.value.catatan;

            form.appendChild(csrfToken);
            form.appendChild(sectionInput);
            form.appendChild(durasiInput);
            form.appendChild(catatanInput);

            if (result.value.method === 'existing') {
                const vidioInput = document.createElement('input');
                vidioInput.type = 'hidden';
                vidioInput.name = 'vidio_vidio_id';
                vidioInput.value = result.value.vidioId;
                form.appendChild(vidioInput);
            } else {
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = 'video_method';
                methodInput.value = 'youtube';
                form.appendChild(methodInput);

                const urlInput = document.createElement('input');
                urlInput.type = 'hidden';
                urlInput.name = 'youtube_url';
                urlInput.value = result.value.youtubeUrl;
                form.appendChild(urlInput);

                const titleInput = document.createElement('input');
                titleInput.type = 'hidden';
                titleInput.name = 'video_title';
                titleInput.value = result.value.videoTitle;
                form.appendChild(titleInput);

                const descInput = document.createElement('input');
                descInput.type = 'hidden';
                descInput.name = 'video_description';
                descInput.value = result.value.videoDescription;
                form.appendChild(descInput);
            }

            document.body.appendChild(form);
            form.submit();
        }
    });
}

// Toggle video input method
function toggleVideoMethod() {
    const method = document.querySelector('input[name="video_method"]:checked').value;
    const existingSection = document.getElementById('existing-video-section');
    const youtubeSection = document.getElementById('youtube-video-section');

    if (method === 'existing') {
        existingSection.style.display = 'block';
        youtubeSection.style.display = 'none';
    } else {
        existingSection.style.display = 'none';
        youtubeSection.style.display = 'block';
    }
}

// Fetch YouTube video data (title, duration, etc.)
async function fetchYouTubeData() {
    const url = document.getElementById('swal-youtube_url').value;
    const statusEl = document.getElementById('youtube-status');

    if (!url) {
        statusEl.textContent = 'Masukkan URL YouTube terlebih dahulu';
        statusEl.className = 'text-sm text-red-500';
        return;
    }

    statusEl.textContent = 'Mengambil data YouTube...';
    statusEl.className = 'text-sm text-blue-500';

    try {
        // Extract video ID from URL
        const videoId = extractYouTubeVideoId(url);
        if (!videoId) {
            throw new Error('URL YouTube tidak valid');
        }

        // Use oEmbed API to get basic video info (doesn't require API key)
        const oembedUrl = `https://www.youtube.com/oembed?url=${encodeURIComponent(url)}&format=json`;

        const response = await fetch(oembedUrl);

        if (!response.ok) {
            throw new Error('Video tidak ditemukan atau privat');
        }

        const data = await response.json();

        // Auto-fill title
        const titleInput = document.getElementById('swal-video_title');
        if (data.title && !titleInput.value) {
            titleInput.value = data.title;
        }

        // Try to estimate duration (this is a fallback, as oEmbed doesn't provide duration)
        // In production, you would need YouTube Data API v3 for accurate duration
        statusEl.textContent = 'Data berhasil diambil! Silakan isi durasi video secara manual.';
        statusEl.className = 'text-sm text-green-500';

        // Focus on duration input
        const durationInput = document.getElementById('swal-durasi_menit');
        durationInput.focus();

        // Show additional info if available
        if (data.author_name) {
            statusEl.textContent += ` Channel: ${data.author_name}`;
        }

    } catch (error) {
        // Fallback: just validate URL format
        const videoId = extractYouTubeVideoId(url);
        if (videoId) {
            statusEl.textContent = 'URL valid! Silakan isi judul dan durasi video secara manual.';
            statusEl.className = 'text-sm text-orange-500';

            // Auto-generate placeholder if empty
            const titleInput = document.getElementById('swal-video_title');
            if (!titleInput.value) {
                titleInput.placeholder = 'Masukkan judul video...';
                titleInput.focus();
            }
        } else {
            statusEl.textContent = 'URL YouTube tidak valid';
            statusEl.className = 'text-sm text-red-500';
        }
    }
}

// Extract YouTube video ID from URL
function extractYouTubeVideoId(url) {
    const regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#&?]*).*/;
    const match = url.match(regExp);
    return (match && match[7].length == 11) ? match[7] : null;
}

// Show add review dialog
function showAddReviewDialog() {
    Swal.fire({
        title: '<i class="fas fa-star text-yellow-500"></i> Tambah Quick Review',
        html: `
            <div class="text-left">
                <div class="mb-4">
                    <label for="swal-judul_review" class="block text-sm font-medium text-gray-700 mb-2">Judul Review</label>
                    <input type="text" id="swal-judul_review" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Judul review..." required>
                </div>
                <div class="mb-4">
                    <label for="swal-tipe_review" class="block text-sm font-medium text-gray-700 mb-2">Tipe Review</label>
                    <select id="swal-tipe_review" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        <option value="">-- Pilih Tipe --</option>
                        <option value="setelah_video">Setelah Video</option>
                        <option value="setelah_section">Setelah Section</option>
                        <option value="tengah_course">Tengah Course</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="swal-konten_review" class="block text-sm font-medium text-gray-700 mb-2">Konten Review</label>
                    <textarea id="swal-konten_review" rows="6" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Konten review (gunakan HTML tags untuk formatting)..." required></textarea>
                    <small class="text-gray-500">Gunakan HTML tags untuk formatting (h3, ul, li, strong, em, dll.)</small>
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonColor: '#F59E0B',
        cancelButtonColor: '#6B7280',
        confirmButtonText: '<i class="fas fa-save mr-2"></i>Tambah Review',
        cancelButtonText: 'Batal',
        width: '600px',
        focusConfirm: false,
        preConfirm: () => {
            const judul = document.getElementById('swal-judul_review').value;
            const tipe = document.getElementById('swal-tipe_review').value;
            const konten = document.getElementById('swal-konten_review').value;

            if (!judul) {
                Swal.showValidationMessage('Judul review harus diisi!');
                return false;
            }
            if (!tipe) {
                Swal.showValidationMessage('Tipe review harus dipilih!');
                return false;
            }
            if (!konten) {
                Swal.showValidationMessage('Konten review harus diisi!');
                return false;
            }

            return { judul, tipe, konten };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Create form and submit
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/courses/{{ $course->course_id }}/reviews`;

            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const judulInput = document.createElement('input');
            judulInput.type = 'hidden';
            judulInput.name = 'judul_review';
            judulInput.value = result.value.judul;

            const tipeInput = document.createElement('input');
            tipeInput.type = 'hidden';
            tipeInput.name = 'tipe_review';
            tipeInput.value = result.value.tipe;

            const kontenInput = document.createElement('input');
            kontenInput.type = 'hidden';
            kontenInput.name = 'konten_review';
            kontenInput.value = result.value.konten;

            form.appendChild(csrfToken);
            form.appendChild(judulInput);
            form.appendChild(tipeInput);
            form.appendChild(kontenInput);
            document.body.appendChild(form);
            form.submit();
        }
    });
}

// Remove video
function removeVideo(courseId, courseVideoId) {
    showDeleteConfirm('Video akan dihapus dari section ini. Tindakan ini tidak dapat dibatalkan.', 'Hapus Video?')
    .then((result) => {
        if (result.isConfirmed) {
            showLoading('Menghapus video...');

            // Create form and submit
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/courses/${courseId}/videos/${courseVideoId}`;

            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';

            form.appendChild(csrfToken);
            form.appendChild(methodInput);
            document.body.appendChild(form);
            form.submit();
        }
    });
}

// Remove section
function removeSection(courseId, sectionId) {
    showDeleteConfirm('Section dan semua video di dalamnya akan dihapus. Tindakan ini tidak dapat dibatalkan.', 'Hapus Section?')
    .then((result) => {
        if (result.isConfirmed) {
            showLoading('Menghapus section...');

            // Create form and submit
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/courses/${courseId}/sections/${sectionId}`;

            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';

            form.appendChild(csrfToken);
            form.appendChild(methodInput);
            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>
@endpush
