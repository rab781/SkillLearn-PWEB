@extends('layouts.admin')

@section('title', 'Detail Course - ' . $course->nama_course)

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')
<!-- Page Header -->
<div class="mb-8">
    <nav class="flex mb-4" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('admin.courses.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 transition-colors">
                    <i class="fas fa-graduation-cap mr-2"></i>
                    Courses
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

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
        <div>
            <h1 class="text-4xl font-bold text-gray-900 mb-2">{{ $course->nama_course }}</h1>
            <div class="flex items-center space-x-4">
                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium
                    @if($course->is_active) bg-green-100 text-green-800 @else bg-gray-100 text-gray-800 @endif">
                    <i class="fas fa-{{ $course->is_active ? 'check-circle' : 'pause-circle' }} mr-2"></i>
                    {{ $course->is_active ? 'Aktif' : 'Nonaktif' }}
                </span>
                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium
                    @switch($course->level)
                        @case('pemula') bg-green-100 text-green-800 @break
                        @case('menengah') bg-yellow-100 text-yellow-800 @break
                        @case('lanjut') bg-red-100 text-red-800 @break
                    @endswitch">
                    <i class="fas fa-signal mr-2"></i>
                    {{ ucfirst($course->level) }}
                </span>
                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                    <i class="fas fa-folder mr-2"></i>
                    {{ $course->kategori->kategori ?? 'N/A' }}
                </span>
            </div>
        </div>

        <div class="flex space-x-3 mt-6 sm:mt-0">
            <a href="{{ route('admin.courses.edit', $course->course_id) }}"
               class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-yellow-500 to-orange-500 text-white rounded-xl hover:from-yellow-600 hover:to-orange-600 focus:outline-none focus:ring-4 focus:ring-yellow-300 transition-all duration-200 shadow-lg">
                <i class="fas fa-edit mr-2"></i> Edit Course
            </a>
            <button onclick="toggleCourseStatus({{ $course->course_id }}, {{ $course->is_active ? 'false' : 'true' }})"
                    class="inline-flex items-center px-6 py-3 rounded-xl focus:outline-none focus:ring-4 focus:ring-offset-2 transition-all duration-200 shadow-lg
                    {{ $course->is_active ? 'bg-gradient-to-r from-red-500 to-pink-500 text-white hover:from-red-600 hover:to-pink-600 focus:ring-red-300' : 'bg-gradient-to-r from-green-500 to-emerald-500 text-white hover:from-green-600 hover:to-emerald-600 focus:ring-green-300' }}">
                <i class="fas fa-{{ $course->is_active ? 'pause' : 'play' }} mr-2"></i>
                {{ $course->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
            </button>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="mb-8 bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-xl shadow-lg">
        <div class="flex items-center">
            <i class="fas fa-check-circle mr-3 text-green-500"></i>
            {{ session('success') }}
        </div>
    </div>
@endif

<!-- Main Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
    <!-- Sidebar - Course Info & Actions -->
    <div class="lg:col-span-1">
        <!-- Course Information Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8">
            @if($course->gambar_course)
                <div class="aspect-video overflow-hidden">
                    <img src="{{ Storage::url($course->gambar_course) }}"
                         alt="{{ $course->nama_course }}"
                         class="w-full h-full object-cover">
                </div>
            @endif

            <div class="p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                    Informasi Course
                </h3>

                <div class="space-y-4">
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-gray-600 font-medium">Total Video:</span>
                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-semibold">
                            {{ $course->total_video }} videos
                        </span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-gray-600 font-medium">Total Durasi:</span>
                        <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm font-semibold">
                            {{ $course->total_durasi_menit }} menit
                        </span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-gray-600 font-medium">Siswa:</span>
                        <span class="bg-emerald-100 text-emerald-800 px-3 py-1 rounded-full text-sm font-semibold">
                            {{ $course->userProgress->count() }} enrolled
                        </span>
                    </div>
                </div>

                @if($course->deskripsi_course)
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h4 class="font-semibold text-gray-900 mb-2">Deskripsi:</h4>
                        <p class="text-gray-600 text-sm leading-relaxed">{{ $course->deskripsi_course }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Quick Actions Card -->
        <div class="bg-white rounded-2xl shadow-xl p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                <i class="fas fa-lightning-bolt mr-2 text-yellow-500"></i>
                Quick Actions
            </h3>

            <div class="space-y-3">
                <button onclick="showAddSectionDialog()"
                        class="w-full bg-gradient-to-r from-green-500 to-emerald-500 text-white py-3 px-4 rounded-xl hover:from-green-600 hover:to-emerald-600 transition-all duration-200 shadow-lg flex items-center justify-center">
                    <i class="fas fa-plus mr-2"></i> Tambah Section
                </button>

                <button onclick="showAddReviewDialog()"
                        class="w-full bg-gradient-to-r from-blue-500 to-cyan-500 text-white py-3 px-4 rounded-xl hover:from-blue-600 hover:to-cyan-600 transition-all duration-200 shadow-lg flex items-center justify-center">
                    <i class="fas fa-star mr-2"></i> Tambah Quick Review
                </button>

                <a href="{{ route('admin.courses.quizzes', $course->course_id) }}"
                   class="w-full bg-gradient-to-r from-purple-500 to-pink-500 text-white py-3 px-4 rounded-xl hover:from-purple-600 hover:to-pink-600 transition-all duration-200 shadow-lg flex items-center justify-center">
                    <i class="fas fa-question-circle mr-2"></i> Kelola Quiz
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content - Course Sections & Reviews -->
    <div class="lg:col-span-3">
        <!-- Course Content -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8">
            <div class="bg-gradient-to-r from-blue-500 to-purple-600 px-6 py-4">
                <h3 class="text-xl font-bold text-white flex items-center">
                    <i class="fas fa-list mr-3"></i>
                    Course Content ({{ $course->sections->count() }} Sections)
                </h3>
            </div>

            <div class="p-6">
                @if($course->sections->count() > 0)
                    <div class="space-y-4">
                        @foreach($course->sections as $section)
                        <div class="border border-gray-200 rounded-xl overflow-hidden">
                            <div class="bg-gray-50 px-6 py-4 cursor-pointer hover:bg-gray-100 transition-colors"
                                 onclick="toggleSection({{ $section->section_id }})">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <h4 class="font-bold text-gray-900 text-lg">
                                            {{ $section->urutan_section }}. {{ $section->nama_section }}
                                        </h4>
                                        <p class="text-gray-600 text-sm mt-1">
                                            <i class="fas fa-play-circle mr-1"></i>
                                            {{ $section->videos->count() }} videos
                                        </p>
                                    </div>
                                    <i id="arrow-{{ $section->section_id }}" class="fas fa-chevron-down text-gray-400 transition-transform"></i>
                                </div>
                            </div>

                            <div id="section-{{ $section->section_id }}" class="hidden">
                                <div class="px-6 py-4">
                                    @if($section->deskripsi_section)
                                        <p class="text-gray-600 mb-4 italic">{{ $section->deskripsi_section }}</p>
                                    @endif

                                    @if($section->videos->count() > 0)
                                        <div class="space-y-3 mb-4">
                                            @foreach($section->videos as $courseVideo)
                                            <div class="bg-gray-50 rounded-lg p-4 flex justify-between items-center">
                                                <div class="flex-1">
                                                    <h5 class="font-semibold text-gray-900">
                                                        {{ $courseVideo->urutan_video }}. {{ $courseVideo->vidio->nama }}
                                                    </h5>
                                                    <div class="flex items-center text-sm text-gray-600 mt-1">
                                                        <i class="fas fa-clock mr-1"></i>
                                                        {{ $courseVideo->durasi_menit }} menit
                                                        @if($courseVideo->catatan_admin)
                                                            <span class="mx-2">•</span>
                                                            <i class="fas fa-sticky-note mr-1 text-blue-500"></i>
                                                            {{ Str::limit($courseVideo->catatan_admin, 30) }}
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="flex space-x-2">
                                                    <a href="{{ $courseVideo->vidio->url }}" target="_blank"
                                                       class="bg-blue-500 text-white px-3 py-2 rounded-lg hover:bg-blue-600 transition-colors">
                                                        <i class="fas fa-play"></i>
                                                    </a>
                                                    <button onclick="removeVideo({{ $course->course_id }}, {{ $courseVideo->course_video_id }})"
                                                            class="bg-red-500 text-white px-3 py-2 rounded-lg hover:bg-red-600 transition-colors">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    @endif

                                    <div class="flex space-x-3">
                                        <button onclick="showAddVideoDialog({{ $section->section_id }}, '{{ $section->nama_section }}')"
                                                class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors flex items-center">
                                            <i class="fas fa-plus mr-2"></i> Tambah Video
                                        </button>
                                        <button onclick="removeSection({{ $course->course_id }}, {{ $section->section_id }})"
                                                class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition-colors flex items-center">
                                            <i class="fas fa-trash mr-2"></i> Hapus Section
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <i class="fas fa-folder-open text-6xl text-gray-300 mb-4"></i>
                        <h4 class="text-xl font-semibold text-gray-500 mb-2">Belum Ada Section</h4>
                        <p class="text-gray-400 mb-6">Tambahkan section pertama untuk memulai!</p>
                        <button onclick="showAddSectionDialog()"
                                class="bg-gradient-to-r from-blue-500 to-purple-600 text-white px-6 py-3 rounded-xl hover:from-blue-600 hover:to-purple-700 transition-all duration-200 shadow-lg">
                            <i class="fas fa-plus mr-2"></i> Tambah Section
                        </button>
                    </div>
                @endif
            </div>
        </div>

        <!-- Quick Reviews -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="bg-gradient-to-r from-yellow-500 to-orange-500 px-6 py-4">
                <h3 class="text-xl font-bold text-white flex items-center">
                    <i class="fas fa-star mr-3"></i>
                    Quick Reviews ({{ $course->quickReviews->count() }})
                </h3>
            </div>

            <div class="p-6">
                @if($course->quickReviews->count() > 0)
                    <div class="space-y-4">
                        @foreach($course->quickReviews as $review)
                        <div class="border border-gray-200 rounded-xl p-4 hover:shadow-md transition-shadow">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <h4 class="font-bold text-gray-900 text-lg">{{ $review->judul_review }}</h4>
                                    <div class="flex items-center space-x-3 mt-2">
                                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-semibold">
                                            {{ $review->getTypeLabel() }}
                                        </span>
                                        <span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-xs font-semibold">
                                            Urutan: {{ $review->urutan_review }}
                                        </span>
                                    </div>
                                    <p class="text-gray-600 mt-3">{!! Str::limit(strip_tags($review->konten_review), 150) !!}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-star text-4xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500">Belum ada quick review.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Toggle course status
function toggleCourseStatus(courseId, newStatus) {
    const title = newStatus === 'true' ? 'Aktifkan Course?' : 'Nonaktifkan Course?';
    const text = newStatus === 'true' ? 'Course akan dapat diakses oleh siswa.' : 'Course akan disembunyikan dari siswa.';
    const confirmText = newStatus === 'true' ? 'Ya, Aktifkan' : 'Ya, Nonaktifkan';

    Swal.fire({
        title: title,
        text: text,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: newStatus === 'true' ? '#10B981' : '#EF4444',
        cancelButtonColor: '#6B7280',
        confirmButtonText: confirmText,
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            showLoading('Memproses...');

            // Create form and submit
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/courses/${courseId}/toggle-status`;

            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            form.appendChild(csrfToken);
            document.body.appendChild(form);
            form.submit();
        }
    });
}

// Toggle section visibility
function toggleSection(sectionId) {
    const section = document.getElementById(`section-${sectionId}`);
    const arrow = document.getElementById(`arrow-${sectionId}`);

    if (section.classList.contains('hidden')) {
        section.classList.remove('hidden');
        arrow.classList.add('transform', 'rotate-180');
    } else {
        section.classList.add('hidden');
        arrow.classList.remove('transform', 'rotate-180');
    }
}

// Show add section dialog
function showAddSectionDialog() {
    Swal.fire({
        title: '<i class="fas fa-plus text-green-500"></i> Tambah Section Baru',
        html: `
            <div class="text-left">
                <div class="mb-4">
                    <label for="swal-nama_section" class="block text-sm font-medium text-gray-700 mb-2">Nama Section</label>
                    <input type="text" id="swal-nama_section" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Masukkan nama section..." required>
                </div>
                <div class="mb-4">
                    <label for="swal-deskripsi_section" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Section (opsional)</label>
                    <textarea id="swal-deskripsi_section" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Deskripsi section..."></textarea>
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonColor: '#10B981',
        cancelButtonColor: '#6B7280',
        confirmButtonText: '<i class="fas fa-save mr-2"></i>Tambah Section',
        cancelButtonText: 'Batal',
        focusConfirm: false,
        preConfirm: () => {
            const namaSection = document.getElementById('swal-nama_section').value;
            const deskripsiSection = document.getElementById('swal-deskripsi_section').value;

            if (!namaSection) {
                Swal.showValidationMessage('Nama section harus diisi!');
                return false;
            }

            return { namaSection, deskripsiSection };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Create form and submit
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/courses/{{ $course->course_id }}/sections`;

            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const namaInput = document.createElement('input');
            namaInput.type = 'hidden';
            namaInput.name = 'nama_section';
            namaInput.value = result.value.namaSection;

            const deskripsiInput = document.createElement('input');
            deskripsiInput.type = 'hidden';
            deskripsiInput.name = 'deskripsi_section';
            deskripsiInput.value = result.value.deskripsiSection;

            form.appendChild(csrfToken);
            form.appendChild(namaInput);
            form.appendChild(deskripsiInput);
            document.body.appendChild(form);
            form.submit();
        }
    });
}

// Show add video dialog
function showAddVideoDialog(sectionId, sectionName) {
    Swal.fire({
        title: `<i class="fas fa-video text-blue-500"></i> Tambah Video ke ${sectionName}`,
        html: `
            <div class="text-left">
                <div class="mb-4">
                    <label for="swal-vidio_vidio_id" class="block text-sm font-medium text-gray-700 mb-2">Pilih Video</label>
                    <select id="swal-vidio_vidio_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        <option value="">-- Pilih Video --</option>
                        @foreach($availableVideos as $video)
                            <option value="{{ $video->vidio_id }}">{{ $video->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label for="swal-durasi_menit" class="block text-sm font-medium text-gray-700 mb-2">Durasi (menit)</label>
                    <input type="number" id="swal-durasi_menit" min="1" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Durasi dalam menit..." required>
                </div>
                <div class="mb-4">
                    <label for="swal-catatan_admin" class="block text-sm font-medium text-gray-700 mb-2">Catatan Admin (opsional)</label>
                    <textarea id="swal-catatan_admin" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Catatan untuk video ini..."></textarea>
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonColor: '#3B82F6',
        cancelButtonColor: '#6B7280',
        confirmButtonText: '<i class="fas fa-save mr-2"></i>Tambah Video',
        cancelButtonText: 'Batal',
        focusConfirm: false,
        preConfirm: () => {
            const vidioId = document.getElementById('swal-vidio_vidio_id').value;
            const durasi = document.getElementById('swal-durasi_menit').value;
            const catatan = document.getElementById('swal-catatan_admin').value;

            if (!vidioId) {
                Swal.showValidationMessage('Video harus dipilih!');
                return false;
            }
            if (!durasi || durasi < 1) {
                Swal.showValidationMessage('Durasi harus diisi dan minimal 1 menit!');
                return false;
            }

            return { vidioId, durasi, catatan };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Create form and submit
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/courses/{{ $course->course_id }}/videos`;

            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const sectionInput = document.createElement('input');
            sectionInput.type = 'hidden';
            sectionInput.name = 'section_id';
            sectionInput.value = sectionId;

            const vidioInput = document.createElement('input');
            vidioInput.type = 'hidden';
            vidioInput.name = 'vidio_vidio_id';
            vidioInput.value = result.value.vidioId;

            const durasiInput = document.createElement('input');
            durasiInput.type = 'hidden';
            durasiInput.name = 'durasi_menit';
            durasiInput.value = result.value.durasi;

            const catatanInput = document.createElement('input');
            catatanInput.type = 'hidden';
            catatanInput.name = 'catatan_admin';
            catatanInput.value = result.value.catatan;

            form.appendChild(csrfToken);
            form.appendChild(sectionInput);
            form.appendChild(vidioInput);
            form.appendChild(durasiInput);
            form.appendChild(catatanInput);
            document.body.appendChild(form);
            form.submit();
        }
    });
}

// Show add review dialog
function showAddReviewDialog() {
    Swal.fire({
        title: '<i class="fas fa-star text-yellow-500"></i> Tambah Quick Review',
        html: `
            <div class="text-left">
                <div class="mb-4">
                    <label for="swal-judul_review" class="block text-sm font-medium text-gray-700 mb-2">Judul Review</label>
                    <input type="text" id="swal-judul_review" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Judul review..." required>
                </div>
                <div class="mb-4">
                    <label for="swal-tipe_review" class="block text-sm font-medium text-gray-700 mb-2">Tipe Review</label>
                    <select id="swal-tipe_review" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        <option value="">-- Pilih Tipe --</option>
                        <option value="setelah_video">Setelah Video</option>
                        <option value="setelah_section">Setelah Section</option>
                        <option value="tengah_course">Tengah Course</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="swal-konten_review" class="block text-sm font-medium text-gray-700 mb-2">Konten Review</label>
                    <textarea id="swal-konten_review" rows="6" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Konten review (gunakan HTML tags untuk formatting)..." required></textarea>
                    <small class="text-gray-500">Gunakan HTML tags untuk formatting (h3, ul, li, strong, em, dll.)</small>
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonColor: '#F59E0B',
        cancelButtonColor: '#6B7280',
        confirmButtonText: '<i class="fas fa-save mr-2"></i>Tambah Review',
        cancelButtonText: 'Batal',
        width: '600px',
        focusConfirm: false,
        preConfirm: () => {
            const judul = document.getElementById('swal-judul_review').value;
            const tipe = document.getElementById('swal-tipe_review').value;
            const konten = document.getElementById('swal-konten_review').value;

            if (!judul) {
                Swal.showValidationMessage('Judul review harus diisi!');
                return false;
            }
            if (!tipe) {
                Swal.showValidationMessage('Tipe review harus dipilih!');
                return false;
            }
            if (!konten) {
                Swal.showValidationMessage('Konten review harus diisi!');
                return false;
            }

            return { judul, tipe, konten };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Create form and submit
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/courses/{{ $course->course_id }}/reviews`;

            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const judulInput = document.createElement('input');
            judulInput.type = 'hidden';
            judulInput.name = 'judul_review';
            judulInput.value = result.value.judul;

            const tipeInput = document.createElement('input');
            tipeInput.type = 'hidden';
            tipeInput.name = 'tipe_review';
            tipeInput.value = result.value.tipe;

            const kontenInput = document.createElement('input');
            kontenInput.type = 'hidden';
            kontenInput.name = 'konten_review';
            kontenInput.value = result.value.konten;

            form.appendChild(csrfToken);
            form.appendChild(judulInput);
            form.appendChild(tipeInput);
            form.appendChild(kontenInput);
            document.body.appendChild(form);
            form.submit();
        }
    });
}

// Remove video
function removeVideo(courseId, courseVideoId) {
    showDeleteConfirm('Video akan dihapus dari section ini. Tindakan ini tidak dapat dibatalkan.', 'Hapus Video?')
    .then((result) => {
        if (result.isConfirmed) {
            showLoading('Menghapus video...');

            // Create form and submit
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/courses/${courseId}/videos/${courseVideoId}`;

            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';

            form.appendChild(csrfToken);
            form.appendChild(methodInput);
            document.body.appendChild(form);
            form.submit();
        }
    });
}

// Remove section
function removeSection(courseId, sectionId) {
    showDeleteConfirm('Section dan semua video di dalamnya akan dihapus. Tindakan ini tidak dapat dibatalkan.', 'Hapus Section?')
    .then((result) => {
        if (result.isConfirmed) {
            showLoading('Menghapus section...');

            // Create form and submit
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/courses/${courseId}/sections/${sectionId}`;

            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';

            form.appendChild(csrfToken);
            form.appendChild(methodInput);
            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>
@endpush
