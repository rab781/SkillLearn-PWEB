@extends('layouts.app')

@section('title', 'Login - Skillearn')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50">
    <!-- Background Decorations -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute top-10 left-10 w-20 h-20 bg-blue-200 rounded-full opacity-20 animate-pulse"></div>
        <div class="absolute top-20 right-20 w-16 h-16 bg-purple-200 rounded-full opacity-30 animate-bounce"></div>
        <div class="absolute bottom-20 left-1/4 w-12 h-12 bg-pink-200 rounded-full opacity-25 animate-ping"></div>
        <div class="absolute bottom-10 right-1/3 w-8 h-8 bg-indigo-200 rounded-full opacity-40 animate-pulse"></div>
    </div>

    <div class="relative max-w-md w-full">
        <!-- Enhanced Login Card -->
        <div class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-2xl p-8 border border-white/20">
            <!-- Logo and Title -->
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                    Selamat Datang! 👋
                </h2>
                <p class="mt-2 text-gray-600">
                    Masuk ke akun Skillearn Anda dan mulai belajar
                </p>
                <p class="mt-1 text-sm text-gray-500">
                    Belum punya akun? 
                    <a href="/register" class="font-medium text-blue-600 hover:text-blue-500 transition-colors">
                        Daftar sekarang 🚀
                    </a>
                </p>
            </div>
            
            <!-- Enhanced Error Messages -->
            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-400 rounded-lg p-4 mb-6 animate-shake">
                    <div class="flex items-center">
                        <div class="text-2xl mr-3">⚠️</div>
                        <div>
                            <h3 class="text-sm font-medium text-red-800">Oops! Ada yang salah:</h3>
                            <ul class="mt-2 text-sm text-red-700 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li class="flex items-center">
                                        <span class="w-1 h-1 bg-red-500 rounded-full mr-2"></span>
                                        {{ $error }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif
        
            <form class="space-y-6" id="loginForm" action="{{ route('login.post') }}" method="POST">
                @csrf
                
                <!-- Enhanced Form Fields -->
                <div class="space-y-4">
                    <div>
                        <label for="login" class="block text-sm font-medium text-gray-700 mb-2">
                            📧 Username atau Email
                        </label>
                        <div class="relative">
                            <input id="login" name="login" type="text" required value="{{ old('login') }}"
                                   class="block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                   placeholder="Masukkan username atau email">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <span class="text-gray-400">👤</span>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            🔒 Password
                        </label>
                        <div class="relative">
                            <input id="password" name="password" type="password" required
                                   class="block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                   placeholder="Masukkan password">
                        </div>
                    </div>
                </div>

                <!-- Enhanced Submit Button -->
                <div>
                    <button type="submit" 
                            class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-xl text-white btn-primary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <svg class="h-5 w-5 text-blue-300 group-hover:text-blue-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                            </svg>
                        </span>
                        🚀 Masuk Sekarang
                    </button>
                </div>

                <!-- Demo Accounts Info -->
                <div class="mt-6 p-4 bg-blue-50 rounded-xl border border-blue-200">
                    <h4 class="text-sm font-medium text-blue-800 mb-2">🎯 Demo Accounts:</h4>
                    <div class="text-xs text-blue-700 space-y-1">
                        <div><strong>Admin:</strong> admin@skillearn.com | password123</div>
                        <div><strong>Customer:</strong> customer@skillearn.com | password123</div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Footer Links -->
        <div class="text-center mt-6 text-sm text-gray-500">
            <p>© 2025 Skillearn. Platform pembelajaran terdepan di Indonesia 🇮🇩</p>
        </div>
    </div>
</div>

<script>
function togglePassword() {
    const passwordField = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');
    
    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        eyeIcon.textContent = '🙈';
    } else {
        passwordField.type = 'password';
        eyeIcon.textContent = '👁️';
    }
}
</script>
                    <input id="password" name="password" type="password" required
                           class="relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                           placeholder="Password">
                </div>
            </div>

            <div>
                <button type="submit"
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Masuk
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Show loading state on form submit
document.getElementById('loginForm').addEventListener('submit', function(e) {
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    submitBtn.innerHTML = 'Memproses...';
    submitBtn.disabled = true;
    
    // Re-enable after 5 seconds as fallback
    setTimeout(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    }, 5000);
});
</script>
@endpush
