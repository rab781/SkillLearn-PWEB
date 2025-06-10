@extends('layouts.app')

@section('title', 'Video Pembelajaran - Skillearn')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Enhanced Header -->
    <div class="mb-12">
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent mb-4">
                🎬 Koleksi Video Pembelajaran
            </h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Temukan ribuan video pembelajaran berkualitas tinggi yang dikurasi khusus untuk meningkatkan skill Anda
            </p>
        </div>

        <!-- Enhanced Search & Filter -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
            <div class="flex flex-col lg:flex-row gap-6">
                <!-- Search Input -->
                <div class="flex-1 relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-gray-400 text-xl">🔍</span>
                    </div>
                    <input type="text" id="searchInput"
                           placeholder="Cari video, topik, atau skill yang ingin dipelajari..."
                           class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                </div>

                <!-- Category Filter -->
                <div class="lg:w-64">
                    <select id="categoryFilter"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                        <option value="">📚 Semua Kategori</option>
                        <!-- Categories will be loaded here -->
                    </select>
                </div>

                <!-- Sort Options -->
                <div class="lg:w-48">
                    <select id="sortFilter"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                        <option value="popular">🔥 Terpopuler</option>
                        <option value="newest">✨ Terbaru</option>
                        <option value="oldest">📅 Terlama</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading State -->
    <div id="loading-state" class="hidden">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="animate-pulse" v-for="i in 6">
                <div class="bg-gray-200 rounded-xl h-48 mb-4"></div>
                <div class="h-4 bg-gray-200 rounded mb-2"></div>
                <div class="h-3 bg-gray-200 rounded w-2/3"></div>
            </div>
        </div>
    </div>

    <!-- Enhanced Videos Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" id="videos-grid">
    </div>

    <!-- No Results State -->
    <div id="no-results" class="hidden text-center py-16">
        <div class="text-6xl mb-4">😢</div>
        <h3 class="text-2xl font-bold text-gray-700 mb-2">Tidak ada video ditemukan</h3>
        <p class="text-gray-500 mb-6">Coba ubah kata kunci pencarian atau pilih kategori lain</p>
        <button onclick="clearFilters()" class="btn-primary text-white px-6 py-3 rounded-xl">
            🔄 Reset Filter
        </button>
    </div>

    <!-- Enhanced Pagination -->
    <div class="mt-12 flex justify-center" id="pagination">
        <!-- Pagination will be loaded here -->
    </div>
</div>
@endsection

@push('scripts')
<script>
let currentPage = 1;
let currentSearch = '';
let currentCategory = '';
let currentSort = 'popular';
let isLoading = false;

async function loadVideos(page = 1, search = '', category = '', sort = 'popular') {
    if (isLoading) return;

    isLoading = true;
    showLoadingState();

    try {
        let url = `/api/videos?page=${page}`;
        if (search) url += `&search=${encodeURIComponent(search)}`;
        if (category) url += `&kategori_id=${category}`;

        // Handle sorting
        switch(sort) {
            case 'newest':
                url += '&sort=created_at&order=desc';
                break;
            case 'oldest':
                url += '&sort=created_at&order=asc';
                break;
            case 'popular':
            default:
                url += '&sort=jumlah_tayang&order=desc';
                break;
        }

        console.log('Loading from URL:', url);

        const response = await fetch(url);
        const data = await response.json();

        console.log('API Response:', data);

        if (data.success) {
            displayVideos(data.videos);
            displayPagination(data.videos);
        } else {
            showNoResults();
        }
    } catch (error) {
        console.error('Error loading videos:', error);
        showErrorState();
    } finally {
        isLoading = false;
        hideLoadingState();
    }
}

function showLoadingState() {
    const loadingState = document.getElementById('loading-state');
    const videosGrid = document.getElementById('videos-grid');
    const noResults = document.getElementById('no-results');

    if (loadingState) loadingState.classList.remove('hidden');
    if (videosGrid) videosGrid.innerHTML = '';
    if (noResults) noResults.classList.add('hidden');
}

function hideLoadingState() {
    const loadingState = document.getElementById('loading-state');
    if (loadingState) loadingState.classList.add('hidden');
}

function showNoResults() {
    const noResults = document.getElementById('no-results');
    const videosGrid = document.getElementById('videos-grid');

    if (noResults) noResults.classList.remove('hidden');
    if (videosGrid) videosGrid.innerHTML = '';
}

function showErrorState() {
    const videosGrid = document.getElementById('videos-grid');
    if (videosGrid) {
        videosGrid.innerHTML = `
            <div class="col-span-full text-center py-16">
                <div class="text-6xl mb-4">😔</div>
                <h3 class="text-2xl font-bold text-gray-700 mb-2">Oops! Terjadi kesalahan</h3>
                <p class="text-gray-500 mb-6">Gagal memuat video. Silakan coba lagi.</p>
                <button onclick="loadVideos(currentPage, currentSearch, currentCategory, currentSort)"
                        class="btn-primary text-white px-6 py-3 rounded-xl">
                    🔄 Coba Lagi
                </button>
            </div>
        `;
    }
}

async function loadCategories() {
    try {
        const response = await fetch('/api/categories');
        const data = await response.json();

        if (data.success) {
            const select = document.getElementById('categoryFilter');
            select.innerHTML = '<option value="">📚 Semua Kategori</option>' +
                data.categories.map(cat =>
                    `<option value="${cat.kategori_id}">${cat.kategori}</option>`
                ).join('');
        }
    } catch (error) {
        console.error('Error loading categories:', error);
    }
}

function displayVideos(videosData) {
    const grid = document.getElementById('videos-grid');
    const noResults = document.getElementById('no-results');

    // Handle both paginated and non-paginated responses
    const videos = videosData.data || videosData;

    console.log('Videos to display:', videos);

    if (!videos || videos.length === 0) {
        showNoResults();
        return;
    }

    noResults.classList.add('hidden');

    grid.innerHTML = videos.map((video, index) => {
        const gradients = [
            'from-blue-500 to-purple-500',
            'from-purple-500 to-pink-500',
            'from-green-500 to-blue-500',
            'from-orange-500 to-red-500',
            'from-indigo-500 to-purple-500',
            'from-pink-500 to-rose-500'
        ];
        const cardGradient = gradients[index % gradients.length];

        return `
        <div class="group relative bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-500 transform hover:scale-105 cursor-pointer video-card"
             onclick="goToVideo(${video.vidio_id})"
             data-video-id="${video.vidio_id}"
             tabindex="0"
             role="button"
             aria-label="Tonton video ${video.nama}">

            <!-- Video Thumbnail with Play Overlay -->
            <div class="relative aspect-video overflow-hidden bg-gradient-to-br from-gray-100 to-gray-200">
                <img src="${video.gambar || '/images/default-video.jpg'}"
                     alt="${video.nama}"
                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                     onerror="this.src='/images/default-video.jpg'"
                     loading="lazy">

                <!-- Dark Overlay on Hover -->
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-500"></div>

                <!-- Play Button Overlay -->
                <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-500">
                    <div class="w-20 h-20 bg-white/95 backdrop-blur-sm rounded-full flex items-center justify-center transform scale-75 group-hover:scale-100 transition-all duration-500 shadow-2xl play-button hover:bg-gradient-to-r hover:from-red-500 hover:to-pink-500 hover:text-white">
                        <svg class="w-8 h-8 text-red-600 ml-1 group-hover:text-white transition-colors duration-300" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M8 5v14l11-7z"/>
                        </svg>
                    </div>
                </div>

                <!-- Category Badge -->
                <div class="absolute top-3 left-3 z-10 opacity-90 group-hover:opacity-100 transition-opacity duration-300">
                    <span class="bg-gradient-to-r ${cardGradient} text-white text-xs px-3 py-1.5 rounded-full font-medium shadow-lg backdrop-blur-sm border border-white/20 hover:shadow-xl transition-all duration-300">
                        ${video.kategori?.kategori || 'Umum'}
                    </span>
                </div>

                <!-- Top Right Badges -->
                <div class="absolute top-3 right-3 z-10 flex items-center space-x-2 opacity-90 group-hover:opacity-100 transition-opacity duration-300">
                    <!-- Views Badge -->
                    <span class="bg-black/70 backdrop-blur-md text-white text-xs px-2.5 py-1.5 rounded-full border border-white/20 flex items-center space-x-1">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                        </svg>
                        <span>${formatViews(video.jumlah_tayang)}</span>
                    </span>

                    <!-- Bookmark Button -->
                    ${isAuthenticated() ? `
                    <button onclick="event.stopPropagation(); toggleBookmark(${video.vidio_id})"
                            class="bg-black/70 backdrop-blur-md text-white p-2 rounded-full hover:bg-blue-500/80 transition-all duration-200 hover:scale-110 bookmark-btn border border-white/20"
                            title="Bookmark video ini">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </button>
                    ` : ''}
                </div>

                <!-- Duration/Quality Badge -->
                <div class="absolute bottom-3 right-3 z-10 opacity-90 group-hover:opacity-100 transition-opacity duration-300">
                    <span class="bg-black/70 backdrop-blur-md text-white text-xs px-2.5 py-1.5 rounded-md border border-white/20 flex items-center space-x-1">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 6a2 2 0 012-2h6l2 2h6a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM5 8a1 1 0 011-1h1a1 1 0 010 2H6a1 1 0 01-1-1zm6 1a1 1 0 100 2h3a1 1 0 100-2H11z"/>
                        </svg>
                        <span>HD Video</span>
                    </span>
                </div>

                <!-- Watch Later Icon (appears on hover) -->
                <div class="absolute bottom-3 left-3 z-10 opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-y-2 group-hover:translate-y-0">
                    <button onclick="event.stopPropagation(); addToWatchLater(${video.vidio_id})"
                            class="bg-white/90 backdrop-blur-sm text-gray-700 p-2 rounded-full hover:bg-white transition-all duration-200 hover:scale-110 shadow-lg"
                            title="Tonton nanti">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Card Content -->
            <div class="p-6 bg-gradient-to-b from-white to-gray-50/50 group-hover:from-gray-50 group-hover:to-white transition-all duration-500">
                <!-- Title -->
                <h3 class="text-lg font-bold mb-3 line-clamp-2 text-gray-800 group-hover:text-transparent group-hover:bg-gradient-to-r group-hover:${cardGradient} group-hover:bg-clip-text transition-all duration-300 leading-tight">
                    ${video.nama}
                </h3>

                <!-- Description -->
                <p class="text-gray-600 text-sm mb-4 line-clamp-3 leading-relaxed group-hover:text-gray-700 transition-colors duration-300">
                    ${video.deskripsi || 'Deskripsi video akan tersedia segera...'}
                </p>

                <!-- Meta Information -->
                <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                    <div class="flex items-center space-x-4">
                        <!-- Views with enhanced icon -->
                        <span class="flex items-center space-x-1.5 bg-gray-100 px-2.5 py-1 rounded-full group-hover:bg-gradient-to-r group-hover:${cardGradient} group-hover:text-white transition-all duration-300">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="font-medium">${formatViews(video.jumlah_tayang)}</span>
                        </span>

                        <!-- Date with enhanced styling -->
                        <span class="flex items-center space-x-1.5 text-xs">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                            </svg>
                            <span>${formatDate(video.created_at)}</span>
                        </span>
                    </div>

                    <!-- Quality and Rating Indicators -->
                    <div class="flex items-center space-x-2">
                        <span class="text-yellow-500 text-lg">⭐</span>
                        <span class="text-xs font-medium bg-blue-100 text-blue-800 px-2 py-0.5 rounded-full">HD</span>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center space-x-3">
                    <!-- Enhanced Watch Button -->
                    <button onclick="event.stopPropagation(); goToVideo(${video.vidio_id})"
                            class="flex-1 bg-gradient-to-r ${cardGradient} text-white px-6 py-3.5 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 hover:shadow-xl watch-btn relative overflow-hidden group/btn">
                        <span class="flex items-center justify-center space-x-2 relative z-10">
                            <svg class="w-5 h-5 group-hover/btn:animate-pulse" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M8 5v14l11-7z"/>
                            </svg>
                            <span class="font-medium">Tonton</span>
                        </span>
                        <!-- Button shine effect -->
                        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover/btn:translate-x-full transition-transform duration-700"></div>
                    </button>

                    <!-- Quick Action Menu with enhanced styling -->
                    <div class="relative">
                        <button onclick="event.stopPropagation(); toggleQuickMenu(${video.vidio_id})"
                                class="p-3.5 text-gray-400 hover:text-white hover:bg-gradient-to-r hover:${cardGradient} transition-all duration-300 hover:scale-110 rounded-xl hover:shadow-lg quick-menu-btn"
                                title="Menu aksi cepat">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Enhanced Progress Bar with animation -->
                <div class="mt-5 relative">
                    <div class="h-1.5 bg-gray-200 rounded-full overflow-hidden opacity-0 group-hover:opacity-100 transition-all duration-500 transform translate-y-2 group-hover:translate-y-0">
                        <div class="h-full bg-gradient-to-r ${cardGradient} rounded-full progress-bar transition-all duration-1000"
                             style="width: ${getRandomProgress()}%"></div>
                    </div>
                    <p class="text-xs text-gray-400 mt-2 opacity-0 group-hover:opacity-100 transition-all duration-500 text-center">
                        ${getProgressText()}
                    </p>
                </div>
            </div>

            <!-- Hover Glow Effect -->
            <div class="absolute inset-0 rounded-2xl bg-gradient-to-r ${cardGradient} opacity-0 group-hover:opacity-10 transition-opacity duration-500 pointer-events-none"></div>
        </div>
        `;
    }).join('');
}

function displayPagination(paginationData) {
    const container = document.getElementById('pagination');

    if (!paginationData || !paginationData.current_page) {
        container.innerHTML = '';
        return;
    }

    const { current_page, last_page, from, to, total } = paginationData;

    if (last_page <= 1) {
        container.innerHTML = '';
        return;
    }

    let pagination = '<div class="flex items-center justify-center space-x-2">';

    // Previous button
    if (current_page > 1) {
        pagination += `
            <button onclick="changePage(${current_page - 1})"
                    class="px-4 py-2 text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                Previous
            </button>
        `;
    }

    // Page numbers
    for (let i = Math.max(1, current_page - 2); i <= Math.min(last_page, current_page + 2); i++) {
        const activeClass = i === current_page
            ? 'bg-gradient-to-r from-blue-500 to-purple-500 text-white border-transparent'
            : 'text-gray-700 bg-white border-gray-300 hover:bg-gray-50';
        pagination += `
            <button onclick="changePage(${i})"
                    class="px-4 py-2 border rounded-lg transition-colors ${activeClass}">
                ${i}
            </button>
        `;
    }

    // Next button
    if (current_page < last_page) {
        pagination += `
            <button onclick="changePage(${current_page + 1})"
                    class="px-4 py-2 text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                Next
            </button>
        `;
    }

    pagination += '</div>';

    if (from && to && total) {
        pagination += `
            <p class="text-gray-600 text-sm mt-4 text-center">
                Menampilkan ${from} sampai ${to} dari ${total} video
            </p>
        `;
    }

    container.innerHTML = pagination;
}

function changePage(page) {
    currentPage = page;
    loadVideos(currentPage, currentSearch, currentCategory, currentSort);
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function clearFilters() {
    currentSearch = '';
    currentCategory = '';
    currentSort = 'popular';
    currentPage = 1;

    document.getElementById('searchInput').value = '';
    document.getElementById('categoryFilter').value = '';
    document.getElementById('sortFilter').value = 'popular';
    document.getElementById('no-results').classList.add('hidden');

    loadVideos(currentPage, currentSearch, currentCategory, currentSort);
}

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
            body: JSON.stringify({ vidio_vidio_id: videoId }),
            credentials: 'same-origin'
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
                body: JSON.stringify({ vidio_vidio_id: videoId }),
                credentials: 'same-origin'
            });
        }

        const result = await response.json();

        if (result.success) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Berhasil!',
                    text: result.message,
                    icon: 'success',
                    timer: 1500,
                    showConfirmButton: false
                });
            }
        } else {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Info',
                    text: result.message,
                    icon: 'info'
                });
            }
        }
    } catch (error) {
        console.error('Bookmark error:', error);
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Error!',
                text: 'Terjadi kesalahan sistem',
                icon: 'error'
            });
        }
    }
}

function isAuthenticated() {
    return document.querySelector('nav').textContent.includes('Dashboard') ||
           document.querySelector('nav').textContent.includes('Admin');
}

// Navigation Functions
function goToVideo(videoId) {
    // Add loading effect
    const card = document.querySelector(`[data-video-id="${videoId}"]`);
    if (card) {
        card.classList.add('loading');
        card.style.transform = 'scale(0.95)';
        card.style.opacity = '0.7';

        // Add loading spinner
        const spinner = document.createElement('div');
        spinner.className = 'absolute inset-0 bg-white/80 backdrop-blur-sm flex items-center justify-center z-50 rounded-2xl';
        spinner.innerHTML = `
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
        `;
        card.appendChild(spinner);
    }

    // Add a small delay for better UX
    setTimeout(() => {
        window.location.href = `/videos/${videoId}`;
    }, 300);
}

// Utility Functions
function formatViews(views) {
    if (views >= 1000000) {
        return Math.floor(views / 1000000) + 'M';
    } else if (views >= 1000) {
        return Math.floor(views / 1000) + 'K';
    }
    return views?.toString() || '0';
}

function formatDate(dateString) {
    if (!dateString) return 'Baru';

    const date = new Date(dateString);
    const now = new Date();
    const diffTime = Math.abs(now - date);
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

    if (diffDays === 1) return 'Kemarin';
    if (diffDays <= 7) return `${diffDays} hari lalu`;
    if (diffDays <= 30) return `${Math.floor(diffDays / 7)} minggu lalu`;
    if (diffDays <= 365) return `${Math.floor(diffDays / 30)} bulan lalu`;

    return date.toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
}

function getRandomProgress() {
    return Math.floor(Math.random() * 80) + 10; // 10-90%
}

function getProgressText() {
    const messages = [
        'Mulai menonton sekarang',
        'Video berkualitas tinggi',
        'Pembelajaran interaktif',
        'Konten terbaru tersedia'
    ];
    return messages[Math.floor(Math.random() * messages.length)];
}

// Watch Later Function
function addToWatchLater(videoId) {
    if (!isAuthenticated()) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Perlu Login',
                text: 'Silakan login untuk menggunakan fitur ini',
                icon: 'info',
                confirmButtonText: 'Login'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '/login';
                }
            });
        } else {
            alert('Silakan login untuk menggunakan fitur ini');
        }
        return;
    }

    // Add to watch later logic here
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Ditambahkan!',
            text: 'Video telah ditambahkan ke daftar tonton nanti',
            icon: 'success',
            timer: 2000,
            showConfirmButton: false
        });
    }
}

// Quick Menu Functions
function toggleQuickMenu(videoId) {
    // Close any existing menu
    document.querySelectorAll('.quick-menu').forEach(menu => menu.remove());

    const menu = document.createElement('div');
    menu.className = 'quick-menu absolute top-full right-0 mt-2 bg-white rounded-xl shadow-2xl border border-gray-200 p-2 z-50 min-w-52 backdrop-blur-lg bg-white/95';
    menu.innerHTML = `
        <div class="py-1">
            <button onclick="goToVideo(${videoId})" class="w-full text-left px-4 py-3 text-sm hover:bg-gradient-to-r hover:from-blue-50 hover:to-purple-50 rounded-lg flex items-center space-x-3 transition-all duration-200 hover:scale-105 group">
                <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-500 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M8 5v14l11-7z"/>
                    </svg>
                </div>
                <div>
                    <div class="font-medium text-gray-900">Tonton Video</div>
                    <div class="text-xs text-gray-500">Mulai pembelajaran</div>
                </div>
            </button>

            ${isAuthenticated() ? `
            <button onclick="toggleBookmark(${videoId})" class="w-full text-left px-4 py-3 text-sm hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 rounded-lg flex items-center space-x-3 transition-all duration-200 hover:scale-105 group">
                <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <div>
                    <div class="font-medium text-gray-900">Bookmark</div>
                    <div class="text-xs text-gray-500">Simpan ke favorit</div>
                </div>
            </button>

            <button onclick="addToWatchLater(${videoId})" class="w-full text-left px-4 py-3 text-sm hover:bg-gradient-to-r hover:from-green-50 hover:to-emerald-50 rounded-lg flex items-center space-x-3 transition-all duration-200 hover:scale-105 group">
                <div class="w-8 h-8 bg-gradient-to-r from-green-500 to-emerald-500 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <div class="font-medium text-gray-900">Tonton Nanti</div>
                    <div class="text-xs text-gray-500">Tambah ke playlist</div>
                </div>
            </button>
            ` : ''}

            <button onclick="shareVideo(${videoId})" class="w-full text-left px-4 py-3 text-sm hover:bg-gradient-to-r hover:from-blue-50 hover:to-cyan-50 rounded-lg flex items-center space-x-3 transition-all duration-200 hover:scale-105 group">
                <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"/>
                    </svg>
                </div>
                <div>
                    <div class="font-medium text-gray-900">Bagikan</div>
                    <div class="text-xs text-gray-500">Kirim ke teman</div>
                </div>
            </button>
        </div>
    `;

    // Position menu relative to clicked button
    const button = event.target.closest('button');
    const container = button.closest('.relative');
    container.appendChild(menu);

    // Add entrance animation
    menu.style.opacity = '0';
    menu.style.transform = 'translateY(-10px) scale(0.95)';
    setTimeout(() => {
        menu.style.transition = 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
        menu.style.opacity = '1';
        menu.style.transform = 'translateY(0) scale(1)';
    }, 10);

    // Close menu when clicking outside
    setTimeout(() => {
        document.addEventListener('click', function closeMenu(e) {
            if (!menu.contains(e.target) && !button.contains(e.target)) {
                menu.style.transform = 'translateY(-10px) scale(0.95)';
                menu.style.opacity = '0';
                setTimeout(() => menu.remove(), 200);
                document.removeEventListener('click', closeMenu);
            }
        });
    }, 100);
}

// Share Video Function
function shareVideo(videoId) {
    const url = `${window.location.origin}/videos/${videoId}`;
    const title = document.querySelector(`[data-video-id="${videoId}"] h3`).textContent;

    if (navigator.share) {
        navigator.share({
            title: `${title} - Skillearn`,
            text: 'Lihat video pembelajaran menarik ini di Skillearn!',
            url: url
        }).catch(err => console.log('Error sharing:', err));
    } else {
        // Enhanced fallback with copy animation
        navigator.clipboard.writeText(url).then(() => {
            // Show copy animation
            const button = event.target.closest('button');
            const originalHTML = button.innerHTML;

            button.innerHTML = `
                <div class="w-8 h-8 bg-gradient-to-r from-green-500 to-emerald-500 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <div>
                    <div class="font-medium text-green-600">Berhasil!</div>
                    <div class="text-xs text-green-500">Link disalin</div>
                </div>
            `;

            setTimeout(() => {
                button.innerHTML = originalHTML;
            }, 2000);

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Link Disalin!',
                    text: 'Link video telah disalin ke clipboard',
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            }
        }).catch(err => {
            console.error('Failed to copy:', err);
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Error!',
                    text: 'Gagal menyalin link',
                    icon: 'error',
                    toast: true,
                    position: 'top-end'
                });
            }
        });
    }
}

// Enhanced Card Interactions
document.addEventListener('DOMContentLoaded', function() {
    // Initialize card animations
    initCardAnimations();

    // Add card hover sound effect (optional)
    document.addEventListener('mouseover', function(e) {
        if (e.target.closest('.video-card')) {
            // Optional: Add subtle hover sound
            // playHoverSound();
        }
    });

    // Enhanced keyboard navigation
    document.addEventListener('keydown', function(e) {
        const focusedCard = document.activeElement.closest('.video-card');
        if (focusedCard) {
            const videoId = focusedCard.dataset.videoId;

            switch(e.key) {
                case 'Enter':
                case ' ':
                    e.preventDefault();
                    goToVideo(videoId);
                    break;
                case 'b':
                case 'B':
                    if (isAuthenticated()) {
                        e.preventDefault();
                        toggleBookmark(videoId);
                    }
                    break;
                case 's':
                case 'S':
                    e.preventDefault();
                    shareVideo(videoId);
                    break;
                case 'm':
                case 'M':
                    e.preventDefault();
                    toggleQuickMenu(videoId);
                    break;
            }
        }

        // Arrow key navigation between cards
        if (['ArrowUp', 'ArrowDown', 'ArrowLeft', 'ArrowRight'].includes(e.key)) {
            handleArrowNavigation(e);
        }
    });

    // Add intersection observer for card animations
    const cardObserver = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.style.animationDelay = `${Math.random() * 0.5}s`;
                entry.target.classList.add('animate-fade-in-up');
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '50px'
    });

    // Observe all video cards
    document.querySelectorAll('.video-card').forEach(card => {
        cardObserver.observe(card);
    });
});

function initCardAnimations() {
    // Add stagger animation for initial load
    const cards = document.querySelectorAll('.video-card');
    cards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
        card.classList.add('animate-fade-in');
    });
}

function handleArrowNavigation(e) {
    const cards = Array.from(document.querySelectorAll('.video-card'));
    const currentIndex = cards.findIndex(card => card === document.activeElement);

    if (currentIndex === -1) return;

    let newIndex;
    const cardsPerRow = getCardsPerRow();

    switch(e.key) {
        case 'ArrowLeft':
            newIndex = Math.max(0, currentIndex - 1);
            break;
        case 'ArrowRight':
            newIndex = Math.min(cards.length - 1, currentIndex + 1);
            break;
        case 'ArrowUp':
            newIndex = Math.max(0, currentIndex - cardsPerRow);
            break;
        case 'ArrowDown':
            newIndex = Math.min(cards.length - 1, currentIndex + cardsPerRow);
            break;
    }

    if (newIndex !== undefined && newIndex !== currentIndex) {
        e.preventDefault();
        cards[newIndex].focus();
        cards[newIndex].scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }
}

function getCardsPerRow() {
    const screenWidth = window.innerWidth;
    if (screenWidth >= 1280) return 4; // xl
    if (screenWidth >= 1024) return 3; // lg
    if (screenWidth >= 768) return 2;  // md
    return 1; // sm
}

// Performance optimizations
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Lazy loading for images
function initLazyLoading() {
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.remove('lazy');
                    imageObserver.unobserve(img);
                }
            });
        });

        document.querySelectorAll('img[data-src]').forEach(img => {
            imageObserver.observe(img);
        });
    }
}

// Event listeners
document.getElementById('searchInput').addEventListener('input', function(e) {
    currentSearch = e.target.value;
    currentPage = 1;
    clearTimeout(this.searchTimeout);
    this.searchTimeout = setTimeout(() => {
        loadVideos(currentPage, currentSearch, currentCategory, currentSort);
    }, 500);
});

document.getElementById('categoryFilter').addEventListener('change', function(e) {
    currentCategory = e.target.value;
    currentPage = 1;
    loadVideos(currentPage, currentSearch, currentCategory, currentSort);
});

document.getElementById('sortFilter').addEventListener('change', function(e) {
    currentSort = e.target.value;
    currentPage = 1;
    loadVideos(currentPage, currentSearch, currentCategory, currentSort);
});

// Load data when page loads
document.addEventListener('DOMContentLoaded', function() {
    console.log('Page loaded, initializing...');

    const urlParams = new URLSearchParams(window.location.search);
    currentCategory = urlParams.get('category') || '';
    currentSearch = urlParams.get('search') || '';

    loadCategories();
    loadVideos(currentPage, currentSearch, currentCategory, currentSort);

    if (currentCategory) document.getElementById('categoryFilter').value = currentCategory;
    if (currentSearch) document.getElementById('searchInput').value = currentSearch;
});
</script>
@endpush

@push('styles')
<style>
/* Enhanced Video Card Styling */
.video-card {
    transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    transform-origin: center;
    will-change: transform, box-shadow;
    opacity: 0;
    transform: translateY(20px);
}

.video-card.animate-fade-in {
    animation: fadeInUp 0.8s ease-out forwards;
}

.video-card.animate-fade-in-up {
    animation: fadeInUpScroll 0.6s ease-out forwards;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

@keyframes fadeInUpScroll {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.video-card:hover {
    transform: translateY(-16px) scale(1.04);
    box-shadow:
        0 25px 50px -12px rgba(0, 0, 0, 0.25),
        0 0 0 1px rgba(255, 255, 255, 0.1),
        0 0 40px rgba(59, 130, 246, 0.15);
}

.video-card:active {
    transform: translateY(-12px) scale(1.02);
    transition: all 0.1s ease;
}

/* Enhanced Play Button Animation */
.play-button {
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.play-button::before {
    content: '';
    position: absolute;
    inset: 0;
    border-radius: 50%;
    background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.3), transparent);
    transform: translateX(-100%);
    transition: transform 0.6s;
}

.play-button:hover::before {
    transform: translateX(100%);
}

.play-button:hover {
    transform: scale(1.15);
    box-shadow:
        0 0 0 15px rgba(239, 68, 68, 0.1),
        0 0 40px rgba(239, 68, 68, 0.3);
    animation: pulse-play 2s infinite;
}

@keyframes pulse-play {
    0%, 100% {
        box-shadow:
            0 0 0 0 rgba(239, 68, 68, 0.4),
            0 0 40px rgba(239, 68, 68, 0.3);
    }
    50% {
        box-shadow:
            0 0 0 20px rgba(239, 68, 68, 0),
            0 0 40px rgba(239, 68, 68, 0.3);
    }
}

/* Enhanced Watch Button */
.watch-btn {
    position: relative;
    overflow: hidden;
    background-size: 200% 100%;
    background-position: 100% 0;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.watch-btn:hover {
    background-position: 0 0;
    transform: scale(1.05) translateY(-2px);
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.25);
}

/* Enhanced Bookmark Button */
.bookmark-btn {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
}

.bookmark-btn::after {
    content: '';
    position: absolute;
    inset: 0;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(59, 130, 246, 0.3) 0%, transparent 70%);
    transform: scale(0);
    transition: transform 0.3s;
}

.bookmark-btn:hover::after {
    transform: scale(1);
}

.bookmark-btn:hover {
    transform: scale(1.2) rotate(10deg);
    background-color: rgba(59, 130, 246, 0.9);
}

/* Enhanced Quick Menu */
.quick-menu {
    animation: slideInScale 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    box-shadow:
        0 20px 25px -5px rgba(0, 0, 0, 0.1),
        0 10px 10px -5px rgba(0, 0, 0, 0.04),
        0 0 0 1px rgba(255, 255, 255, 0.1);
}

@keyframes slideInScale {
    from {
        opacity: 0;
        transform: translateY(-15px) scale(0.9);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

.quick-menu button {
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    transform-origin: left center;
}

.quick-menu button:hover {
    transform: translateX(8px);
    background-color: rgba(255, 255, 255, 0.8);
}

/* Enhanced Progress Bar */
.progress-bar {
    animation: progressFill 1.2s ease-out;
    position: relative;
    overflow: hidden;
}

.progress-bar::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    height: 100%;
    width: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
    animation: shimmer 2s infinite;
}

@keyframes progressFill {
    from {
        width: 0%;
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

@keyframes shimmer {
    0% {
        transform: translateX(-100%);
    }
    100% {
        transform: translateX(100%);
    }
}

/* Enhanced Card Glow Effect */
.video-card::after {
    content: '';
    position: absolute;
    top: -2px;
    left: -2px;
    right: -2px;
    bottom: -2px;
    border-radius: 1.125rem;
    background: linear-gradient(45deg,
        transparent 0%,
        rgba(59, 130, 246, 0.3) 25%,
        rgba(147, 51, 234, 0.3) 50%,
        rgba(239, 68, 68, 0.3) 75%,
        transparent 100%);
    opacity: 0;
    transition: opacity 0.4s ease;
    pointer-events: none;
    z-index: -1;
    animation: borderRotate 3s linear infinite;
}

.video-card:hover::after {
    opacity: 1;
}

@keyframes borderRotate {
    0% {
        background: linear-gradient(45deg, transparent, rgba(59, 130, 246, 0.3), transparent);
    }
    25% {
        background: linear-gradient(135deg, transparent, rgba(147, 51, 234, 0.3), transparent);
    }
    50% {
        background: linear-gradient(225deg, transparent, rgba(239, 68, 68, 0.3), transparent);
    }
    75% {
        background: linear-gradient(315deg, transparent, rgba(16, 185, 129, 0.3), transparent);
    }
    100% {
        background: linear-gradient(45deg, transparent, rgba(59, 130, 246, 0.3), transparent);
    }
}

/* Loading State */
.video-card.loading {
    opacity: 0.6;
    transform: scale(0.98);
    pointer-events: none;
    position: relative;
}

.video-card.loading::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
    animation: loadingShimmer 1.5s infinite;
    z-index: 10;
    border-radius: 1rem;
}

@keyframes loadingShimmer {
    0% {
        transform: translateX(-100%);
    }
    100% {
        transform: translateX(100%);
    }
}

/* Text Gradient Animation */
.video-card h3 {
    background-size: 200% 100%;
    transition: all 0.4s ease;
}

.video-card:hover h3 {
    animation: textShine 2s ease-in-out infinite;
}

@keyframes textShine {
    0% { background-position: 200% center; }
    100% { background-position: -200% center; }
}

/* Enhanced Focus States */
.video-card:focus-visible {
    outline: none;
    box-shadow:
        0 0 0 3px rgba(59, 130, 246, 0.5),
        0 25px 50px -12px rgba(0, 0, 0, 0.25);
    transform: translateY(-8px) scale(1.02);
}

/* Responsive Enhancements */
@media (max-width: 768px) {
    .video-card:hover {
        transform: translateY(-12px) scale(1.03);
    }

    .play-button {
        width: 60px;
        height: 60px;
    }

    .play-button svg {
        width: 24px;
        height: 24px;
    }

    .quick-menu {
        min-width: 200px;
        left: 50%;
        right: auto;
        transform: translateX(-50%);
    }
}

/* Dark Mode Support */
@media (prefers-color-scheme: dark) {
    .video-card {
        background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
        color: #f9fafb;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .quick-menu {
        background: rgba(31, 41, 55, 0.95);
        border-color: rgba(255, 255, 255, 0.2);
    }
}

/* High Contrast Mode */
@media (prefers-contrast: high) {
    .video-card {
        border: 3px solid #000;
        box-shadow: none;
    }

    .video-card:hover {
        border-color: #0066cc;
        box-shadow: 0 0 0 3px #0066cc;
    }
}

/* Reduced Motion */
@media (prefers-reduced-motion: reduce) {
    .video-card,
    .play-button,
    .watch-btn,
    .bookmark-btn,
    .quick-menu {
        transition: none !important;
        animation: none !important;
    }

    .video-card:hover {
        transform: none;
    }
}

/* Print Styles */
@media print {
    .video-card {
        break-inside: avoid;
        box-shadow: none;
        border: 1px solid #e5e7eb;
        transform: none !important;
    }

    .play-button,
    .bookmark-btn,
    .watch-btn,
    .quick-menu {
        display: none;
    }
}
</style>
@endpush
