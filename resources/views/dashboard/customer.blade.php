@extends('layouts.app')

@section('title', 'Dashboard - Skillearn')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
        <p class="text-gray-600">Selamat datang kembali, {{ auth()->user()->nama_lengkap }}!</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8" id="stats-cards">
        <!-- Stats will be loaded here -->
    </div>

    <!-- Recent Bookmarks -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-xl font-semibold mb-4">Bookmark Terbaru</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="recent-bookmarks">
            <!-- Recent bookmarks will be loaded here -->
        </div>
    </div>

    <!-- Popular Videos -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-xl font-semibold mb-4">Video Populer</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="popular-videos">
            <!-- Popular videos will be loaded here -->
        </div>
    </div>

    <!-- Categories -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold mb-4">Kategori Pembelajaran</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4" id="categories">
            <!-- Categories will be loaded here -->
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Load dashboard data
async function loadDashboard() {
    try {
        const response = await fetch('/api/dashboard', {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            },
            credentials: 'same-origin'
        });
        });
        const data = await response.json();

        if (data.success) {
            loadStats(data.stats);
            loadRecentBookmarks(data.recent_bookmarks);
            loadPopularVideos(data.popular_videos);
            loadCategories(data.categories);
        }
    } catch (error) {
        console.error('Error loading dashboard:', error);
    }
}

function loadStats(stats) {
    const statsContainer = document.getElementById('stats-cards');
    statsContainer.innerHTML = `
        <div class="bg-blue-50 p-6 rounded-lg">
            <div class="flex items-center">
                <div class="p-2 bg-blue-600 rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Bookmark</p>
                    <p class="text-2xl font-bold text-gray-900">${stats.bookmarks_count}</p>
                </div>
            </div>
        </div>
        <div class="bg-green-50 p-6 rounded-lg">
            <div class="flex items-center">
                <div class="p-2 bg-green-600 rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Feedback</p>
                    <p class="text-2xl font-bold text-gray-900">${stats.feedbacks_count}</p>
                </div>
            </div>
        </div>
        <div class="bg-purple-50 p-6 rounded-lg">
            <div class="flex items-center">
                <div class="p-2 bg-purple-600 rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Progress</p>
                    <p class="text-2xl font-bold text-gray-900">85%</p>
                </div>
            </div>
        </div>
    `;
}

function loadRecentBookmarks(bookmarks) {
    const container = document.getElementById('recent-bookmarks');
    if (bookmarks.length === 0) {
        container.innerHTML = '<p class="text-gray-500 col-span-full text-center">Belum ada bookmark</p>';
        return;
    }

    container.innerHTML = bookmarks.map(bookmark => `
        <div class="border rounded-lg p-4 hover:shadow-md transition-shadow">
            <img src="${bookmark.vidio.gambar}" alt="${bookmark.vidio.nama}" class="w-full h-32 object-cover rounded mb-2">
            <h3 class="font-semibold text-sm mb-1">${bookmark.vidio.nama}</h3>
            <p class="text-gray-600 text-xs">${bookmark.vidio.kategori?.kategori || 'Umum'}</p>
            <a href="/videos/${bookmark.vidio.vidio_id}" class="mt-2 inline-block bg-blue-600 text-white text-xs px-3 py-1 rounded hover:bg-blue-700">Tonton</a>
        </div>
    `).join('');
}

function loadPopularVideos(videos) {
    const container = document.getElementById('popular-videos');
    container.innerHTML = videos.slice(0, 6).map(video => `
        <div class="border rounded-lg p-4 hover:shadow-md transition-shadow">
            <img src="${video.gambar}" alt="${video.nama}" class="w-full h-32 object-cover rounded mb-2">
            <h3 class="font-semibold text-sm mb-1">${video.nama}</h3>
            <p class="text-gray-600 text-xs mb-1">${video.kategori?.kategori || 'Umum'}</p>
            <p class="text-gray-500 text-xs mb-2">${video.jumlah_tayang} views</p>
            <div class="flex gap-2">
                <a href="/videos/${video.vidio_id}" class="bg-blue-600 text-white text-xs px-3 py-1 rounded hover:bg-blue-700">Tonton</a>
                <button onclick="toggleBookmark(${video.vidio_id})" class="bg-gray-200 text-gray-700 text-xs px-3 py-1 rounded hover:bg-gray-300">
                    ⭐ Bookmark
                </button>
            </div>
        </div>
    `).join('');
}

// Bookmark functionality
async function toggleBookmark(videoId) {
    try {
        const response = await fetch('/api/bookmarks', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            credentials: 'same-origin',
            body: JSON.stringify({
                vidio_vidio_id: videoId
            })
        });

        const result = await response.json();
        
        if (result.success) {
            Swal.fire({
                title: 'Berhasil!',
                text: result.message,
                icon: 'success',
                timer: 1500,
                showConfirmButton: false
            });
            // Reload dashboard to update bookmark count
            loadDashboard();
        } else {
            Swal.fire({
                title: 'Info',
                text: result.message,
                icon: 'info'
            });
        }
    } catch (error) {
        Swal.fire({
            title: 'Error!',
            text: 'Terjadi kesalahan sistem',
            icon: 'error'
        });
    }
}

// Load data when page loads
document.addEventListener('DOMContentLoaded', loadDashboard);
</script>
@endpush
