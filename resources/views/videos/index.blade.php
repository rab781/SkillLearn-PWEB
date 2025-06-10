@extends('layouts.app')

@section('title', 'Video Pembelajaran - Skillearn')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-4">Video Pembelajaran</h1>

        <!-- Search & Filter -->
        <div class="flex flex-col md:flex-row gap-4 mb-6">
            <div class="flex-1">
                <input type="text" id="searchInput" placeholder="Cari video..."
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            <select id="categoryFilter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                <option value="">Semua Kategori</option>
                <!-- Categories will be loaded here -->
            </select>
        </div>
    </div>

    <!-- Videos Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="videos-grid">
        <!-- Videos will be loaded here -->
    </div>

    <!-- Pagination -->
    <div class="mt-8 flex justify-center" id="pagination">
        <!-- Pagination will be loaded here -->
    </div>
</div>
@endsection

@push('scripts')
<script>
let currentPage = 1;
let currentSearch = '';
let currentCategory = '';

async function loadVideos(page = 1, search = '', category = '') {
    try {
        let url = `/api/videos?page=${page}`;
        if (search) url += `&search=${encodeURIComponent(search)}`;
        if (category) url += `&kategori_id=${category}`;

        const response = await fetch(url);
        const data = await response.json();

        if (data.success) {
            displayVideos(data.videos.data);
            displayPagination(data.videos);
        }
    } catch (error) {
        console.error('Error loading videos:', error);
    }
}

async function loadCategories() {
    try {
        const response = await fetch('/api/categories');
        const data = await response.json();

        if (data.success) {
            const select = document.getElementById('categoryFilter');
            select.innerHTML = '<option value="">Semua Kategori</option>' +
                data.categories.map(cat =>
                    `<option value="${cat.kategori_id}">${cat.kategori}</option>`
                ).join('');
        }
    } catch (error) {
        console.error('Error loading categories:', error);
    }
}

function displayVideos(videos) {
    const grid = document.getElementById('videos-grid');

    if (videos.length === 0) {
        grid.innerHTML = '<div class="col-span-full text-center py-8"><p class="text-gray-500">Tidak ada video ditemukan</p></div>';
        return;
    }

    grid.innerHTML = videos.map(video => `
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
            <img src="${video.gambar}" alt="${video.nama}" class="w-full h-48 object-cover">
            <div class="p-6">
                <div class="flex justify-between items-start mb-2">
                    <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">${video.kategori?.kategori || 'Umum'}</span>
                    ${isAuthenticated() ? `<button onclick="toggleBookmark(${video.vidio_id})" class="text-gray-400 hover:text-red-500"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg></button>` : ''}
                </div>
                <h3 class="text-lg font-semibold mb-2 line-clamp-2">${video.nama}</h3>
                <p class="text-gray-600 text-sm mb-4 line-clamp-3">${video.deskripsi}</p>
                <div class="flex justify-between items-center">
                    <span class="text-gray-500 text-sm">${video.jumlah_tayang} views</span>
                    <a href="/videos/${video.vidio_id}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Tonton</a>
                </div>
            </div>
        </div>
    `).join('');
}

function displayPagination(paginationData) {
    const container = document.getElementById('pagination');
    const { current_page, last_page, from, to, total } = paginationData;

    if (last_page <= 1) {
        container.innerHTML = '';
        return;
    }

    let pagination = '<div class="flex items-center space-x-2">';

    // Previous button
    if (current_page > 1) {
        pagination += `<button onclick="changePage(${current_page - 1})" class="px-3 py-2 text-gray-500 hover:text-gray-700">Previous</button>`;
    }

    // Page numbers
    for (let i = Math.max(1, current_page - 2); i <= Math.min(last_page, current_page + 2); i++) {
        const activeClass = i === current_page ? 'bg-blue-600 text-white' : 'text-gray-700 hover:bg-gray-100';
        pagination += `<button onclick="changePage(${i})" class="px-3 py-2 ${activeClass} rounded">${i}</button>`;
    }

    // Next button
    if (current_page < last_page) {
        pagination += `<button onclick="changePage(${current_page + 1})" class="px-3 py-2 text-gray-500 hover:text-gray-700">Next</button>`;
    }

    pagination += '</div>';
    pagination += `<p class="text-gray-600 text-sm mt-2">Showing ${from} to ${to} of ${total} results</p>`;

    container.innerHTML = pagination;
}

function changePage(page) {
    currentPage = page;
    loadVideos(currentPage, currentSearch, currentCategory);
}

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

        if (result.success) {
            Swal.fire({
                title: 'Berhasil!',
                text: result.message,
                icon: 'success',
                timer: 1500,
                showConfirmButton: false
            });
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

function isAuthenticated() {
    // Simple check - in real app, you'd check session/token
    return document.querySelector('nav').textContent.includes('Hi,');
}

// Event listeners
document.getElementById('searchInput').addEventListener('input', function(e) {
    currentSearch = e.target.value;
    currentPage = 1;
    clearTimeout(this.searchTimeout);
    this.searchTimeout = setTimeout(() => {
        loadVideos(currentPage, currentSearch, currentCategory);
    }, 500);
});

document.getElementById('categoryFilter').addEventListener('change', function(e) {
    currentCategory = e.target.value;
    currentPage = 1;
    loadVideos(currentPage, currentSearch, currentCategory);
});

// Load data when page loads
document.addEventListener('DOMContentLoaded', function() {
    // Check URL params
    const urlParams = new URLSearchParams(window.location.search);
    currentCategory = urlParams.get('category') || '';
    currentSearch = urlParams.get('search') || '';

    loadCategories();
    loadVideos(currentPage, currentSearch, currentCategory);

    // Set form values from URL params
    if (currentCategory) document.getElementById('categoryFilter').value = currentCategory;
    if (currentSearch) document.getElementById('searchInput').value = currentSearch;
});
</script>
@endpush
