@extends('layouts.app')

@section('title', 'Dashboard - Skillearn')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Enhanced Header -->
    <div class="mb-8 text-center lg:text-left">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h1 class="text-4xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                    🎯 Dashboard Pembelajaran
                </h1>
                <p class="text-xl text-gray-600 mt-2">
                    Selamat datang kembali, <span class="font-semibold text-blue-600">{{ auth()->user()->nama_lengkap }}</span>!
                    <span class="text-2xl">👋</span>
                </p>
            </div>
            <div class="mt-4 lg:mt-0">
                <div class="inline-flex items-center px-4 py-2 glass-effect rounded-full text-sm font-medium">
                    <span class="w-2 h-2 bg-green-400 rounded-full mr-2 animate-pulse"></span>
                    🔥 Keep Learning!
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8" id="stats-cards">
        <!-- Enhanced loading state -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100">📚 Total Bookmarks</p>
                    <p class="text-3xl font-bold">
                        <span class="loading-spinner inline-block"></span>
                    </p>
                </div>
                <div class="text-4xl opacity-80">💾</div>
            </div>
        </div>
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100">💬 Total Feedback</p>
                    <p class="text-3xl font-bold">
                        <span class="loading-spinner inline-block"></span>
                    </p>
                </div>
                <div class="text-4xl opacity-80">📝</div>
            </div>
        </div>
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100">🎓 Progress Score</p>
                    <p class="text-3xl font-bold">
                        <span class="loading-spinner inline-block"></span>
                    </p>
                </div>
                <div class="text-4xl opacity-80">📊</div>
            </div>
        </div>
    </div>

    <!-- Enhanced Recent Bookmarks -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8 card-hover">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                <span class="text-3xl mr-2">⭐</span>
                Bookmark Favoritmu
            </h2>
            <a href="/videos" class="btn-primary text-white px-4 py-2 rounded-lg text-sm font-medium">
                Lihat Semua 🔍
            </a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="recent-bookmarks">
            <!-- Enhanced loading state -->
            <div class="animate-pulse">
                <div class="bg-gray-200 rounded-lg h-48 mb-4"></div>
                <div class="h-4 bg-gray-200 rounded mb-2"></div>
                <div class="h-3 bg-gray-200 rounded w-2/3"></div>
            </div>
        </div>
    </div>

    <!-- Enhanced Recently Watched Videos -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8 card-hover">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                <span class="text-3xl mr-2">🕒</span>
                Riwayat Video yang Baru Ditonton
            </h2>
            <div class="flex space-x-2">
                <span class="skill-badge">📺 Riwayat</span>
                <span class="skill-badge">⏰ Terbaru</span>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="recently-watched">
            <!-- Enhanced loading state -->
            <div class="animate-pulse">
                <div class="bg-gray-200 rounded-lg h-48 mb-4"></div>
                <div class="h-4 bg-gray-200 rounded mb-2"></div>
                <div class="h-3 bg-gray-200 rounded w-2/3"></div>
            </div>
        </div>
    </div>

    <!-- Enhanced Categories -->
    <div class="bg-white rounded-xl shadow-lg p-6 card-hover">
        <div class="text-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800 flex items-center justify-center">
                <span class="text-3xl mr-2">🎯</span>
                Jelajahi Kategori Skill
            </h2>
            <p class="text-gray-600 mt-2">Pilih kategori yang sesuai dengan minatmu dan mulai belajar!</p>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6" id="categories">
            <!-- Enhanced loading state -->
            <div class="animate-pulse text-center">
                <div class="bg-gray-200 rounded-full h-16 w-16 mx-auto mb-3"></div>
                <div class="h-4 bg-gray-200 rounded"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<style>
/* Enhanced Dashboard Styles */
.glass-effect {
    background: rgba(255, 255, 255, 0.25);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.18);
}

.card-hover {
    transition: all 0.3s ease;
}

.card-hover:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

.loading-spinner {
    width: 20px;
    height: 20px;
    border: 2px solid #ffffff33;
    border-top: 2px solid #ffffff;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.animate-fade-in {
    animation: fadeIn 0.5s ease-in-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    transition: all 0.3s ease;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
}

.skill-badge {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
}

.aspect-video {
    aspect-ratio: 16 / 9;
}

/* Notification Styles */
.notification {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
    background: white;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    padding: 16px 20px;
    min-width: 300px;
    transform: translateX(100%);
    transition: all 0.3s ease;
}

.notification.show {
    transform: translateX(0);
}

.notification.success {
    border-left: 4px solid #10b981;
}

.notification.error {
    border-left: 4px solid #ef4444;
}

.notification.info {
    border-left: 4px solid #3b82f6;
}

/* Responsive Grid Fixes */
@media (max-width: 768px) {
    .grid-cols-1.md\\:grid-cols-2.lg\\:grid-cols-3 {
        grid-template-columns: repeat(1, minmax(0, 1fr));
    }

    .grid-cols-2.md\\:grid-cols-3.lg\\:grid-cols-4 {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 640px) {
    .grid-cols-2.md\\:grid-cols-3.lg\\:grid-cols-4 {
        grid-template-columns: repeat(1, minmax(0, 1fr));
    }
}
</style>

<script>
// Load dashboard data
async function loadDashboard() {
    try {
        // Show loading state
        showLoadingState();

        // Check if we have server-side data first
        @if(isset($dashboardData) && $dashboardData['success'])
            const dashboardData = @json($dashboardData);
            console.log('Using server-side dashboard data:', dashboardData);

            loadStats(dashboardData.stats || {});
            loadRecentBookmarks(dashboardData.recent_bookmarks || []);
            loadRecentlyWatched(dashboardData.recently_watched || []);
            loadCategories(dashboardData.categories || []);
            return;
        @endif

        // Fallback to API if no server-side data
        console.log('No server-side data, trying API...');

        // Check if we're authenticated first by checking if user name is displayed
        const userName = document.querySelector('span.font-semibold.text-blue-600');
        if (!userName || userName.textContent.trim() === '') {
            console.log('User not properly authenticated, redirecting to login');
            window.location.href = '/login';
            return;
        }

        // Try API endpoint first, then fallback
        let response = await fetch('/api/dashboard', {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            credentials: 'same-origin'
        });

        console.log('Dashboard API response status:', response.status);

        // If unauthorized, user might need to login
        if (response.status === 401) {
            console.log('401 Unauthorized - trying fallback data');
            loadFallbackData();
            return;
        }

        if (response.status === 403) {
            const errorData = await response.json();
            console.log('403 Forbidden:', errorData);
            showErrorState('Akses ditolak: ' + (errorData.message || 'Insufficient permissions'));
            return;
        }

        if (!response.ok) {
            const errorData = await response.json().catch(() => ({}));
            console.error('API Error:', response.status, errorData);
            throw new Error(`HTTP error! status: ${response.status} - ${errorData.message || 'Unknown error'}`);
        }

        const data = await response.json();

        if (data.success) {
            loadStats(data.stats || {});
            loadRecentBookmarks(data.recent_bookmarks || []);
            loadRecentlyWatched(data.recently_watched || []);
            loadCategories(data.categories || []);
        } else {
            throw new Error(data.message || 'Failed to load dashboard data');
        }
    } catch (error) {
        console.error('Error loading dashboard:', error);

        // If API fails, try to load basic fallback data
        console.log('API failed, loading fallback data...');
        loadFallbackData();
    }
}

function loadFallbackData() {
    console.log('Loading fallback dashboard data...');

    // Load basic stats with fallback data
    loadStats({
        bookmarks_count: 0,
        feedbacks_count: 0
    });

    // Load empty states for other sections
    loadRecentBookmarks([]);
    loadRecentlyWatched([]);
    loadCategories([
        {kategori_id: 1, kategori: 'Programming', vidios_count: 0},
        {kategori_id: 2, kategori: 'Design', vidios_count: 0},
        {kategori_id: 3, kategori: 'Marketing', vidios_count: 0},
        {kategori_id: 4, kategori: 'Business', vidios_count: 0}
    ]);

    // Show info message
    showInfoNotification('Memuat data dashboard dalam mode offline. Beberapa fitur mungkin terbatas.');
}

function showLoadingState() {
    // Loading state is already in HTML
    console.log('Loading dashboard data...');
}

function showErrorState(errorMessage = 'Gagal memuat data dashboard') {
    const statsContainer = document.getElementById('stats-cards');
    if (statsContainer) {
        statsContainer.innerHTML = `
            <div class="col-span-3 text-center py-8">
                <div class="text-6xl mb-4">⚠️</div>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">Terjadi Kesalahan</h3>
                <p class="text-gray-500 mb-4">${errorMessage}</p>
                <button onclick="loadDashboard()" class="btn-primary text-white px-6 py-3 rounded-xl">
                    🔄 Coba Lagi
                </button>
            </div>
        `;
    }
}

function loadStats(stats) {
    const statsContainer = document.getElementById('stats-cards');
    const bookmarksCount = stats.bookmarks_count || 0;
    const feedbacksCount = stats.feedbacks_count || 0;
    const progressScore = Math.min(85 + bookmarksCount * 2, 100);

    statsContainer.innerHTML = `
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white card-hover animate-fade-in">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100">📚 Total Bookmarks</p>
                    <p class="text-3xl font-bold">${bookmarksCount}</p>
                    <p class="text-sm text-blue-200 mt-1">Video tersimpan</p>
                </div>
                <div class="text-4xl opacity-80">💾</div>
            </div>
        </div>
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white card-hover animate-fade-in">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100">💬 Total Feedback</p>
                    <p class="text-3xl font-bold">${feedbacksCount}</p>
                    <p class="text-sm text-purple-200 mt-1">Komentar diberikan</p>
                </div>
                <div class="text-4xl opacity-80">📝</div>
            </div>
        </div>
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white card-hover animate-fade-in">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100">🎓 Progress Score</p>
                    <p class="text-3xl font-bold">${progressScore}%</p>
                    <p class="text-sm text-green-200 mt-1">Skor pembelajaran</p>
                </div>
                <div class="text-4xl opacity-80">📊</div>
            </div>
        </div>
    `;
}

function loadRecentBookmarks(bookmarks) {
    const container = document.getElementById('recent-bookmarks');
    if (!bookmarks || bookmarks.length === 0) {
        container.innerHTML = `
            <div class="col-span-full text-center py-12">
                <div class="text-6xl mb-4">📚</div>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">Belum ada bookmark</h3>
                <p class="text-gray-500 mb-4">Mulai bookmark video favoritmu untuk akses cepat</p>
                <a href="/videos" class="btn-primary text-white px-6 py-3 rounded-xl">
                    🔍 Jelajahi Video
                </a>
            </div>
        `;
        return;
    }

    container.innerHTML = bookmarks.map(bookmark => `
        <div class="group bg-gray-50 rounded-xl overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300 transform hover:scale-105">
            <div class="aspect-video bg-gray-200 relative overflow-hidden">
                <img src="${bookmark.vidio.gambar || '/images/default-video.svg'}"
                     alt="${bookmark.vidio.nama}"
                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300"
                     onerror="this.src='/images/default-video.svg'">
                <div class="absolute top-2 right-2">
                    <span class="bg-black/70 text-white text-xs px-2 py-1 rounded">${bookmark.vidio.kategori?.kategori || 'Umum'}</span>
                </div>
                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-all duration-300 flex items-center justify-center">
                    <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <div class="bg-white/90 rounded-full p-3">
                            <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M8 5v14l11-7z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-4">
                <h4 class="font-semibold text-gray-900 mb-2 line-clamp-2 group-hover:text-blue-600 transition-colors">
                    ${bookmark.vidio.nama}
                </h4>
                <p class="text-sm text-gray-600 mb-3 line-clamp-2">${bookmark.vidio.deskripsi || 'Deskripsi tidak tersedia'}</p>
                <div class="flex items-center justify-between">
                    <div class="flex items-center text-sm text-gray-500">
                        <span class="mr-3">👁️ ${bookmark.vidio.jumlah_tayang || 0}</span>
                        <span>⭐ Bookmark</span>
                    </div>
                    <a href="/videos/${bookmark.vidio.vidio_id}"
                       onclick="incrementViewCount(${bookmark.vidio.vidio_id})"
                       class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        Tonton →
                    </a>
                </div>
            </div>
        </div>
    `).join('');
}
function loadRecentlyWatched(watchHistory) {
    const container = document.getElementById('recently-watched');

    if (!watchHistory || watchHistory.length === 0) {
        container.innerHTML = `
            <div class="col-span-full text-center py-12">
                <div class="text-6xl mb-4">🕒</div>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">Belum ada riwayat tonton</h3>
                <p class="text-gray-500 mb-4">Video yang kamu tonton akan muncul di sini</p>
                <a href="/videos" class="btn-primary text-white px-6 py-3 rounded-xl">
                    🔍 Jelajahi Video
                </a>
            </div>
        `;
        return;
    }

    container.innerHTML = watchHistory.slice(0, 6).map(history => {
        const video = history.video || history; // Handle both watch history and fallback video objects
        const watchedAt = history.waktu_ditonton ? new Date(history.waktu_ditonton).toLocaleDateString('id-ID', {
            day: 'numeric',
            month: 'short',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        }) : 'Baru saja';
        const progress = history.persentase_progress || 0;

        return `
        <div class="group bg-gray-50 rounded-xl overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300 transform hover:scale-105">
            <div class="aspect-video bg-gray-200 relative overflow-hidden">
                <img src="${video.gambar || '/images/default-video.svg'}"
                     alt="${video.nama}"
                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300"
                     onerror="this.src='/images/default-video.svg'">
                <div class="absolute top-2 right-2">
                    <span class="bg-blue-500 text-white text-xs px-2 py-1 rounded">🕒 Riwayat</span>
                </div>
                <div class="absolute top-2 left-2">
                    <span class="bg-black/70 text-white text-xs px-2 py-1 rounded">${video.kategori?.kategori || 'Umum'}</span>
                </div>
                ${progress > 0 ? `
                <div class="absolute bottom-0 left-0 right-0 bg-black/50 text-white p-2">
                    <div class="w-full bg-gray-600 rounded-full h-1.5 mb-1">
                        <div class="bg-blue-500 h-1.5 rounded-full" style="width: ${progress}%"></div>
                    </div>
                    <span class="text-xs">${Math.round(progress)}% selesai</span>
                </div>
                ` : ''}
                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-all duration-300 flex items-center justify-center">
                    <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <div class="bg-white/90 rounded-full p-3">
                            <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M8 5v14l11-7z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-4">
                <h4 class="font-semibold text-gray-900 mb-2 line-clamp-2 group-hover:text-blue-600 transition-colors">
                    ${video.nama}
                </h4>
                <p class="text-sm text-gray-600 mb-2 line-clamp-2">${video.deskripsi || 'Deskripsi tidak tersedia'}</p>
                <p class="text-xs text-gray-500 mb-3">📅 Ditonton: ${watchedAt}</p>
                <div class="flex items-center justify-between">
                    <div class="flex items-center text-sm text-gray-500">
                        <span class="mr-3">👁️ ${video.jumlah_tayang || 0}</span>
                        <span class="mr-3">💬 ${video.feedbacks_count || 0}</span>
                    </div>
                    <div class="flex gap-2">
                        <a href="/videos/${video.vidio_id}"
                           onclick="recordWatchHistory(${video.vidio_id})"
                           class="bg-blue-600 text-white text-xs px-3 py-1 rounded hover:bg-blue-700 transition-colors">
                            ${progress > 0 ? 'Lanjutkan' : 'Tonton Lagi'}
                        </a>
                        <button onclick="toggleBookmark(${video.vidio_id})"
                                class="bg-gray-200 text-gray-700 text-xs px-3 py-1 rounded hover:bg-gray-300 transition-colors">
                            ⭐
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    }).join('');
}

function loadCategories(categories) {
    const container = document.getElementById('categories');

    if (!categories || categories.length === 0) {
        container.innerHTML = `
            <div class="col-span-full text-center py-12">
                <div class="text-6xl mb-4">🎯</div>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">Belum ada kategori</h3>
                <p class="text-gray-500">Kategori skill akan muncul di sini</p>
            </div>
        `;
        return;
    }

    // Category icons mapping
    const categoryIcons = {
        'Programming': '💻',
        'Design': '🎨',
        'Business': '💼',
        'Marketing': '📈',
        'Photography': '📸',
        'Music': '🎵',
        'Cooking': '👨‍🍳',
        'Fitness': '💪',
        'Language': '🌍',
        'Science': '🔬',
        'default': '📚'
    };

    container.innerHTML = categories.map(category => {
        const icon = categoryIcons[category.kategori] || categoryIcons.default;
        const videoCount = category.vidios_count || 0;

        return `
            <div class="group text-center p-6 bg-gray-50 rounded-xl hover:bg-blue-50 transition-all duration-300 transform hover:scale-105 cursor-pointer">
                <div class="text-4xl mb-3 group-hover:scale-110 transition-transform duration-300">
                    ${icon}
                </div>
                <h3 class="font-semibold text-gray-900 mb-1 group-hover:text-blue-600 transition-colors">
                    ${category.kategori}
                </h3>
                <p class="text-sm text-gray-600">
                    ${videoCount} video${videoCount !== 1 ? 's' : ''}
                </p>
                <div class="mt-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <a href="/videos?kategori=${category.kategori_id}"
                       class="inline-block bg-blue-600 text-white text-xs px-4 py-2 rounded-full hover:bg-blue-700 transition-colors">
                        Jelajahi →
                    </a>
                </div>
            </div>
        `;
    }).join('');
}

// Bookmark functionality
async function toggleBookmark(videoId) {
    try {
        // Try API endpoint first, then fallback to web route
        let response = await fetch('/api/bookmarks', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            credentials: 'same-origin',
            body: JSON.stringify({
                vidio_vidio_id: videoId
            })
        });

        // If API fails, try web route
        if (!response.ok && response.status === 401) {
            response = await fetch('/web/bookmark', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                credentials: 'same-origin',
                body: JSON.stringify({
                    vidio_vidio_id: videoId
                })
            });
        }

        const result = await response.json();

        if (result.success) {
            // Use custom notification instead of Swal
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Berhasil!',
                    text: result.message,
                    icon: 'success',
                    timer: 1500,
                    showConfirmButton: false
                });
            } else {
                showSuccessNotification(result.message);
            }
            // Reload dashboard to update bookmark count
            loadDashboard();
        } else {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Info',
                    text: result.message,
                    icon: 'info'
                });
            } else {
                showInfoNotification(result.message);
            }
        }
    } catch (error) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Error!',
                text: 'Terjadi kesalahan sistem',
                icon: 'error'
            });
        } else {
            showErrorNotification('Terjadi kesalahan sistem');
        }
    }
}

// Increment view count when user clicks on video
async function incrementViewCount(videoId) {
    try {
        await fetch(`/api/videos/${videoId}/increment-view`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            credentials: 'same-origin'
        });
    } catch (error) {
        console.error('Failed to increment view count:', error);
    }
}

// Record watch history when user watches a video
async function recordWatchHistory(videoId, duration = 0, progress = 0) {
    try {
        const response = await fetch('/api/watch-history', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            credentials: 'same-origin',
            body: JSON.stringify({
                video_id: videoId,
                duration: duration,
                progress: progress
            })
        });

        const data = await response.json();

        if (data.success) {
            console.log('Watch history recorded successfully');
        }
    } catch (error) {
        console.error('Failed to record watch history:', error);
    }
}

// Enhanced notification system
function showNotification(message, type = 'info', duration = 3000) {
    // Remove existing notifications
    document.querySelectorAll('.notification').forEach(n => n.remove());

    const notification = document.createElement('div');
    notification.className = `notification ${type}`;

    const icon = type === 'success' ? '✅' : type === 'error' ? '❌' : 'ℹ️';

    notification.innerHTML = `
        <div class="flex items-center">
            <span class="text-lg mr-3">${icon}</span>
            <div class="flex-1">
                <p class="font-medium text-gray-900">${message}</p>
            </div>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-3 text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
            </button>
        </div>
    `;

    document.body.appendChild(notification);

    // Show notification
    setTimeout(() => notification.classList.add('show'), 100);

    // Auto remove after duration
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => notification.remove(), 300);
    }, duration);
}

// Replace Swal alerts with custom notifications
function showSuccessNotification(message) {
    showNotification(message, 'success');
}

function showErrorNotification(message) {
    showNotification(message, 'error');
}

function showInfoNotification(message) {
    showNotification(message, 'info');
}

// Load data when page loads
document.addEventListener('DOMContentLoaded', loadDashboard);
</script>
@endpush
