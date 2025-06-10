@extends('layouts.app')

@section('title', 'Admin Dashboard - Skillearn')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Admin Dashboard</h1>
        <p class="text-gray-600">Kelola platform Skillearn</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8" id="stats-cards">
        <!-- Stats will be loaded here -->
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-xl font-semibold mb-4">Quick Actions</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <button onclick="openAddVideoModal()" class="bg-blue-600 text-white p-4 rounded-lg hover:bg-blue-700 text-center">
                <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Video
            </button>
            <button onclick="openAddCategoryModal()" class="bg-green-600 text-white p-4 rounded-lg hover:bg-green-700 text-center">
                <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                </svg>
                Tambah Kategori
            </button>
            <a href="/admin/reports" class="bg-purple-600 text-white p-4 rounded-lg hover:bg-purple-700 text-center block">
                <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                Lihat Reports
            </a>
        </div>
    </div>

    <!-- Recent Feedbacks -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-xl font-semibold mb-4">Feedback Terbaru</h2>
        <div class="space-y-4" id="recent-feedbacks">
            <!-- Recent feedbacks will be loaded here -->
        </div>
    </div>

    <!-- Popular Videos -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold mb-4">Video Terpopuler</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full" id="popular-videos-table">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Video</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Views</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody id="popular-videos-body">
                    <!-- Popular videos will be loaded here -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Video Modal -->
<div id="addVideoModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center">
    <div class="bg-white p-8 rounded-lg max-w-md w-full mx-4">
        <h3 class="text-lg font-semibold mb-4">Tambah Video Baru</h3>
        <form id="addVideoForm" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Nama Video</label>
                <input type="text" name="nama" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                <textarea name="deskripsi" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md"></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">URL Video</label>
                <input type="url" name="url" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">URL Gambar</label>
                <input type="url" name="gambar" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Kategori</label>
                <select name="kategori_kategori_id" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md" id="categorySelect">
                    <!-- Categories will be loaded here -->
                </select>
            </div>
            <div class="flex justify-end space-x-2">
                <button type="button" onclick="closeAddVideoModal()" class="px-4 py-2 text-gray-600 hover:text-gray-800">Batal</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Add Category Modal -->
<div id="addCategoryModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center">
    <div class="bg-white p-8 rounded-lg max-w-md w-full mx-4">
        <h3 class="text-lg font-semibold mb-4">Tambah Kategori Baru</h3>
        <form id="addCategoryForm" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Nama Kategori</label>
                <input type="text" name="kategori" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>
            <div class="flex justify-end space-x-2">
                <button type="button" onclick="closeAddCategoryModal()" class="px-4 py-2 text-gray-600 hover:text-gray-800">Batal</button>
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Load admin dashboard data
async function loadAdminDashboard() {
    try {
        const response = await fetch('/api/admin/dashboard');
        const data = await response.json();

        if (data.success) {
            loadAdminStats(data.stats);
            loadRecentFeedbacks(data.recent_feedbacks);
            loadPopularVideosTable(data.popular_videos);
        }
    } catch (error) {
        console.error('Error loading admin dashboard:', error);
    }
}

// Load categories for dropdown
async function loadCategories() {
    try {
        const response = await fetch('/api/categories');
        const data = await response.json();

        if (data.success) {
            const select = document.getElementById('categorySelect');
            select.innerHTML = data.categories.map(cat =>
                `<option value="${cat.kategori_id}">${cat.kategori}</option>`
            ).join('');
        }
    } catch (error) {
        console.error('Error loading categories:', error);
    }
}

function loadAdminStats(stats) {
    const statsContainer = document.getElementById('stats-cards');
    statsContainer.innerHTML = `
        <div class="bg-blue-50 p-6 rounded-lg">
            <div class="text-center">
                <p class="text-3xl font-bold text-blue-600">${stats.total_users}</p>
                <p class="text-sm text-gray-600">Total Users</p>
            </div>
        </div>
        <div class="bg-green-50 p-6 rounded-lg">
            <div class="text-center">
                <p class="text-3xl font-bold text-green-600">${stats.total_videos}</p>
                <p class="text-sm text-gray-600">Total Videos</p>
            </div>
        </div>
        <div class="bg-yellow-50 p-6 rounded-lg">
            <div class="text-center">
                <p class="text-3xl font-bold text-yellow-600">${stats.total_categories}</p>
                <p class="text-sm text-gray-600">Categories</p>
            </div>
        </div>
        <div class="bg-purple-50 p-6 rounded-lg">
            <div class="text-center">
                <p class="text-3xl font-bold text-purple-600">${stats.total_feedbacks}</p>
                <p class="text-sm text-gray-600">Feedbacks</p>
            </div>
        </div>
        <div class="bg-red-50 p-6 rounded-lg">
            <div class="text-center">
                <p class="text-3xl font-bold text-red-600">${stats.total_bookmarks}</p>
                <p class="text-sm text-gray-600">Bookmarks</p>
            </div>
        </div>
    `;
}

function loadRecentFeedbacks(feedbacks) {
    const container = document.getElementById('recent-feedbacks');
    if (feedbacks.length === 0) {
        container.innerHTML = '<p class="text-gray-500 text-center">Belum ada feedback</p>';
        return;
    }

    container.innerHTML = feedbacks.map(feedback => `
        <div class="border-l-4 border-blue-500 pl-4 py-2">
            <div class="flex justify-between items-start">
                <div>
                    <p class="font-semibold text-sm">${feedback.user?.nama_lengkap || 'Unknown User'}</p>
                    <p class="text-gray-600 text-sm mb-1">${feedback.vidio?.nama || 'Unknown Video'}</p>
                    <p class="text-gray-800">${feedback.pesan}</p>
                </div>
                <span class="text-xs text-gray-500">${new Date(feedback.tanggal).toLocaleDateString('id-ID')}</span>
            </div>
        </div>
    `).join('');
}

function loadPopularVideosTable(videos) {
    const tbody = document.getElementById('popular-videos-body');
    tbody.innerHTML = videos.slice(0, 10).map(video => `
        <tr class="border-b">
            <td class="px-6 py-4">
                <div class="flex items-center">
                    <img src="${video.gambar}" alt="${video.nama}" class="w-12 h-12 object-cover rounded mr-3">
                    <span class="font-medium">${video.nama}</span>
                </div>
            </td>
            <td class="px-6 py-4">${video.kategori?.kategori || 'Umum'}</td>
            <td class="px-6 py-4">${video.jumlah_tayang}</td>
            <td class="px-6 py-4">
                <button onclick="editVideo(${video.vidio_id})" class="text-blue-600 hover:text-blue-800 mr-2">Edit</button>
                <button onclick="deleteVideo(${video.vidio_id})" class="text-red-600 hover:text-red-800">Delete</button>
            </td>
        </tr>
    `).join('');
}

// Modal functions
function openAddVideoModal() {
    loadCategories();
    document.getElementById('addVideoModal').classList.remove('hidden');
    document.getElementById('addVideoModal').classList.add('flex');
}

function closeAddVideoModal() {
    document.getElementById('addVideoModal').classList.add('hidden');
    document.getElementById('addVideoModal').classList.remove('flex');
}

function openAddCategoryModal() {
    document.getElementById('addCategoryModal').classList.remove('hidden');
    document.getElementById('addCategoryModal').classList.add('flex');
}

function closeAddCategoryModal() {
    document.getElementById('addCategoryModal').classList.add('hidden');
    document.getElementById('addCategoryModal').classList.remove('flex');
}

// Form submissions
document.getElementById('addVideoForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const data = Object.fromEntries(formData);

    try {
        const response = await fetch('/api/admin/videos', {
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
                text: result.message,
                icon: 'success'
            }).then(() => {
                closeAddVideoModal();
                loadAdminDashboard();
                this.reset();
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

document.getElementById('addCategoryForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const data = Object.fromEntries(formData);

    try {
        const response = await fetch('/api/admin/categories', {
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
                text: result.message,
                icon: 'success'
            }).then(() => {
                closeAddCategoryModal();
                loadAdminDashboard();
                this.reset();
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

// Video actions
async function deleteVideo(videoId) {
    const result = await Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Video ini akan dihapus permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    });

    if (result.isConfirmed) {
        try {
            const response = await fetch(`/api/admin/videos/${videoId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const data = await response.json();

            if (data.success) {
                Swal.fire('Dihapus!', data.message, 'success');
                loadAdminDashboard();
            } else {
                Swal.fire('Gagal!', data.message, 'error');
            }
        } catch (error) {
            Swal.fire('Error!', 'Terjadi kesalahan sistem', 'error');
        }
    }
}

// Load data when page loads
document.addEventListener('DOMContentLoaded', loadAdminDashboard);
</script>
@endpush
