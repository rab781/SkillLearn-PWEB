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
                <img src="{{ $course->gambar_course ?: 'https://via.placeholder.com/400x200/4f46e5/ffffff?text=' . urlencode($course->nama_course) }}" 
                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300"
                     alt="{{ $course->nama_course }}">
                
                <!-- Level Badge -->
                <div class="absolute top-3 right-3">
                    @switch($course->level)
                        @case('pemula')
                            <span class="bg-green-500 text-white px-3 py-1 rounded-full text-sm font-medium">Pemula</span>
                            @break
                        @case('menengah')
                            <span class="bg-yellow-500 text-black px-3 py-1 rounded-full text-sm font-medium">Menengah</span>
                            @break
                        @case('lanjut')
                            <span class="bg-red-500 text-white px-3 py-1 rounded-full text-sm font-medium">Lanjut</span>
                            @break
                        @default
                            <span class="bg-gray-500 text-white px-3 py-1 rounded-full text-sm font-medium">Umum</span>
                    @endswitch
                </div>

                <!-- Category Badge -->
                <div class="absolute top-3 left-3">
                    <span class="bg-black bg-opacity-75 text-white px-3 py-1 rounded-full text-sm">
                        {{ $course->kategori->kategori ?? 'N/A' }}
                    </span>
                </div>

                <!-- Play Button Overlay -->
                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-300 flex items-center justify-center">
                    <div class="w-16 h-16 bg-white bg-opacity-90 rounded-full flex items-center justify-center transform scale-0 group-hover:scale-100 transition-transform duration-300 shadow-xl">
                        <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3z"></path>
                        </svg>
                    </div>
                </div>
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
                    <div class="text-sm">
                        <div class="flex items-center justify-center mb-1">
                            <svg class="w-4 h-4 text-blue-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"></path>
                            </svg>
                            <span class="font-semibold text-gray-700">{{ $course->videos_count }}</span>
                        </div>
                        <span class="text-gray-500 text-xs">Videos</span>
                    </div>
                    <div class="text-sm">
                        <div class="flex items-center justify-center mb-1">
                            <svg class="w-4 h-4 text-yellow-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="font-semibold text-gray-700">{{ $course->total_durasi_menit ?? 0 }}</span>
                        </div>
                        <span class="text-gray-500 text-xs">Menit</span>
                    </div>
                    <div class="text-sm">
                        <div class="flex items-center justify-center mb-1">
                            <svg class="w-4 h-4 text-green-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"></path>
                            </svg>
                            <span class="font-semibold text-gray-700">{{ $course->user_progress_count }}</span>
                        </div>
                        <span class="text-gray-500 text-xs">Students</span>
                    </div>
                </div>

                <!-- Progress Bar (if user has started) -->
                @auth
                    @if($course->userProgress->isNotEmpty())
                        @php
                            $userProgress = $course->userProgress->first();
                        @endphp
                        <div class="mb-4">
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-green-500 h-2 rounded-full transition-all duration-300" 
                                     style="width: {{ $userProgress->progress_percentage }}%">
                                </div>
                            </div>
                            <p class="text-sm text-gray-600 mt-2">
                                Progress: {{ $userProgress->progress_percentage }}% selesai
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
                                   class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 rounded-lg transition-colors duration-200 flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"></path>
                                    </svg>
                                    Lanjut Belajar
                                </a>
                            @else
                                <a href="{{ route('courses.show', $course->course_id) }}" 
                                   class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition-colors duration-200 flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Mulai Pembelajaran
                                </a>
                            @endif
                        @else
                            <a href="{{ route('courses.show', $course->course_id) }}" 
                               class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition-colors duration-200 flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Mulai Pembelajaran
                            </a>
                        @endif
                    @else
                        <a href="{{ route('courses.show', $course->course_id) }}" 
                           class="w-full bg-gray-600 hover:bg-gray-700 text-white font-semibold py-3 rounded-lg transition-colors duration-200 flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                            </svg>
                            Lihat Detail
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
</style>
@endsection
