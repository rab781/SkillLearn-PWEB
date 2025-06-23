@extends('layouts.admin')

@section('title', 'Kelola Feedback')

@section('content')
<!-- Page Header -->
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Kelola Feedback</h1>
        <p class="text-gray-600 mt-1">Pantau dan balas feedback dari siswa</p>
    </div>
    {{-- <div class="flex space-x-3">
        <a href="{{ route('courses.index') }}?admin_mode=1"
           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md transition-colors duration-200">
            <i class="fas fa-eye mr-2"></i> Monitor Pembelajaran
        </a>
    </div> --}}
</div>

<!-- Filter Section -->
<div class="bg-white rounded-xl shadow-lg p-6 mb-8">
    <form method="GET" action="{{ route('admin.feedback.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label for="course_id" class="block text-sm font-medium text-gray-700 mb-2">Filter Course</label>
            <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    id="course_id"
                    name="course_id">
                <option value="">Semua Course</option>
                @foreach($courses as $course)
                    <option value="{{ $course->course_id }}"
                            {{ request('course_id') == $course->course_id ? 'selected' : '' }}>
                        {{ $course->nama_course }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
            <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    id="status"
                    name="status">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="replied" {{ request('status') == 'replied' ? 'selected' : '' }}>Sudah Dibalas</option>
            </select>
        </div>
        <div class="flex items-end">
            <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center">
                <i class="fas fa-filter mr-2"></i> Filter
            </button>
        </div>
    </form>
</div>

<!-- Feedback List -->
<div class="bg-white rounded-xl shadow-lg overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900">Daftar Feedback ({{ $feedbacks->total() }})</h3>
    </div>

    @forelse($feedbacks as $feedback)
    <div class="border-b border-gray-200 p-6 hover:bg-gray-50 transition-colors">
        <div class="flex items-start justify-between">
            <!-- Feedback Content -->
            <div class="flex-1">
                <div class="flex items-center space-x-3 mb-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-blue-600"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900">{{ $feedback->user->nama_lengkap }}</h4>
                        <p class="text-sm text-gray-500">{{ $feedback->user->email }}</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        @if($feedback->rating)
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star {{ $i <= $feedback->rating ? 'text-yellow-500' : 'text-gray-300' }}"></i>
                            @endfor
                        @endif
                    </div>
                </div>

                <div class="bg-gray-50 rounded-lg p-4 mb-3">
                    <div class="flex items-center space-x-2 mb-2">
                        <i class="fas fa-graduation-cap text-blue-600"></i>
                        <span class="font-medium text-gray-700">{{ $feedback->course->nama_course }}</span>
                        @if($feedback->courseVideo)
                            <span class="text-gray-500">→</span>
                            <span class="text-sm text-gray-600">{{ $feedback->courseVideo->judul_video }}</span>
                        @endif
                    </div>
                    <p class="text-gray-800">{{ $feedback->pesan }}</p>
                    @if($feedback->catatan)
                        <p class="text-sm text-gray-600 mt-2"><strong>Catatan:</strong> {{ $feedback->catatan }}</p>
                    @endif
                    <p class="text-xs text-gray-500 mt-2">{{ $feedback->tanggal->format('d M Y, H:i') }}</p>
                </div>

                <!-- Admin Reply Section -->
                @if($feedback->balasan)
                    <div class="bg-blue-50 border-l-4 border-blue-500 rounded-lg p-4 mb-3">
                        <div class="flex items-center space-x-2 mb-2">
                            <i class="fas fa-reply text-blue-600"></i>
                            <span class="font-medium text-blue-800">Balasan Admin</span>
                        </div>
                        <p class="text-blue-800">{{ $feedback->balasan }}</p>
                        <p class="text-xs text-blue-600 mt-2">{{ $feedback->tanggal_balasan ? $feedback->tanggal_balasan->format('d M Y, H:i') : '' }}</p>
                    </div>
                @else
                    <!-- Reply Form -->
                    <div class="reply-section-{{ $feedback->feedback_id }}" style="display: none;">
                        <form class="reply-form bg-yellow-50 border-l-4 border-yellow-500 rounded-lg p-4"
                              data-feedback-id="{{ $feedback->feedback_id }}">
                            @csrf
                            <div class="flex items-center space-x-2 mb-3">
                                <i class="fas fa-edit text-yellow-600"></i>
                                <span class="font-medium text-yellow-800">Tulis Balasan</span>
                            </div>
                            <textarea name="balasan"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                      rows="3"
                                      placeholder="Tulis balasan untuk siswa..."
                                      required></textarea>
                            <div class="flex space-x-3 mt-3">
                                <button type="submit"
                                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors">
                                    <i class="fas fa-paper-plane mr-2"></i>Kirim Balasan
                                </button>
                                <button type="button"
                                        class="cancel-reply px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg transition-colors">
                                    Batal
                                </button>
                            </div>
                        </form>
                    </div>
                @endif
            </div>

            <!-- Actions -->
            <div class="ml-4 flex flex-col space-y-2">
                @if(!$feedback->balasan)
                    <button class="reply-btn px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition-colors"
                            data-feedback-id="{{ $feedback->feedback_id }}">
                        <i class="fas fa-reply mr-1"></i>Balas
                    </button>
                @endif

                <div class="relative">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                        {{ $feedback->balasan ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                        {{ $feedback->balasan ? 'Sudah Dibalas' : 'Belum Dibalas' }}
                    </span>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="p-12 text-center">
        <i class="fas fa-comments text-6xl text-gray-300 mb-4"></i>
        <h4 class="text-xl font-semibold text-gray-500 mb-2">Belum Ada Feedback</h4>
        <p class="text-gray-400">Feedback dari siswa akan muncul di sini</p>
    </div>
    @endforelse
</div>

<!-- Pagination -->
@if($feedbacks->hasPages())
<div class="mt-8">
    <div class="flex justify-center">
        {{ $feedbacks->withQueryString()->links() }}
    </div>
</div>
@endif

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle reply button clicks
    document.querySelectorAll('.reply-btn').forEach(button => {
        button.addEventListener('click', function() {
            const feedbackId = this.dataset.feedbackId;
            const replySection = document.querySelector(`.reply-section-${feedbackId}`);

            // Hide all other reply sections
            document.querySelectorAll('[class*="reply-section-"]').forEach(section => {
                if (section !== replySection) {
                    section.style.display = 'none';
                }
            });

            // Toggle current reply section
            replySection.style.display = replySection.style.display === 'none' ? 'block' : 'none';

            if (replySection.style.display === 'block') {
                const textarea = replySection.querySelector('textarea');
                textarea.focus();
            }
        });
    });

    // Handle cancel reply buttons
    document.querySelectorAll('.cancel-reply').forEach(button => {
        button.addEventListener('click', function() {
            const replySection = this.closest('[class*="reply-section-"]');
            replySection.style.display = 'none';

            // Clear textarea
            const textarea = replySection.querySelector('textarea');
            textarea.value = '';
        });
    });

    // Handle reply form submissions
    document.querySelectorAll('.reply-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const feedbackId = this.dataset.feedbackId;
            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');

            // Disable submit button
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mengirim...';

            fetch(`/admin/feedback/${feedbackId}/reply`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    alert('Balasan berhasil dikirim!');

                    // Reload page to show updated feedback
                    window.location.reload();
                } else {
                    alert('Gagal mengirim balasan: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengirim balasan');
            })
            .finally(() => {
                // Re-enable submit button
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-paper-plane mr-2"></i>Kirim Balasan';
            });
        });
    });
});
</script>
@endpush
