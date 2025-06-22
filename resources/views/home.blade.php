@extends('layouts.app')

@section('title', 'Skillearn - Platform Pembelajaran Online Modern')

@section('content')
<!-- Hero Section -->
<section class="relative bg-gradient-to-br from-blue-600 via-purple-600 to-indigo-700 text-white py-24 overflow-hidden">
    <!-- Animated Background Elements -->
    <div class="absolute inset-0">
        <div class="absolute top-20 left-10 w-20 h-20 bg-white/10 rounded-full animate-bounce opacity-60"></div>
        <div class="absolute top-40 right-20 w-16 h-16 bg-yellow-400/20 rounded-full animate-pulse"></div>
        <div class="absolute bottom-20 left-1/4 w-12 h-12 bg-pink-400/20 rounded-full animate-ping"></div>
        <div class="absolute top-1/2 right-1/3 w-8 h-8 bg-green-400/20 rounded-full animate-bounce delay-1000"></div>
    </div>

    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-10">
        <svg class="w-full h-full" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
            <pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse">
                <path d="M 10 0 L 0 0 0 10" fill="none" stroke="white" stroke-width="0.5"/>
            </pattern>
            <rect width="100" height="100" fill="url(#grid)" />
        </svg>
    </div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <!-- Left Content -->
            <div class="text-center lg:text-left fade-in">
                <div class="inline-flex items-center px-4 py-2 glass-effect rounded-full text-sm font-medium mb-6">
                    <span class="w-2 h-2 bg-green-400 rounded-full mr-2 animate-pulse"></span>
                    🏆 Platform Pembelajaran #1 di Indonesia
                </div>

                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold mb-6 leading-tight">
                    Belajar Skill Baru dengan
                    <span class="bg-gradient-to-r from-yellow-400 via-orange-400 to-red-400 bg-clip-text text-transparent animate-pulse">
                        Course Terkurasi
                    </span>
                    <span class="block text-3xl sm:text-4xl lg:text-5xl mt-2">🚀 Terarah</span>
                </h1>

                <p class="text-lg sm:text-xl mb-7 text-blue-100 max-w-2xl leading-relaxed">
                    🎯 Platform pembelajaran online modern dengan kurasi course berkualitas tinggi.
                    Tingkatkan skill dengan pembelajaran terarah dari expert terpercaya.
                </p>

                <!-- Enhanced Stats with Icons -->
                <div class="grid grid-cols-3 gap-6 mb-7">
                    <div class="text-center glass-effect rounded-xl p-4">
                        <div class="text-2xl font-bold" id="stats-videos">-</div>
                        <div class="text-sm text-blue-200">Course Berkualitas</div>
                    </div>
                    <div class="text-center glass-effect rounded-xl p-4">
                        <div class="text-2xl font-bold" id="stats-categories">-</div>
                        <div class="text-sm text-blue-200">Kategori Skill</div>
                    </div>
                    <div class="text-center glass-effect rounded-xl p-4">
                        <div class="text-2xl font-bold" id="stats-users">-</div>
                        <div class="text-sm text-blue-200">Happy Learners</div>
                    </div>
                </div>

                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row gap-3 justify-center lg:justify-start mb-10">
                    @guest
                    <a href="/register" class="bg-white text-blue-600 px-8 py-4 rounded-xl font-semibold hover:bg-gray-50 transition-all shadow-lg hover:shadow-xl">
                        <span class="flex items-center justify-center">
                            Mulai Belajar Gratis
                            <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </span>
                    </a>
                    <a href="/courses" class="border-2 border-white text-white px-8 py-4 rounded-xl font-semibold hover:bg-white hover:text-blue-600 transition-all backdrop-blur-sm">
                        Jelajahi Courses
                    </a>

                    @else
                        <a href="{{ auth()->user()->isAdmin() ? '/admin/dashboard' : '/customer/dashboard' }}" class="group bg-white text-blue-600 px-8 py-4 rounded-xl font-semibold hover:bg-gray-50 transition-all shadow-lg hover:shadow-xl">
                            <span class="flex items-center justify-center">
                                Dashboard Saya
                                <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                            </span>
                        </a>
                        <a href="/courses" class="border-2 border-white text-white px-8 py-4 rounded-xl font-semibold hover:bg-white hover:text-blue-600 transition-all backdrop-blur-sm">
                            Jelajahi Courses
                        </a>
                    @endguest
                </div>
            </div>

            <!-- Right Content - Visual Element -->
            <div class="hidden lg:block">
                <div class="relative">
                    <!-- Floating Cards -->
                    <div class="space-y-4">
                        <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 transform rotate-3 hover:rotate-0 transition-transform">
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 bg-gradient-to-r from-yellow-400 to-orange-400 rounded-full flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <div class="font-semibold">Programming</div>
                                    <div class="text-sm text-blue-200">25+ Course</div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 transform -rotate-2 hover:rotate-0 transition-transform ml-8">
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 bg-gradient-to-r from-pink-400 to-red-400 rounded-full flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zm0 4a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1V8zm8 0a1 1 0 011-1h4a1 1 0 011 1v2a1 1 0 01-1 1h-4a1 1 0 01-1-1V8z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div>
                                    <div class="font-semibold">Design</div>
                                    <div class="text-sm text-blue-200">15+ Course</div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 transform rotate-1 hover:rotate-0 transition-transform">
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 bg-gradient-to-r from-green-400 to-blue-400 rounded-full flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <div class="font-semibold">Business</div>
                                    <div class="text-sm text-blue-200">20+ Course</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Wave Bottom -->
    <div class="absolute bottom-0 left-0 right-0">
        <svg viewBox="0 0 1440 120" class="w-full h-auto">
            <path fill="#f8fafc" d="M0,64L48,69.3C96,75,192,85,288,80C384,75,480,53,576,48C672,43,768,53,864,69.3C960,85,1056,107,1152,112C1248,117,1344,107,1392,101.3L1440,96L1440,120L1392,120C1344,120,1248,120,1152,120C1056,120,960,120,864,120C768,120,672,120,576,120C480,120,384,120,288,120C192,120,96,120,48,120L0,120Z"></path>
        </svg>
    </div>
</section>

<!-- Features Section -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl sm:text-4xl font-bold text-gray-700 mb-4">Mengapa Memilih Skillearn?</h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">Platform pembelajaran dengan fitur lengkap untuk mendukung journey belajar Anda</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Feature 1 -->
            <div class="card-hover bg-gradient-to-br from-blue-50 to-indigo-50 p-8 rounded-2xl border border-blue-100">
                <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl flex items-center justify-center mb-6">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">Konten Terkurasi</h3>
                <p class="text-gray-600">Course pembelajaran dipilih oleh expert untuk memastikan kualitas dan relevansi materi.</p>
            </div>

            <!-- Feature 2 -->
            <div class="card-hover bg-gradient-to-br from-purple-50 to-pink-50 p-8 rounded-2xl border border-purple-100">
                <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl flex items-center justify-center mb-6">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">Personal Dashboard</h3>
                <p class="text-gray-600">Pantau progress belajar, bookmark course favorit, dan kelola aktivitas pembelajaran Anda.</p>
            </div>

            <!-- Feature 3 -->
            <div class="card-hover bg-gradient-to-br from-green-50 to-emerald-50 p-8 rounded-2xl border border-green-100">
                <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center mb-6">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2M7 4h10v9a2 2 0 01-2 2H9a2 2 0 01-2-2V4zM9 9h6M9 13h6m-3-8v4"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">Multi Kategori</h3>
                <p class="text-gray-600">Berbagai kategori pembelajaran dari Programming, Design, hingga Business Skills.</p>
            </div>

            <!-- Feature 4 -->
            <div class="card-hover bg-gradient-to-br from-yellow-50 to-orange-50 p-8 rounded-2xl border border-yellow-100">
                <div class="w-12 h-12 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center mb-6">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">Mobile Friendly</h3>
                <p class="text-gray-600">Akses pembelajaran kapan saja, di mana saja dengan design yang responsive di semua device.</p>
            </div>            <!-- Feature 5 -->
            <div class="card-hover bg-gradient-to-br from-blue-50 to-indigo-50 p-8 rounded-2xl border border-blue-100">
                <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-xl flex items-center justify-center mb-6">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">Bookmark System</h3>
                <p class="text-gray-600">Simpan course favorit Anda untuk dapat diakses dengan mudah di kemudian hari.</p>
            </div>

            <!-- Feature 6 -->
            <div class="card-hover bg-gradient-to-br from-indigo-50 to-blue-50 p-8 rounded-2xl border border-indigo-100">
                <div class="w-12 h-12 bg-gradient-to-r from-indigo-500 to-blue-500 rounded-xl flex items-center justify-center mb-6">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">Feedback & Rating</h3>
                <p class="text-gray-600">Berikan feedback dan rating untuk membantu learner lain memilih konten terbaik.</p>
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="py-20 bg-gradient-to-br from-gray-50 via-blue-50 to-purple-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl sm:text-4xl font-bold bg-gradient-to-r from-purple-900 to-purple-400 bg-clip-text text-transparent mb-4">
                Kategori Course Populer
            </h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Temukan kategori dengan course pembelajaran terlengkap dan paling diminati
            </p>
        </div>

        <!-- Loading State -->
        <div id="categories-loading" class="flex justify-center">
            <div class="flex items-center space-x-2">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                <span class="text-gray-600">Memuat kategori...</span>
            </div>
        </div>

        <!-- Categories Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8" id="categories-grid" style="display: none;">
            <!-- Categories will be loaded here -->
        </div>

        <!-- View All Categories Button -->
        <div class="text-center mt-12">
            <a href="/courses" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-gray-600 to-gray-700 text-white font-semibold rounded-xl hover:from-gray-700 hover:to-gray-800 transition-all shadow-lg hover:scale-105 hover:shadow-xl">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
                Lihat Semua Courses
            </a>
        </div>
    </div>
</section>

<!-- Popular Courses Section -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl sm:text-4xl font-bold bg-gradient-to-r from-red-600 to-orange-600 bg-clip-text text-transparent mb-4">
                Course Populer
            </h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Course pembelajaran terpopuler dengan rating tertinggi dari community
            </p>
        </div>

        <!-- Loading State -->
        <div id="popular-loading" class="flex justify-center">
            <div class="flex items-center space-x-2">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-red-600"></div>
                <span class="text-gray-600">Memuat course populer...</span>
            </div>
        </div>

        <!-- Popular Courses Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8" id="popular-courses" style="display: none;">
            <!-- Courses will be loaded here -->
        </div>

        <div class="text-center mt-12">
            <a href="/courses" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-gray-600 to-gray-700 text-white font-semibold rounded-xl hover:from-gray-700 hover:to-gray-800 transition-all shadow-lg hover:scale-105 hover:shadow-xl">
                Lihat Semua Course Populer
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                </svg>
            </a>
        </div>
    </div>
</section>

<!-- Latest Courses Section -->
<section class="py-20 bg-gradient-to-br from-blue-50 to-purple-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl sm:text-4xl font-bold bg-gradient-to-r from-green-600 to-blue-600 bg-clip-text text-transparent mb-4">
                Course Terbaru
            </h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Course pembelajaran terkini yang baru saja ditambahkan untuk Anda
            </p>
        </div>

        <!-- Loading State -->
        <div id="latest-loading" class="flex justify-center">
            <div class="flex items-center space-x-2">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-green-600"></div>
                <span class="text-gray-600">Memuat course terbaru...</span>
            </div>
        </div>

        <!-- Latest Courses Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8" id="latest-courses" style="display: none;">
            <!-- Courses will be loaded here -->
        </div>

        <div class="text-center mt-12">
            <a href="/courses" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-gray-600 to-gray-700 text-white font-semibold rounded-xl hover:from-gray-700 hover:to-gray-800 transition-all hover:scale-105 shadow-lg hover:shadow-xl">
                Lihat Semua Course Terbaru
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                </svg>
            </a>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-600 text-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl sm:text-4xl font-bold mb-6">Siap Mulai Perjalanan Belajar Anda?</h2>
        <p class="text-xl mb-8 text-blue-100">
            Bergabunglah dengan ribuan learner lainnya dan tingkatkan skill Anda hari ini juga!
        </p>
        @guest
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="/register" class="bg-white text-blue-600 px-8 py-4 rounded-xl font-semibold hover:bg-gray-50 transition-all shadow-lg hover:shadow-xl">
                    Daftar Sekarang - Gratis!
                </a>
                <a href="/courses" class="border-2 border-white text-white px-8 py-4 rounded-xl font-semibold hover:bg-white hover:text-blue-600 transition-all">
                    Jelajahi Courses
                </a>
            </div>
        @else
            <a href="{{ auth()->user()->isAdmin() ? '/admin/dashboard' : '/customer/dashboard' }}" class="inline-flex items-center bg-white text-blue-600 px-8 py-4 rounded-xl font-semibold hover:bg-gray-50 transition-all hover:scale-105 shadow-lg hover:shadow-xl">
                Lanjutkan Belajar
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                </svg>
            </a>
        @endguest
    </div>
</section>
@endsection

@push('scripts')
<script>
// Load top 3 categories with most videos
async function loadCategories() {
    try {
        const response = await fetch('/api/categories');
        const data = await response.json();

        if (data.success) {
            // Sort categories by course count and take top 3
            const topCategories = data.categories
                .filter(category => category.courses_count > 0)
                .sort((a, b) => b.courses_count - a.courses_count)
                .slice(0, 3);

            const categoriesGrid = document.getElementById('categories-grid');
            const loadingState = document.getElementById('categories-loading');

            // Define gradient colors for categories
            const gradients = [
                'from-blue-500 to-blue-600',
                'from-purple-500 to-purple-600',
                'from-green-500 to-green-600',
                'from-orange-500 to-orange-600',
                'from-pink-500 to-pink-600'
            ];

            // Define category icons
            const categoryIcons = {
                'Programming': '💻',
                'Design': '🎨',
                'Marketing': '📈',
                'Business': '💼',
                'Photography': '📸',
                'Music': '🎵',
                'Cooking': '👨‍🍳',
                'Language': '🗣️',
                'Fitness': '💪',
                'Art': '🖼️'
            };

            categoriesGrid.innerHTML = topCategories.map((category, index) => {
                const gradient = gradients[index] || 'from-gray-500 to-gray-600';
                const icon = categoryIcons[category.kategori] || '📚';

                return `
                    <div class="group relative overflow-hidden bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:scale-105 card-hover">
                        <!-- Background Gradient -->
                        <div class="absolute inset-0 bg-gradient-to-br ${gradient} opacity-0 group-hover:opacity-5 transition-opacity duration-300"></div>

                        <!-- Rank Badge -->
                        <div class="absolute top-3 right-3 w-6 h-6 bg-gradient-to-r ${gradient} text-white rounded-full flex items-center justify-center text-xs font-bold shadow-lg">
                            ${index + 1}
                        </div>

                        <div class="relative p-6 text-center">
                            <!-- Icon -->
                            <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-r ${gradient} rounded-2xl flex items-center justify-center text-2xl shadow-lg group-hover:shadow-xl transition-shadow duration-300">
                                ${icon}
                            </div>

                            <!-- Category Name -->
                            <h3 class="text-xl font-bold text-gray-800 mb-2 group-hover:text-transparent group-hover:bg-gradient-to-r group-hover:${gradient} group-hover:bg-clip-text transition-all duration-300">
                                ${category.kategori}
                            </h3>

                            <!-- Course Count -->
                            <div class="flex items-center justify-center space-x-1 text-gray-600 mb-4">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"></path>
                                </svg>
                                <span class="font-semibold">${category.courses_count || 0}</span>
                                <span class="text-sm">course</span>
                            </div>

                            <!-- CTA Button -->
                            <a href="/courses?kategori_id=${category.kategori_id}"
                               class="inline-flex items-center justify-center w-full px-4 py-2 bg-gradient-to-r ${gradient} text-white font-medium rounded-xl hover:shadow-lg transform hover:scale-105 transition-all duration-200 group-hover:shadow-xl">
                                <span>Jelajahi</span>
                                <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                            </a>
                        </div>

                        <!-- Decorative Elements -->
                        <div class="absolute -top-4 -right-4 w-8 h-8 bg-gradient-to-r ${gradient} rounded-full opacity-10 group-hover:opacity-20 transition-opacity duration-300"></div>
                        <div class="absolute -bottom-2 -left-2 w-6 h-6 bg-gradient-to-r ${gradient} rounded-full opacity-10 group-hover:opacity-20 transition-opacity duration-300"></div>
                    </div>
                `;
            }).join('');

            // Show grid and hide loading
            loadingState.style.display = 'none';
            categoriesGrid.style.display = 'grid';

            // Add stagger animation
            const categoryCards = categoriesGrid.children;
            Array.from(categoryCards).forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    card.style.transition = 'all 0.6s ease-out';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        }
    } catch (error) {
        console.error('Error loading categories:', error);
        const loadingState = document.getElementById('categories-loading');
        loadingState.innerHTML = `
            <div class="text-center text-gray-500">
                <svg class="w-12 h-12 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p>Gagal memuat kategori</p>
            </div>
        `;
    }
}

// Load popular courses
async function loadPopularCourses() {
    try {
        const response = await fetch('/api/courses?sort=popular&limit=3');
        const data = await response.json();

        if (data.success) {
            // Get top 3 most popular courses
            const topCourses = data.courses.slice(0, 3);

            const grid = document.getElementById('popular-courses');
            const loadingState = document.getElementById('popular-loading');

            grid.innerHTML = topCourses.map((course, index) => {
                const rankBadges = ['🥇', '🥈', '🥉'];
                const rankColors = [
                    'from-yellow-400 to-yellow-600',
                    'from-gray-400 to-gray-600',
                    'from-orange-400 to-orange-600'
                ];

                const levelColors = {
                    'pemula': 'from-green-100 to-green-200 text-green-700',
                    'menengah': 'from-yellow-100 to-yellow-200 text-yellow-700',
                    'lanjut': 'from-red-100 to-red-200 text-red-700'
                };

                return `
                    <div class="group relative overflow-hidden bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:scale-105 card-hover">
                        <!-- Rank Badge -->
                        <div class="absolute top-3 left-3 z-10 w-8 h-8 bg-gradient-to-r ${rankColors[index]} text-white rounded-full flex items-center justify-center text-lg font-bold shadow-lg">
                            ${rankBadges[index]}
                        </div>

                        <!-- Enrollment Badge -->
                        <div class="absolute top-3 right-3 z-10 bg-black bg-opacity-60 text-white text-xs px-2 py-1 rounded-full backdrop-blur-sm">
                            👥 ${course.user_progress_count || 0} enrolled
                        </div>

                        <!-- Course Image -->
                        <div class="relative aspect-video overflow-hidden">
                            <img src="${course.gambar_course || '/images/default-course.jpg'}"
                                 alt="${course.nama_course}"
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300"
                                 onerror="this.src='/images/default-course.jpg'">

                            <!-- Overlay -->
                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-300 flex items-center justify-center">
                                <div class="w-16 h-16 bg-white bg-opacity-90 rounded-full flex items-center justify-center transform scale-0 group-hover:scale-100 transition-transform duration-300 shadow-xl">
                                    <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-6">
                            <!-- Level & Category -->
                            <div class="flex items-center gap-2 mb-3">
                                <div class="inline-flex items-center px-3 py-1 bg-gradient-to-r ${levelColors[course.level] || 'from-gray-100 to-gray-200 text-gray-700'} text-xs font-medium rounded-full">
                                    ${course.level ? course.level.charAt(0).toUpperCase() + course.level.slice(1) : 'Umum'}
                                </div>
                                <div class="inline-flex items-center px-3 py-1 bg-gradient-to-r from-blue-100 to-blue-200 text-blue-700 text-xs font-medium rounded-full">
                                    ${course.kategori?.kategori || 'Umum'}
                                </div>
                            </div>

                            <!-- Title -->
                            <h3 class="text-lg font-bold text-gray-800 mb-3 line-clamp-2 group-hover:text-blue-600 transition-colors">
                                ${course.nama_course}
                            </h3>

                            <!-- Description -->
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                                ${course.deskripsi_course || 'Tidak ada deskripsi tersedia'}
                            </p>

                            <!-- Course Stats -->
                            <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                                <div class="flex items-center space-x-4">
                                    <div class="flex items-center space-x-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"></path>
                                        </svg>
                                        <span>${course.total_video || 0} videos</span>
                                    </div>
                                    <div class="flex items-center space-x-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span>${course.total_durasi_menit || 0} min</span>
                                    </div>                            </div>
                            <span class="text-red-600 font-semibold">#${index + 1} Populer</span>
                        </div>

                        <!-- CTA Button -->
                        <a href="/courses/${course.course_id}"
                           class="block w-full text-center bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold py-3 rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all transform hover:scale-105 shadow-lg hover:shadow-xl">
                            🎓 Mulai Belajar
                        </a>
                        </div>

                        <!-- Decorative Corner -->
                        <div class="absolute -top-2 -right-2 w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full opacity-20 group-hover:opacity-40 transition-opacity duration-300"></div>
                    </div>
                `;
            }).join('');

            // Show grid and hide loading
            loadingState.style.display = 'none';
            grid.style.display = 'grid';

            // Add stagger animation
            const courseCards = grid.children;
            Array.from(courseCards).forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    card.style.transition = 'all 0.6s ease-out';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 150);
            });
        }
    } catch (error) {
        console.error('Error loading popular courses:', error);
        const loadingState = document.getElementById('popular-loading');
        loadingState.innerHTML = `
            <div class="text-center text-gray-500">
                <svg class="w-12 h-12 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p>Gagal memuat course populer</p>
            </div>
        `;
    }
}

// Load latest courses
async function loadLatestCourses() {
    try {
        const response = await fetch('/api/courses?sort=created_at&order=desc&limit=3');
        const data = await response.json();

        if (data.success) {
            // Get 3 newest courses
            const latestCourses = data.courses.slice(0, 3);

            const grid = document.getElementById('latest-courses');
            const loadingState = document.getElementById('latest-loading');

            grid.innerHTML = latestCourses.map((course, index) => {
                const gradients = [
                    'from-green-500 to-blue-500',
                    'from-blue-500 to-purple-500',
                    'from-purple-500 to-pink-500'
                ];

                return `
                    <div class="group relative overflow-hidden bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:scale-105 card-hover">
                        <!-- New Badge -->
                        <div class="absolute top-3 left-3 z-10 bg-gradient-to-r ${gradients[index]} text-white text-xs px-3 py-1 rounded-full font-bold shadow-lg animate-pulse">
                            ✨ BARU
                        </div>

                        <!-- Level Badge -->
                        <div class="absolute top-3 right-3 z-10 bg-white bg-opacity-90 text-gray-700 text-xs px-2 py-1 rounded-full backdrop-blur-sm">
                            ${course.level ? course.level.charAt(0).toUpperCase() + course.level.slice(1) : 'Umum'}
                        </div>

                        <!-- Course Image -->
                        <div class="relative aspect-video overflow-hidden">
                            <img src="${course.gambar_course || '/images/default-course.jpg'}"
                                 alt="${course.nama_course}"
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300"
                                 onerror="this.src='/images/default-course.jpg'">

                            <!-- Gradient Overlay -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

                            <!-- Play Button Overlay -->
                            <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <div class="w-16 h-16 bg-white bg-opacity-90 rounded-full flex items-center justify-center transform scale-75 group-hover:scale-100 transition-transform duration-300 shadow-xl">
                                    <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-6">
                            <!-- Fresh Badge -->
                            <div class="inline-flex items-center px-3 py-1 bg-gradient-to-r ${gradients[index]} bg-opacity-10 text-gray-700 text-xs font-medium rounded-full mb-3">
                                🆕 Fresh Content
                            </div>

                            <!-- Title -->
                            <h3 class="text-lg font-bold text-gray-800 mb-3 line-clamp-2 group-hover:text-green-600 transition-colors">
                                ${course.nama_course}
                            </h3>

                            <!-- Description -->
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                                ${course.deskripsi_course || 'Course pembelajaran terbaru yang siap untuk Anda pelajari'}
                            </p>

                            <!-- Meta Info -->
                            <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-green-600 font-medium">
                                        ${course.created_at ? new Date(course.created_at).toLocaleDateString('id-ID') : 'Baru ditambahkan'}
                                    </span>
                                </div>
                                <div class="flex items-center space-x-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"></path>
                                    </svg>
                                    <span>${course.total_video || 0} video</span>
                                </div>
                            </div>

                            <!-- CTA Button -->
                            <a href="/courses/${course.course_id}"
                               class="block w-full text-center bg-gradient-to-r ${gradients[index]} text-white font-semibold py-3 rounded-xl hover:shadow-lg transform hover:scale-105 transition-all duration-200 shadow-md">
                                🚀 Mulai Belajar
                            </a>
                        </div>

                        <!-- Decorative Elements -->
                        <div class="absolute -top-3 -left-3 w-6 h-6 bg-gradient-to-r ${gradients[index]} rounded-full opacity-20 group-hover:opacity-40 transition-opacity duration-300"></div>
                        <div class="absolute -bottom-3 -right-3 w-8 h-8 bg-gradient-to-r ${gradients[index]} rounded-full opacity-10 group-hover:opacity-30 transition-opacity duration-300"></div>
                    </div>
                `;
            }).join('');

            // Show grid and hide loading
            loadingState.style.display = 'none';
            grid.style.display = 'grid';

            // Add stagger animation
            const courseCards = grid.children;
            Array.from(courseCards).forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    card.style.transition = 'all 0.6s ease-out';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 150);
            });
        }
    } catch (error) {
        console.error('Error loading latest courses:', error);
        const loadingState = document.getElementById('latest-loading');
        loadingState.innerHTML = `
            <div class="text-center text-gray-500">
                <svg class="w-12 h-12 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p>Gagal memuat course terbaru</p>
            </div>
        `;
    }
}

// Load all data when page loads
document.addEventListener('DOMContentLoaded', function() {
    loadStats();

    // Stagger content loading for smooth experience
    setTimeout(() => loadCategories(), 200);
    setTimeout(() => loadPopularCourses(), 400);
    setTimeout(() => loadLatestCourses(), 600);
});

// Animate counter function
function animateCounter(element, target, duration = 2000) {
    const start = 0;
    const increment = target / (duration / 16); // 60 FPS
    let current = start;

    const timer = setInterval(() => {
        current += increment;
        if (current >= target) {
            current = target;
            clearInterval(timer);
        }
        element.textContent = Math.floor(current);
    }, 16);
}

// Load statistics with animation
async function loadStats() {
    try {
        const [categoriesRes, coursesRes] = await Promise.all([
            fetch('/api/categories'),
            fetch('/api/courses')
        ]);

        const categoriesData = await categoriesRes.json();
        const coursesData = await coursesRes.json();

        if (categoriesData.success && coursesData.success) {
            // Animate counters
            setTimeout(() => {
                animateCounter(document.getElementById('stats-videos'), coursesData.courses.length || 0);
                animateCounter(document.getElementById('stats-categories'), categoriesData.categories.length);
                animateCounter(document.getElementById('stats-users'), 150 + Math.floor(Math.random() * 50)); // Simulated user count
            }, 500);
        }
    } catch (error) {
        console.error('Error loading stats:', error);
    }
}
</script>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in-up {
    animation: fadeInUp 0.6s ease-out forwards;
}

/* Category Cards Animation */
.card-hover {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.card-hover:hover {
    transform: translateY(-8px) scale(1.02);
}

/* Glassmorphism effect */
.glass-effect {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

/* Custom gradient text */
.gradient-text {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* Pulse animation for badges */
@keyframes pulse-scale {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

.pulse-scale {
    animation: pulse-scale 2s ease-in-out infinite;
}

/* Video card hover effects */
.video-card-hover {
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.video-card-hover:hover {
    transform: translateY(-12px) scale(1.03);
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

/* Play button animation */
@keyframes play-button-pulse {
    0%, 100% {
        transform: scale(1);
        box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.7);
    }
    50% {
        transform: scale(1.1);
        box-shadow: 0 0 0 10px rgba(255, 255, 255, 0);
    }
}

.play-button:hover {
    animation: play-button-pulse 1.5s infinite;
}

/* Rank badge glow */
@keyframes rank-glow {
    0%, 100% { box-shadow: 0 0 5px rgba(255, 215, 0, 0.5); }
    50% { box-shadow: 0 0 20px rgba(255, 215, 0, 0.8); }
}

.rank-badge {
    animation: rank-glow 2s ease-in-out infinite;
}

/* Fresh badge animation */
@keyframes fresh-badge {
    0% { transform: scale(1) rotate(0deg); }
    25% { transform: scale(1.1) rotate(-5deg); }
    50% { transform: scale(1) rotate(0deg); }
    75% { transform: scale(1.1) rotate(5deg); }
    100% { transform: scale(1) rotate(0deg); }
}

.fresh-badge {
    animation: fresh-badge 3s ease-in-out infinite;
}

/* Shimmer effect for loading */
@keyframes shimmer {
    0% { background-position: -200% 0; }
    100% { background-position: 200% 0; }
}

.shimmer {
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: shimmer 1.5s infinite;
}
</style>
@endpush
