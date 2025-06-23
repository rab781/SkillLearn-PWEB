@extends('layouts.admin')

@section('title', 'Monitoring Pembelajaran')

@section('content')
<div class="mb-8">
    <h1 class="text-4xl font-bold text-gray-900 mb-2">Monitoring Pembelajaran</h1>
    <p class="text-gray-600">Pantau aktivitas dan progress pembelajaran siswa</p>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-2xl shadow-xl p-6">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-users text-blue-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-2xl font-bold text-gray-900">{{ $totalStudents }}</h3>
                <p class="text-gray-600 text-sm">Total Siswa</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-xl p-6">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-graduation-cap text-green-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-2xl font-bold text-gray-900">{{ $totalCourses }}</h3>
                <p class="text-gray-600 text-sm">Total Course</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-xl p-6">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-play-circle text-yellow-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-2xl font-bold text-gray-900">{{ $activeCourses }}</h3>
                <p class="text-gray-600 text-sm">Course Aktif</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-xl p-6">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-trophy text-purple-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-2xl font-bold text-gray-900">{{ $totalCompletions }}</h3>
                <p class="text-gray-600 text-sm">Course Selesai</p>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Course Statistics -->
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900 flex items-center">
                <i class="fas fa-chart-bar mr-2 text-blue-500"></i>
                Statistik Course
            </h2>
        </div>
        <div class="p-6">
            <div class="space-y-4 max-h-96 overflow-y-auto">
                @forelse($courseStats->take(10) as $stat)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-blue-50 transition-colors">
                    <div class="flex-1">
                        <h4 class="font-semibold text-gray-900">{{ Str::limit($stat['nama_course'], 30) }}</h4>
                        <div class="flex items-center mt-1 space-x-4">
                            <span class="text-sm text-gray-600">
                                <i class="fas fa-users mr-1"></i>{{ $stat['total_students'] }} siswa
                            </span>
                            <span class="text-sm {{ $stat['is_active'] ? 'text-green-600' : 'text-gray-500' }}">
                                <i class="fas fa-{{ $stat['is_active'] ? 'check-circle' : 'pause-circle' }} mr-1"></i>
                                {{ $stat['is_active'] ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-lg font-bold
                            {{ $stat['completion_rate'] >= 70 ? 'text-green-600' : ($stat['completion_rate'] >= 40 ? 'text-yellow-600' : 'text-red-600') }}">
                            {{ $stat['completion_rate'] }}%
                        </div>
                        <div class="text-xs text-gray-500">completion</div>
                    </div>
                    <div class="ml-4">
                        <a href="{{ route('admin.monitoring.course-detail', $stat['course_id']) }}"
                           class="px-3 py-1 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm">
                            Detail
                        </a>
                    </div>
                </div>
                @empty
                <div class="text-center py-8">
                    <i class="fas fa-chart-bar text-4xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500">Belum ada data statistik course</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900 flex items-center">
                <i class="fas fa-history mr-2 text-green-500"></i>
                Aktivitas Terbaru
            </h2>
        </div>
        <div class="p-6">
            <div class="space-y-4 max-h-96 overflow-y-auto">
                @forelse($recentActivities as $activity)
                <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-play text-blue-600 text-xs"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900">
                            {{ $activity->user->nama_lengkap }}
                        </p>
                        <p class="text-xs text-gray-600 mb-1">
                            Menonton: {{ Str::limit($activity->video->nama ?? 'Video tidak ditemukan', 25) }}
                        </p>
                        <p class="text-xs text-gray-500">
                            Course: {{ Str::limit($activity->course->nama_course ?? 'Course tidak ditemukan', 20) }}
                        </p>
                        <p class="text-xs text-gray-400 mt-1">
                            {{ $activity->waktu_ditonton->diffForHumans() }}
                        </p>
                    </div>
                    <div class="text-right">
                        <span class="text-xs text-gray-500">
                            {{ $activity->durasi_tonton ?? 0 }} mnt
                        </span>
                    </div>
                </div>
                @empty
                <div class="text-center py-8">
                    <i class="fas fa-history text-4xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500">Belum ada aktivitas terbaru</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- All Courses Table -->
<div class="mt-8 bg-white rounded-2xl shadow-xl overflow-hidden">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-xl font-bold text-gray-900 flex items-center">
            <i class="fas fa-list mr-2 text-purple-500"></i>
            Semua Course
        </h2>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Course
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Kategori
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Siswa
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Videos
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Status
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($courses as $course)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                @if($course->gambar_course)
                                    <img class="h-10 w-10 rounded-lg object-cover"
                                         src="{{ Storage::url($course->gambar_course) }}"
                                         alt="{{ $course->nama_course }}">
                                @else
                                    <div class="h-10 w-10 rounded-lg bg-gray-300 flex items-center justify-center">
                                        <i class="fas fa-graduation-cap text-gray-600"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ Str::limit($course->nama_course, 40) }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ ucfirst($course->level) }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                            {{ $course->kategori->kategori ?? 'N/A' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $course->user_progress_count }} siswa
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $course->total_video }} videos
                        <div class="text-xs text-gray-500">{{ $course->total_durasi_menit }} menit</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                            {{ $course->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $course->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                        <a href="{{ route('admin.courses.show', $course->course_id) }}"
                           class="text-blue-600 hover:text-blue-900">
                            <i class="fas fa-eye mr-1"></i>Detail
                        </a>
                        <a href="{{ route('admin.monitoring.course-detail', $course->course_id) }}"
                           class="text-green-600 hover:text-green-900">
                            <i class="fas fa-chart-line mr-1"></i>Monitor
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center">
                        <i class="fas fa-graduation-cap text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">Belum ada course yang tersedia</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
