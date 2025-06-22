@extends('layouts.admin')

@section('title', 'Edit Course - ' . $course->nama_course)

@section('content')
<!-- Page Header -->
<div class="mb-6">
    <nav class="flex" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('admin.courses.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                    Courses
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                    <a href="{{ route('admin.courses.show', $course->course_id) }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2">{{ $course->nama_course }}</a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                    <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Edit</span>
                </div>
            </li>
        </ol>
    </nav>
    <h1 class="text-3xl font-bold text-gray-900 mt-2">Edit Course</h1>
</div>

@if(session('success'))
    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
        {{ session('success') }}
    </div>
@endif

<div class="max-w-6xl mx-auto">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Form -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Edit Informasi Course</h3>
                </div>
                <div class="p-6">
                    <form action="{{ route('admin.courses.update', $course->course_id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <!-- Nama Course -->
                        <div>
                            <label for="nama_course" class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Course <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('nama_course') border-red-500 @enderror" 
                                   id="nama_course" 
                                   name="nama_course" 
                                   value="{{ old('nama_course', $course->nama_course) }}" 
                                   placeholder="Contoh: PHP untuk Pemula" 
                                   required>
                            @error('nama_course')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Deskripsi Course -->
                        <div>
                            <label for="deskripsi_course" class="block text-sm font-medium text-gray-700 mb-2">
                                Deskripsi Course <span class="text-red-500">*</span>
                            </label>
                            <textarea class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('deskripsi_course') border-red-500 @enderror" 
                                      id="deskripsi_course" 
                                      name="deskripsi_course" 
                                      rows="4" 
                                      placeholder="Jelaskan tentang course ini, apa yang akan dipelajari, dan siapa target audiensnya..." 
                                      required>{{ old('deskripsi_course', $course->deskripsi_course) }}</textarea>
                            @error('deskripsi_course')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Category and Level -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Kategori -->
                            <div>
                                <label for="kategori_kategori_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    Kategori <span class="text-red-500">*</span>
                                </label>
                                <select class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('kategori_kategori_id') border-red-500 @enderror" 
                                        id="kategori_kategori_id" 
                                        name="kategori_kategori_id" 
                                        required>
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach($kategoris as $kategori)
                                        <option value="{{ $kategori->kategori_id }}" 
                                                {{ old('kategori_kategori_id', $course->kategori_kategori_id) == $kategori->kategori_id ? 'selected' : '' }}>
                                            {{ $kategori->kategori }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('kategori_kategori_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Level -->
                            <div>
                                <label for="level" class="block text-sm font-medium text-gray-700 mb-2">
                                    Level Course <span class="text-red-500">*</span>
                                </label>
                                <select class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('level') border-red-500 @enderror" 
                                        id="level" 
                                        name="level" 
                                        required>
                                    <option value="">-- Pilih Level --</option>
                                    <option value="pemula" {{ old('level', $course->level) == 'pemula' ? 'selected' : '' }}>Pemula</option>
                                    <option value="menengah" {{ old('level', $course->level) == 'menengah' ? 'selected' : '' }}>Menengah</option>
                                    <option value="lanjut" {{ old('level', $course->level) == 'lanjut' ? 'selected' : '' }}>Lanjut</option>
                                </select>
                                @error('level')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Current Image -->
                        @if($course->gambar_course)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Gambar Course Saat Ini:</label>
                            <div class="relative">
                                <img src="{{ Storage::url($course->gambar_course) }}" 
                                     alt="{{ $course->nama_course }}" 
                                     class="rounded-lg shadow-md w-full max-w-md h-48 object-cover">
                            </div>
                        </div>
                        @endif

                        <!-- Gambar Course -->
                        <div>
                            <label for="gambar_course" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ $course->gambar_course ? 'Ganti Gambar Course' : 'Upload Gambar Course' }}
                            </label>
                            <input type="file" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('gambar_course') border-red-500 @enderror" 
                                   id="gambar_course" 
                                   name="gambar_course" 
                                   accept="image/*">
                            <p class="mt-1 text-sm text-gray-500">
                                {{ $course->gambar_course ? 'Biarkan kosong jika tidak ingin mengganti gambar.' : 'Upload gambar untuk course (opsional).' }} 
                                Format: JPG, PNG, GIF. Maksimal 2MB.
                            </p>
                            @error('gambar_course')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Preview Upload -->
                        <div id="imagePreview" class="hidden">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Preview Gambar Baru:</label>
                            <div class="relative">
                                <img id="previewImg" src="" alt="Preview" class="rounded-lg shadow-md w-full max-w-md h-48 object-cover">
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="flex justify-between items-center pt-6">
                            <a href="{{ route('admin.courses.show', $course->course_id) }}" class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                                <i class="fas fa-arrow-left mr-2"></i> Kembali
                            </a>
                            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                                <i class="fas fa-save mr-2"></i> Update Course
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- Course Stats -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Statistik Course</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-blue-600 font-medium">Total Videos</p>
                                <p class="text-2xl font-bold text-blue-800">{{ $course->total_video }}</p>
                            </div>
                            <div class="p-3 bg-blue-200 rounded-full">
                                <i class="fas fa-play text-blue-600"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-green-600 font-medium">Total Durasi</p>
                                <p class="text-2xl font-bold text-green-800">{{ $course->total_durasi_menit }} min</p>
                            </div>
                            <div class="p-3 bg-green-200 rounded-full">
                                <i class="fas fa-clock text-green-600"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-r from-purple-50 to-purple-100 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-purple-600 font-medium">Total Students</p>
                                <p class="text-2xl font-bold text-purple-800">{{ $course->userProgress()->count() }}</p>
                            </div>
                            <div class="p-3 bg-purple-200 rounded-full">
                                <i class="fas fa-users text-purple-600"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Danger Zone -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden border-2 border-red-200">
                <div class="px-6 py-4 bg-red-50 border-b border-red-200">
                    <h3 class="text-lg font-semibold text-red-800">
                        <i class="fas fa-exclamation-triangle mr-2"></i> Danger Zone
                    </h3>
                </div>
                <div class="p-6">
                    <h4 class="font-semibold text-gray-900 mb-2">Hapus Course</h4>
                    <p class="text-sm text-gray-600 mb-4">
                        Menghapus course akan menghapus semua sections, videos, reviews, dan progress user. 
                        <strong>Aksi ini tidak dapat dibatalkan!</strong>
                    </p>
                    <form action="{{ route('admin.courses.destroy', $course->course_id) }}" method="POST" class="delete-form">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors">
                            <i class="fas fa-trash mr-2"></i> Hapus Course Permanen
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Image preview
    const imageInput = document.getElementById('gambar_course');
    const imagePreview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');
    
    imageInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                imagePreview.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        } else {
            imagePreview.classList.add('hidden');
        }
    });

    // Enhanced delete confirmation
    const deleteForm = document.querySelector('.delete-form');
    if (deleteForm) {
        deleteForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            Swal.fire({
                title: 'Konfirmasi Penghapusan',
                text: 'Ketik "HAPUS" untuk mengkonfirmasi penghapusan course ini:',
                input: 'text',
                inputPlaceholder: 'Ketik HAPUS',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                inputValidator: (value) => {
                    if (value !== 'HAPUS') {
                        return 'Anda harus mengetik "HAPUS" untuk konfirmasi!'
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    }
});
</script>
@endpush
