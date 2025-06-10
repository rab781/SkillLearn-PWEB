@extends('layouts.app')

@section('title', 'Detail Video - Skillearn')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Video Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Video Section -->
        <div class="lg:col-span-2">
            <!-- Video Player -->
            <div class="bg-black rounded-lg overflow-hidden mb-6" id="video-player">
                <!-- Video will be loaded here -->
            </div>

            <!-- Video Info -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6" id="video-info">
                <!-- Video info will be loaded here -->
            </div>

            <!-- Feedback Section -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-semibold mb-4">Feedback & Komentar</h3>

                @auth
                <!-- Add Feedback Form -->
                <form id="feedbackForm" class="mb-6">
                    @csrf
                    <div class="mb-4">
                        <textarea name="pesan" placeholder="Berikan feedback untuk video ini..."
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                  rows="3"></textarea>
                    </div>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        Kirim Feedback
                    </button>
                </form>
                @else
                <div class="mb-6 p-4 bg-gray-100 rounded-lg text-center">
                    <p class="text-gray-600">
                        <a href="/login" class="text-blue-600 hover:text-blue-800">Login</a>
                        untuk memberikan feedback
                    </p>
                </div>
                @endauth

                <!-- Feedbacks List -->
                <div id="feedbacks-list">
                    <!-- Feedbacks will be loaded here -->
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- Related Videos -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-semibold mb-4">Video Terkait</h3>
                <div class="space-y-4" id="related-videos">
                    <!-- Related videos will be loaded here -->
                </div>
            </div>

            <!-- Categories -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold mb-4">Kategori Lainnya</h3>
                <div class="space-y-2" id="categories-list">
                    <!-- Categories will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let currentVideoId = {{ request()->route('id') ?? 'null' }};

async function loadVideoDetail() {
    if (!currentVideoId) return;

    try {
        const response = await fetch(`/api/videos/${currentVideoId}`);
        const data = await response.json();

        if (data.success) {
            displayVideoPlayer(data.video);
            displayVideoInfo(data.video);
            displayFeedbacks(data.video.feedbacks || []);
            loadRelatedVideos(data.video.kategori_kategori_id);
        }
    } catch (error) {
        console.error('Error loading video:', error);
    }
}

function displayVideoPlayer(video) {
    const container = document.getElementById('video-player');

    // Extract YouTube video ID if it's a YouTube URL
    let embedUrl = video.url;
    const youtubeMatch = video.url.match(/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&\n?#]+)/);
    if (youtubeMatch) {
        embedUrl = `https://www.youtube.com/embed/${youtubeMatch[1]}`;
    }

    container.innerHTML = `
        <div class="relative w-full h-0 pb-[56.25%]">
            <iframe src="${embedUrl}"
                    class="absolute top-0 left-0 w-full h-full"
                    frameborder="0"
                    allowfullscreen>
            </iframe>
        </div>
    `;
}

function displayVideoInfo(video) {
    const container = document.getElementById('video-info');
    container.innerHTML = `
        <div class="flex justify-between items-start mb-4">
            <div>
                <h1 class="text-2xl font-bold mb-2">${video.nama}</h1>
                <div class="flex items-center space-x-4 text-gray-600">
                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-sm">${video.kategori?.kategori || 'Umum'}</span>
                    <span>${video.jumlah_tayang} views</span>
                    <span>${new Date(video.created_at).toLocaleDateString('id-ID')}</span>
                </div>
            </div>
            ${isAuthenticated() ? `
            <button onclick="toggleBookmark(${video.vidio_id})" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
                Bookmark
            </button>` : ''}
        </div>
        <div class="prose max-w-none">
            <h3 class="text-lg font-semibold mb-2">Deskripsi</h3>
            <p class="text-gray-700 leading-relaxed">${video.deskripsi}</p>
        </div>
    `;
}

function displayFeedbacks(feedbacks) {
    const container = document.getElementById('feedbacks-list');

    if (feedbacks.length === 0) {
        container.innerHTML = '<p class="text-gray-500 text-center py-4">Belum ada feedback untuk video ini</p>';
        return;
    }

    container.innerHTML = feedbacks.map(feedback => `
        <div class="border-b border-gray-200 pb-4 mb-4 last:border-b-0">
            <div class="flex justify-between items-start mb-2">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white text-sm font-semibold">
                        ${feedback.user?.nama_lengkap?.charAt(0) || 'U'}
                    </div>
                    <div class="ml-3">
                        <p class="font-semibold text-sm">${feedback.user?.nama_lengkap || 'User'}</p>
                        <p class="text-gray-500 text-xs">${new Date(feedback.tanggal).toLocaleDateString('id-ID')}</p>
                    </div>
                </div>
                ${isCurrentUser(feedback.users_id) ? `
                <div class="flex space-x-2">
                    <button onclick="editFeedback(${feedback.feedback_id})" class="text-blue-600 hover:text-blue-800 text-sm">Edit</button>
                    <button onclick="deleteFeedback(${feedback.feedback_id})" class="text-red-600 hover:text-red-800 text-sm">Hapus</button>
                </div>` : ''}
            </div>
            <p class="text-gray-800 mb-2">${feedback.pesan}</p>
            ${feedback.balasan ? `
            <div class="bg-gray-50 p-3 rounded-lg mt-2">
                <p class="text-sm font-semibold text-gray-600 mb-1">Balasan Admin:</p>
                <p class="text-gray-800">${feedback.balasan}</p>
            </div>` : ''}
        </div>
    `).join('');
}

async function loadRelatedVideos(categoryId) {
    if (!categoryId) return;

    try {
        const response = await fetch(`/api/videos/category/${categoryId}`);
        const data = await response.json();

        if (data.success) {
            const relatedVideos = data.videos.filter(v => v.vidio_id != currentVideoId).slice(0, 5);
            displayRelatedVideos(relatedVideos);
        }
    } catch (error) {
        console.error('Error loading related videos:', error);
    }
}

function displayRelatedVideos(videos) {
    const container = document.getElementById('related-videos');

    if (videos.length === 0) {
        container.innerHTML = '<p class="text-gray-500 text-sm">Tidak ada video terkait</p>';
        return;
    }

    container.innerHTML = videos.map(video => `
        <div class="flex space-x-3">
            <img src="${video.gambar}" alt="${video.nama}" class="w-20 h-12 object-cover rounded">
            <div class="flex-1">
                <a href="/videos/${video.vidio_id}" class="text-sm font-medium hover:text-blue-600 line-clamp-2">${video.nama}</a>
                <p class="text-xs text-gray-500 mt-1">${video.jumlah_tayang} views</p>
            </div>
        </div>
    `).join('');
}

async function loadCategories() {
    try {
        const response = await fetch('/api/categories');
        const data = await response.json();

        if (data.success) {
            displayCategories(data.categories);
        }
    } catch (error) {
        console.error('Error loading categories:', error);
    }
}

function displayCategories(categories) {
    const container = document.getElementById('categories-list');
    container.innerHTML = categories.map(category => `
        <a href="/videos?category=${category.kategori_id}"
           class="block p-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded">
            ${category.kategori} (${category.vidios_count || 0})
        </a>
    `).join('');
}

// Feedback actions
document.getElementById('feedbackForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const data = Object.fromEntries(formData);
    data.vidio_vidio_id = currentVideoId;

    try {
        const response = await fetch('/api/feedbacks', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(data)
        });

        const result = await response.json();

        if (result.success) {
            Swal.fire({
                title: 'Berhasil!',
                text: 'Feedback berhasil dikirim',
                icon: 'success'
            }).then(() => {
                this.reset();
                loadVideoDetail(); // Reload to show new feedback
            });
        } else {
            Swal.fire({
                title: 'Gagal!',
                text: result.message,
                icon: 'error'
            });
        }
    } catch (error) {
        Swal.fire({
            title: 'Error!',
            text: 'Terjadi kesalahan sistem',
            icon: 'error'
        });
    }
});

async function toggleBookmark(videoId) {
    try {
        const response = await fetch('/api/bookmarks', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ vidio_vidio_id: videoId })
        });

        const result = await response.json();

        Swal.fire({
            title: result.success ? 'Berhasil!' : 'Info',
            text: result.message,
            icon: result.success ? 'success' : 'info',
            timer: 1500,
            showConfirmButton: false
        });
    } catch (error) {
        Swal.fire({
            title: 'Error!',
            text: 'Terjadi kesalahan sistem',
            icon: 'error'
        });
    }
}

function isAuthenticated() {
    return document.querySelector('nav').textContent.includes('Hi,');
}

function isCurrentUser(userId) {
    // Simple check - in real app, you'd compare with current user ID
    return isAuthenticated(); // For demo purposes
}

// Load data when page loads
document.addEventListener('DOMContentLoaded', function() {
    loadVideoDetail();
    loadCategories();
});
</script>
@endpush
