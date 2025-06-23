@extends('layouts.admin')

@section('title', 'Detail Monitoring - ' . $course->nama_course)

@section('content')
<div class="mb-8">
    <nav class="flex mb-4" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('admin.monitoring.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 transition-colors">
                    <i class="fas fa-chart-line mr-2"></i>
                    Monitoring
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                    <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">{{ $course->nama_course }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
        <div>
            <h1 class="text-4xl font-bold text-gray-900 mb-2">{{ $course->nama_course }}</h1>
            <p class="text-gray-600">Detail monitoring pembelajaran dan statistik siswa</p>
        </div>
        <div class="mt-4 lg:mt-0">
            <a href="{{ route('admin.courses.show', $course->course_id) }}"
               class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors shadow-lg">
                <i class="fas fa-eye mr-2"></i>
                Lihat Course
            </a>
        </div>
    </div>
</div>

<!-- Course Statistics -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-2xl shadow-xl p-6">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-users text-blue-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-2xl font-bold text-gray-900">{{ $studentProgress->count() }}</h3>
                <p class="text-gray-600 text-sm">Total Siswa</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-xl p-6">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-check-circle text-green-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-2xl font-bold text-gray-900">{{ $studentProgress->where('progress.status', 'completed')->count() }}</h3>
                <p class="text-gray-600 text-sm">Siswa Selesai</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-xl p-6">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-play-circle text-yellow-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-2xl font-bold text-gray-900">{{ $course->sections->flatMap->videos->count() }}</h3>
                <p class="text-gray-600 text-sm">Total Video</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-xl p-6">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-clock text-purple-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-2xl font-bold text-gray-900">{{ $studentProgress->sum('watched_minutes') ?? 0 }}</h3>
                <p class="text-gray-600 text-sm">Total Menit Ditonton</p>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Student Progress -->
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900 flex items-center">
                <i class="fas fa-user-graduate mr-2 text-blue-500"></i>
                Progress Siswa
            </h2>
        </div>
        <div class="p-6">
            <div class="space-y-4 max-h-96 overflow-y-auto">
                @forelse($studentProgress->take(10) as $student)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-blue-50 transition-colors">
                    <div class="flex-1">
                        <h4 class="font-semibold text-gray-900">{{ $student['user']->nama_lengkap }}</h4>
                        <div class="flex items-center mt-1 space-x-4">
                            <span class="text-sm text-gray-600">
                                {{ $student['completed_videos'] }}/{{ $student['total_videos'] }} video
                            </span>
                            <span class="text-sm text-gray-600">
                                {{ $student['watched_minutes'] }} menit
                            </span>
                        </div>
                        <!-- Progress bar -->
                        <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $student['completion_percentage'] }}%"></div>
                        </div>
                    </div>
                    <div class="text-right ml-4">
                        <div class="text-lg font-bold
                            {{ $student['completion_percentage'] >= 100 ? 'text-green-600' : ($student['completion_percentage'] >= 50 ? 'text-yellow-600' : 'text-red-600') }}">
                            {{ $student['completion_percentage'] }}%
                        </div>
                        <div class="text-xs text-gray-500">completion</div>
                    </div>
                </div>
                @empty
                <div class="text-center py-8">
                    <i class="fas fa-user-graduate text-4xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500">Belum ada siswa yang mengikuti course ini</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Video Statistics -->
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900 flex items-center">
                <i class="fas fa-video mr-2 text-green-500"></i>
                Statistik Video
            </h2>
        </div>
        <div class="p-6">
            <div class="space-y-4 max-h-96 overflow-y-auto">
                @forelse($videoStats->take(10) as $stat)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-green-50 transition-colors">
                    <div class="flex-1">
                        <h4 class="font-semibold text-gray-900">{{ Str::limit($stat['video']->nama, 30) }}</h4>
                        <div class="flex items-center mt-1 space-x-4">
                            <span class="text-sm text-gray-600">
                                <i class="fas fa-eye mr-1"></i>{{ $stat['unique_viewers'] }} viewers
                            </span>
                            <span class="text-sm text-gray-600">
                                <i class="fas fa-play mr-1"></i>{{ $stat['watch_count'] }} plays
                            </span>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-lg font-bold text-green-600">{{ $stat['average_watch_time'] }}m</div>
                        <div class="text-xs text-gray-500">avg time</div>
                    </div>
                </div>
                @empty
                <div class="text-center py-8">
                    <i class="fas fa-video text-4xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500">Belum ada statistik video</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Quiz Statistics -->
@if($quizStats->count() > 0)
<div class="mt-8 bg-white rounded-2xl shadow-xl overflow-hidden">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-xl font-bold text-gray-900 flex items-center">
            <i class="fas fa-question-circle mr-2 text-purple-500"></i>
            Statistik Quiz
        </h2>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Quiz
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Total Attempts
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Pass Rate
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Average Score
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($quizStats as $stat)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $stat['quiz']->judul_quiz }}</div>
                        <div class="text-sm text-gray-500">{{ Str::limit($stat['quiz']->deskripsi_quiz, 50) }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $stat['total_attempts'] }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-bold
                            {{ $stat['pass_rate'] >= 70 ? 'text-green-600' : ($stat['pass_rate'] >= 50 ? 'text-yellow-600' : 'text-red-600') }}">
                            {{ $stat['pass_rate'] }}%
                        </div>
                        <div class="text-xs text-gray-500">{{ $stat['passed_attempts'] }}/{{ $stat['total_attempts'] }} passed</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-bold text-gray-900">{{ $stat['average_score'] }}%</div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

<!-- Detailed Student Progress Table -->
<div class="mt-8 bg-white rounded-2xl shadow-xl overflow-hidden">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-xl font-bold text-gray-900 flex items-center">
            <i class="fas fa-table mr-2 text-indigo-500"></i>
            Detail Progress Semua Siswa
        </h2>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Siswa
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Progress Video
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Waktu Tonton
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Status
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Aktivitas Terakhir
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($studentProgress as $student)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                    <span class="text-sm font-medium text-gray-700">
                                        {{ substr($student['user']->nama_lengkap, 0, 2) }}
                                    </span>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">{{ $student['user']->nama_lengkap }}</div>
                                <div class="text-sm text-gray-500">{{ $student['user']->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">
                            {{ $student['completed_videos'] }}/{{ $student['total_videos'] }} videos
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $student['completion_percentage'] }}%"></div>
                        </div>
                        <div class="text-xs text-gray-500 mt-1">{{ $student['completion_percentage'] }}% complete</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $student['watched_minutes'] }} menit</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                            {{ $student['progress']->status === 'completed' ? 'bg-green-100 text-green-800' : ($student['progress']->status === 'in_progress' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                            {{ ucfirst(str_replace('_', ' ', $student['progress']->status)) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $student['last_activity']->diffForHumans() }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center">
                        <i class="fas fa-user-graduate text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">Belum ada siswa yang mengikuti course ini</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
