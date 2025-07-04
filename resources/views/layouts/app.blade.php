<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Skillearn - Platform Pembelajaran Online Modern')</title>
    <meta name="description" content="Platform pembelajaran online terdepan dengan kurasi video berkualitas tinggi untuk semua kalangan">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Tailwind CSS CDN for immediate styling -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'sans': ['Inter', 'ui-sans-serif', 'system-ui'],
                    },
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                        }
                    }
                }
            }
        }
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- jQuery for AJAX support -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="{{ asset('resources/js/notifications.js') }}"></script>
    <script src="{{ asset('resources/js/responsive.js') }}"></script>
    <script src="{{ asset('resources/js/search.js') }}"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .gradient-bg { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .card-hover { transition: all 0.3s ease; }
        .card-hover:hover { transform: translateY(-4px); box-shadow: 0 20px 40px rgba(0,0,0,0.15); }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.4);
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .loading-spinner {
            border: 3px solid #f3f3f3;
            border-top: 3px solid #667eea;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .fade-in {
            animation: fadeIn 0.6s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .skill-badge {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-block;
            margin: 0.125rem;
        }
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* SweetAlert2 Custom Styling */
        .swal2-popup-custom {
            border-radius: 1rem !important;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
        }

        .swal2-title-custom {
            font-family: 'Inter', sans-serif !important;
            font-weight: 700 !important;
            color: #1f2937 !important;
        }

        .swal2-content-custom {
            font-family: 'Inter', sans-serif !important;
            color: #4b5563 !important;
        }

        .swal2-confirm-custom {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%) !important;
            color: white !important;
            padding: 0.75rem 1.5rem !important;
            border-radius: 0.75rem !important;
            font-weight: 600 !important;
            font-family: 'Inter', sans-serif !important;
            transition: all 0.3s ease !important;
            border: none !important;
            box-shadow: 0 4px 14px 0 rgba(59, 130, 246, 0.39) !important;
        }

        .swal2-confirm-custom:hover {
            transform: translateY(-1px) !important;
            box-shadow: 0 6px 20px 0 rgba(59, 130, 246, 0.5) !important;
        }

        .swal2-cancel-custom {
            background: #f3f4f6 !important;
            color: #374151 !important;
            padding: 0.75rem 1.5rem !important;
            border-radius: 0.75rem !important;
            font-weight: 600 !important;
            font-family: 'Inter', sans-serif !important;
            transition: all 0.3s ease !important;
            border: none !important;
            margin-right: 0.5rem !important;
        }

        .swal2-cancel-custom:hover {
            background: #e5e7eb !important;
            transform: translateY(-1px) !important;
        }

        .swal2-input-custom {
            border: 2px solid #e5e7eb !important;
            border-radius: 0.75rem !important;
            padding: 0.75rem 1rem !important;
            font-family: 'Inter', sans-serif !important;
            transition: border-color 0.3s ease !important;
        }

        .swal2-input-custom:focus {
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
        }

        .swal2-textarea-custom {
            border: 2px solid #e5e7eb !important;
            border-radius: 0.75rem !important;
            padding: 0.75rem 1rem !important;
            font-family: 'Inter', sans-serif !important;
            resize: vertical !important;
        }

        .swal2-select-custom {
            border: 2px solid #e5e7eb !important;
            border-radius: 0.75rem !important;
            padding: 0.75rem 1rem !important;
            font-family: 'Inter', sans-serif !important;
        }

        .swal2-progress-steps {
            margin: 0 0 1.25rem 0 !important;
        }

        .swal2-progress-step {
            background: #3b82f6 !important;
            color: white !important;
            border-radius: 50% !important;
            width: 2rem !important;
            height: 2rem !important;
            line-height: 2rem !important;
            font-weight: 600 !important;
        }

        .swal2-progress-step-line {
            background: #e5e7eb !important;
        }

        .swal2-timer-progress-bar {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%) !important;
        }

        /* Animation untuk SweetAlert */
        @keyframes swalBounceIn {
            0% { transform: scale3d(0.3, 0.3, 0.3); }
            50% { transform: scale3d(1.05, 1.05, 1.05); }
            70% { transform: scale3d(0.9, 0.9, 0.9); }
            100% { transform: scale3d(1, 1, 1); }
        }

        @keyframes swalBounceOut {
            20% { transform: scale3d(0.9, 0.9, 0.9); }
            50%, 55% { transform: scale3d(1.1, 1.1, 1.1); }
            100% { transform: scale3d(0.3, 0.3, 0.3); }
        }

        .swal2-show {
            animation: swalBounceIn 0.6s !important;
        }

        .swal2-hide {
            animation: swalBounceOut 0.6s !important;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-blue-50 min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white/95 backdrop-blur-sm shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="/" class="flex items-center">
                        <img src="{{ asset('images/logo.png') }}" alt="Skillearn Logo" class="h-8 w-auto mr-3">
                    </a>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-6">
                    <a href="/" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md font-medium transition-colors">Home</a>
                    <a href="/videos" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md font-medium transition-colors">Pembelajaran</a>

                    @auth
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center space-x-2">
                            <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center">
                                <span class="text-white text-sm font-medium">{{ substr(auth()->user()->nama_lengkap, 0, 1) }}</span>
                            </div>


                        @auth
                            <a href="{{ route('profil.show') }}" class="text-gray-700 hover:text-blue-600 font-medium">
                            Hi, {{ auth()->user()->nama_lengkap }}</a>
                         @endauth


                        {{-- Tombol Dashboard --}}
                        @if(auth()->user()->isAdmin())
                            <a href="/admin/dashboard"
                            class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-4 py-2 rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all font-medium shadow-md">
                                Admin Dashboard
                            </a>
                        @else
                            <a href="/customer/dashboard"
                            class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-4 py-2 rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all font-medium shadow-md">
                                Dashboard
                            </a>
                        @endif

                        {{-- Logout --}}
                        <form action="/logout" method="POST" class="inline">
                            @csrf
                            <button type="submit"
                                    class="text-red-600 hover:text-red-700 px-3 py-2 rounded-md font-medium transition-colors">
                                Logout
                            </button>
                        </form>
                    </div>
                    @else
                    <div class="flex items-center space-x-3">
                        <a href="/login" class="text-blue-600 hover:text-blue-700 px-4 py-2 rounded-md font-medium transition-colors">Login</a>
                        <a href="/register"
                        class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-2 rounded-lg hover:from-blue-700 hover:to-purple-700 transition-all font-medium shadow-md">Register</a>
                    </div>
                    @endauth
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden flex items-center">
                    <button id="mobile-menu-button" class="text-gray-700 hover:text-blue-600 p-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation -->
        <div id="mobile-menu" class="md:hidden hidden bg-white border-t">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="/" class="block text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md font-medium">Home</a>
                <a href="/videos" class="block text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md font-medium">Pembelajaran</a>
                @auth
                    @if(auth()->user()->isAdmin())
                        <a href="/admin/dashboard" class="block text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md font-medium">Admin Dashboard</a>
                    @else
                        <a href="/customer/dashboard" class="block text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md font-medium">Dashboard</a>
                    @endif
                    <form action="/logout" method="POST" class="block">
                        @csrf
                        <button type="submit" class="w-full text-left text-red-600 hover:text-red-700 px-3 py-2 rounded-md font-medium">Logout</button>
                    </form>
                @else
                    <a href="/login" class="block text-blue-600 hover:text-blue-700 px-3 py-2 rounded-md font-medium">Login</a>
                    <a href="/register" class="block bg-blue-600 text-white px-3 py-2 rounded-md font-medium text-center">Register</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="min-h-screen">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gradient-to-r from-gray-800 to-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="col-span-1 md:col-span-2">
                    <img src="{{ asset('images/logo.png') }}" alt="Skillearn Logo" class="h-12 mb-4">
                    <p class="text-gray-300 mb-4 max-w-md">Platform pembelajaran online modern dengan kurasi video berkualitas tinggi. Belajar dari mana saja, kapan saja.</p>
                </div>

                <div>
                    <h3 class="text-white font-semibold mb-4">Platform</h3>
                    <ul class="space-y-2">
                        <li><a href="/videos" class="text-gray-300 hover:text-white transition-colors">Browse Videos</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-700 mt-8 pt-8 text-center">
                <p class="text-gray-400">&copy; 2025 Skillearn. Platform pembelajaran online modern untuk semua kalangan.</p>
            </div>
        </div>
    </footer>

    @stack('scripts')

    <script>
        // SweetAlert2 Global Configuration
        window.Swal = Swal.mixin({
            customClass: {
                popup: 'swal2-popup-custom',
                title: 'swal2-title-custom',
                content: 'swal2-content-custom',
                confirmButton: 'swal2-confirm-custom',
                cancelButton: 'swal2-cancel-custom',
                input: 'swal2-input-custom'
            },
            buttonsStyling: false,
            showClass: {
                popup: 'animate__animated animate__fadeInDown animate__faster'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp animate__faster'
            }
        });

        // Global SweetAlert Functions
        window.showSuccess = function(message, title = 'Berhasil!') {
            return Swal.fire({
                icon: 'success',
                title: title,
                text: message,
                confirmButtonText: 'OK',
                timer: 3000,
                timerProgressBar: true
            });
        };

        window.showError = function(message, title = 'Oops...') {
            return Swal.fire({
                icon: 'error',
                title: title,
                text: message,
                confirmButtonText: 'OK'
            });
        };

        window.showWarning = function(message, title = 'Peringatan') {
            return Swal.fire({
                icon: 'warning',
                title: title,
                text: message,
                confirmButtonText: 'OK'
            });
        };

        window.showInfo = function(message, title = 'Info') {
            return Swal.fire({
                icon: 'info',
                title: title,
                text: message,
                confirmButtonText: 'OK'
            });
        };

        window.showConfirm = function(message, title = 'Konfirmasi', confirmText = 'Ya', cancelText = 'Batal') {
            return Swal.fire({
                icon: 'question',
                title: title,
                text: message,
                showCancelButton: true,
                confirmButtonText: confirmText,
                cancelButtonText: cancelText,
                reverseButtons: true
            });
        };

        window.showDeleteConfirm = function(message = 'Data yang dihapus tidak dapat dikembalikan!', title = 'Hapus Data?') {
            return Swal.fire({
                icon: 'warning',
                title: title,
                text: message,
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#dc2626',
                reverseButtons: true
            });
        };

        window.showLoading = function(message = 'Memproses...') {
            return Swal.fire({
                title: message,
                allowEscapeKey: false,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        };

        window.showInputModal = function(title, inputPlaceholder, inputType = 'text', inputValue = '') {
            return Swal.fire({
                title: title,
                input: inputType,
                inputValue: inputValue,
                inputPlaceholder: inputPlaceholder,
                showCancelButton: true,
                confirmButtonText: 'Simpan',
                cancelButtonText: 'Batal',
                inputValidator: (value) => {
                    if (!value) {
                        return 'Input tidak boleh kosong!';
                    }
                }
            });
        };

        window.showTextareaModal = function(title, textareaPlaceholder, textareaValue = '') {
            return Swal.fire({
                title: title,
                input: 'textarea',
                inputValue: textareaValue,
                inputPlaceholder: textareaPlaceholder,
                showCancelButton: true,
                confirmButtonText: 'Simpan',
                cancelButtonText: 'Batal',
                inputValidator: (value) => {
                    if (!value) {
                        return 'Input tidak boleh kosong!';
                    }
                }
            });
        };

        window.showSelectModal = function(title, options, placeholder = 'Pilih salah satu') {
            return Swal.fire({
                title: title,
                input: 'select',
                inputOptions: options,
                inputPlaceholder: placeholder,
                showCancelButton: true,
                confirmButtonText: 'Pilih',
                cancelButtonText: 'Batal',
                inputValidator: (value) => {
                    if (!value) {
                        return 'Anda harus memilih salah satu!';
                    }
                }
            });
        };

        // Handle Laravel Session Messages
        @if(session('success'))
            showSuccess('{{ session('success') }}');
        @endif

        @if(session('error'))
            showError('{{ session('error') }}');
        @endif

        @if(session('warning'))
            showWarning('{{ session('warning') }}');
        @endif

        @if(session('info'))
            showInfo('{{ session('info') }}');
        @endif

        // CSRF Token for AJAX requests
        window.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Global AJAX Setup
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': window.csrfToken
            }
        });

        // Utility Functions for SweetAlert2
        window.showLoading = function(message = 'Loading...') {
            return Swal.fire({
                title: message,
                html: '<div class="text-center"><i class="fas fa-spinner fa-spin text-4xl text-blue-600 mb-3"></i></div>',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        };

        window.showSuccess = function(message, title = 'Berhasil!') {
            return Swal.fire({
                icon: 'success',
                title: title,
                text: message,
                confirmButtonColor: '#10b981'
            });
        };

        window.showError = function(message, title = 'Error!') {
            return Swal.fire({
                icon: 'error',
                title: title,
                text: message,
                confirmButtonColor: '#dc3545'
            });
        };

        window.showWarning = function(message, title = 'Peringatan!') {
            return Swal.fire({
                icon: 'warning',
                title: title,
                text: message,
                confirmButtonColor: '#f59e0b'
            });
        };

        window.showInfo = function(message, title = 'Info') {
            return Swal.fire({
                icon: 'info',
                title: title,
                text: message,
                confirmButtonColor: '#3b82f6'
            });
        };

        window.showDeleteConfirm = function(message = 'Data akan dihapus permanen!') {
            return Swal.fire({
                title: 'Konfirmasi Hapus',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            });
        };

        // Mobile menu toggle
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.classList.toggle('hidden');
        });

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const mobileMenu = document.getElementById('mobile-menu');
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            if (!mobileMenuButton.contains(event.target) && !mobileMenu.contains(event.target)) {
                mobileMenu.classList.add('hidden');
            }
        });
    </script>
</body>
</html>
