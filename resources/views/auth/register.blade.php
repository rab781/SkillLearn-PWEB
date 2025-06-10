@extends('layouts.app')

@section('title', 'Register - Skillearn')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-green-50 via-blue-50 to-purple-50">
    <!-- Background Decorations -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute top-20 left-20 w-24 h-24 bg-green-200 rounded-full opacity-20 animate-pulse"></div>
        <div class="absolute top-40 right-10 w-20 h-20 bg-blue-200 rounded-full opacity-30 animate-bounce"></div>
        <div class="absolute bottom-32 left-1/3 w-16 h-16 bg-purple-200 rounded-full opacity-25 animate-ping"></div>
        <div class="absolute bottom-20 right-1/4 w-12 h-12 bg-pink-200 rounded-full opacity-40 animate-pulse"></div>
    </div>

    <div class="relative max-w-md w-full">
        <!-- Enhanced Register Card -->
        <div class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-2xl p-8 border border-white/20">
            <!-- Logo and Title -->
            <div class="text-center mb-6">
                <h2 class="text-3xl font-bold bg-gradient-to-r from-green-600 to-blue-600 bg-clip-text text-transparent">
                    Bergabung Sekarang! 🎉
                </h2>
                <p class="mt-2 text-gray-600">
                    Mulai perjalanan belajarmu bersama Skillearn
                </p>
                <p class="mt-1 text-sm text-gray-500">
                    Sudah punya akun? 
                    <a href="/login" class="font-medium text-blue-600 hover:text-blue-500 transition-colors">
                        Masuk di sini 🚀
                    </a>
                </p>
            </div>
            
            <form class="space-y-4" id="registerForm">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 mb-2">
                            👤 Nama Lengkap
                        </label>
                        <input id="nama_lengkap" name="nama_lengkap" type="text" required
                               class="block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200"
                               placeholder="Masukkan nama lengkap">
                    </div>
                    
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                            🏷️ Username
                        </label>
                        <input id="username" name="username" type="text" required
                               class="block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200"
                               placeholder="Pilih username unik">
                    </div>
                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            📧 Email
                        </label>
                        <input id="email" name="email" type="email" required
                               class="block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200"
                               placeholder="nama@email.com">
                    </div>
                    
                    <div>
                        <label for="no_telepon" class="block text-sm font-medium text-gray-700 mb-2">
                            📱 No. Telepon
                        </label>
                        <input id="no_telepon" name="no_telepon" type="tel" required
                               class="block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200"
                               placeholder="08xxxxxxxxxx">
                    </div>
                    
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            🔒 Password
                        </label>
                        <div class="relative">
                            <input id="password" name="password" type="password" required
                                   class="block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200"
                                   placeholder="Minimal 8 karakter">
                        </div>
                    </div>
                    
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            🔐 Konfirmasi Password
                        </label>
                        <div class="relative">
                            <input id="password_confirmation" name="password_confirmation" type="password" required
                                   class="block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200"
                                   placeholder="Ulangi password">                            
                        </div>
                    </div>
                </div>

                <!-- Enhanced Submit Button -->
                <div class="pt-4">
                    <button type="submit" 
                            class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-xl text-white bg-gradient-to-r from-green-500 to-blue-600 hover:from-green-600 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 transform hover:scale-105">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <svg class="h-5 w-5 text-green-300 group-hover:text-green-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z" />
                            </svg>
                        </span>
                        🎯 Daftar Sekarang
                    </button>
                </div>

                <!-- Terms -->
                <div class="mt-4 text-center text-xs text-gray-500">
                    <p>Dengan mendaftar, Anda menyetujui</p>
                    <p>
                        <a href="#" class="text-blue-600 hover:text-blue-500">Syarat & Ketentuan</a> dan 
                        <a href="#" class="text-blue-600 hover:text-blue-500">Kebijakan Privasi</a>
                    </p>
                </div>
            </form>
        </div>

        <!-- Footer -->
        <div class="text-center mt-6 text-sm text-gray-500">
            <p>🎓 Bergabung dengan ribuan learners lainnya</p>
            <p>© 2025 Skillearn. Masa depan belajar dimulai di sini 🚀</p>
        </div>
    </div>
</div>

<script>
function togglePassword(fieldId) {
    const passwordField = document.getElementById(fieldId);
    const eyeIcon = fieldId === 'password' ? document.getElementById('eyeIcon1') : document.getElementById('eyeIcon2');
    
    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        eyeIcon.textContent = '🙈';
    } else {
        passwordField.type = 'password';
        eyeIcon.textContent = '👁️';
    }
}
            </div>

            <div>
                <button type="submit"
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Daftar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('registerForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const data = Object.fromEntries(formData);

    try {
        const response = await fetch('/api/register', {
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
                text: 'Registrasi berhasil! Silakan login.',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                window.location.href = '/login';
            });
        } else {
            let errorMessage = result.message;
            if (result.errors) {
                errorMessage = Object.values(result.errors).flat().join('\n');
            }
            Swal.fire({
                title: 'Gagal!',
                text: errorMessage,
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
</script>
@endpush
