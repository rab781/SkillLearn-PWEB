@extends('layouts.app')

@section('title', 'Pembelajaran - SkillLearn')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Admin Mode Banner -->
    @if(isset($adminMode) && $adminMode)
    <div class="bg-gradient-to-r from-red-500 to-orange-500 text-white rounded-xl p-6 mb-8 shadow-lg">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="bg-white bg-opacity-20 rounded-full p-3 mr-4">
                    <i class="fas fa-shield-alt text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold">Mode Admin - Monitor Pembelajaran</h2>
                    <p class="text-red-100 mt-1">Anda dapat memantau dan mengelola ulasan pembelajaran dari siswa</p>
                </div>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('dashboard.admin') }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>
    @endif

    <!-- Header Section -->
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">
            @if(isset($adminMode) && $adminMode)
                🛡️ Monitor Pembelajaran
            @else
                🎓 Pusat Pembelajaran
            @endif
        </h1>
        <p class="text-xl text-gray-600 mb-2">
            @if(isset($adminMode) && $adminMode)
                Pantau dan kelola pembelajaran siswa dengan mudah
            @else
                Pelajari skill baru dengan course terstruktur dan terarah
            @endif
        </p>
        <p class="text-gray-500">Temukan berbagai course berkualitas untuk mengembangkan keahlian Anda</p>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
        <form method="GET" action="{{ route('courses.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Cari Course</label>
                <input type="text"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       id="search"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Nama course...">
            </div>
            <div>
                <label for="kategori" class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        id="kategori"
                        name="kategori">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoris as $kategori)
                        <option value="{{ $kategori->kategori_id }}"
                                {{ request('kategori') == $kategori->kategori_id ? 'selected' : '' }}>
                            {{ $kategori->kategori }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="level" class="block text-sm font-medium text-gray-700 mb-2">Level</label>
                <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        id="level"
                        name="level">
                    <option value="">Semua Level</option>
                    @foreach($levels as $key => $label)
                        <option value="{{ $key }}" {{ request('level') == $key ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Courses Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($courses as $course)
        <div class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:scale-105 overflow-hidden">
            <!-- Course Image -->
            <div class="relative aspect-video overflow-hidden">
                @php
                    $thumbnailPath = null;
                    if ($course->gambar_course) {
                        // Check if it's a file path or URL
                        if (strpos($course->gambar_course, 'http') === 0) {
                            $thumbnailPath = $course->gambar_course;
                        } else {
                            // Check if file exists in uploads
                            $uploadPath = public_path('uploads/' . $course->gambar_course);
                            if (file_exists($uploadPath)) {
                                $thumbnailPath = asset('uploads/' . $course->gambar_course);
                            }
                        }
                    }

                    // Fallback to placeholder if no valid thumbnail
                    if (!$thumbnailPath) {
                        $thumbnailPath = 'https://via.placeholder.com/400x200/6366f1/ffffff?text=' . urlencode($course->nama_course);
                    }
                @endphp

                <img src="{{ $thumbnailPath }}"
                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                     alt="{{ $course->nama_course }}"
                     loading="lazy">

                <!-- Level Badge -->
                <div class="absolute top-3 right-3 z-10">
                    @switch($course->level)
                        @case('pemula')
                            <span class="inline-flex items-center bg-green-500 text-white px-3 py-1 rounded-full text-sm font-medium shadow-lg">
                                <i class="fas fa-leaf mr-1"></i>Pemula
                            </span>
                            @break
                        @case('menengah')
                            <span class="inline-flex items-center bg-yellow-500 text-black px-3 py-1 rounded-full text-sm font-medium shadow-lg">
                                <i class="fas fa-star mr-1"></i>Menengah
                            </span>
                            @break
                        @case('lanjut')
                            <span class="inline-flex items-center bg-red-500 text-white px-3 py-1 rounded-full text-sm font-medium shadow-lg">
                                <i class="fas fa-fire mr-1"></i>Lanjut
                            </span>
                            @break
                        @default
                            <span class="inline-flex items-center bg-gray-500 text-white px-3 py-1 rounded-full text-sm font-medium shadow-lg">
                                <i class="fas fa-graduation-cap mr-1"></i>Umum
                            </span>
                    @endswitch
                </div>

                <!-- Category Badge -->
                <div class="absolute top-3 left-3 z-10">
                    <span class="inline-flex items-center bg-black bg-opacity-75 text-white px-3 py-1 rounded-full text-sm backdrop-blur-sm">
                        <i class="fas fa-tag mr-1 text-xs"></i>
                        {{ $course->kategori->kategori ?? 'N/A' }}
                    </span>
                </div>

                <!-- Play Button Overlay with Enhanced Animation -->
                <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-all duration-500 flex items-center justify-center">
                    <div class="relative">
                        <!-- Outer Ring -->
                        <div class="w-20 h-20 border-4 border-white border-opacity-50 rounded-full absolute inset-0 animate-pulse"></div>

                        <!-- Main Play Button -->
                        <div class="w-16 h-16 bg-white bg-opacity-95 rounded-full flex items-center justify-center transform scale-0 group-hover:scale-100 transition-all duration-500 shadow-2xl hover:shadow-white/20 hover:bg-opacity-100">
                            <svg class="w-8 h-8 text-blue-600 ml-1" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M8 5v14l11-7z"/>
                            </svg>
                        </div>

                        <!-- Ripple Effect -->
                        <div class="absolute inset-0 w-16 h-16 border-2 border-white rounded-full animate-ping opacity-75"></div>
                    </div>
                </div>

                <!-- Duration Badge (if available) -->
                @if($course->total_durasi_menit > 0)
                <div class="absolute bottom-3 right-3 z-10">
                    <span class="inline-flex items-center bg-black bg-opacity-75 text-white px-2 py-1 rounded text-xs backdrop-blur-sm">
                        <i class="fas fa-clock mr-1"></i>
                        {{ $course->total_durasi_menit }} min
                    </span>
                </div>
                @endif
            </div>

            <div class="p-6">
                <!-- Course Title -->
                <h3 class="text-xl font-bold text-gray-900 mb-3 line-clamp-2 group-hover:text-blue-600 transition-colors">
                    {{ $course->nama_course }}
                </h3>

                <!-- Course Description -->
                <p class="text-gray-600 mb-4 line-clamp-3">
                    {{ Str::limit($course->deskripsi_course, 120) }}
                </p>

                <!-- Course Stats -->
                <div class="grid grid-cols-3 gap-4 mb-4 text-center">
                    <div class="bg-blue-50 rounded-lg p-3 group-hover:bg-blue-100 transition-colors">
                        <div class="flex items-center justify-center mb-1">
                            <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center mr-2">
                                <i class="fas fa-play text-white text-xs"></i>
                            </div>
                            <span class="font-bold text-blue-700 text-lg">{{ $course->videos_count }}</span>
                        </div>
                        <span class="text-blue-600 text-xs font-medium">Video</span>
                    </div>
                    <div class="bg-yellow-50 rounded-lg p-3 group-hover:bg-yellow-100 transition-colors">
                        <div class="flex items-center justify-center mb-1">
                            <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center mr-2">
                                <i class="fas fa-clock text-white text-xs"></i>
                            </div>
                            <span class="font-bold text-yellow-700 text-lg">{{ $course->total_durasi_menit ?? 0 }}</span>
                        </div>
                        <span class="text-yellow-600 text-xs font-medium">Menit</span>
                    </div>
                    <div class="bg-green-50 rounded-lg p-3 group-hover:bg-green-100 transition-colors">
                        <div class="flex items-center justify-center mb-1">
                            <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center mr-2">
                                <i class="fas fa-users text-white text-xs"></i>
                            </div>
                            <span class="font-bold text-green-700 text-lg">{{ $course->user_progress_count }}</span>
                        </div>
                        <span class="text-green-600 text-xs font-medium">Student</span>
                    </div>
                </div>

                <!-- Progress Bar (if user has started) -->
                @auth
                    @if($course->userProgress->isNotEmpty())
                        @php
                            $userProgress = $course->userProgress->first();
                        @endphp
                        <div class="mb-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-medium text-gray-700">Progress Belajar</span>
                                <span class="text-sm font-bold text-green-600">{{ $userProgress->progress_percentage }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                                <div class="bg-gradient-to-r from-green-400 to-green-600 h-3 rounded-full transition-all duration-500 shadow-sm"
                                     style="width: {{ $userProgress->progress_percentage }}%">
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 mt-1 flex items-center">
                                <i class="fas fa-trophy text-yellow-500 mr-1"></i>
                                {{ $userProgress->progress_percentage }}% telah diselesaikan
                            </p>
                        </div>
                    @endif
                @endauth

                <!-- Action Button -->
                <div class="mt-auto">
                    @auth
                        @if($course->userProgress->isNotEmpty())
                            @php
                                $userProgress = $course->userProgress->first();
                            @endphp
                            @if($userProgress->status !== 'not_started')
                                <a href="{{ route('courses.show', $course->course_id) }}"
                                   class="group/btn w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-bold py-3 px-4 rounded-xl transition-all duration-300 flex items-center justify-center shadow-lg hover:shadow-green-500/25 hover:scale-105 transform">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-3 group-hover/btn:bg-opacity-30 transition-all">
                                            <i class="fas fa-play text-sm"></i>
                                        </div>
                                        <span class="text-sm">Lanjut Belajar</span>
                                        <i class="fas fa-arrow-right ml-2 text-sm transform group-hover/btn:translate-x-1 transition-transform"></i>
                                    </div>
                                </a>
                            @else
                                <a href="{{ route('courses.show', $course->course_id) }}"
                                   class="group/btn w-full bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold py-3 px-4 rounded-xl transition-all duration-300 flex items-center justify-center shadow-lg hover:shadow-blue-500/25 hover:scale-105 transform">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-3 group-hover/btn:bg-opacity-30 transition-all">
                                            <i class="fas fa-rocket text-sm"></i>
                                        </div>
                                        <span class="text-sm">Mulai Pembelajaran</span>
                                        <i class="fas fa-arrow-right ml-2 text-sm transform group-hover/btn:translate-x-1 transition-transform"></i>
                                    </div>
                                </a>
                            @endif
                        @else
                            <a href="{{ route('courses.show', $course->course_id) }}"
                               class="group/btn w-full bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold py-3 px-4 rounded-xl transition-all duration-300 flex items-center justify-center shadow-lg hover:shadow-blue-500/25 hover:scale-105 transform">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-3 group-hover/btn:bg-opacity-30 transition-all">
                                        <i class="fas fa-rocket text-sm"></i>
                                    </div>
                                    <span class="text-sm">Mulai Pembelajaran</span>
                                    <i class="fas fa-arrow-right ml-2 text-sm transform group-hover/btn:translate-x-1 transition-transform"></i>
                                </div>
                            </a>
                        @endif
                    @else
                        <a href="{{ route('courses.show', $course->course_id) }}"
                           class="group/btn w-full bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white font-bold py-3 px-4 rounded-xl transition-all duration-300 flex items-center justify-center shadow-lg hover:shadow-gray-500/25 hover:scale-105 transform">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-3 group-hover/btn:bg-opacity-30 transition-all">
                                    <i class="fas fa-eye text-sm"></i>
                                </div>
                                <span class="text-sm">Lihat Detail</span>
                                <i class="fas fa-arrow-right ml-2 text-sm transform group-hover/btn:translate-x-1 transition-transform"></i>
                            </div>
                        </a>
                    @endauth
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full">
            <div class="text-center py-12">
                <svg class="w-24 h-24 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                <h4 class="text-xl font-semibold text-gray-900 mb-2">Belum Ada Course Tersedia</h4>
                <p class="text-gray-600">Course baru akan segera ditambahkan. Silakan kembali lagi nanti!</p>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($courses->hasPages())
    <div class="mt-8">
        <div class="flex justify-center">
            {{ $courses->withQueryString()->links() }}
        </div>
    </div>
    @endif
</div>

<!-- Hidden elements for Tailwind JIT compilation -->
<div class="hidden">
    <!-- Force compilation of all color combinations separately -->
    <div class="bg-green-500"></div><div class="bg-green-600"></div><div class="bg-green-700"></div><div class="bg-green-50"></div><div class="bg-green-100"></div>
    <div class="text-green-600"></div><div class="text-green-700"></div>
    <div class="bg-blue-500"></div><div class="bg-blue-600"></div><div class="bg-blue-700"></div><div class="bg-blue-50"></div><div class="bg-blue-100"></div>
    <div class="text-blue-600"></div><div class="text-blue-700"></div>
    <div class="bg-yellow-500"></div><div class="bg-yellow-600"></div><div class="bg-yellow-700"></div><div class="bg-yellow-50"></div><div class="bg-yellow-100"></div>
    <div class="text-yellow-600"></div><div class="text-yellow-700"></div>
    <div class="bg-red-500"></div><div class="bg-red-600"></div><div class="bg-red-700"></div>
    <div class="bg-gray-500"></div><div class="bg-gray-600"></div><div class="bg-gray-700"></div>
    <div class="shadow-green-500/25"></div><div class="shadow-blue-500/25"></div><div class="shadow-gray-500/25"></div>
    <div class="from-green-500 to-green-600"></div><div class="from-green-600 to-green-700"></div>
    <div class="from-blue-500 to-blue-600"></div><div class="from-blue-600 to-blue-700"></div>
    <div class="from-gray-500 to-gray-600"></div><div class="from-gray-600 to-gray-700"></div>
</div>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Enhanced animations */
@keyframes ripple {
    0% {
        transform: scale(0.8);
        opacity: 1;
    }
    100% {
        transform: scale(2.4);
        opacity: 0;
    }
}

@keyframes float {
    0%, 100% {
        transform: translateY(0px);
    }
    50% {
        transform: translateY(-10px);
    }
}

.animate-ripple {
    animation: ripple 1.5s infinite;
}

.animate-float {
    animation: float 3s ease-in-out infinite;
}

/* Custom hover effects */
.group:hover .animate-float {
    animation-play-state: paused;
}

/* Smooth gradient transitions */
.bg-gradient-to-r {
    transition: background-image 0.3s ease;
}

/* Enhanced shadow effects */
.shadow-glow {
    box-shadow: 0 0 20px rgba(59, 130, 246, 0.3);
}

.shadow-glow-green {
    box-shadow: 0 0 20px rgba(34, 197, 94, 0.3);
}

.shadow-glow-gray {
    box-shadow: 0 0 20px rgba(107, 114, 128, 0.3);
}
</style>
@endsection
