@extends('layouts.admin')

@section('title', 'Buat Course Baru')

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
                    <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Buat Course Baru</span>
                </div>
            </li>
        </ol>
    </nav>
    <h1 class="text-3xl font-bold text-gray-900 mt-2">Buat Course Baru</h1>
</div>

<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Informasi Course</h3>
        </div>
        <div class="p-6">
            <form action="{{ route('admin.courses.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                
                <!-- Nama Course -->
                <div>
                    <label for="nama_course" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Course <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('nama_course') border-red-500 @enderror" 
                           id="nama_course" 
                           name="nama_course" 
                           value="{{ old('nama_course') }}" 
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
                              required>{{ old('deskripsi_course') }}</textarea>
                    @error('deskripsi_course')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror                </div>

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
                                        {{ old('kategori_kategori_id') == $kategori->kategori_id ? 'selected' : '' }}>
                                    {{ $kategori->kategori }}
                                </option>                            @endforeach
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
                            <option value="pemula" {{ old('level') == 'pemula' ? 'selected' : '' }}>Pemula</option>
                            <option value="menengah" {{ old('level') == 'menengah' ? 'selected' : '' }}>Menengah</option>
                            <option value="lanjut" {{ old('level') == 'lanjut' ? 'selected' : '' }}>Lanjut</option>
                        </select>
                        @error('level')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Gambar Course -->                <div>
                    <label for="gambar_course" class="block text-sm font-medium text-gray-700 mb-2">Gambar Course</label>
                    <input type="file" 
                           class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('gambar_course') border-red-500 @enderror" 
                           id="gambar_course" 
                           name="gambar_course" 
                           accept="image/*">
                    <p class="mt-1 text-sm text-gray-500">Upload gambar untuk course (opsional). Format: JPG, PNG, GIF. Maksimal 2MB.</p>
                    @error('gambar_course')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Preview Upload -->
                <div id="imagePreview" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Preview Gambar:</label>
                    <div>
                        <img id="previewImg" src="" alt="Preview" class="rounded-lg shadow-sm border border-gray-200" style="max-width: 300px; max-height: 200px;">
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex justify-between items-center pt-6">
                    <a href="{{ route('admin.courses.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md transition-colors duration-200">
                        <i class="fas fa-save mr-2"></i> Simpan Course
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

            <!-- Info Box --><!-- Info Card -->
<div class="mt-8 bg-blue-50 border border-blue-200 rounded-xl p-6">
    <div class="flex items-center mb-4">
        <i class="fas fa-info-circle text-blue-600 text-xl mr-3"></i>
        <h3 class="text-lg font-semibold text-blue-900">Langkah Selanjutnya</h3>
    </div>
    <div>
        <p class="text-blue-800 mb-3"><strong>Setelah course berhasil dibuat, Anda dapat:</strong></p>
        <ol class="text-blue-700 space-y-1 list-decimal list-inside">
            <li>Menambahkan sections untuk mengorganisir pembelajaran</li>
            <li>Menambahkan videos ke setiap section</li>
            <li>Mengatur urutan videos</li>
            <li>Menambahkan quick reviews di titik strategis</li>
            <li>Mengaktifkan course untuk user</li>
        </ol>
    </div>
</div>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Image preview
    const gambarCourse = document.getElementById('gambar_course');
    const imagePreview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');
    
    gambarCourse.addEventListener('change', function() {
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

    // Auto-generate slug from course name (optional enhancement)
    const namaCourse = document.getElementById('nama_course');
    namaCourse.addEventListener('input', function() {
        // This could be used to generate a course slug in the future
        const courseName = this.value;
        console.log('Course name:', courseName);
    });
});
</script>
@endpush
