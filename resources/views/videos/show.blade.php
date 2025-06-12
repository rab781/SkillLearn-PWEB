@extends('layouts.app')

@section('title', 'Detail Video - Skillearn')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="flex mb-8" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="/" class="text-gray-500 hover:text-blue-600 transition-colors">🏠 Home</a>
            </li>
            <li>
                <div class="flex items-center">
                    <span class="mx-2 text-gray-400">/</span>
                    <a href="/videos" class="text-gray-500 hover:text-blue-600 transition-colors">📹 Videos</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <span class="mx-2 text-gray-400">/</span>
                    <span class="text-gray-700 font-medium" id="breadcrumb-title">Loading...</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Video Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Video Section -->
        <div class="lg:col-span-2">
            <!-- Enhanced Video Player -->
            <div class="bg-gradient-to-br from-gray-900 to-black rounded-2xl overflow-hidden mb-6 shadow-2xl" id="video-player">
                <!-- Enhanced loading state -->
                <div class="aspect-video flex items-center justify-center">
                    <div class="text-center text-white">
                        <div class="loading-spinner mx-auto mb-4"></div>
                        <p>🎬 Memuat video...</p>
                    </div>
                </div>
            </div>

            <!-- Enhanced Video Info -->
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-8 card-hover" id="video-info">
                <!-- Enhanced loading state -->
                <div class="animate-pulse">
                    <div class="h-8 bg-gray-200 rounded w-3/4 mb-4"></div>
                    <div class="h-4 bg-gray-200 rounded w-1/2 mb-6"></div>
                    <div class="space-y-2 mb-6">
                        <div class="h-3 bg-gray-200 rounded"></div>
                        <div class="h-3 bg-gray-200 rounded w-5/6"></div>
                        <div class="h-3 bg-gray-200 rounded w-4/6"></div>
                    </div>
                    <div class="flex space-x-4">
                        <div class="h-10 bg-gray-200 rounded w-32"></div>
                        <div class="h-10 bg-gray-200 rounded w-24"></div>
                    </div>
                </div>
            </div>

            <!-- Enhanced Feedback Section -->
            <div class="bg-white rounded-2xl shadow-lg p-8 card-hover">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold text-gray-800 flex items-center">
                        <span class="text-3xl mr-3">💬</span>
                        Feedback & Diskusi
                    </h3>
                    <div class="flex items-center space-x-2">
                        {{-- <span class="skill-badge">🔥 Aktif</span> --}}
                        <span class="text-sm text-gray-500" id="feedback-count">0 komentar</span>
                    </div>
                </div>

                @auth
                <!-- Enhanced Add Feedback Form -->
                @if(auth()->user()->role === 'CU')
                <div class="mb-8 p-6 bg-gradient-to-r from-blue-50 to-purple-50 rounded-xl border border-blue-200">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center text-white font-bold mr-3">
                            {{ substr(auth()->user()->nama_lengkap, 0, 1) }}
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">{{ auth()->user()->nama_lengkap }}</p>
                            <p class="text-sm text-gray-500">Bagikan pendapat Anda tentang video ini</p>
                        </div>
                    </div>
                    <form id="feedbackForm">
                        @csrf
                        <div class="mb-4">
                            <textarea name="pesan"
                                    placeholder="💭 Tulis feedback, pertanyaan, atau insights Anda tentang video ini..."
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 resize-none"
                                    rows="4"></textarea>
                        </div>
                        <div class="flex justify-between items-center">
                            <div class="text-xs text-gray-500">
                                💡 Tips: Berikan feedback yang konstruktif dan helpful untuk learners lain
                            </div>
                            <button type="submit"
                                    class="btn-primary text-white px-6 py-3 rounded-xl font-medium flex items-center space-x-2">
                                <span>📤</span>
                                <span>Kirim Feedback</span>
                            </button>
                        </div>
                    </form>
                </div>
                @endif


                @else
                <div class="mb-8 p-6 bg-gradient-to-r from-gray-50 to-blue-50 rounded-xl border border-gray-200 text-center">
                    <div class="text-4xl mb-3">🔐</div>
                    <p class="text-gray-700 mb-4">
                        Bergabung dengan diskusi dan berikan feedback untuk video ini
                    </p>
                    <div class="space-x-4">
                        <a href="/login" class="btn-primary text-white px-6 py-3 rounded-xl inline-flex items-center space-x-2">
                            <span>🚀</span>
                            <span>Login</span>
                        </a>
                        <a href="/register" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-xl inline-flex items-center space-x-2 transition-colors">
                            <span>📝</span>
                            <span>Daftar</span>
                        </a>
                    </div>
                </div>
                @endauth

                <!-- Enhanced Feedbacks List -->
                <div id="feedbacks-list">
                    <!-- Enhanced loading state -->
                    <div class="space-y-6">
                        <div class="animate-pulse">
                            <div class="flex space-x-4 p-6 bg-gray-50 rounded-xl">
                                <div class="rounded-full bg-gray-200 h-12 w-12"></div>
                                <div class="flex-1 space-y-3">
                                    <div class="flex justify-between">
                                        <div class="h-4 bg-gray-200 rounded w-1/4"></div>
                                        <div class="h-3 bg-gray-200 rounded w-16"></div>
                                    </div>
                                    <div class="space-y-2">
                                        <div class="h-3 bg-gray-200 rounded w-3/4"></div>
                                        <div class="h-3 bg-gray-200 rounded w-1/2"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="animate-pulse" style="animation-delay: 0.2s">
                            <div class="flex space-x-4 p-6 bg-gray-50 rounded-xl">
                                <div class="rounded-full bg-gray-200 h-12 w-12"></div>
                                <div class="flex-1 space-y-3">
                                    <div class="flex justify-between">
                                        <div class="h-4 bg-gray-200 rounded w-1/3"></div>
                                        <div class="h-3 bg-gray-200 rounded w-16"></div>
                                    </div>
                                    <div class="space-y-2">
                                        <div class="h-3 bg-gray-200 rounded w-5/6"></div>
                                        <div class="h-3 bg-gray-200 rounded w-2/3"></div>
                                        <div class="h-3 bg-gray-200 rounded w-1/3"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-center text-gray-500 loading-dots">Memuat feedback</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enhanced Sidebar -->
        <div class="lg:col-span-1">
            <!-- Video Actions -->
            <div class="bg-white rounded-2xl shadow-lg p-6 mb-6 card-hover" id="video-actions">
                <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                    <span class="text-2xl mr-2">⚡</span>
                    Aksi Video
                </h3>

                <!-- Loading state -->
                <div id="actions-loading" class="animate-pulse space-y-4">
                    <div class="h-12 bg-gray-200 rounded-xl"></div>
                    <div class="h-12 bg-gray-200 rounded-xl"></div>
                    <div class="h-16 bg-gray-200 rounded-xl"></div>
                </div>

                <!-- Actions content (will be populated by JavaScript) -->
                <div id="actions-content" class="hidden space-y-4">

                    <!-- Video Progress -->
                    <div class="bg-gradient-to-r from-blue-50 to-cyan-50 rounded-xl p-4 border border-blue-200 hover:shadow-md transition-all duration-300">
                        <h4 class="font-semibold text-gray-800 mb-3 flex items-center">
                            <span class="text-lg mr-2">📊</span>
                            Progress Menonton
                        </h4>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-600">Progress:</span>
                                <span id="watch-progress" class="font-medium text-blue-600">0%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                                <div id="progress-bar" class="bg-gradient-to-r from-blue-500 to-cyan-500 h-3 rounded-full transition-all duration-500 ease-out" style="width: 0%"></div>
                            </div>
                            <div class="flex justify-between text-xs text-gray-500">
                                <span id="watch-time">0:00</span>
                                <span id="total-time">--:--</span>
                            </div>
                        </div>
                    </div>

                    <!-- Video Rating -->
                    <div class="bg-gradient-to-r from-yellow-50 to-orange-50 rounded-xl p-4 border border-yellow-200 hover:shadow-md transition-all duration-300">
                        <h4 class="font-semibold text-gray-800 mb-3 flex items-center">
                            <span class="text-lg mr-2">⭐</span>
                            Rating Video
                        </h4>
                        <div class="space-y-3">
                            <div class="flex justify-center space-x-1" id="rating-stars">
                                <button onclick="setRating(1)" class="text-2xl hover:scale-125 transition-transform duration-200 star-btn cursor-pointer" data-rating="1">⭐</button>
                                <button onclick="setRating(2)" class="text-2xl hover:scale-125 transition-transform duration-200 star-btn cursor-pointer" data-rating="2">⭐</button>
                                <button onclick="setRating(3)" class="text-2xl hover:scale-125 transition-transform duration-200 star-btn cursor-pointer" data-rating="3">⭐</button>
                                <button onclick="setRating(4)" class="text-2xl hover:scale-125 transition-transform duration-200 star-btn cursor-pointer" data-rating="4">⭐</button>
                                <button onclick="setRating(5)" class="text-2xl hover:scale-125 transition-transform duration-200 star-btn cursor-pointer" data-rating="5">⭐</button>
                            </div>
                            <p id="rating-text" class="text-center text-sm text-gray-600">Klik bintang untuk memberi rating</p>
                        </div>
                    </div>

                    <!-- Quick Notes -->
                    <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl p-4 border border-indigo-200 hover:shadow-md transition-all duration-300">
                        <h4 class="font-semibold text-gray-800 mb-3 flex items-center">
                            <span class="text-lg mr-2">📝</span>
                            Catatan Cepat
                        </h4>
                        <div class="space-y-3">
                            <textarea id="quick-notes" placeholder="Tulis catatan tentang video ini..."
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 resize-none text-sm hover:border-indigo-300"
                                      rows="3" maxlength="200"></textarea>
                            <div class="flex justify-between items-center">
                                <span id="notes-count" class="text-xs text-gray-500">0/200 karakter</span>
                                <button onclick="saveNotes()"
                                        class="bg-gradient-to-r from-indigo-500 to-purple-500 text-white px-4 py-1.5 rounded-lg text-xs font-medium hover:from-indigo-600 hover:to-purple-600 transition-all duration-200 transform hover:scale-105 hover:shadow-lg">
                                    Simpan
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Learning Stats -->
                    <div class="bg-gradient-to-r from-teal-50 to-cyan-50 rounded-xl p-4 border border-teal-200 hover:shadow-md transition-all duration-300">
                        <h4 class="font-semibold text-gray-800 mb-3 flex items-center">
                            <span class="text-lg mr-2">📈</span>
                            Statistik Belajar
                        </h4>
                        <div class="grid grid-cols-2 gap-3 text-center">
                            <div class="bg-white rounded-lg p-3 hover:shadow-sm transition-shadow duration-200">
                                <div class="text-2xl font-bold text-teal-600 animate-pulse" id="videos-watched">0</div>
                                <div class="text-xs text-gray-600">Video Ditonton</div>
                            </div>
                            <div class="bg-white rounded-lg p-3 hover:shadow-sm transition-shadow duration-200">
                                <div class="text-2xl font-bold text-cyan-600 animate-pulse" id="learning-streak">0</div>
                                <div class="text-xs text-gray-600">Hari Berturut</div>
                            </div>
                        </div>
                    </div>

                    <!-- Button Quiz -->
                    <button onclick="saveNotes()"
                            class="w-full bg-gradient-to-r from-indigo-500 to-purple-500 hover:from-indigo-600 hover:to-purple-600 text-white rounded-xl p-4 border border-indigo-200 transition-all duration-200 transform hover:scale-105 hover:shadow-lg">
                        📝 Latihan Quiz
                    </button>
                    </div>
                </div>
                <!-- Enhanced Related Videos -->
            <div class="bg-white rounded-2xl shadow-lg p-6 card-hover">
                <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                    <span class="text-2xl mr-2">🎯</span>
                    Video Terkait
                </h3>
                <div id="related-videos" class="space-y-4">
                    <!-- Enhanced loading state -->
                    <div class="animate-pulse space-y-4">
                        <div class="flex space-x-3">
                            <div class="bg-gray-200 rounded-lg h-16 w-24"></div>
                            <div class="flex-1 space-y-2">
                                <div class="h-3 bg-gray-200 rounded"></div>
                                <div class="h-2 bg-gray-200 rounded w-3/4"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6 mt-8 card-hover">
                <h3 class="text-lg font-semibold mb-4">Kategori Lainnya</h3>
                <div class="space-y-2" id="categories-list">
                    <!-- Categories will be loaded here -->
                </div>
            </div>
            {{-- </div> --}}

        </div>

        <!-- Sidebar -->
        {{-- <div class="lg:col-span-1">
            <!-- Related Videos -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-semibold mb-4">Video Terkait</h3>
                <div class="space-y-4" id="related-videos">
                    <!-- Related videos will be loaded here -->
                </div>
            </div> --}}

            <!-- Categories -->

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let currentVideoId = {{ request()->route('id') ?? 'null' }};

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes bounce {
        0%, 20%, 53%, 80%, 100% {
            transform: translateY(0);
        }
        40%, 43% {
            transform: translateY(-8px);
        }
        70% {
            transform: translateY(-4px);
        }
        90% {
            transform: translateY(-2px);
        }
    }

    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.7;
        }
    }

    .animate-fade-in {
        animation: fadeInUp 0.6s ease-out forwards;
        opacity: 0;
    }

    .animate-bounce-gentle {
        animation: bounce 2s infinite;
    }

    .animate-pulse-slow {
        animation: pulse 2s infinite;
    }

    .feedback-item:hover {
        transform: translateX(4px);
        transition: transform 0.2s ease;
    }

    .loading-dots::after {
        content: '';
        animation: loading-dots 1.5s infinite;
    }

    @keyframes loading-dots {
        0%, 20% { content: '.'; }
        40% { content: '..'; }
        60%, 100% { content: '...'; }
    }
`;
document.head.appendChild(style);

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

            // Load bookmark status after video info is displayed
            if (isAuthenticated()) {
                setTimeout(() => loadBookmarkStatus(), 500);
            }
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

    // Update breadcrumb title
    const breadcrumbTitle = document.getElementById('breadcrumb-title');
    if (breadcrumbTitle) {
        breadcrumbTitle.textContent = video.nama;
    }

    // Update page title
    document.title = `${video.nama} - Skillearn`;

    container.innerHTML = `
        <div class="flex justify-between items-start mb-6">
            <div class="flex-1">
                <h1 class="text-3xl font-bold mb-3 bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent">
                    ${video.nama}
                </h1>
                <div class="flex flex-wrap items-center gap-4 text-gray-600">
                    <span class="bg-gradient-to-r from-blue-100 to-purple-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                        📚 ${video.kategori?.kategori || 'Umum'}
                    </span>
                    </div>
                <div class="flex items-center space-x-4 mt-3 text-sm text-gray-500">
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                        </svg>
                        ${video.jumlah_tayang} views
                    </span>
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                        </svg>
                        ${new Date(video.created_at).toLocaleDateString('id-ID')}
                    </span>
                    <span class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24"><path d="M6.166 16.943l1.4 1.4-4.622 4.657h-2.944l6.166-6.057zm11.768-6.012c2.322-2.322 4.482-.457 6.066-1.931l-8-8c-1.474 1.584.142
                            3.494-2.18 5.817-3.016 3.016-4.861-.625-10.228 4.742l9.6 9.6c5.367-5.367 1.725-7.211 4.742-10.228z"/></svg>
                        ${video.channel}
                    </span>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                ${isAuthenticated() ? `
                <button id="bookmark-btn" onclick="toggleBookmark(${video.vidio_id})"
                        class="bg-gradient-to-r from-blue-500 to-indigo-500 hover:from-blue-600 hover:to-indigo-600 text-white px-6 py-3 rounded-xl font-medium transition-all duration-200 transform hover:scale-105 flex items-center space-x-2">
                    <span id="bookmark-icon">📖</span>
                    <span id="bookmark-text">Bookmark</span>
                </button>
                ` : ''}
                <button onclick="shareVideo(${video.vidio_id}, '${video.nama.replace(/'/g, "\\'")}')"
                        class="bg-gradient-to-r from-green-500 to-emerald-500 hover:from-green-600 hover:to-emerald-600 text-white px-6 py-3 rounded-xl font-medium transition-all duration-200 transform hover:scale-105 flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"/>
                    </svg>
                    <span>Share</span>
                </button>
            </div>
        </div>
        <div class="bg-gradient-to-r from-gray-50 to-blue-50 rounded-xl p-6 border border-blue-100">
            <h3 class="text-lg font-semibold mb-3 flex items-center">
                <span class="text-2xl mr-2">📝</span>
                Deskripsi Video
            </h3>
            <p class="text-gray-700 leading-relaxed">${video.deskripsi || 'Tidak ada deskripsi untuk video ini.'}</p>
        </div>
    `;

    // Track video view
    trackVideoView(video.vidio_id);
}

function displayFeedbacks(feedbacks) {
    const container = document.getElementById('feedbacks-list');
    const feedbackCount = document.getElementById('feedback-count');

    // Update feedback count
    if (feedbackCount) {
        feedbackCount.textContent = `${feedbacks.length} komentar`;
    }

    if (feedbacks.length === 0) {
        container.innerHTML = `
            <div class="text-center py-8">
                <div class="text-6xl mb-4">💬</div>
                <p class="text-gray-500 text-lg mb-2">Belum ada feedback untuk video ini</p>
                <p class="text-gray-400 text-sm">Jadilah yang pertama memberikan feedback!</p>
            </div>
        `;
        return;
    }

    container.innerHTML = feedbacks.map((feedback, index) => `
        <div class="feedback-item border-b border-gray-200 pb-6 mb-6 last:border-b-0 animate-fade-in" style="animation-delay: ${index * 0.1}s">
            <div class="flex justify-between items-start mb-3">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center text-white text-sm font-semibold shadow-lg">
                        ${feedback.user?.nama_lengkap?.charAt(0) || 'U'}
                    </div>
                    <div class="ml-3">
                        <p class="font-semibold text-gray-800">${feedback.user?.nama_lengkap || 'User'}</p>
                        <p class="text-gray-500 text-xs flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                            </svg>
                            ${new Date(feedback.tanggal).toLocaleDateString('id-ID', {
                                year: 'numeric',
                                month: 'long',
                                day: 'numeric',
                                hour: '2-digit',
                                minute: '2-digit'
                            })}
                        </p>
                    </div>
                </div>


                ${isCurrentUser(feedback.users_id) && '{{ auth()->user()->role ?? "" }}' === 'CU' ? `
                <div class="flex space-x-2">
                    <button onclick="editFeedback(${feedback.feedback_id})"
                            class="text-blue-600 hover:text-blue-800 text-sm font-medium px-2 py-1 rounded hover:bg-blue-50 transition-colors">
                        ✏️ Edit
                    </button>
                    <button onclick="deleteFeedback(${feedback.feedback_id})"
                            class="text-red-600 hover:text-red-800 text-sm font-medium px-2 py-1 rounded hover:bg-red-50 transition-colors">
                        🗑️ Hapus
                    </button>
                </div>`
                : (('{{ auth()->user()->role ?? "" }}' !== 'CU') ? `
                <div class="flex space-x-2">
                    <button onclick="replyFeedback(${feedback.feedback_id})"
                            class="text-purple-800 hover:text-purple-900 text-sm font-medium px-6 py-3 rounded hover:bg-purple-50 transition-colors">
                        Balas
                    </button>
                </div>` : '')
            }

            </div>
            <div class="ml-13">
                <p class="text-gray-800 leading-relaxed mb-3 p-4 bg-gray-50 rounded-lg border-l-4 border-blue-500">${feedback.pesan}</p>
                ${feedback.balasan ? `
                <div class="bg-gradient-to-r from-blue-50 to-purple-50 p-4 rounded-lg border border-blue-200 mt-3">
                    <div class="flex items-center mb-2">
                        <div class="w-6 h-6 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center mr-2">
                            <span class="text-white text-xs">A</span>
                        </div>
                        <p class="text-sm font-semibold text-blue-800">Balasan Admin:</p>
                    </div>
                    <p class="text-gray-800 leading-relaxed">${feedback.balasan}</p>
                </div>` : ''}
            </div>
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
        container.innerHTML = `
            <div class="text-center py-6">
                <div class="text-4xl mb-3">🎯</div>
                <p class="text-gray-500 text-sm">Tidak ada video terkait</p>
            </div>
        `;
        return;
    }

    container.innerHTML = videos.map((video, index) => `
        <div class="related-video-item group cursor-pointer animate-fade-in hover:bg-gray-50 rounded-lg p-2 transition-all duration-200"
             style="animation-delay: ${index * 0.1}s"
             onclick="window.location.href='/videos/${video.vidio_id}'">
            <div class="flex space-x-3">
                <div class="relative overflow-hidden rounded-lg">
                    <img src="${video.gambar}"
                         alt="${video.nama}"
                         class="w-24 h-16 object-cover group-hover:scale-110 transition-transform duration-300">
                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-300 flex items-center justify-center">
                        <svg class="w-6 h-6 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
                <div class="flex-1">
                    <h4 class="text-sm font-medium text-gray-800 line-clamp-2 group-hover:text-blue-600 transition-colors">${video.nama}</h4>
                    <div class="flex items-center mt-1 text-xs text-gray-500">
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                        </svg>
                        ${video.jumlah_tayang} views
                    </div>
                    <div class="mt-1">
                        <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-0.5 rounded-full">
                            ${video.kategori?.kategori || 'Umum'}
                        </span>
                    </div>
                </div>
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
        // Try API endpoint first, then fallback to web route
        let response = await fetch('/api/feedbacks', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify(data),
            credentials: 'same-origin'
        });

        // If API fails, try web route
        if (!response.ok && response.status === 401) {
            response = await fetch('/web/feedback', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data),
                credentials: 'same-origin'
            });
        }

        const result = await response.json();

        if (result.success) {
            // Success notification
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: '✅ Berhasil!',
                    text: 'Feedback berhasil dikirim',
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            } else {
                alert('✅ Feedback berhasil dikirim!');
            }
            this.reset();
            loadVideoDetail(); // Reload to show new feedback
        } else {
            // Error notification
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: '❌ Gagal!',
                    text: result.message || 'Gagal mengirim feedback',
                    icon: 'error'
                });
            } else {
                alert('❌ ' + (result.message || 'Gagal mengirim feedback'));
            }
        }
    } catch (error) {
        console.error('Feedback error:', error);
        // Network error notification
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: '🌐 Error Koneksi!',
                text: 'Terjadi kesalahan jaringan. Silakan coba lagi.',
                icon: 'error'
            });
        } else {
            alert('🌐 Terjadi kesalahan jaringan. Silakan coba lagi.');
        }
    }
});

async function toggleBookmark(videoId) {
    if (!isAuthenticated()) {
        showLoginPrompt();
        return;
    }

    try {
        console.log('Toggling bookmark for video:', videoId);

        // Try API endpoint first
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

        console.log('API Response status:', response.status);

        // If API fails with 401, try web route
        if (!response.ok && response.status === 401) {
            console.log('API failed, trying web route...');
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
            console.log('Web route response status:', response.status);
        }

        const result = await response.json();
        console.log('Response result:', result);

        if (result.success) {
            // Bookmark success notification
            if (typeof Swal !== 'undefined') {
                const isAdded = result.action === 'added';
                Swal.fire({
                    title: isAdded ? '📖 Ditambahkan!' : '📚 Dihapus!',
                    text: result.message,
                    icon: 'success',
                    timer: 1500,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            } else {
                alert(result.message);
            }

            // Update bookmark UI
            updateBookmarkUI(result.action === 'added');
        } else {
            console.error('Bookmark operation failed:', result);
            // Error notification
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: '❌ Gagal!',
                    text: result.message || 'Gagal mengelola bookmark',
                    icon: 'error'
                });
            } else {
                alert('❌ ' + (result.message || 'Gagal mengelola bookmark'));
            }
        }
    } catch (error) {
        console.error('Bookmark error:', error);
        // Network error
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: '🌐 Error Koneksi!',
                text: 'Terjadi kesalahan jaringan. Silakan coba lagi.',
                icon: 'error'
            });
        } else {
            alert('🌐 Terjadi kesalahan jaringan. Silakan coba lagi.');
        }
    }
}

function isAuthenticated() {
    // Check if user is authenticated using Laravel's auth check
    return {{ auth()->check() ? 'true' : 'false' }};
}

function isCurrentUser(userId) {
    // Simple check - in real app, you'd compare with current user ID
    return isAuthenticated(); // For demo purposes
}

// Load data when page loads
document.addEventListener('DOMContentLoaded', function() {
    loadVideoDetail();
    loadCategories();
    initializeVideoPlayer();
    setupVideoActions();
    setupProgressTracking();
    loadUserStats();
});

// Initialize video player with better error handling
function initializeVideoPlayer() {
    const playerContainer = document.getElementById('video-player');
    if (playerContainer) {
        // Add loading animation
        playerContainer.innerHTML = `
            <div class="aspect-video flex items-center justify-center bg-gradient-to-br from-gray-900 to-black">
                <div class="text-center text-white">
                    <div class="loading-spinner mx-auto mb-4"></div>
                    <p class="text-lg">🎬 Memuat video...</p>
                    <p class="text-sm opacity-75 mt-2">Mohon tunggu sebentar</p>
                </div>
            </div>
        `;
    }
}

// Setup video actions with enhanced UX
function setupVideoActions() {
    // Show actions content and hide loading
    setTimeout(() => {
        document.getElementById('actions-loading').classList.add('hidden');
        document.getElementById('actions-content').classList.remove('hidden');

        // Setup notes character counter
        setupNotesCounter();

        // Setup rating system
        setupRatingSystem();
    }, 1000);

    // Add bookmark button animation
    const bookmarkButtons = document.querySelectorAll('[onclick*="toggleBookmark"]');
    bookmarkButtons.forEach(button => {
        button.addEventListener('click', function() {
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 150);
        });
    });
}

// Setup progress tracking
function setupProgressTracking() {
    // Simulate video progress (in real app, this would track actualanjl video progress)
    let progress = 0;
    const progressInterval = setInterval(() => {
        if (progress < 100) {
            progress += Math.random() * 5;
            if (progress > 100) progress = 100;

            updateProgressDisplay(progress);
        } else {
            clearInterval(progressInterval);
        }
    }, 10000); // Update every 10 seconds for demo
}

// Update progress display
function updateProgressDisplay(progress) {
    const progressBar = document.getElementById('progress-bar');
    const progressText = document.getElementById('watch-progress');
    const watchTime = document.getElementById('watch-time');

    if (progressBar && progressText) {
        progressBar.style.width = `${progress}%`;
        progressText.textContent = `${Math.round(progress)}%`;

        // Update watch time (simulated)
        const totalMinutes = Math.round(progress * 0.3); // Simulate time
        const minutes = Math.floor(totalMinutes);
        const seconds = Math.round((totalMinutes - minutes) * 60);
        watchTime.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
    }
}

// Load bookmark status
async function loadBookmarkStatus() {
    if (!isAuthenticated() || !currentVideoId) {
        console.log('Not authenticated or no video ID');
        return;
    }

    try {
        console.log('Loading bookmark status for video:', currentVideoId);

        // Try API endpoint first
        let response = await fetch(`/api/bookmarks/check/${currentVideoId}`, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            credentials: 'same-origin'
        });

        console.log('Bookmark check API response status:', response.status);

        // If API fails with 401, try web route
        if (!response.ok && response.status === 401) {
            console.log('API failed, trying web route...');
            response = await fetch(`/web/bookmark/check/${currentVideoId}`, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                credentials: 'same-origin'
            });
            console.log('Web route response status:', response.status);
        }

        if (response.ok) {
            const result = await response.json();
            console.log('Bookmark status result:', result);

            if (result.success) {
                updateBookmarkUI(result.bookmarked);
            }
        } else {
            console.error('Failed to load bookmark status:', response.status, response.statusText);
        }
    } catch (error) {
        console.error('Error checking bookmark status:', error);
    }
}

// Update bookmark UI
function updateBookmarkUI(isBookmarked) {
    const bookmarkBtn = document.getElementById('bookmark-btn');
    const bookmarkIcon = document.getElementById('bookmark-icon');
    const bookmarkText = document.getElementById('bookmark-text');

    if (!bookmarkBtn || !bookmarkIcon || !bookmarkText) {
        console.log('Bookmark UI elements not found');
        return;
    }

    if (isBookmarked) {
        bookmarkBtn.className = bookmarkBtn.className.replace('from-blue-500 to-indigo-500', 'from-green-500 to-emerald-500');
        bookmarkBtn.className = bookmarkBtn.className.replace('hover:from-blue-600 hover:to-indigo-600', 'hover:from-green-600 hover:to-emerald-600');
        bookmarkIcon.textContent = '📚';
        bookmarkText.textContent = 'Tersimpan';
    } else {
        bookmarkBtn.className = bookmarkBtn.className.replace('from-green-500 to-emerald-500', 'from-blue-500 to-indigo-500');
        bookmarkBtn.className = bookmarkBtn.className.replace('hover:from-green-600 hover:to-emerald-600', 'hover:from-blue-600 hover:to-indigo-600');
        bookmarkIcon.textContent = '📖';
        bookmarkText.textContent = 'Bookmark';
    }
}

// Setup notes character counter
function setupNotesCounter() {
    const notesTextarea = document.getElementById('quick-notes');
    const notesCount = document.getElementById('notes-count');

    if (notesTextarea && notesCount) {
        notesTextarea.addEventListener('input', function() {
            const count = this.value.length;
            notesCount.textContent = `${count}/200 karakter`;

            if (count > 200) {
                notesCount.className = notesCount.className.replace('text-gray-500', 'text-red-500');
            } else {
                notesCount.className = notesCount.className.replace('text-red-500', 'text-gray-500');
            }
        });

        // Load saved notes
        loadSavedNotes();
    }
}

// Setup rating system
function setupRatingSystem() {
    const stars = document.querySelectorAll('.star-btn');
    const ratingText = document.getElementById('rating-text');

    stars.forEach((star, index) => {
        star.addEventListener('mouseenter', function() {
            highlightStars(index + 1);
        });

        star.addEventListener('mouseleave', function() {
            highlightStars(0);
        });
    });

    // Load user's rating
    loadUserRating();
}

// Highlight stars
function highlightStars(count) {
    const stars = document.querySelectorAll('.star-btn');
    stars.forEach((star, index) => {
        if (index < count) {
            star.style.opacity = '1';
            star.style.transform = 'scale(1.1)';
        } else {
            star.style.opacity = '0.5';
            star.style.transform = 'scale(1)';
        }
    });
}

// Load user statistics
async function loadUserStats() {
    if (!isAuthenticated()) return;

    try {
        const response = await fetch('/api/customer/stats', {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });

        const result = await response.json();

        if (result.success) {
            updateStatsDisplay(result.stats);
        }
    } catch (error) {
        console.error('Error loading stats:', error);
    }
}

// Update stats display
function updateStatsDisplay(stats) {
    const videosWatched = document.getElementById('videos-watched');
    const learningStreak = document.getElementById('learning-streak');

    if (videosWatched) {
        videosWatched.textContent = stats.videos_watched || 0;
    }

    if (learningStreak) {
        learningStreak.textContent = stats.learning_streak || 0;
    }
}

// Video action functions
function addToWatchLater() {
    if (!isAuthenticated()) {
        showLoginPrompt();
        return;
    }

    // Add to watch later logic
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: '⏰ Ditambahkan!',
            text: 'Video telah ditambahkan ke daftar tonton nanti',
            icon: 'success',
            timer: 2000,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });
    } else {
        alert('⏰ Video telah ditambahkan ke daftar tonton nanti!');
    }
}

function shareToSocial(platform) {
    const url = window.location.href;
    const title = document.querySelector('h1').textContent;

    let shareUrl;
    switch(platform) {
        case 'whatsapp':
            shareUrl = `https://wa.me/?text=${encodeURIComponent(title + ' - ' + url)}`;
            break;
        default:
            shareUrl = url;
    }

    window.open(shareUrl, '_blank');
}

function copyVideoLink() {
    const url = window.location.href;

    navigator.clipboard.writeText(url).then(() => {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: '📋 Link Disalin!',
                text: 'Link video telah disalin ke clipboard',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        } else {
            alert('📋 Link video telah disalin ke clipboard!');
        }
    }).catch(err => {
        console.error('Failed to copy: ', err);
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: '🔗 Link Video',
                text: url,
                icon: 'info'
            });
        } else {
            alert('🔗 Link Video: ' + url);
        }
    });
}

function setRating(rating) {
    if (!isAuthenticated()) {
        showLoginPrompt();
        return;
    }

    const ratingText = document.getElementById('rating-text');
    const ratingTexts = [
        'Klik bintang untuk memberi rating',
        'Sangat buruk 😞',
        'Buruk 😕',
        'Biasa saja 😐',
        'Bagus 😊',
        'Sangat bagus! 🤩'
    ];

    highlightStars(rating);
    ratingText.textContent = ratingTexts[rating];

    // Save rating
    saveUserRating(rating);
}

function saveNotes() {
    if (!isAuthenticated()) {
        showLoginPrompt();
        return;
    }

    const notesTextarea = document.getElementById('quick-notes');
    const notes = notesTextarea.value.trim();

    if (notes.length === 0) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: '📝 Catatan Kosong',
                text: 'Silakan tulis catatan terlebih dahulu',
                icon: 'info'
            });
        } else {
            alert('📝 Silakan tulis catatan terlebih dahulu');
        }
        return;
    }

    if (notes.length > 200) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: '📝 Catatan Terlalu Panjang',
                text: 'Catatan maksimal 200 karakter',
                icon: 'warning'
            });
        } else {
            alert('📝 Catatan maksimal 200 karakter');
        }
        return;
    }

    // Save notes to localStorage for demo
    localStorage.setItem(`notes_video_${currentVideoId}`, notes);

    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: '💾 Tersimpan!',
            text: 'Catatan berhasil disimpan',
            icon: 'success',
            timer: 2000,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });
    } else {
        alert('💾 Catatan berhasil disimpan!');
    }
}

// Helper functions
function loadSavedNotes() {
    if (!currentVideoId) return;

    const savedNotes = localStorage.getItem(`notes_video_${currentVideoId}`);
    if (savedNotes) {
        const notesTextarea = document.getElementById('quick-notes');
        if (notesTextarea) {
            notesTextarea.value = savedNotes;
            // Trigger input event to update counter
            notesTextarea.dispatchEvent(new Event('input'));
        }
    }
}

function loadUserRating() {
    if (!currentVideoId) return;

    const savedRating = localStorage.getItem(`rating_video_${currentVideoId}`);
    if (savedRating) {
        setRating(parseInt(savedRating));
    }
}

function saveUserRating(rating) {
    if (!currentVideoId) return;

    localStorage.setItem(`rating_video_${currentVideoId}`, rating);

    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: '⭐ Rating Tersimpan!',
            text: `Anda memberikan ${rating} bintang untuk video ini`,
            icon: 'success',
            timer: 2000,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });
    }
}

function showLoginPrompt() {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: '🔐 Perlu Login',
            text: 'Silakan login untuk menggunakan fitur ini',
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Login',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '/login';
            }
        });
    } else {
        const shouldLogin = confirm('🔐 Silakan login untuk menggunakan fitur ini. Ingin login sekarang?');
        if (shouldLogin) {
            window.location.href = '/login';
        }
    }
}

// Enhanced video view tracking
async function trackVideoView(videoId) {
    try {
        await fetch(`/api/videos/${videoId}/view`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
    } catch (error) {
        console.error('Error tracking video view:', error);
    }
}

// Enhanced feedback deletion with confirmation
async function deleteFeedback(feedbackId) {
    // Confirmation dialog
    const confirmDelete = typeof Swal !== 'undefined'
        ? await Swal.fire({
            title: '⚠️ Konfirmasi Hapus',
            text: 'Apakah Anda yakin ingin menghapus feedback ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        })
        : confirm('⚠️ Apakah Anda yakin ingin menghapus feedback ini?');

    if ((typeof Swal !== 'undefined' && confirmDelete.isConfirmed) || (typeof Swal === 'undefined' && confirmDelete)) {
        try {
            // Try API endpoint first, then fallback to web route
            let response = await fetch(`/api/feedbacks/${feedbackId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                credentials: 'same-origin'
            });

            // If API fails, try web route
            if (!response.ok && response.status === 401) {
                response = await fetch(`/web/feedback/${feedbackId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    credentials: 'same-origin'
                });
            }

            const data = await response.json();

            if (data.success) {
                // Success notification
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: '🗑️ Berhasil!',
                        text: 'Feedback berhasil dihapus',
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false,
                        toast: true,
                        position: 'top-end'
                    });
                } else {
                    alert('🗑️ Feedback berhasil dihapus!');
                }
                loadVideoDetail(); // Reload to update feedback list
            } else {
                // Error notification
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: '❌ Gagal!',
                        text: data.message || 'Gagal menghapus feedback',
                        icon: 'error'
                    });
                } else {
                    alert('❌ ' + (data.message || 'Gagal menghapus feedback'));
                }
            }
        } catch (error) {
            console.error('Delete error:', error);
            // Network error
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: '🌐 Error Koneksi!',
                    text: 'Terjadi kesalahan jaringan. Silakan coba lagi.',
                    icon: 'error'
                });
            } else {
                alert('🌐 Terjadi kesalahan jaringan. Silakan coba lagi.');
            }
        }
    }
}

// Enhanced share functionality
function shareVideo(videoId, videoTitle) {
    const url = window.location.href;
    const text = `Lihat video pembelajaran "${videoTitle}" di Skillearn`;

    if (navigator.share) {
        navigator.share({
            title: videoTitle,
            text: text,
            url: url
        }).catch(err => console.log('Error sharing:', err));
    } else {
        // Fallback: Copy to clipboard
        navigator.clipboard.writeText(url).then(() => {
            // Success notification
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: '📋 Link Disalin!',
                    text: 'Link video telah disalin ke clipboard',
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            } else {
                alert('📋 Link video telah disalin ke clipboard!');
            }
        }).catch(err => {
            console.error('Failed to copy: ', err);
            // Error notification
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: '🔗 Link Video',
                    text: `Link: ${url}`,
                    icon: 'info'
                });
            } else {
                alert('🔗 Link Video: ' + url);
            }
        });
    }
}
</script>
@endpush
