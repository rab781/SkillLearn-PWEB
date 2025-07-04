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
                                                        @if($userProgress && isset($video->video_progress) && $video->video_progress && $video->video_progress->is_completed)
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

                <!-- Course Highlights -->
                @php
                    $courseReviews = $course->quickReviews->where('tipe_review', 'tengah_course');
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

                <!-- Student Feedback Section -->
                @auth
                <div class="bg-white rounded-2xl shadow-xl p-8">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-comments text-white text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">Berikan Feedback</h2>
                            <p class="text-gray-600">Bagikan pengalaman belajar Anda</p>
                        </div>
                    </div>

                    <form id="feedback-form" class="space-y-6">
                        @csrf
                        <input type="hidden" name="course_id" value="{{ $course->course_id }}">

                        <!-- Rating -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Rating Course</label>
                            <div class="flex space-x-2">
                                @for($i = 1; $i <= 5; $i++)
                                    <button type="button" class="star-rating text-3xl text-gray-300 hover:text-yellow-500 transition-colors" data-rating="{{ $i }}">
                                        <i class="fas fa-star"></i>
                                    </button>
                                @endfor
                            </div>
                            <input type="hidden" name="rating" id="rating-input">
                        </div>

                        <!-- Feedback Text -->
                        <div>
                            <label for="komentar" class="block text-sm font-medium text-gray-700 mb-2">Komentar</label>
                            <textarea id="komentar" name="komentar" rows="4"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                                      placeholder="Bagikan pengalaman belajar Anda, saran, atau kritik konstruktif..."></textarea>
                        </div>

                        <button type="submit"
                                class="w-full bg-gradient-to-r from-purple-500 to-pink-600 hover:from-purple-600 hover:to-pink-700 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-300 transform hover:scale-105">
                            <i class="fas fa-paper-plane mr-2"></i>
                            Kirim Feedback
                        </button>
                    </form>
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
                            <img src="{{ $relatedCourse->gambar_course ?: 'https://via.placeholder.com/80x60/4f46e5/ffffff?text=Course' }}"
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

                <!-- Course Stats -->
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
