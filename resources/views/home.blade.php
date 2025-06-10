@extends('layouts.app')

@section('title', 'Skillearn - Platform Pembelajaran Online Modern')

@section('content')
<!-- Hero Section -->
<section class="relative bg-gradient-to-br from-blue-600 via-purple-600 to-indigo-700 text-white py-24 overflow-hidden">
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
            <div class="text-center lg:text-left">
                <div class="inline-flex items-center px-4 py-2 bg-white/10 rounded-full text-sm font-medium mb-6 backdrop-blur-sm">
                    <span class="w-2 h-2 bg-green-400 rounded-full mr-2 animate-pulse"></span>
                    Platform Pembelajaran #1 di Indonesia
                </div>

                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold mb-6 leading-tight">
                    Belajar Skill Baru dengan
                    <span class="bg-gradient-to-r from-yellow-400 to-orange-400 bg-clip-text text-transparent">
                        Video Terkurasi
                    </span>
                </h1>

                <p class="text-lg sm:text-xl mb-8 text-blue-100 max-w-2xl">
                    Platform pembelajaran online modern dengan kurasi video berkualitas tinggi.
                    Tingkatkan skill dengan pembelajaran terarah dari expert terpercaya.
                </p>

                <!-- Stats -->
                <div class="grid grid-cols-3 gap-4 mb-8">
                    <div class="text-center">
                        <div class="text-2xl font-bold" id="stats-videos">-</div>
                        <div class="text-sm text-blue-200">Video</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold" id="stats-categories">-</div>
                        <div class="text-sm text-blue-200">Kategori</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold" id="stats-users">-</div>
                        <div class="text-sm text-blue-200">Learners</div>
                    </div>
                </div>

                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                    @guest
                        <a href="/register" class="group bg-white text-blue-600 px-8 py-4 rounded-xl font-semibold hover:bg-gray-50 transition-all shadow-lg hover:shadow-xl">
                            <span class="flex items-center justify-center">
                                Mulai Belajar Gratis
                                <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                            </span>
                        </a>
                        <a href="/videos" class="border-2 border-white text-white px-8 py-4 rounded-xl font-semibold hover:bg-white hover:text-blue-600 transition-all backdrop-blur-sm">
                            Jelajahi Video
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
                        <a href="/videos" class="border-2 border-white text-white px-8 py-4 rounded-xl font-semibold hover:bg-white hover:text-blue-600 transition-all backdrop-blur-sm">
                            Jelajahi Video
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
                                    <div class="text-sm text-blue-200">25+ Video</div>
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
                                    <div class="text-sm text-blue-200">15+ Video</div>
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
                                    <div class="text-sm text-blue-200">20+ Video</div>
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
            <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">Mengapa Memilih Skillearn?</h2>
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
                <p class="text-gray-600">Video pembelajaran dipilih oleh expert untuk memastikan kualitas dan relevansi materi.</p>
            </div>

            <!-- Feature 2 -->
            <div class="card-hover bg-gradient-to-br from-purple-50 to-pink-50 p-8 rounded-2xl border border-purple-100">
                <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl flex items-center justify-center mb-6">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">Personal Dashboard</h3>
                <p class="text-gray-600">Pantau progress belajar, bookmark video favorit, dan kelola aktivitas pembelajaran Anda.</p>
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
            </div>

            <!-- Feature 5 -->
            <div class="card-hover bg-gradient-to-br from-red-50 to-pink-50 p-8 rounded-2xl border border-red-100">
                <div class="w-12 h-12 bg-gradient-to-r from-red-500 to-pink-500 rounded-xl flex items-center justify-center mb-6">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">Bookmark System</h3>
                <p class="text-gray-600">Simpan video favorit Anda untuk dapat diakses dengan mudah di kemudian hari.</p>
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
<section class="py-20 bg-gradient-to-br from-gray-50 to-blue-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">Kategori Pembelajaran</h2>
            <p class="text-lg text-gray-600">Jelajahi berbagai kategori sesuai dengan minat dan kebutuhan Anda</p>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6" id="categories-grid">
            <div class="flex justify-center">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
            </div>
        </div>
    </div>
</section>

<!-- Popular Videos Section -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">Video Populer</h2>
            <p class="text-lg text-gray-600">Video dengan rating dan view terbanyak pilihan community</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" id="popular-videos">
            <div class="flex justify-center col-span-full">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
            </div>
        </div>
        <div class="text-center mt-12">
            <a href="/videos" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all shadow-lg hover:shadow-xl">
                Lihat Semua Video
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                </svg>
            </a>
        </div>
    </div>
</section>

<!-- Latest Videos Section -->
<section class="py-20 bg-gradient-to-br from-blue-50 to-purple-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">Video Terbaru</h2>
            <p class="text-lg text-gray-600">Konten pembelajaran terkini yang baru saja ditambahkan</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" id="latest-videos">
            <div class="flex justify-center col-span-full">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
            </div>
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
                <a href="/videos" class="border-2 border-white text-white px-8 py-4 rounded-xl font-semibold hover:bg-white hover:text-blue-600 transition-all">
                    Jelajahi Konten
                </a>
            </div>
        @else
            <a href="{{ auth()->user()->isAdmin() ? '/admin/dashboard' : '/customer/dashboard' }}" class="inline-flex items-center bg-white text-blue-600 px-8 py-4 rounded-xl font-semibold hover:bg-gray-50 transition-all shadow-lg hover:shadow-xl">
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
// Load categories
async function loadCategories() {
    try {
        const response = await fetch('/api/categories');
        const data = await response.json();

        if (data.success) {
            const grid = document.getElementById('categories-grid');
            grid.innerHTML = data.categories.map(category => `
                <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                    <h3 class="text-xl font-semibold mb-2">${category.kategori}</h3>
                    <p class="text-gray-600">${category.vidios_count || 0} video</p>
                    <a href="/videos?category=${category.kategori_id}" class="mt-4 inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Lihat Video</a>
                </div>
            `).join('');
        }
    } catch (error) {
        console.error('Error loading categories:', error);
    }
}

// Load popular videos
async function loadPopularVideos() {
    try {
        const response = await fetch('/api/videos/popular');
        const data = await response.json();

        if (data.success) {
            const grid = document.getElementById('popular-videos');
            grid.innerHTML = data.videos.map(video => `
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                    <img src="${video.gambar}" alt="${video.nama}" class="w-full h-48 object-cover">
                    <div class="p-4">
                        <h3 class="text-lg font-semibold mb-2">${video.nama}</h3>
                        <p class="text-gray-600 text-sm mb-2">${video.kategori?.kategori || 'Umum'}</p>
                        <p class="text-gray-500 text-sm mb-4">${video.jumlah_tayang} kali ditonton</p>
                        <a href="/videos/${video.vidio_id}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Tonton</a>
                    </div>
                </div>
            `).join('');
        }
    } catch (error) {
        console.error('Error loading popular videos:', error);
    }
}

// Load latest videos
async function loadLatestVideos() {
    try {
        const response = await fetch('/api/videos/latest');
        const data = await response.json();

        if (data.success) {
            const grid = document.getElementById('latest-videos');
            grid.innerHTML = data.videos.map(video => `
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                    <img src="${video.gambar}" alt="${video.nama}" class="w-full h-48 object-cover">
                    <div class="p-4">
                        <h3 class="text-lg font-semibold mb-2">${video.nama}</h3>
                        <p class="text-gray-600 text-sm mb-2">${video.kategori?.kategori || 'Umum'}</p>
                        <p class="text-gray-500 text-sm mb-4">${new Date(video.created_at).toLocaleDateString('id-ID')}</p>
                        <a href="/videos/${video.vidio_id}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Tonton</a>
                    </div>
                </div>
            `).join('');
        }
    } catch (error) {
        console.error('Error loading latest videos:', error);
    }
}

// Load all data when page loads
document.addEventListener('DOMContentLoaded', function() {
    loadCategories();
    loadPopularVideos();
    loadStats();

    // Stagger other content loading for smooth experience
    setTimeout(() => loadCategories(), 200);
    setTimeout(() => loadPopularVideos(), 400);
    setTimeout(() => loadLatestVideos(), 600);
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
        const [categoriesRes, videosRes] = await Promise.all([
            fetch('/api/categories'),
            fetch('/api/videos')
        ]);

        const categoriesData = await categoriesRes.json();
        const videosData = await videosRes.json();

        if (categoriesData.success && videosData.success) {
            // Animate counters
            setTimeout(() => {
                animateCounter(document.getElementById('stats-videos'), videosData.videos.total || videosData.videos.data?.length || 0);
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
</style>
@endpush
