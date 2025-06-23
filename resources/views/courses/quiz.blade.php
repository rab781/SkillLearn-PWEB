@extends('layouts.app')

@section('title', $quiz->judul_quiz . ' - ' . $course->nama_course . ' - SkillLearn')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50">
    <!-- Quiz Header -->
    <div id="quiz-header" class="relative bg-gradient-to-r from-purple-600 via-blue-600 to-purple-800 text-white overflow-hidden">
        <div class="absolute inset-0 bg-black opacity-20"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
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
                            <span class="text-white font-medium">{{ $quiz->judul_quiz }}</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Quiz Info -->
                <div class="lg:col-span-2">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-question-circle text-2xl text-white"></i>
                        </div>
                        <div>
                            <h1 class="text-3xl lg:text-4xl font-bold mb-2">{{ $quiz->judul_quiz }}</h1>
                            <p class="text-blue-100">{{ $course->nama_course }}</p>
                        </div>
                    </div>

                    @if($quiz->deskripsi_quiz)
                        <p class="text-xl text-blue-100 mb-6 leading-relaxed">{{ $quiz->deskripsi_quiz }}</p>
                    @endif

                    <!-- Quiz Stats -->
                    <div class="grid grid-cols-3 gap-6">
                        <div class="text-center">
                            <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-list text-2xl text-white"></i>
                            </div>
                            <div class="text-2xl font-bold text-white">{{ $quiz->questions->count() }}</div>
                            <div class="text-blue-200 text-sm">Soal</div>
                        </div>
                        <div class="text-center">
                            <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-clock text-2xl text-white"></i>
                            </div>
                            <div class="text-2xl font-bold text-white">{{ $quiz->durasi_menit ?? 30 }}</div>
                            <div class="text-blue-200 text-sm">Menit</div>
                        </div>
                        <div class="text-center">
                            <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-trophy text-2xl text-white"></i>
                            </div>
                            <div class="text-2xl font-bold text-white">{{ $bestScore }}%</div>
                            <div class="text-blue-200 text-sm">Best Score</div>
                        </div>
                    </div>
                </div>

                <!-- Quiz Action Card -->
                <div class="lg:col-span-1">
                    <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-2xl p-6">
                        <div class="text-center mb-6">
                            <div class="w-20 h-20 bg-gradient-to-br from-purple-500 to-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-play text-2xl text-white"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Mulai Quiz</h3>
                            <p class="text-gray-600 text-sm">Uji pemahaman Anda tentang materi yang telah dipelajari</p>
                        </div>

                        <!-- Quiz Info -->
                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Jumlah Soal</span>
                                <span class="font-semibold text-gray-900">{{ $quiz->questions->count() }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Durasi</span>
                                <span class="font-semibold text-gray-900">{{ $quiz->durasi_menit ?? 30 }} menit</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Passing Score</span>
                                <span class="font-semibold text-green-600">60%</span>
                            </div>
                            @if($attemptCount > 0)
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">Attempts</span>
                                    <span class="font-semibold text-blue-600">{{ $attemptCount }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Start Quiz Button -->
                        <button onclick="startQuiz()"
                                class="w-full bg-gradient-to-r from-purple-500 to-blue-600 hover:from-purple-600 hover:to-blue-700 text-white font-bold py-4 px-6 rounded-xl transition-all duration-300 transform hover:scale-105 flex items-center justify-center shadow-lg">
                            <i class="fas fa-play mr-3"></i>
                            {{ $attemptCount > 0 ? 'Ulangi Quiz' : 'Mulai Quiz' }}
                        </button>

                        @if($attemptCount > 0)
                            <div class="mt-4 text-center">
                                <button onclick="showPreviousResults()"
                                        class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                                    <i class="fas fa-history mr-1"></i>Lihat Hasil Sebelumnya
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quiz Content -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Quiz Instructions -->
        <div id="quiz-instructions" class="bg-white rounded-2xl shadow-xl p-8 mb-8">
            <div class="flex items-center mb-6">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center mr-4">
                    <i class="fas fa-info-circle text-white text-xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Petunjuk Quiz</h2>
                    <p class="text-gray-600">Baca dengan teliti sebelum memulai</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mr-3 mt-1">
                            <span class="font-bold text-sm">1</span>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900">Waktu Terbatas</h4>
                            <p class="text-gray-600 text-sm">Anda memiliki {{ $quiz->durasi_menit ?? 30 }} menit untuk menyelesaikan {{ $quiz->questions->count() }} soal.</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mr-3 mt-1">
                            <span class="font-bold text-sm">2</span>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900">Satu Jawaban</h4>
                            <p class="text-gray-600 text-sm">Setiap soal hanya memiliki satu jawaban yang benar.</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mr-3 mt-1">
                            <span class="font-bold text-sm">3</span>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900">Tidak Bisa Kembali</h4>
                            <p class="text-gray-600 text-sm">Setelah menjawab soal, Anda tidak dapat mengubah jawaban.</p>
                        </div>
                    </div>
                </div>
                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="w-8 h-8 bg-green-100 text-green-600 rounded-full flex items-center justify-center mr-3 mt-1">
                            <span class="font-bold text-sm">4</span>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900">Passing Score</h4>
                            <p class="text-gray-600 text-sm">Anda harus mencapai minimal 60% untuk lulus quiz.</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="w-8 h-8 bg-green-100 text-green-600 rounded-full flex items-center justify-center mr-3 mt-1">
                            <span class="font-bold text-sm">5</span>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900">Dapat Diulang</h4>
                            <p class="text-gray-600 text-sm">Anda dapat mengulang quiz jika belum mencapai target.</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="w-8 h-8 bg-green-100 text-green-600 rounded-full flex items-center justify-center mr-3 mt-1">
                            <span class="font-bold text-sm">6</span>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900">Auto Submit</h4>
                            <p class="text-gray-600 text-sm">Quiz akan otomatis submit ketika waktu habis.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quiz Questions Container (Hidden initially) -->
        <div id="quiz-container" class="hidden" style="display: none;">
            <!-- Timer -->
            <div class="bg-white rounded-2xl shadow-xl p-6 mb-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <i class="fas fa-clock text-blue-600 mr-3"></i>
                        <span class="text-lg font-semibold text-gray-900">Waktu Tersisa:</span>
                    </div>
                    <div id="timer" class="text-2xl font-bold text-red-600">
                        {{ $quiz->durasi_menit ?? 30 }}:00
                    </div>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2 mt-3">
                    <div id="timer-progress" class="bg-red-500 h-2 rounded-full transition-all duration-1000" style="width: 100%"></div>
                </div>
            </div>

            <!-- Quiz Form -->
            <form id="quiz-form">
                @csrf
                <input type="hidden" name="quiz_id" value="{{ $quiz->quiz_id }}">
                <input type="hidden" name="course_id" value="{{ $course->course_id }}">

                <div id="questions-container"></div>

                <!-- Submit Button -->
                <div class="text-center mt-8">
                    <button type="submit" id="submit-quiz-btn"
                            class="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-bold py-4 px-8 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg">
                        <i class="fas fa-check mr-3"></i>
                        Submit Quiz
                    </button>
                </div>
            </form>
        </div>

        <!-- Previous Results -->
        @if($previousResults->count() > 0)
        <div id="previous-results" class="hidden bg-white rounded-2xl shadow-xl p-8">
            <div class="flex items-center mb-6">
                <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-xl flex items-center justify-center mr-4">
                    <i class="fas fa-history text-white text-xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Hasil Quiz Sebelumnya</h2>
                    <p class="text-gray-600">{{ $previousResults->count() }} percobaan</p>
                </div>
            </div>

            <div class="space-y-4">
                @foreach($previousResults as $result)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div>
                        <div class="font-semibold {{ $result->nilai_total >= 60 ? 'text-green-600' : 'text-red-600' }}">
                            Percobaan #{{ $loop->iteration }}
                        </div>
                        <div class="text-sm text-gray-600">
                            {{ $result->created_at->format('d M Y, H:i') }}
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-2xl font-bold {{ $result->nilai_total >= 60 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $result->nilai_total }}%
                        </div>
                        <div class="text-sm text-gray-600">
                            {{ $result->jumlah_benar }}/{{ $result->total_soal }} benar
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
// Global variables
let quizTimer;
let timeRemaining;
let totalTime;
let currentQuestionIndex = 0;
let quizQuestions = [];
let userAnswers = {};

// Quiz data from backend
const quizData = {
    quiz_id: {{ $quiz->quiz_id }},
    course_id: {{ $course->course_id }},
    duration: {{ $quiz->durasi_menit ?? 30 }},
    questions: {!! json_encode($quiz->questions->map(function($question) {
        return [
            'question_id' => $question->question_id,
            'pertanyaan' => $question->pertanyaan,
            'pilihan_jawaban' => $question->pilihan_jawaban,
            'urutan_pertanyaan' => $question->urutan_pertanyaan
        ];
    })->values()) !!}
};

// Helper functions for SweetAlert2
function showLoading(message = 'Memproses...') {
    Swal.fire({
        title: message,
        html: '<div class="flex items-center justify-center"><i class="fas fa-spinner fa-spin mr-3"></i>Mohon tunggu...</div>',
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        customClass: {
            popup: 'swal2-loading'
        },
        willOpen: () => {
            Swal.showLoading();
        }
    });
}

function showError(message) {
    Swal.fire({
        icon: 'error',
        title: 'Terjadi Kesalahan!',
        text: message,
        confirmButtonText: '<i class="fas fa-times mr-2"></i>Tutup',
        confirmButtonColor: '#EF4444'
    });
}

// Main quiz functions - these need to be global for onclick access
function startQuiz() {
    console.log('Start quiz clicked');
    console.log('Quiz data:', quizData);
    
    if (!quizData.questions || quizData.questions.length === 0) {
        showError('Tidak ada soal yang tersedia untuk quiz ini.');
        return;
    }
    
    Swal.fire({
        title: 'Mulai Quiz?',
        text: `Anda akan memulai quiz dengan ${quizData.questions.length} soal dalam waktu ${quizData.duration} menit.`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3B82F6',
        cancelButtonColor: '#6B7280',
        confirmButtonText: '<i class="fas fa-play mr-2"></i>Ya, Mulai Quiz!',
        cancelButtonText: '<i class="fas fa-times mr-2"></i>Batal',
        reverseButtons: true,
        allowOutsideClick: false
    }).then((result) => {
        if (result.isConfirmed) {
            console.log('User confirmed, initializing quiz...');
            initializeQuiz();
        }
    });
}

function initializeQuiz() {
    console.log('Initializing quiz...');
    console.log('Quiz data:', quizData);
    
    // Hide header, instructions and previous results for focused quiz experience
    const quizHeader = document.getElementById('quiz-header');
    if (quizHeader) {
        quizHeader.style.display = 'none';
        console.log('Quiz header hidden');
    }
    
    const instructionsDiv = document.getElementById('quiz-instructions');
    if (instructionsDiv) {
        instructionsDiv.style.display = 'none';
        console.log('Instructions hidden');
    }
    
    const previousResults = document.getElementById('previous-results');
    if (previousResults) {
        previousResults.style.display = 'none';
        console.log('Previous results hidden');
    }
    
    // Show quiz container with smooth transition
    const quizContainer = document.getElementById('quiz-container');
    if (quizContainer) {
        quizContainer.classList.remove('hidden');
        quizContainer.style.display = 'block';
        // Add smooth fade-in effect
        quizContainer.style.opacity = '0';
        quizContainer.style.transition = 'opacity 0.5s ease-in-out';
        setTimeout(() => {
            quizContainer.style.opacity = '1';
        }, 100);
        console.log('Quiz container shown');
    }

    // Initialize quiz data
    quizQuestions = quizData.questions.sort((a, b) => a.urutan_pertanyaan - b.urutan_pertanyaan);
    totalTime = quizData.duration * 60; // Convert to seconds
    timeRemaining = totalTime;
    
    console.log(`Quiz initialized: ${quizQuestions.length} questions, ${totalTime} seconds`);

    // Load questions
    loadQuestions();

    // Start timer
    startTimer();

    // Prevent page reload/close
    window.addEventListener('beforeunload', function(e) {
        e.preventDefault();
        e.returnValue = '';
    });
    
    // Scroll to top for focused experience
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function loadQuestions() {
    console.log('Loading questions...');
    const container = document.getElementById('questions-container');
    if (!container) {
        console.error('Questions container not found!');
        return;
    }
    
    container.innerHTML = '';
    console.log(`Loading ${quizQuestions.length} questions`);

    quizQuestions.forEach((question, index) => {
        console.log(`Loading question ${index + 1}:`, question);
        
        const questionDiv = document.createElement('div');
        questionDiv.className = 'bg-white rounded-2xl shadow-xl p-8 mb-6';

        // Parse pilihan_jawaban if it's a JSON string
        let options = question.pilihan_jawaban;
        if (typeof options === 'string') {
            try {
                options = JSON.parse(options);
                console.log(`Parsed options for question ${index + 1}:`, options);
            } catch (e) {
                console.error(`Failed to parse options for question ${index + 1}:`, options);
                options = [];
            }
        }

        if (!Array.isArray(options)) {
            console.error(`Question options not found or not array:`, options);
            return;
        }

        let optionsHtml = '';
        options.forEach((option, optionIndex) => {
            const optionId = `question_${question.question_id}_option_${optionIndex}`;
            console.log(`Creating option ${optionIndex + 1}: "${option}"`);
            
            // Simple escaping for HTML attributes
            const escapedOption = String(option).replace(/"/g, '&quot;').replace(/'/g, '&#39;');
            const safeOption = String(option).replace(/</g, '&lt;').replace(/>/g, '&gt;');
            
            optionsHtml += `
                <div class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-blue-50 transition-colors cursor-pointer option-container"
                     onclick="selectAnswer(${question.question_id}, \`${escapedOption}\`, this)">
                    <input type="radio" id="${optionId}" name="question_${question.question_id}" value="${escapedOption}"
                           class="mr-4 w-5 h-5 text-blue-600 flex-shrink-0">
                    <label for="${optionId}" class="flex-1 cursor-pointer font-medium text-gray-700">
                        ${safeOption}
                    </label>
                </div>
            `;
        });
        
        console.log('Generated options HTML:', optionsHtml);

        questionDiv.innerHTML = `
            <div class="flex items-center mb-6">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center mr-4">
                    <span class="text-white font-bold">${index + 1}</span>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Soal ${index + 1}</h3>
                    <p class="text-gray-600">Pilih satu jawaban yang paling tepat</p>
                </div>
            </div>

            <div class="mb-6">
                <p class="text-lg text-gray-800 leading-relaxed">${question.pertanyaan}</p>
            </div>

            <div class="space-y-3">
                ${optionsHtml}
            </div>
        `;

        container.appendChild(questionDiv);
        console.log(`Question ${index + 1} added to container`);
        
        // Debug: Check if options are actually rendered
        const addedOptions = questionDiv.querySelectorAll('.option-container');
        console.log(`Question ${index + 1} rendered with ${addedOptions.length} options`);
        addedOptions.forEach((opt, i) => {
            console.log(`Option ${i + 1} text:`, opt.querySelector('label').textContent);
        });
    });
    
    console.log('Questions loaded successfully');
}

function selectAnswer(questionId, answer, element) {
    console.log(`Selected answer for question ${questionId}: "${answer}"`);
    
    // Remove previous selection in this question
    const questionContainer = element.closest('.bg-white');
    const allOptions = questionContainer.querySelectorAll('.option-container');
    
    allOptions.forEach(option => {
        option.classList.remove('bg-blue-100', 'border-blue-500', 'selected');
        option.classList.add('bg-gray-50');
        const radio = option.querySelector('input[type="radio"]');
        if (radio) radio.checked = false;
    });

    // Highlight selected answer with enhanced styling
    element.classList.remove('bg-gray-50');
    element.classList.add('bg-blue-100', 'border-blue-500', 'selected');

    // Check the radio button
    const radio = element.querySelector('input[type="radio"]');
    if (radio) {
        radio.checked = true;
        console.log('Radio button checked for question', questionId);
    }

    // Store answer
    userAnswers[questionId] = answer;
    console.log('Current answers:', userAnswers);
    
    // Optional: Add a subtle success animation
    element.style.animation = 'none';
    element.offsetHeight; // Trigger reflow
    element.style.animation = 'pulse 0.3s ease-in-out';
}

function startTimer() {
    console.log('Starting timer...');
    const timerElement = document.getElementById('timer');
    const progressElement = document.getElementById('timer-progress');
    
    if (!timerElement) {
        console.error('Timer element not found!');
        return;
    }
    
    if (!progressElement) {
        console.error('Timer progress element not found!');
        return;
    }
    
    console.log(`Timer started with ${timeRemaining} seconds (${totalTime} total)`);

    // Clear any existing timer
    if (quizTimer) {
        clearInterval(quizTimer);
    }

    quizTimer = setInterval(() => {
        timeRemaining--;

        const minutes = Math.floor(timeRemaining / 60);
        const seconds = timeRemaining % 60;

        timerElement.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
        console.log(`Timer: ${minutes}:${seconds.toString().padStart(2, '0')}`);

        // Update progress bar
        const progress = (timeRemaining / totalTime) * 100;
        progressElement.style.width = `${progress}%`;

        // Enhanced warning system when time is running out
        if (timeRemaining <= 300) { // 5 minutes
            timerElement.classList.add('text-red-600', 'timer-warning');
            progressElement.classList.remove('bg-blue-500');
            progressElement.classList.add('bg-red-500');
        } else if (timeRemaining <= 600) { // 10 minutes
            timerElement.classList.remove('text-blue-600');
            timerElement.classList.add('text-yellow-600');
            progressElement.classList.remove('bg-blue-500');
            progressElement.classList.add('bg-yellow-500');
        }

        // Show warning at 5 minutes and 1 minute
        if (timeRemaining === 300) {
            Swal.fire({
                title: 'Perhatian!',
                text: 'Waktu tersisa 5 menit lagi. Periksa jawaban Anda.',
                icon: 'warning',
                timer: 3000,
                timerProgressBar: true,
                toast: true,
                position: 'top-end',
                showConfirmButton: false
            });
        } else if (timeRemaining === 60) {
            Swal.fire({
                title: 'Waktu Hampir Habis!',
                text: 'Waktu tersisa 1 menit lagi!',
                icon: 'error',
                timer: 3000,
                timerProgressBar: true,
                toast: true,
                position: 'top-end',
                showConfirmButton: false
            });
        }

        if (timeRemaining <= 0) {
            console.log('Time up! Auto-submitting...');
            clearInterval(quizTimer);
            autoSubmitQuiz();
        }
    }, 1000);
    
    console.log('Timer interval started');
}

function autoSubmitQuiz() {
    Swal.fire({
        title: 'Waktu Habis!',
        text: 'Quiz akan otomatis di-submit karena waktu telah habis.',
        icon: 'warning',
        timer: 5000,
        timerProgressBar: true,
        showConfirmButton: true,
        confirmButtonText: '<i class="fas fa-check mr-2"></i>Submit Sekarang',
        confirmButtonColor: '#EF4444',
        allowOutsideClick: false
    }).then(() => {
        submitQuiz();
    });
}

// Quiz form submission
document.getElementById('quiz-form').addEventListener('submit', function(e) {
    e.preventDefault();

    const answeredCount = Object.keys(userAnswers).length;
    const totalQuestions = quizQuestions.length;

    if (answeredCount < totalQuestions) {
        Swal.fire({
            title: 'Quiz Belum Selesai',
            text: `Anda baru menjawab ${answeredCount} dari ${totalQuestions} soal. Apakah Anda yakin ingin submit quiz sekarang?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#EF4444',
            cancelButtonColor: '#6B7280',
            confirmButtonText: '<i class="fas fa-paper-plane mr-2"></i>Ya, Submit Sekarang',
            cancelButtonText: '<i class="fas fa-arrow-left mr-2"></i>Lanjut Mengerjakan',
            reverseButtons: true,
            allowOutsideClick: false
        }).then((result) => {
            if (result.isConfirmed) {
                submitQuiz();
            }
        });
    } else {
        Swal.fire({
            title: 'Submit Quiz?',
            text: 'Anda yakin ingin submit quiz? Jawaban tidak dapat diubah setelah submit.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10B981',
            cancelButtonColor: '#6B7280',
            confirmButtonText: '<i class="fas fa-check mr-2"></i>Ya, Submit Quiz!',
            cancelButtonText: '<i class="fas fa-edit mr-2"></i>Periksa Jawaban Lagi',
            reverseButtons: true,
            allowOutsideClick: false
        }).then((result) => {
            if (result.isConfirmed) {
                submitQuiz();
            }
        });
    }
});

function submitQuiz() {
    console.log('Starting quiz submission...');
    console.log('User answers:', userAnswers);
    
    // Clear timer
    if (quizTimer) {
        clearInterval(quizTimer);
    }

    // Remove beforeunload event
    window.removeEventListener('beforeunload', function() {});

    const submitBtn = document.getElementById('submit-quiz-btn');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-3"></i>Submitting...';

    // Prepare answers data
    const answersData = {};
    for (const [questionId, answer] of Object.entries(userAnswers)) {
        answersData[questionId] = answer;
    }
    
    console.log('Prepared answers data:', answersData);

    // Prepare submission data
    const submissionData = {
        answers: answersData,
        _token: '{{ csrf_token() }}'
    };
    
    console.log('Submission data:', submissionData);

    showLoading('Memproses hasil quiz...');

    $.ajax({
        url: '{{ route("courses.quiz.submit", [$course->course_id, $quiz->quiz_id]) }}',
        type: 'POST',
        data: submissionData,
        dataType: 'json',
        success: function(data) {
            console.log('Submit success:', data);
            Swal.close();

            if (data.success) {
                showQuizResult(data.result);
            } else {
                showError(data.message || 'Gagal submit quiz');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-check mr-3"></i>Submit Quiz';
            }
        },
        error: function(xhr, status, error) {
            console.error('Submit error:', {xhr, status, error});
            console.error('Response text:', xhr.responseText);
            console.error('Response JSON:', xhr.responseJSON);
            
            Swal.close();
            
            let errorMessage = 'Terjadi kesalahan saat submit quiz';
            
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                const errors = Object.values(xhr.responseJSON.errors).flat();
                errorMessage = errors.join(', ');
            } else if (xhr.status === 422) {
                errorMessage = 'Data yang dikirim tidak valid. Pastikan semua soal sudah dijawab.';
            } else if (xhr.status === 419) {
                errorMessage = 'Session expired. Silakan refresh halaman dan coba lagi.';
            }
            
            showError(errorMessage);
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-check mr-3"></i>Submit Quiz';
        }
    });
}

function showQuizResult(result) {
    const passed = result.is_passed;
    const hasNextLesson = result.next_lesson && result.next_lesson.url;
    
    const resultHtml = `
        <div class="text-center">
            <div class="w-24 h-24 mx-auto mb-6 ${passed ? 'bg-green-100' : 'bg-red-100'} rounded-full flex items-center justify-center">
                <i class="fas ${passed ? 'fa-check-circle text-green-600' : 'fa-times-circle text-red-600'} text-4xl"></i>
            </div>
            <h3 class="text-2xl font-bold ${passed ? 'text-green-600' : 'text-red-600'} mb-4">
                ${passed ? 'Selamat! Quiz Lulus' : 'Quiz Belum Lulus'}
            </h3>
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="text-3xl font-bold ${passed ? 'text-green-600' : 'text-red-600'}">${result.nilai_total}%</div>
                    <div class="text-sm text-gray-600">Nilai Akhir</div>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="text-3xl font-bold text-blue-600">${result.jumlah_benar}/${result.total_soal}</div>
                    <div class="text-sm text-gray-600">Jawaban Benar</div>
                </div>
            </div>
            <p class="text-gray-600 mb-6">
                ${passed ?
                    'Anda telah berhasil menyelesaikan quiz dengan baik!' :
                    'Anda perlu mencapai minimal 60% untuk lulus. Silakan coba lagi!'
                }
            </p>
            ${hasNextLesson && passed ? `
                <div class="bg-blue-50 p-4 rounded-lg mb-6">
                    <div class="flex items-center justify-center">
                        <i class="fas fa-arrow-right text-blue-600 mr-2"></i>
                        <span class="text-blue-800 font-medium">Pembelajaran Berikutnya: ${result.next_lesson.title}</span>
                    </div>
                </div>
            ` : ''}
        </div>
    `;

    // Determine button configuration based on quiz result and next lesson availability
    let confirmButtonText, confirmButtonColor, cancelButtonText;
    
    if (passed && hasNextLesson) {
        confirmButtonText = '<i class="fas fa-arrow-right mr-2"></i>Lanjut ke: ' + result.next_lesson.title;
        confirmButtonColor = '#10B981';
        cancelButtonText = '<i class="fas fa-home mr-2"></i>Kembali ke Course';
    } else if (passed) {
        confirmButtonText = '<i class="fas fa-home mr-2"></i>Kembali ke Course';
        confirmButtonColor = '#10B981';
        cancelButtonText = '<i class="fas fa-chart-line mr-2"></i>Lihat Progress';
    } else {
        confirmButtonText = '<i class="fas fa-redo mr-2"></i>Ulangi Quiz';
        confirmButtonColor = '#3B82F6';
        cancelButtonText = '<i class="fas fa-home mr-2"></i>Kembali ke Course';
    }

    Swal.fire({
        html: resultHtml,
        showConfirmButton: true,
        confirmButtonText: confirmButtonText,
        confirmButtonColor: confirmButtonColor,
        showCancelButton: true,
        cancelButtonText: cancelButtonText,
        cancelButtonColor: '#6B7280',
        reverseButtons: true,
        allowOutsideClick: false,
        customClass: {
            popup: 'swal-wide'
        }
    }).then((result_action) => {
        if (result_action.isConfirmed) {
            if (passed && hasNextLesson) {
                // Go to next lesson
                window.location.href = result.next_lesson.url;
            } else if (passed) {
                // Go back to course
                window.location.href = '{{ route("courses.show", $course->course_id) }}';
            } else {
                // Retry quiz
                location.reload();
            }
        } else {
            // Always go back to course on cancel
            window.location.href = '{{ route("courses.show", $course->course_id) }}';
        }
    });
}

function showPreviousResults() {
    const resultsDiv = document.getElementById('previous-results');
    if (resultsDiv.classList.contains('hidden')) {
        resultsDiv.classList.remove('hidden');
    } else {
        resultsDiv.classList.add('hidden');
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM ready');
    console.log('Quiz data check:', quizData);
    
    // Check if quiz elements exist
    const quizContainer = document.getElementById('quiz-container');
    const questionsContainer = document.getElementById('questions-container');
    const timer = document.getElementById('timer');
    
    console.log('Quiz container:', quizContainer);
    console.log('Questions container:', questionsContainer);
    console.log('Timer:', timer);
    
    if (!quizContainer) console.error('Quiz container not found!');
    if (!questionsContainer) console.error('Questions container not found!');
    if (!timer) console.error('Timer not found!');
});
</script>

<style>
.swal-wide {
    width: 600px !important;
}

/* Quiz Focus Mode Styles */
#quiz-container {
    transition: all 0.5s ease-in-out;
}

.option-container:hover {
    transform: translateX(4px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.option-container.selected {
    background-color: #dbeafe !important;
    border: 2px solid #3b82f6 !important;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
}

/* Timer animation when time is running out */
.timer-warning {
    animation: pulse-red 1s infinite;
}

@keyframes pulse-red {
    0% { color: #ef4444; }
    50% { color: #dc2626; }
    100% { color: #ef4444; }
}

/* Smooth transitions for all interactive elements */
button, .option-container, .bg-white {
    transition: all 0.3s ease-in-out;
}

/* Focus styles for better accessibility */
input[type="radio"]:focus {
    outline: 2px solid #3b82f6;
    outline-offset: 2px;
}

/* Custom SweetAlert styling */
.swal2-popup {
    border-radius: 1rem !important;
}

.swal2-title {
    font-size: 1.5rem !important;
}

.swal2-html-container {
    font-size: 1rem !important;
}

.swal2-loading {
    border: none !important;
}
</style>
@endpush
