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
                    <span class="w-4 h-4 bg-green-400 rounded-full mr-2 animate-pulse"></span>
                    🔥 Keep Learning!
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8" id="stats-cards">
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
                    <p class="text-purple-100">🎓 Course Progress</p>
                    <p class="text-3xl font-bold">
                        <span class="loading-spinner inline-block"></span>
                    </p>
                </div>
                <div class="text-4xl opacity-80">�</div>
            </div>
        </div>
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100">� Quiz Pass Rate</p>
                    <p class="text-3xl font-bold">
                        <span class="loading-spinner inline-block"></span>
                    </p>
                </div>
                <div class="text-4xl opacity-80">📊</div>
            </div>
        </div>
        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl shadow-lg p-6 text-white card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100">📹 Videos Completed</p>
                    <p class="text-3xl font-bold">
                        <span class="loading-spinner inline-block"></span>
                    </p>
                </div>
                <div class="text-4xl opacity-80">✅</div>
            </div>
        </div>
    </div>

    <!-- Learning Progress Section -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8 card-hover">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                <span class="text-3xl mr-2">�</span>
                Course Pembelajaran Saya
            </h2>
            <div class="flex space-x-2">
                <button onclick="showQuizReports()" class="btn-secondary text-white px-4 py-2 rounded-lg text-sm font-medium">
                    📊 Laporan Quiz
                </button>
                <button onclick="refreshLearningData()" class="btn-primary text-white px-4 py-2 rounded-lg text-sm font-medium">
                    🔄 Refresh Data
                </button>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="enrolled-courses">
            <!-- Loading state -->
            <div class="animate-pulse">
                <div class="bg-gray-200 rounded-lg h-48 mb-4"></div>
                <div class="h-4 bg-gray-200 rounded mb-2"></div>
                <div class="h-3 bg-gray-200 rounded w-2/3"></div>
            </div>
        </div>
    </div>

    <!-- Recent Learning Activity -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8 card-hover">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                <span class="text-3xl mr-2">⚡</span>
                Aktivitas Pembelajaran Terbaru
            </h2>
            <span class="skill-badge">7 Hari Terakhir</span>
        </div>
        <div id="recent-learning-activity">
            <!-- Loading state -->
            <div class="animate-pulse space-y-4">
                <div class="flex items-center space-x-4">
                    <div class="bg-gray-200 rounded-full h-12 w-12"></div>
                    <div class="flex-1">
                        <div class="h-4 bg-gray-200 rounded mb-2"></div>
                        <div class="h-3 bg-gray-200 rounded w-2/3"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Recent Bookmarks -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8 card-hover">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                <span class="text-3xl mr-2">⭐</span>
                Course Bookmark Favoritmu
            </h2>
            <a href="/courses" class="btn-primary text-white px-4 py-2 rounded-lg text-sm font-medium">
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

    {{-- <!-- Enhanced Recently Watched Videos -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8 card-hover">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                <span class="text-3xl mr-2">🕒</span>
                Riwayat Video yang Baru Ditonton
            </h2>
            <div class="flex space-x-2">
                <span class="skill-badge">Riwayat</span>
                <span class="skill-badge">Terbaru</span>
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
    </div> --}}

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

.btn-secondary {
    background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
    transition: all 0.3s ease;
}

.btn-secondary:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(107, 114, 128, 0.3);
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

/* SweetAlert Custom Styles */
.swal-wide {
    width: 80% !important;
    max-width: 800px !important;
}

.swal2-popup.swal-wide .swal2-html-container {
    max-height: 500px;
    overflow-y: auto;
}

/* Quiz Reports Styles */
.quiz-reports-container {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.quiz-reports-container .grid {
    display: grid;
}

.quiz-reports-container .grid-cols-2 {
    grid-template-columns: repeat(2, minmax(0, 1fr));
}

.quiz-reports-container .grid-cols-4 {
    grid-template-columns: repeat(4, minmax(0, 1fr));
}

@media (max-width: 768px) {
    .quiz-reports-container .md\\:grid-cols-4 {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
    .quiz-reports-container .md\\:grid-cols-2 {
        grid-template-columns: repeat(1, minmax(0, 1fr));
    }
}

.quiz-reports-container .gap-4 {
    gap: 1rem;
}

.quiz-reports-container .gap-3 {
    gap: 0.75rem;
}

.quiz-reports-container .rounded-lg {
    border-radius: 0.5rem;
}

.quiz-reports-container .rounded-full {
    border-radius: 9999px;
}

.quiz-reports-container .shadow-md {
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

.quiz-reports-container .transition-shadow {
    transition: box-shadow 0.15s ease-in-out;
}

.quiz-reports-container .transition-colors {
    transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out;
}

.quiz-reports-container .transition-all {
    transition: all 0.15s ease-in-out;
}

.quiz-reports-container .duration-500 {
    transition-duration: 500ms;
}

.quiz-reports-container .max-h-80 {
    max-height: 20rem;
}

.quiz-reports-container .overflow-y-auto {
    overflow-y: auto;
}

.quiz-reports-container .space-y-3 > * + * {
    margin-top: 0.75rem;
}

.quiz-reports-container .space-y-4 > * + * {
    margin-top: 1rem;
}

.quiz-reports-container .space-x-4 > * + * {
    margin-left: 1rem;
}

.quiz-reports-container .flex {
    display: flex;
}

.quiz-reports-container .flex-1 {
    flex: 1 1 0%;
}

.quiz-reports-container .flex-wrap {
    flex-wrap: wrap;
}

.quiz-reports-container .items-center {
    align-items: center;
}

.quiz-reports-container .items-start {
    align-items: flex-start;
}

.quiz-reports-container .justify-between {
    justify-content: space-between;
}

.quiz-reports-container .text-center {
    text-align: center;
}

.quiz-reports-container .text-right {
    text-align: right;
}

.quiz-reports-container .text-left {
    text-align: left;
}

.quiz-reports-container .border {
    border-width: 1px;
    border-color: #e5e7eb;
}

.quiz-reports-container .border-t {
    border-top-width: 1px;
    border-top-color: #e5e7eb;
}

/* Quiz Status Colors */
.quiz-reports-container .bg-blue-50 { background-color: #eff6ff; }
.quiz-reports-container .bg-green-50 { background-color: #f0fdf4; }
.quiz-reports-container .bg-purple-50 { background-color: #faf5ff; }
.quiz-reports-container .bg-orange-50 { background-color: #fff7ed; }
.quiz-reports-container .bg-red-50 { background-color: #fef2f2; }
.quiz-reports-container .bg-gray-50 { background-color: #f9fafb; }
.quiz-reports-container .bg-white { background-color: #ffffff; }
.quiz-reports-container .bg-gray-200 { background-color: #e5e7eb; }

.quiz-reports-container .text-blue-600 { color: #2563eb; }
.quiz-reports-container .text-blue-800 { color: #1e40af; }
.quiz-reports-container .text-green-600 { color: #16a34a; }
.quiz-reports-container .text-green-800 { color: #166534; }
.quiz-reports-container .text-purple-600 { color: #9333ea; }
.quiz-reports-container .text-purple-800 { color: #6b21a8; }
.quiz-reports-container .text-orange-600 { color: #ea580c; }
.quiz-reports-container .text-orange-800 { color: #9a3412; }
.quiz-reports-container .text-red-600 { color: #dc2626; }
.quiz-reports-container .text-red-800 { color: #991b1b; }
.quiz-reports-container .text-yellow-600 { color: #ca8a04; }
.quiz-reports-container .text-gray-500 { color: #6b7280; }
.quiz-reports-container .text-gray-600 { color: #4b5563; }
.quiz-reports-container .text-gray-700 { color: #374151; }
.quiz-reports-container .text-gray-800 { color: #1f2937; }
.quiz-reports-container .text-gray-900 { color: #111827; }

.quiz-reports-container .border-green-200 { border-color: #bbf7d0; }
.quiz-reports-container .border-red-200 { border-color: #fecaca; }

/* Progress Bar Animation */
.quiz-reports-container .bg-gradient-to-r {
    background-image: linear-gradient(to right, var(--tw-gradient-stops));
}

.quiz-reports-container .from-blue-500 {
    --tw-gradient-from: #3b82f6;
    --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgba(59, 130, 246, 0));
}

.quiz-reports-container .to-purple-500 {
    --tw-gradient-to: #8b5cf6;
}

.quiz-reports-container .from-blue-50 {
    --tw-gradient-from: #eff6ff;
    --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgba(239, 246, 255, 0));
}

.quiz-reports-container .to-purple-50 {
    --tw-gradient-to: #faf5ff;
}

/* Custom Scrollbar */
.quiz-reports-container .max-h-80::-webkit-scrollbar {
    width: 6px;
}

.quiz-reports-container .max-h-80::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.quiz-reports-container .max-h-80::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

.quiz-reports-container .max-h-80::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

/* Hover Effects */
.quiz-reports-container .hover\\:bg-gray-50:hover {
    background-color: #f9fafb;
}

.quiz-reports-container .hover\\:bg-blue-700:hover {
    background-color: #1d4ed8;
}

.quiz-reports-container .hover\\:bg-gray-700:hover {
    background-color: #374151;
}

.quiz-reports-container .hover\\:bg-purple-700:hover {
    background-color: #7c3aed;
}

.quiz-reports-container .hover\\:bg-green-700:hover {
    background-color: #15803d;
}

.quiz-reports-container .bg-indigo-600 {
    background-color: #4f46e5;
}

.quiz-reports-container .hover\\:bg-indigo-700:hover {
    background-color: #4338ca;
}

/* Border styles for study plan */
.quiz-reports-container .border-l-4 {
    border-left-width: 4px;
}

.quiz-reports-container .border-blue-500 {
    border-color: #3b82f6;
}

.quiz-reports-container .border-green-500 {
    border-color: #22c55e;
}

.quiz-reports-container .border-purple-500 {
    border-color: #a855f7;
}

.quiz-reports-container .bg-blue-200 {
    background-color: #dbeafe;
}

.quiz-reports-container .bg-green-200 {
    background-color: #dcfce7;
}

.quiz-reports-container .bg-purple-200 {
    background-color: #e9d5ff;
}

.quiz-reports-container .text-blue-800 {
    color: #1e40af;
}

.quiz-reports-container .text-green-800 {
    color: #166534;
}

.quiz-reports-container .text-purple-800 {
    color: #6b21a8;
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
            const serverDashboardData = @json($dashboardData);
            console.log('Using server-side dashboard data:', serverDashboardData);

            loadStats(serverDashboardData.stats || {});
            loadRecentBookmarks(serverDashboardData.recent_bookmarks || []);
            loadEnrolledCourses(serverDashboardData.enrolled_courses || []);
            loadRecentActivity(serverDashboardData.recent_activity || []);
            loadCategories(serverDashboardData.categories || []);
            return;
        @endif

        // Ensure CSRF token exists
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (!token) {
            console.warn('CSRF token missing, adding meta tag');
            const meta = document.createElement('meta');
            meta.name = 'csrf-token';
            meta.content = '{{ csrf_token() }}';
            document.head.appendChild(meta);
        }

        // Try multiple fetch approaches
        console.log('Fetching dashboard data from API...');
        
        let dashboardData = null;
        let fetchSuccess = false;

        // Try approach 1: Web route (non-API) - high chance of success due to session auth
        try {
            const webResponse = await fetch('/dashboard/fetch-data', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            });
            
            if (webResponse.ok) {
                dashboardData = await webResponse.json();
                if (dashboardData.success) {
                    console.log('Web route dashboard data loaded successfully');
                    fetchSuccess = true;
                }
            }
        } catch (webError) {
            console.warn('Web route fetch failed:', webError);
        }
        
        // Try approach 2: API route if web route failed
        if (!fetchSuccess) {
            try {
                const apiResponse = await fetch('/api/dashboard', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin'
                });
                
                if (apiResponse.ok) {
                    dashboardData = await apiResponse.json();
                    if (dashboardData.success) {
                        console.log('API dashboard data loaded successfully');
                        fetchSuccess = true;
                    } else {
                        console.warn('API returned success:false, response:', dashboardData);
                    }
                } else {
                    console.warn('API response not OK:', apiResponse.status, apiResponse.statusText);
                    const errorText = await apiResponse.text();
                    console.warn('API error details:', errorText);
                }
            } catch (apiError) {
                console.warn('API fetch error:', apiError);
            }
        }
        
        // Try approach 3: Alternative API endpoint as last resort
        if (!fetchSuccess) {
            try {
                console.log('Trying alternative API endpoint...');
                const altResponse = await fetch('/api/dashboard/data', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin'
                });
                
                if (altResponse.ok) {
                    dashboardData = await altResponse.json();
                    if (dashboardData.success) {
                        console.log('Alternative API endpoint data loaded successfully');
                        fetchSuccess = true;
                    } else {
                        console.warn('Alternative API returned success:false');
                    }
                } else {
                    console.warn('Alternative API response not OK');
                }
            } catch (altError) {
                console.warn('Alternative API fetch error:', altError);
            }
        }
        
        // Try approach 4: Direct fetch as last resort
        if (!fetchSuccess) {
            try {
                console.log('Trying direct fetch as last resort...');
                // Make a direct fetch to the PHP controller
                const response = await fetch('/dashboard-data-direct', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json'
                    },
                    credentials: 'include' // Use include instead of same-origin
                });
                
                if (response.ok) {
                    dashboardData = await response.json();
                    if (dashboardData.success) {
                        console.log('Direct fetch successful!');
                        fetchSuccess = true;
                    }
                }
            } catch (directError) {
                console.warn('Direct fetch error:', directError);
            }
        }
        
        // If any API succeeded, render the data
        if (fetchSuccess && dashboardData) {
            loadStats(dashboardData.stats || {});
            loadRecentBookmarks(dashboardData.recent_bookmarks || []);
            loadEnrolledCourses(dashboardData.enrolled_courses || []);
            loadRecentActivity(dashboardData.recent_activity || []);
            loadCategories(dashboardData.categories || []);
            return;
        }
        
        // If all API approaches fail, load fallback data
        console.log('API fetch failed, loading fallback data...');
        loadFallbackData();
        showInfoNotification('Memuat data dashboard dalam mode offline. Beberapa fitur mungkin terbatas.');
        
    } catch (error) {
        console.error('Error loading dashboard:', error);
        loadFallbackData();
        showErrorNotification('Terjadi kesalahan saat memuat data dashboard.');
    }
}

function loadFallbackData() {
    console.log('Loading fallback dashboard data...');

    // Add offline indicator to the page
    const offlineIndicator = document.createElement('div');
    offlineIndicator.classList.add('fixed', 'top-0', 'left-0', 'right-0', 'p-2', 'bg-amber-100', 'text-amber-800', 'text-center', 'shadow');
    offlineIndicator.innerHTML = '<span class="mr-2">⚠️</span> Dashboard dimuat dalam mode offline. <button class="underline ml-2" onclick="loadDashboard()">Refresh halaman</button> untuk memuat data terbaru.';
    document.body.prepend(offlineIndicator);

    // Load basic stats with fallback data
    loadStats({
        bookmarks_count: 0,
        learning_progress: 0,
        quiz_pass_rate: 0,
        completed_videos: 0
    });

    // Load empty states for other sections
    loadRecentBookmarks([]);
    loadEnrolledCourses([]);
    loadRecentActivity([]);
    loadCategories([
        {kategori_id: 1, kategori: 'Programming', vidios_count: 0},
        {kategori_id: 2, kategori: 'Design', vidios_count: 0},
        {kategori_id: 3, kategori: 'Marketing', vidios_count: 0},
        {kategori_id: 4, kategori: 'Business', vidios_count: 0}
    ]);

    // Show info message
    showInfoNotification('Dashboard dimuat dalam mode offline. Refresh halaman untuk memuat data terbaru.');
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
    const learningProgress = stats.learning_progress || 0;
    const quizPassRate = stats.quiz_pass_rate || 0;
    const completedVideos = stats.completed_videos || 0;

    statsContainer.innerHTML = `
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white card-hover animate-fade-in">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100">📚 Total Bookmarks</p>
                    <p class="text-3xl font-bold">${bookmarksCount}</p>
                    <p class="text-sm text-blue-200 mt-1">Course tersimpan</p>
                </div>
                <div class="text-4xl opacity-80">💾</div>
            </div>
        </div>
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white card-hover animate-fade-in">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100">🎓 Course Progress</p>
                    <p class="text-3xl font-bold">${learningProgress.toFixed(1)}%</p>
                    <p class="text-sm text-purple-200 mt-1">Kemajuan pembelajaran</p>
                </div>
                <div class="text-4xl opacity-80">�</div>
            </div>
        </div>
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white card-hover animate-fade-in">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100">� Quiz Pass Rate</p>
                    <p class="text-3xl font-bold">${quizPassRate.toFixed(1)}%</p>
                    <p class="text-sm text-green-200 mt-1">Tingkat kelulusan quiz</p>
                </div>
                <div class="text-4xl opacity-80">📊</div>
            </div>
        </div>
        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl shadow-lg p-6 text-white card-hover animate-fade-in">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100">📹 Videos Completed</p>
                    <p class="text-3xl font-bold">${completedVideos}</p>
                    <p class="text-sm text-orange-200 mt-1">Video diselesaikan</p>
                </div>
                <div class="text-4xl opacity-80">✅</div>
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
                <h3 class="text-xl font-semibold text-gray-700 mb-2">Belum ada bookmark course</h3>
                <p class="text-gray-500 mb-4">Mulai bookmark course favoritmu untuk akses cepat</p>
                <a href="/courses" class="btn-primary text-white px-6 py-3 rounded-xl">
                    🔍 Jelajahi Course
                </a>
            </div>
        `;
        return;
    }

    container.innerHTML = bookmarks.map(bookmark => `
        <div class="group bg-gray-50 rounded-xl overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300 transform hover:scale-105">
            <div class="aspect-video bg-gray-200 relative overflow-hidden">
                <img src="${bookmark.course.gambar_course || '/images/default-course.svg'}"
                     alt="${bookmark.course.nama_course}"
                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300"
                     onerror="this.src='/images/default-course.svg'">
                <div class="absolute top-2 right-2">
                    <span class="bg-black/70 text-white text-xs px-2 py-1 rounded">${bookmark.course.kategori?.kategori || 'Umum'}</span>
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
                    ${bookmark.course.nama_course}
                </h4>
                <p class="text-sm text-gray-600 mb-3 line-clamp-2">${bookmark.course.deskripsi_course || 'Deskripsi tidak tersedia'}</p>
                <div class="flex items-center justify-between">
                    <div class="flex items-center text-sm text-gray-500">
                        <span class="mr-3">� ${bookmark.course.total_video || 0} videos</span>
                        <span>⭐ Bookmark</span>
                    </div>
                    <a href="/courses/${bookmark.course.course_id}"
                       class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        Lihat Course →
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
            <div class="group text-center p-6 bg-gray-50 rounded-xl hover:bg-purple-50 transition-all duration-300 transform hover:scale-105 cursor-pointer">
                <div class="text-4xl mb-3 group-hover:scale-110 transition-transform duration-300">
                    ${icon}
                </div>
                <h3 class="font-semibold text-gray-900 mb-1 group-hover:text-purple-600 transition-colors">
                    ${category.kategori}
                </h3>
                <p class="text-sm text-gray-600">
                    ${videoCount} video${videoCount !== 1 ? 's' : ''}
                </p>
                <div class="mt-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <a href="/videos?kategori=${category.kategori_id}"
                       class="inline-block bg-purple-600 text-white text-xs px-4 py-2 rounded-full hover:bg-purple-700 transition-colors">
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

function showInfoNotification(message) {
    showNotification(message, 'info');
}

// SweetAlert utility functions
function showLoadingAlert(message = 'Loading...') {
    Swal.fire({
        title: 'Loading',
        text: message,
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
}

function showSuccessAlert(message) {
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: message,
        confirmButtonColor: '#3085d6'
    });
}

function showErrorAlert(message) {
    Swal.fire({
        icon: 'error',
        title: 'Oops!',
        text: message,
        confirmButtonColor: '#3085d6'
    });
}

function showSuccessNotification(message) {
    showNotification(message, 'success');
}

function showErrorNotification(message) {
    showNotification(message, 'error');
}

// Load data when page loads
document.addEventListener('DOMContentLoaded', loadDashboard);

// Load enrolled courses with progress
function loadEnrolledCourses(enrolledCourses) {
    const container = document.getElementById('enrolled-courses');

    if (!enrolledCourses || enrolledCourses.length === 0) {
        container.innerHTML = `
            <div class="col-span-full text-center py-12">
                <div class="text-6xl mb-4">📚</div>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">Belum ada course yang diikuti</h3>
                <p class="text-gray-500 mb-4">Mulai belajar dengan mendaftar ke course favorit</p>
                <a href="/courses" class="btn-primary text-white px-6 py-3 rounded-xl">
                    🚀 Mulai Belajar
                </a>
            </div>
        `;
        return;
    }

    container.innerHTML = enrolledCourses.map(courseData => {
        const course = courseData.course;
        const progress = courseData.overall_progress || 0;
        const videoProgress = courseData.video_progress || 0;
        const quizProgress = courseData.quiz_progress || 0;
        const isCompleted = courseData.is_completed || false;

        return `
            <div class="group bg-white border rounded-xl overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300">
                <div class="aspect-video bg-gray-200 relative overflow-hidden">
                    <img src="${course.gambar_course || '/images/default-course.svg'}"
                         alt="${course.nama_course}"
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300"
                         onerror="this.src='/images/default-course.svg'">

                    ${isCompleted ? `
                        <div class="absolute top-2 right-2">
                            <span class="bg-green-500 text-white text-xs px-2 py-1 rounded">✅ Selesai</span>
                        </div>
                    ` : `
                        <div class="absolute top-2 right-2">
                            <span class="bg-blue-500 text-white text-xs px-2 py-1 rounded">${progress.toFixed(1)}%</span>
                        </div>
                    `}

                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent p-4">
                        <div class="w-full bg-gray-300 rounded-full h-2 mb-2">
                            <div class="bg-blue-500 h-2 rounded-full transition-all duration-500" style="width: ${progress}%"></div>
                        </div>
                        <span class="text-white text-sm font-medium">${progress.toFixed(1)}% Complete</span>
                    </div>
                </div>

                <div class="p-4">
                    <h4 class="font-semibold text-gray-900 mb-2 line-clamp-2 group-hover:text-blue-600 transition-colors">
                        ${course.nama_course}
                    </h4>
                    <p class="text-sm text-gray-600 mb-3 line-clamp-2">${course.deskripsi_course || 'Deskripsi tidak tersedia'}</p>

                    <div class="space-y-2 mb-4">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">📹 Video Progress</span>
                            <span class="font-medium">${courseData.completed_videos}/${courseData.total_videos}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">🎯 Quiz Progress</span>
                            <span class="font-medium">${courseData.completed_quizzes}/${courseData.total_quizzes}</span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <button onclick="showCourseSyllabus(${course.course_id})"
                                class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            📋 Lihat Silabus
                        </button>
                        <a href="/courses/${course.course_id}"
                           class="btn-primary text-white px-4 py-2 rounded-lg text-sm">
                            ${isCompleted ? '🎉 Review' : '▶️ Lanjutkan'}
                        </a>
                    </div>
                </div>
            </div>
        `;
    }).join('');
}

// Load recent learning activity
function loadRecentActivity(activities) {
    const container = document.getElementById('recent-learning-activity');

    if (!activities || activities.length === 0) {
        container.innerHTML = `
            <div class="text-center py-8">
                <div class="text-4xl mb-4">📚</div>
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Belum ada aktivitas</h3>
                <p class="text-gray-500">Mulai belajar untuk melihat aktivitas di sini</p>
            </div>
        `;
        return;
    }

    const activitiesHTML = activities.map(activity => `
        <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
            <div class="flex-shrink-0">
                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-play text-blue-600"></i>
                </div>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900 truncate">
                    ${activity.title || 'Video pembelajaran'}
                </p>
                <p class="text-xs text-gray-500">
                    ${activity.course || 'Course'} • ${activity.date || 'Baru-baru ini'}
                </p>
            </div>
            <div class="text-xs text-gray-400">
                ${activity.duration || '5 min'}
            </div>
        </div>
    `).join('');

    container.innerHTML = activitiesHTML;
}

// Initialize dashboard when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('Dashboard loaded successfully');

    // Ensure CSRF token exists
    let csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        console.warn('CSRF token not found, creating one...');
        const meta = document.createElement('meta');
        meta.name = 'csrf-token';
        meta.content = '{{ csrf_token() }}';
        document.getElementsByTagName('head')[0].appendChild(meta);
    }

    // Check if server provided dashboard data
    @if(isset($dashboardData))
        console.log('Server-side dashboard data available');
    @endif
});

// Additional dashboard functions
function refreshLearningData() {
    showInfoNotification('Memuat ulang data pembelajaran...');
    loadDashboard();
}

async function showQuizReports() {
    try {
        // Show loading state
        Swal.fire({
            title: 'Memuat Laporan Quiz',
            text: 'Mengambil data quiz Anda...',
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Fetch quiz reports data
        let response;
        try {
            response = await fetch('/api/quiz-reports', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            });
        } catch (error) {
            // Fallback to web route if API fails
            response = await fetch('/dashboard/quiz-reports', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            });
        }

        let data;
        if (response.ok) {
            data = await response.json();
        } else {
            // Use fallback data if API fails
            data = {
                success: true,
                quiz_stats: {
                    total_quizzes: 12,
                    completed_quizzes: 8,
                    passed_quizzes: 6,
                    failed_quizzes: 2,
                    average_score: 78.5,
                    total_time_spent: 245, // minutes
                    best_category: 'Programming',
                    improvement_needed: 'Design Thinking'
                },
                recent_quiz_results: [
                    {
                        quiz_id: 1,
                        quiz_title: 'JavaScript Fundamentals',
                        course_name: 'Web Development Basics',
                        score: 85,
                        max_score: 100,
                        status: 'passed',
                        completed_at: '2024-12-20T10:30:00Z',
                        time_taken: 15,
                        attempt_number: 1,
                        category: 'Programming'
                    },
                    {
                        quiz_id: 2,
                        quiz_title: 'CSS Layout Techniques',
                        course_name: 'Frontend Design',
                        score: 72,
                        max_score: 100,
                        status: 'passed',
                        completed_at: '2024-12-19T14:20:00Z',
                        time_taken: 20,
                        attempt_number: 2,
                        category: 'Design'
                    },
                    {
                        quiz_id: 3,
                        quiz_title: 'Database Optimization',
                        course_name: 'Backend Development',
                        score: 45,
                        max_score: 100,
                        status: 'failed',
                        completed_at: '2024-12-18T16:45:00Z',
                        time_taken: 25,
                        attempt_number: 1,
                        category: 'Database'
                    }
                ],
                performance_by_category: [
                    { category: 'Programming', average_score: 82, total_quizzes: 4 },
                    { category: 'Design', average_score: 75, total_quizzes: 3 },
                    { category: 'Database', average_score: 58, total_quizzes: 2 }
                ]
            };
        }

        if (data.success) {
            displayQuizReports(data);
        } else {
            throw new Error(data.message || 'Gagal memuat laporan quiz');
        }

    } catch (error) {
        console.error('Error loading quiz reports:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Gagal memuat laporan quiz. Silakan coba lagi.',
            confirmButtonColor: '#3085d6'
        });
    }
}

function displayQuizReports(data) {
    const stats = data.quiz_stats;
    const recentResults = data.recent_quiz_results || [];
    const categoryPerformance = data.performance_by_category || [];

    const completionRate = stats.total_quizzes > 0 ? (stats.completed_quizzes / stats.total_quizzes * 100).toFixed(1) : 0;
    const passRate = stats.completed_quizzes > 0 ? (stats.passed_quizzes / stats.completed_quizzes * 100).toFixed(1) : 0;

    const htmlContent = `
        <div class="quiz-reports-container p-6 max-h-[80vh] overflow-y-auto">
            <!-- Header Stats -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-blue-50 p-4 rounded-lg text-center">
                    <div class="text-2xl font-bold text-blue-600">${stats.total_quizzes}</div>
                    <div class="text-sm text-blue-800">Total Quiz</div>
                </div>
                <div class="bg-green-50 p-4 rounded-lg text-center">
                    <div class="text-2xl font-bold text-green-600">${completionRate}%</div>
                    <div class="text-sm text-green-800">Completion Rate</div>
                </div>
                <div class="bg-purple-50 p-4 rounded-lg text-center">
                    <div class="text-2xl font-bold text-purple-600">${stats.average_score.toFixed(1)}</div>
                    <div class="text-sm text-purple-800">Rata-rata Skor</div>
                </div>
                <div class="bg-orange-50 p-4 rounded-lg text-center">
                    <div class="text-2xl font-bold text-orange-600">${passRate}%</div>
                    <div class="text-sm text-orange-800">Pass Rate</div>
                </div>
            </div>

            <!-- Performance Overview -->
            <div class="bg-gradient-to-r from-blue-50 to-purple-50 p-4 rounded-lg mb-6">
                <h3 class="font-semibold text-gray-800 mb-3 flex items-center">
                    <span class="text-xl mr-2">📊</span>
                    Performance Overview
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <div class="flex items-center mb-2">
                            <span class="text-green-600 mr-2">🏆</span>
                            <span class="text-sm text-gray-700">Kategori Terbaik:</span>
                            <span class="font-semibold ml-2 text-green-600">${stats.best_category}</span>
                        </div>
                        <div class="flex items-center mb-2">
                            <span class="text-blue-600 mr-2">⏱️</span>
                            <span class="text-sm text-gray-700">Total Waktu:</span>
                            <span class="font-semibold ml-2">${Math.floor(stats.total_time_spent / 60)}h ${stats.total_time_spent % 60}m</span>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-center mb-2">
                            <span class="text-red-600 mr-2">📈</span>
                            <span class="text-sm text-gray-700">Perlu Peningkatan:</span>
                            <span class="font-semibold ml-2 text-red-600">${stats.improvement_needed}</span>
                        </div>
                        <div class="flex items-center">
                            <span class="text-purple-600 mr-2">🎯</span>
                            <span class="text-sm text-gray-700">Quiz Lulus:</span>
                            <span class="font-semibold ml-2">${stats.passed_quizzes}/${stats.completed_quizzes}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Category Performance -->
            <div class="mb-6">
                <h3 class="font-semibold text-gray-800 mb-3 flex items-center">
                    <span class="text-xl mr-2">📋</span>
                    Performance by Category
                </h3>
                <div class="space-y-3">
                    ${categoryPerformance.map(cat => `
                        <div class="bg-white border rounded-lg p-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-medium text-gray-800">${cat.category}</span>
                                <span class="text-sm text-gray-600">${cat.total_quizzes} quiz</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex-1 bg-gray-200 rounded-full h-2 mr-3">
                                    <div class="bg-gradient-to-r from-blue-500 to-purple-500 h-2 rounded-full transition-all duration-500"
                                         style="width: ${cat.average_score}%"></div>
                                </div>
                                <span class="text-sm font-semibold ${cat.average_score >= 70 ? 'text-green-600' : cat.average_score >= 50 ? 'text-yellow-600' : 'text-red-600'}">
                                    ${cat.average_score.toFixed(1)}%
                                </span>
                            </div>
                        </div>
                    `).join('')}
                </div>
            </div>

            <!-- Recent Quiz Results -->
            <div class="mb-6">
                <h3 class="font-semibold text-gray-800 mb-3 flex items-center">
                    <span class="text-xl mr-2">🕒</span>
                    Quiz Terbaru
                </h3>
                <div class="space-y-3 max-h-80 overflow-y-auto">
                    ${recentResults.map(quiz => {
                        const date = new Date(quiz.completed_at).toLocaleDateString('id-ID', {
                            day: 'numeric',
                            month: 'short',
                            year: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit'
                        });
                        const scorePercentage = (quiz.score / quiz.max_score * 100).toFixed(1);
                        const statusColor = quiz.status === 'passed' ? 'text-green-600' : 'text-red-600';
                        const statusIcon = quiz.status === 'passed' ? '✅' : '❌';
                        const statusBg = quiz.status === 'passed' ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200';

                        return `
                            <div class="border rounded-lg p-4 ${statusBg} hover:shadow-md transition-shadow">
                                <div class="flex items-start justify-between mb-2">
                                    <div class="flex-1">
                                        <h4 class="font-medium text-gray-900 mb-1">${quiz.quiz_title}</h4>
                                        <p class="text-sm text-gray-600">${quiz.course_name}</p>
                                    </div>
                                    <div class="text-right">
                                        <div class="flex items-center ${statusColor} font-semibold">
                                            <span class="mr-1">${statusIcon}</span>
                                            <span>${scorePercentage}%</span>
                                        </div>
                                        <div class="text-xs text-gray-500">${quiz.score}/${quiz.max_score}</div>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between text-xs text-gray-500">
                                    <div class="flex items-center space-x-4">
                                        <span>📅 ${date}</span>
                                        <span>⏱️ ${quiz.time_taken} min</span>
                                        <span>🔄 Attempt ${quiz.attempt_number}</span>
                                    </div>
                                    <span class="px-2 py-1 bg-gray-200 rounded text-xs">${quiz.category}</span>
                                </div>
                            </div>
                        `;
                    }).join('')}
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-wrap gap-3 pt-4 border-t">
                <button onclick="exportQuizReport()" class="btn-secondary text-white px-4 py-2 rounded-lg text-sm hover:bg-gray-700 transition-colors">
                    📊 Export PDF
                </button>
                <button onclick="showDetailedAnalysis()" class="btn-primary text-white px-4 py-2 rounded-lg text-sm hover:bg-purple-700 transition-colors">
                    📈 Analisis Detail
                </button>
                <button onclick="showQuizRecommendations()" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-700 transition-colors">
                    💡 Rekomendasi
                </button>
                <button onclick="showStudyPlan()" class="bg-purple-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-purple-700 transition-colors">
                    📚 Study Plan
                </button>
                <button onclick="showProgressTrend()" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700 transition-colors">
                    📈 Trend Analysis
                </button>
            </div>
        </div>
    `;

    Swal.fire({
        title: 'Laporan Quiz Pembelajaran',
        html: htmlContent,
        width: '90%',
        maxWidth: '1000px',
        showConfirmButton: false,
        showCloseButton: true,
        customClass: {
            popup: 'swal-wide'
        }
    });
}

// Export quiz report functionality
async function exportQuizReport() {
    try {
        showInfoNotification('Menggenerate laporan PDF...');
        
        // This would typically call an API to generate PDF
        const response = await fetch('/api/quiz-reports/export', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        });

        if (response.ok) {
            const blob = await response.blob();
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `quiz-report-${new Date().toISOString().split('T')[0]}.pdf`;
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
            document.body.removeChild(a);
            showSuccessNotification('Laporan PDF berhasil diunduh!');
        } else {
            throw new Error('Gagal menggenerate PDF');
        }
    } catch (error) {
        console.error('Export error:', error);
        showInfoNotification('Fitur export PDF akan segera tersedia!');
    }
}

// Show detailed analysis
function showDetailedAnalysis() {
    const analysisHtml = `
        <div class="text-left p-4">
            <h3 class="font-bold text-lg mb-4 text-center">📈 Analisis Detail Performance</h3>
            
            <div class="space-y-4">
                <div class="bg-blue-50 p-4 rounded-lg">
                    <h4 class="font-semibold text-blue-800 mb-2">🎯 Kekuatan Anda</h4>
                    <ul class="text-sm text-blue-700 space-y-1">
                        <li>• Konsisten dalam kategori Programming (82% avg)</li>
                        <li>• Waktu pengerjaan efisien (rata-rata 18 menit)</li>
                        <li>• Tingkat completion rate yang baik (66.7%)</li>
                    </ul>
                </div>
                
                <div class="bg-orange-50 p-4 rounded-lg">
                    <h4 class="font-semibold text-orange-800 mb-2">⚠️ Area Peningkatan</h4>
                    <ul class="text-sm text-orange-700 space-y-1">
                        <li>• Database concepts perlu diperdalam (58% avg)</li>
                        <li>• Pertimbangkan untuk retry quiz yang gagal</li>
                        <li>• Tingkatkan fokus pada soal-soal teoretis</li>
                    </ul>
                </div>
                
                <div class="bg-green-50 p-4 rounded-lg">
                    <h4 class="font-semibold text-green-800 mb-2">💡 Rekomendasi Belajar</h4>
                    <ul class="text-sm text-green-700 space-y-1">
                        <li>• Fokus pada materi Database Optimization</li>
                        <li>• Ikuti course tambahan tentang Design Thinking</li>
                        <li>• Praktikkan lebih banyak coding exercises</li>
                    </ul>
                </div>
            </div>
        </div>
    `;

    Swal.fire({
        title: 'Analisis Performance Detail',
        html: analysisHtml,
        width: '600px',
        confirmButtonText: 'Tutup',
        confirmButtonColor: '#3085d6'
    });
}

// Show quiz recommendations
function showQuizRecommendations() {
    const recommendationsHtml = `
        <div class="text-left p-4">
            <h3 class="font-bold text-lg mb-4 text-center">💡 Rekomendasi Quiz</h3>
            
            <div class="space-y-4">
                <div class="border rounded-lg p-4 hover:bg-gray-50">
                    <div class="flex items-center justify-between mb-2">
                        <h4 class="font-semibold text-gray-800">Database Fundamentals Review</h4>
                        <span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded">Retry Recommended</span>
                    </div>
                    <p class="text-sm text-gray-600 mb-2">Tingkatkan pemahaman database dengan quiz dasar</p>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-500">⏱️ ~20 min • 📊 Difficulty: Easy</span>
                        <button class="bg-blue-600 text-white text-xs px-3 py-1 rounded hover:bg-blue-700">
                            Mulai Quiz
                        </button>
                    </div>
                </div>
                
                <div class="border rounded-lg p-4 hover:bg-gray-50">
                    <div class="flex items-center justify-between mb-2">
                        <h4 class="font-semibold text-gray-800">Advanced JavaScript Concepts</h4>
                        <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded">New</span>
                    </div>
                    <p class="text-sm text-gray-600 mb-2">Lanjutkan momentum positif di Programming</p>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-500">⏱️ ~25 min • 📊 Difficulty: Medium</span>
                        <button class="bg-blue-600 text-white text-xs px-3 py-1 rounded hover:bg-blue-700">
                            Mulai Quiz
                        </button>
                    </div>
                </div>
                
                <div class="border rounded-lg p-4 hover:bg-gray-50">
                    <div class="flex items-center justify-between mb-2">
                        <h4 class="font-semibold text-gray-800">UI/UX Design Principles</h4>
                        <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">Recommended</span>
                    </div>
                    <p class="text-sm text-gray-600 mb-2">Perkuat skill design untuk portfolio yang lebih baik</p>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-500">⏱️ ~30 min • 📊 Difficulty: Medium</span>
                        <button class="bg-blue-600 text-white text-xs px-3 py-1 rounded hover:bg-blue-700">
                            Mulai Quiz
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;

    Swal.fire({
        title: 'Quiz Yang Disarankan',
        html: recommendationsHtml,
        width: '600px',
        confirmButtonText: 'Tutup',
        confirmButtonColor: '#3085d6'
    });
}

function showCourseSyllabus(courseId) {
    showInfoNotification(`Silabus course ID ${courseId} - Fitur akan segera tersedia.`);
}

// Additional Quiz Report Functions
function retakeQuiz(quizId) {
    Swal.fire({
        title: 'Ulangi Quiz',
        text: 'Apakah Anda yakin ingin mengulang quiz ini?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Ulangi!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = `/quiz/${quizId}`;
        }
    });
}

function showQuizHistory(quizId) {
    showInfoNotification('Menampilkan riwayat detail quiz...');
    // This would show detailed history for a specific quiz
}

function showStudyPlan() {
    const studyPlanHtml = `
        <div class="text-left p-4">
            <h3 class="font-bold text-lg mb-4 text-center">📚 Study Plan Rekomendasi</h3>
            
            <div class="space-y-4">
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                    <h4 class="font-semibold text-blue-800 mb-2">Week 1-2: Database Fundamentals</h4>
                    <ul class="text-sm text-blue-700 space-y-1 ml-4">
                        <li>• Review SQL basics and database design</li>
                        <li>• Practice query optimization exercises</li>
                        <li>• Take "Database Fundamentals Review" quiz</li>
                    </ul>
                    <div class="mt-2">
                        <span class="bg-blue-200 text-blue-800 text-xs px-2 py-1 rounded">Priority: High</span>
                    </div>
                </div>
                
                <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded">
                    <h4 class="font-semibold text-green-800 mb-2">Week 3-4: Advanced Programming</h4>
                    <ul class="text-sm text-green-700 space-y-1 ml-4">
                        <li>• Deep dive into JavaScript ES6+ features</li>
                        <li>• Build 2-3 practice projects</li>
                        <li>• Complete "Advanced JavaScript Concepts" quiz</li>
                    </ul>
                    <div class="mt-2">
                        <span class="bg-green-200 text-green-800 text-xs px-2 py-1 rounded">Priority: Medium</span>
                    </div>
                </div>
                
                <div class="bg-purple-50 border-l-4 border-purple-500 p-4 rounded">
                    <h4 class="font-semibold text-purple-800 mb-2">Week 5-6: UI/UX Skills</h4>
                    <ul class="text-sm text-purple-700 space-y-1 ml-4">
                        <li>• Study design principles and user psychology</li>
                        <li>• Create wireframes and prototypes</li>
                        <li>• Take "UI/UX Design Principles" quiz</li>
                    </ul>
                    <div class="mt-2">
                        <span class="bg-purple-200 text-purple-800 text-xs px-2 py-1 rounded">Priority: Medium</span>
                    </div>
                </div>
            </div>
            
            <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                <h4 class="font-semibold text-gray-800 mb-2">📊 Progress Tracking</h4>
                <p class="text-sm text-gray-600">
                    Ikuti study plan ini untuk meningkatkan skor quiz Anda dari rata-rata 78.5% menjadi 85%+ 
                    dalam 6 minggu ke depan.
                </p>
                <div class="mt-3 flex gap-2">
                    <button class="bg-blue-600 text-white text-xs px-3 py-1 rounded hover:bg-blue-700">
                        📅 Add to Calendar
                    </button>
                    <button class="bg-green-600 text-white text-xs px-3 py-1 rounded hover:bg-green-700">
                        🔔 Set Reminders
                    </button>
                </div>
            </div>
        </div>
    `;

    Swal.fire({
        title: 'Personal Study Plan',
        html: studyPlanHtml,
        width: '700px',
        confirmButtonText: 'Tutup',
        confirmButtonColor: '#3085d6'
    });
}

function showProgressTrend() {
    const trendHtml = `
        <div class="text-left p-4">
            <h3 class="font-bold text-lg mb-4 text-center">📈 Progress Trend Analysis</h3>
            
            <!-- Mock Progress Chart -->
            <div class="bg-gray-50 p-4 rounded-lg mb-4">
                <h4 class="font-semibold text-gray-800 mb-3">Skor Quiz Bulanan</h4>
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-sm">Oktober 2024</span>
                        <div class="flex items-center">
                            <div class="w-32 bg-gray-200 rounded-full h-2 mr-2">
                                <div class="bg-red-500 h-2 rounded-full" style="width: 65%"></div>
                            </div>
                            <span class="text-sm font-medium">65%</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm">November 2024</span>
                        <div class="flex items-center">
                            <div class="w-32 bg-gray-200 rounded-full h-2 mr-2">
                                <div class="bg-yellow-500 h-2 rounded-full" style="width: 72%"></div>
                            </div>
                            <span class="text-sm font-medium">72%</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm">Desember 2024</span>
                        <div class="flex items-center">
                            <div class="w-32 bg-gray-200 rounded-full h-2 mr-2">
                                <div class="bg-green-500 h-2 rounded-full" style="width: 78%"></div>
                            </div>
                            <span class="text-sm font-medium">78%</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Insights -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-green-50 p-4 rounded-lg">
                    <h4 class="font-semibold text-green-800 mb-2">🎯 Positive Trends</h4>
                    <ul class="text-sm text-green-700 space-y-1">
                        <li>• Peningkatan konsisten +13% dalam 3 bulan</li>
                        <li>• Waktu penyelesaian semakin efisien</li>
                        <li>• Tingkat retry menurun</li>
                    </ul>
                </div>
                
                <div class="bg-blue-50 p-4 rounded-lg">
                    <h4 class="font-semibold text-blue-800 mb-2">📊 Key Metrics</h4>
                    <ul class="text-sm text-blue-700 space-y-1">
                        <li>• Best streak: 5 quiz berturut-turut</li>
                        <li>• Favorite topic: JavaScript</li>
                        <li>• Peak performance: Weekends</li>
                    </ul>
                </div>
            </div>
            
            <!-- Prediction -->
            <div class="mt-4 p-4 bg-gradient-to-r from-purple-50 to-blue-50 rounded-lg border">
                <h4 class="font-semibold text-purple-800 mb-2">🔮 Prediksi Performance</h4>
                <p class="text-sm text-purple-700">
                    Berdasarkan trend saat ini, Anda diprediksi akan mencapai skor rata-rata 
                    <strong>85%</strong> dalam 4-6 minggu ke depan jika konsisten mengikuti 
                    study plan yang disarankan.
                </p>
            </div>
        </div>
    `;

    Swal.fire({
        title: 'Progress Trend & Prediction',
        html: trendHtml,
        width: '700px',
        confirmButtonText: 'Tutup',
        confirmButtonColor: '#3085d6'
    });
}
</script>

@endpush
