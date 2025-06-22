@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@push('styles')
<style>
.card-hover {
    transition: all 0.3s ease;
}

.card-hover:hover {
    transform: translateY(-4px);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

.stats-card {
    background: linear-gradient(135deg, rgba(255,255,255,0.95), rgba(255,255,255,1));
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.2);
}
</style>
@endpush

@section('content')
<!-- Enhanced Admin Header -->
<div class="mb-8">
    <div class="bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-600 rounded-3xl p-8 text-white shadow-2xl relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 bg-gradient-to-r from-white/10 to-transparent"></div>
        <div class="absolute -top-4 -right-4 w-24 h-24 bg-white/10 rounded-full blur-xl"></div>
        <div class="absolute -bottom-4 -left-4 w-32 h-32 bg-white/5 rounded-full blur-xl"></div>
        
        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h1 class="text-4xl lg:text-5xl font-bold mb-4 flex items-center">
                    <i class="fas fa-crown mr-4 text-yellow-300 animate-pulse"></i>
                    Admin Control Center
                </h1>
                <p class="text-xl text-purple-100 mb-2">
                    Selamat datang, <span class="font-bold text-yellow-200">{{ auth()->user()->nama_lengkap }}</span>!
                    <i class="fas fa-sparkles ml-2 text-yellow-300"></i>
                </p>
                <p class="text-purple-200">Kelola platform SkillLearn dengan mudah</p>
                <p class="text-purple-200 text-sm mt-1">Dashboard lengkap untuk mengelola course, user, dan konten pembelajaran</p>
            </div>
            <div class="mt-6 lg:mt-0">
                <div class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-yellow-400 to-orange-500 text-black rounded-full text-sm font-bold shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105">
                    <i class="fas fa-shield-alt mr-2"></i>
                    Super Admin Access
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
    <!-- Users Card -->
    <div class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition-all duration-300 hover:shadow-2xl">
        <div class="flex items-center justify-between">
            <div>
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mb-4">
                    <i class="fas fa-users text-white text-xl"></i>
                </div>
                <p class="text-blue-100 text-sm font-medium">Total Users</p>
                <p class="text-3xl font-bold text-white" id="total-users">
                    {{ $stats['total_users'] ?? 0 }}
                </p>
                <p class="text-blue-200 text-xs mt-1">Registered members</p>
            </div>
        </div>
    </div>

    <!-- Courses Card -->
    <div class="bg-gradient-to-br from-emerald-500 to-green-600 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition-all duration-300 hover:shadow-2xl">
        <div class="flex items-center justify-between">
            <div>
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mb-4">
                    <i class="fas fa-graduation-cap text-white text-xl"></i>
                </div>
                <p class="text-green-100 text-sm font-medium">Total Courses</p>
                <p class="text-3xl font-bold text-white" id="total-courses">
                    {{ $stats['total_courses'] ?? 0 }}
                </p>
                <p class="text-green-200 text-xs mt-1">Available courses</p>
            </div>
        </div>
    </div>

    <!-- Videos Card -->
    <div class="bg-gradient-to-br from-purple-500 to-violet-600 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition-all duration-300 hover:shadow-2xl">
        <div class="flex items-center justify-between">
            <div>
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mb-4">
                    <i class="fas fa-video text-white text-xl"></i>
                </div>
                <p class="text-purple-100 text-sm font-medium">Total Videos</p>
                <p class="text-3xl font-bold text-white" id="total-videos">
                    {{ $stats['total_videos'] ?? 0 }}
                </p>
                <p class="text-purple-200 text-xs mt-1">Learning content</p>
            </div>
        </div>
    </div>

    <!-- Categories Card -->
    <div class="bg-gradient-to-br from-orange-500 to-red-500 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition-all duration-300 hover:shadow-2xl">
        <div class="flex items-center justify-between">
            <div>
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mb-4">
                    <i class="fas fa-tags text-white text-xl"></i>
                </div>
                <p class="text-orange-100 text-sm font-medium">Categories</p>
                <p class="text-3xl font-bold text-white" id="total-categories">
                    {{ $stats['total_categories'] ?? 0 }}
                </p>
                <p class="text-orange-200 text-xs mt-1">Course categories</p>
            </div>
        </div>
    </div>
</div>

<!-- Enhanced Quick Actions -->
<div class="bg-white rounded-3xl shadow-2xl p-8 mb-8 relative overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 bg-gradient-to-br from-blue-50 to-purple-50"></div>
    
    <div class="relative z-10">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-3 flex items-center justify-center">
                <i class="fas fa-bolt text-yellow-500 mr-3 text-4xl animate-pulse"></i>
                Quick Actions
            </h2>
            <p class="text-gray-600">Aksi cepat untuk mengelola konten platform dengan mudah</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <a href="{{ route('admin.courses.create') }}" 
               class="group bg-gradient-to-br from-blue-500 to-indigo-600 text-white p-8 rounded-2xl hover:from-blue-600 hover:to-indigo-700 text-center transition-all duration-300 transform hover:scale-105 shadow-xl hover:shadow-2xl">
                <div class="text-6xl mb-4 group-hover:animate-bounce">
                    <i class="fas fa-plus-circle"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">Buat Course Baru</h3>
                <p class="text-blue-100 text-sm">Tambah course pembelajaran baru dengan mudah</p>
            </a>
            
            <a href="{{ route('admin.courses.index') }}" 
               class="group bg-gradient-to-br from-emerald-500 to-green-600 text-white p-8 rounded-2xl hover:from-emerald-600 hover:to-green-700 text-center transition-all duration-300 transform hover:scale-105 shadow-xl hover:shadow-2xl">
                <div class="text-6xl mb-4 group-hover:animate-bounce">
                    <i class="fas fa-cog"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">Kelola Courses</h3>
                <p class="text-green-100 text-sm">Manage semua courses & video content</p>
            </a>
            
            <a href="{{ route('admin.feedback.index') }}" 
               class="group bg-gradient-to-br from-purple-500 to-violet-600 text-white p-8 rounded-2xl hover:from-purple-600 hover:to-violet-700 text-center transition-all duration-300 transform hover:scale-105 shadow-xl hover:shadow-2xl">
                <div class="text-6xl mb-4 group-hover:animate-bounce">
                    <i class="fas fa-comments"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">Kelola Feedback</h3>
                <p class="text-purple-100 text-sm">Manage feedback dan review dari user</p>
            </a>
        </div>
    </div>
</div>
        </div>
    </div>

    <!-- Feedbacks Card -->
    <div class="stats-card card-hover rounded-2xl shadow-xl p-6">
        <div class="flex items-center justify-between">
            <div>
                <div class="w-12 h-12 bg-gradient-to-br from-pink-500 to-rose-500 rounded-xl flex items-center justify-center mb-4">
                    <i class="fas fa-comments text-white text-xl"></i>
                </div>
                <p class="text-gray-600 text-sm font-medium">Feedbacks</p>
                <p class="text-3xl font-bold text-gray-900" id="total-feedbacks">
                    {{ $stats['total_feedbacks'] ?? 0 }}
                </p>
                <p class="text-gray-500 text-xs mt-1">User feedback</p>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="bg-white rounded-2xl shadow-xl p-8 mb-8">
    <div class="text-center mb-8">
        <h2 class="text-3xl font-bold text-gray-900 mb-2">Quick Actions</h2>
        <p class="text-gray-600">Aksi cepat untuk mengelola konten platform</p>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <a href="{{ route('admin.courses.create') }}" 
           class="group bg-gradient-to-br from-blue-500 to-blue-600 text-white p-8 rounded-2xl hover:from-blue-600 hover:to-blue-700 text-center transition-all duration-300 transform hover:scale-105 shadow-lg">
            <div class="text-6xl mb-4 group-hover:animate-bounce">
                <i class="fas fa-plus-circle"></i>
            </div>
            <h3 class="text-xl font-semibold mb-2">Buat Course Baru</h3>
            <p class="text-blue-100 text-sm">Tambah course pembelajaran baru</p>
        </a>
        
        <a href="{{ route('admin.courses.index') }}" 
           class="group bg-gradient-to-br from-green-500 to-green-600 text-white p-8 rounded-2xl hover:from-green-600 hover:to-green-700 text-center transition-all duration-300 transform hover:scale-105 shadow-lg">
            <div class="text-6xl mb-4 group-hover:animate-bounce">
                <i class="fas fa-cog"></i>
            </div>
            <h3 class="text-xl font-semibold mb-2">Kelola Courses</h3>
            <p class="text-green-100 text-sm">Manage semua courses & video</p>
        </a>
        
        <a href="{{ route('admin.feedback.index') }}" 
           class="group bg-gradient-to-br from-purple-500 to-purple-600 text-white p-8 rounded-2xl hover:from-purple-600 hover:to-purple-700 text-center transition-all duration-300 transform hover:scale-105 shadow-lg">
            <div class="text-6xl mb-4 group-hover:animate-bounce">
                <i class="fas fa-comments"></i>
            </div>
            <h3 class="text-xl font-semibold mb-2">Kelola Feedback</h3>
            <p class="text-purple-100 text-sm">Manage feedback dari user</p>
        </a>
    </div>
</div>
<!-- Recent Feedbacks -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    <div class="bg-white rounded-2xl shadow-xl p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                <i class="fas fa-comments text-blue-500 mr-3"></i>
                Feedback Terbaru
            </h2>
            <a href="{{ route('admin.feedback.index') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                Lihat Semua →
            </a>
        </div>
        <div class="space-y-4" id="recent-feedbacks">
            @if($recentFeedbacks && $recentFeedbacks->count() > 0)
                @foreach($recentFeedbacks as $feedback)
                <div class="flex items-start space-x-4 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-semibold">
                        {{ strtoupper(substr($feedback->user->name ?? 'U', 0, 1)) }}
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center justify-between mb-1">
                            <h4 class="font-semibold text-gray-900">{{ $feedback->user->name ?? 'Unknown User' }}</h4>
                            <span class="text-xs text-gray-500">{{ $feedback->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-gray-600 text-sm">{{ Str::limit($feedback->feedback_text, 80) }}</p>
                        <div class="flex items-center mt-2">
                            <div class="flex text-yellow-400">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star{{ $i <= $feedback->rating ? '' : '-o' }} text-xs"></i>
                                @endfor
                            </div>
                            <span class="ml-2 text-xs text-gray-500">{{ $feedback->rating }}/5</span>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <div class="text-center py-8">
                    <i class="fas fa-comment-slash text-gray-300 text-4xl mb-4"></i>
                    <p class="text-gray-500">Belum ada feedback terbaru</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Popular Courses -->
    <div class="bg-white rounded-2xl shadow-xl p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                <i class="fas fa-star text-yellow-500 mr-3"></i>
                Course Terpopuler
            </h2>
            <a href="{{ route('admin.courses.index') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                Kelola Semua →
            </a>
        </div>
        <div class="space-y-4" id="popular-courses">
            @if($popularCourses && $popularCourses->count() > 0)
                @foreach($popularCourses as $course)
                <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                    <div class="w-16 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center">
                        @if($course->gambar_course)
                            <img src="{{ Storage::url($course->gambar_course) }}" alt="{{ $course->nama_course }}" class="w-full h-full object-cover rounded-lg">
                        @else
                            <i class="fas fa-graduation-cap text-white text-lg"></i>
                        @endif
                    </div>
                    <div class="flex-1">
                        <h4 class="font-semibold text-gray-900 mb-1">{{ Str::limit($course->nama_course, 40) }}</h4>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-blue-600 bg-blue-50 px-2 py-1 rounded-full">
                                {{ $course->kategori->nama_kategori ?? 'Uncategorized' }}
                            </span>
                            <div class="flex items-center text-sm text-gray-500">
                                <i class="fas fa-users mr-1"></i>
                                {{ $course->enrollments ?? 0 }} siswa
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <div class="text-center py-8">
                    <i class="fas fa-graduation-cap text-gray-300 text-4xl mb-4"></i>
                    <p class="text-gray-500">Belum ada data course</p>
                </div>
            @endif
        </div>
    </div>
</div>
</div>

@endsection

@push('scripts')
<script>
// Simple dashboard initialization
document.addEventListener('DOMContentLoaded', function() {
    // Add smooth animations to stats cards
    const statsCards = document.querySelectorAll('[class*="bg-white"]');
    
    statsCards.forEach((card, index) => {
        setTimeout(() => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'all 0.5s ease';
            
            requestAnimationFrame(() => {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            });
        }, index * 100);
    });
});
</script>
@endpush
