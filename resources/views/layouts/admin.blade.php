<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin Dashboard') - SkillLearn</title>

    <!-- Custom fonts for this template-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Tailwind CSS and Admin CSS -->
    @vite(['resources/css/app.css', 'resources/css/admin.css', 'resources/js/app.js'])

    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Sortable.js for drag and drop functionality -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

    <!-- Custom Admin Styles -->
    <style>
        /* Fix for potential conflicts between Bootstrap and Tailwind */
        .btn {
            text-transform: none;
        }
        .card {
            overflow: visible;
        }
        .breadcrumb-item + .breadcrumb-item::before {
            content: "/";
        }
        .table {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
            vertical-align: top;
            border-color: #dee2e6;
        }
        .table-bordered {
            border: 1px solid #dee2e6;
        }
        .table-bordered td, .table-bordered th {
            border: 1px solid #dee2e6;
        }
    </style>

    @stack('styles')
</head>

<body class="bg-gray-50 font-sans">
    <div class="flex">
        <!-- Sidebar -->
        <nav class="w-64 bg-gradient-to-br from-blue-800 via-indigo-800 to-blue-950 min-h-screen shadow-xl relative">
            <!-- Subtle pattern overlay -->
            <div class="absolute inset-0 opacity-10" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.2\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>

            <div class="p-6 relative z-10">
                <div class="text-center mb-8">
                    <div class="bg-gradient-to-r from-white/10 to-indigo-600/10 backdrop-blur-sm p-5 rounded-xl shadow-inner mb-4 border border-white/10">
                        <h2 class="text-2xl font-bold text-white">SkillLearn</h2>
                        <p class="text-blue-200 text-sm mt-1">Admin Dashboard</p>
                    </div>
                </div>

                <ul class="space-y-2">
                    <li>
                        <a class="flex items-center px-5 py-3.5 text-blue-100 hover:text-white hover:bg-white/10 hover:border-l-4 hover:border-blue-300 rounded-xl transition-all duration-300 {{ request()->routeIs('dashboard.admin') ? 'text-white bg-gradient-to-r from-blue-500/30 to-indigo-600/30 border-l-4 border-blue-300 shadow-lg shadow-blue-900/20' : '' }}"
                           href="{{ route('dashboard.admin') }}">
                            <div class="w-8 h-8 flex items-center justify-center mr-3 {{ request()->routeIs('dashboard.admin') ? 'bg-blue-500/40 text-white' : 'text-blue-200' }} rounded-lg transition-all duration-300">
                                <i class="fas fa-tachometer-alt"></i>
                            </div>
                            <span class="font-medium">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a class="flex items-center px-5 py-3.5 text-blue-100 hover:text-white hover:bg-white/10 hover:border-l-4 hover:border-blue-300 rounded-xl transition-all duration-300 {{ request()->routeIs('admin.courses.*') ? 'text-white bg-gradient-to-r from-blue-500/30 to-indigo-600/30 border-l-4 border-blue-300 shadow-lg shadow-blue-900/20' : '' }}"
                           href="{{ route('admin.courses.index') }}">
                            <div class="w-8 h-8 flex items-center justify-center mr-3 {{ request()->routeIs('admin.courses.*') ? 'bg-blue-500/40 text-white' : 'text-blue-200' }} rounded-lg transition-all duration-300">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                            <span class="font-medium">Course Management</span>
                        </a>
                    </li>
                    <li>
                        <a class="flex items-center px-5 py-3.5 text-blue-100 hover:text-white hover:bg-white/10 hover:border-l-4 hover:border-blue-300 rounded-xl transition-all duration-300 {{ request()->routeIs('admin.feedback.*') ? 'text-white bg-gradient-to-r from-blue-500/30 to-indigo-600/30 border-l-4 border-blue-300 shadow-lg shadow-blue-900/20' : '' }}"
                           href="{{ route('admin.feedback.index') }}">
                            <div class="w-8 h-8 flex items-center justify-center mr-3 {{ request()->routeIs('admin.feedback.*') ? 'bg-blue-500/40 text-white' : 'text-blue-200' }} rounded-lg transition-all duration-300">
                                <i class="fas fa-comments"></i>
                            </div>
                            <span class="font-medium">Kelola Feedback</span>
                        </a>
                    </li>
                    {{-- <li>
                        <a class="flex items-center px-5 py-3.5 text-blue-100 hover:text-white hover:bg-white/10 hover:border-l-4 hover:border-blue-300 rounded-xl transition-all duration-300 {{ request()->routeIs('admin.courses.quizzes') || request()->routeIs('admin.courses.quiz.*') || request()->routeIs('admin.courses.quizzes.*') ? 'text-white bg-gradient-to-r from-blue-500/30 to-indigo-600/30 border-l-4 border-blue-300 shadow-lg shadow-blue-900/20' : '' }}"
                           href="{{ route('admin.courses.index', ['tab' => 'quizzes']) }}">
                            <div class="w-8 h-8 flex items-center justify-center mr-3 {{ request()->routeIs('admin.courses.quizzes') || request()->routeIs('admin.courses.quiz.*') || request()->routeIs('admin.courses.quizzes.*') ? 'bg-blue-500/40 text-white' : 'text-blue-200' }} rounded-lg transition-all duration-300">
                                <i class="fas fa-question-circle"></i>
                            </div>
                            <span class="font-medium">Kelola Quiz</span>
                        </a>
                    </li> --}}
                    <li>
                        <a class="flex items-center px-5 py-3.5 text-blue-100 hover:text-white hover:bg-white/10 hover:border-l-4 hover:border-blue-300 rounded-xl transition-all duration-300 {{ request()->routeIs('admin.monitoring.*') ? 'text-white bg-gradient-to-r from-blue-500/30 to-indigo-600/30 border-l-4 border-blue-300 shadow-lg shadow-blue-900/20' : '' }}"
                           href="{{ route('admin.monitoring.index') }}">
                            <div class="w-8 h-8 flex items-center justify-center mr-3 {{ request()->routeIs('admin.monitoring.*') ? 'bg-blue-500/40 text-white' : 'text-blue-200' }} rounded-lg transition-all duration-300">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <span class="font-medium">Monitor Pembelajaran</span>
                        </a>
                    </li>
                    <li class="mt-8 pt-4 border-t border-white/10">
                        <a class="flex items-center px-5 py-3.5 text-blue-100 hover:text-white hover:bg-red-500/20 rounded-xl transition-all duration-300"
                           href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <div class="w-8 h-8 flex items-center justify-center mr-3 text-red-200 rounded-lg transition-all duration-300">
                                <i class="fas fa-sign-out-alt"></i>
                            </div>
                            <span class="font-medium">Logout</span>
                        </a>
                    </li>
                </ul>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
            </div>
        </nav>

        <!-- Main content -->
        <main class="flex-1 min-h-screen bg-gradient-to-br from-gray-50 to-blue-50">
            <!-- Top navbar -->
            <div class="bg-white backdrop-blur-sm bg-opacity-90 shadow-md border-b border-gray-200 px-6 py-3 sticky top-0 z-10">
                <div class="flex justify-between items-center">
                    <div class="flex items-center space-x-4">
                        <div class="flex flex-col">
                            <h1 class="text-xl font-semibold text-gray-800">Welcome, <span class="bg-gradient-to-r from-blue-600 to-indigo-700 text-transparent bg-clip-text font-bold">{{ Auth::user()->username ?? 'Admin' }}</span></h1>
                            <p class="text-sm text-gray-500 mt-1">Mengelola sistem pembelajaran dengan mudah</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-5">
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-4 py-2.5 rounded-lg border border-blue-100 shadow-sm">
                            <div class="text-sm font-medium text-gray-600">
                                <i class="far fa-calendar-alt mr-2 text-blue-500"></i>{{ now()->format('d M Y') }}
                            </div>
                        </div>
                        <div class="relative group">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-500 to-indigo-600 flex items-center justify-center text-white shadow-lg cursor-pointer group-hover:shadow-blue-500/50 transition-all duration-300">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="absolute top-full right-0 mt-2 bg-white rounded-lg shadow-lg p-3 w-48 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform origin-top scale-95 group-hover:scale-100">
                                <div class="px-2 py-1.5 text-sm text-gray-700">{{ Auth::user()->email ?? 'admin@example.com' }}</div>
                                <div class="border-t border-gray-100 my-1"></div>
                                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="flex items-center px-2 py-1.5 text-sm text-red-600 hover:bg-red-50 rounded-md">
                                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Page content -->
            <div class="p-8 admin-content" style="min-height: calc(100vh - 70px);">
                <!-- Breadcrumb -->
                <div class="flex items-center pb-4 mb-4 border-b border-gray-200">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">@yield('page-title', 'Dashboard')</h2>
                        <div class="flex items-center text-sm text-gray-500 mt-1">
                            <a href="{{ route('dashboard.admin') }}" class="hover:text-blue-600">Home</a>
                            <svg class="w-3 h-3 mx-2 text-gray-400" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"/>
                            </svg>
                            <span>@yield('breadcrumb', 'Dashboard')</span>
                        </div>
                    </div>
                </div>

                @if(session('success'))
                <div class="alert alert-success fade-in mb-4">
                    {{ session('success') }}
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-danger fade-in mb-4">
                    {{ session('error') }}
                </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- DataTables with Bootstrap 5 Integration -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

    <!-- CSRF Token setup for AJAX -->
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Initialize Bootstrap tooltip and popover
        $(document).ready(function() {
            // Enable Bootstrap tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Enable Bootstrap popovers
            const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
            popoverTriggerList.map(function (popoverTriggerEl) {
                return new bootstrap.Popover(popoverTriggerEl);
            });

            // Initialize DataTables with Bootstrap styling
            $('.data-table').DataTable({
                "language": {
                    "lengthMenu": "Tampilkan _MENU_ data per halaman",
                    "zeroRecords": "Data tidak ditemukan",
                    "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
                    "infoEmpty": "Tidak ada data yang tersedia",
                    "infoFiltered": "(difilter dari _MAX_ total data)",
                    "search": "Cari:",
                    "paginate": {
                        "first": "Pertama",
                        "last": "Terakhir",
                        "next": "Selanjutnya",
                        "previous": "Sebelumnya"
                    }
                },
                "pageLength": 10,
                "responsive": true
            });

            // Initialize any BS5 modals that need JS initialization
            const myModalEl = document.getElementById('addQuizModal');
            if (myModalEl) {
                const modal = new bootstrap.Modal(myModalEl);
            }

            // Enhancement for quiz statistic charts if available
            if (typeof Chart !== 'undefined' && document.getElementById('quizStatsChart')) {
                const ctx = document.getElementById('quizStatsChart').getContext('2d');

                // If there's a data attribute with JSON statistics
                const quizData = document.getElementById('quizStatsChart').getAttribute('data-stats');
                if (quizData) {
                    try {
                        const stats = JSON.parse(quizData);
                        new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: stats.labels || [],
                                datasets: [{
                                    label: 'Nilai Rata-rata Quiz',
                                    data: stats.data || [],
                                    backgroundColor: 'rgba(59, 130, 246, 0.7)',
                                    borderColor: 'rgba(59, 130, 246, 1)',
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                responsive: true,
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        max: 100
                                    }
                                },
                                plugins: {
                                    legend: {
                                        display: true,
                                        position: 'top',
                                    },
                                    tooltip: {
                                        backgroundColor: 'rgba(59, 130, 246, 0.9)',
                                        callbacks: {
                                            label: function(context) {
                                                return `Nilai: ${context.raw}%`;
                                            }
                                        }
                                    }
                                }
                            }
                        });
                    } catch (e) {
                        console.error('Error parsing quiz stats data:', e);
                    }
                }
            }
        });
    </script>

    <!-- Custom Admin Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Stagger fade-in animation for cards - adding delay for each card
            document.querySelectorAll('.admin-content .card').forEach(function(card, index) {
                card.classList.add('fade-in');
                card.style.animationDelay = (0.1 * index) + 's';
            });

            // More interactive hover effect to table rows with transition
            document.querySelectorAll('.admin-content table tbody tr').forEach(function(row) {
                row.style.transition = 'all 0.2s ease';
                row.addEventListener('mouseover', function() {
                    this.style.backgroundColor = 'rgba(243, 244, 246, 0.6)';
                    this.style.transform = 'translateX(5px)';
                    this.style.boxShadow = '0 2px 4px rgba(0,0,0,0.05)';
                });
                row.addEventListener('mouseout', function() {
                    this.style.backgroundColor = '';
                    this.style.transform = '';
                    this.style.boxShadow = '';
                });
            });

            // Add ripple effect to buttons
            document.querySelectorAll('.btn').forEach(function(button) {
                button.addEventListener('click', function(e) {
                    const ripple = document.createElement('span');
                    const rect = button.getBoundingClientRect();
                    const size = Math.max(rect.width, rect.height);
                    const x = e.clientX - rect.left - size/2;
                    const y = e.clientY - rect.top - size/2;

                    ripple.style.width = ripple.style.height = size + 'px';
                    ripple.style.left = x + 'px';
                    ripple.style.top = y + 'px';
                    ripple.classList.add('ripple');

                    button.appendChild(ripple);

                    setTimeout(() => {
                        ripple.remove();
                    }, 600);
                });
            });

            // Enhance the notification system
            function showNotification(message, type) {
                const colors = {
                    'success': '#10b981',
                    'error': '#ef4444',
                    'warning': '#f59e0b',
                    'info': '#3b82f6'
                };

                const icons = {
                    'success': '<i class="fas fa-check-circle mr-2"></i>',
                    'error': '<i class="fas fa-exclamation-circle mr-2"></i>',
                    'warning': '<i class="fas fa-exclamation-triangle mr-2"></i>',
                    'info': '<i class="fas fa-info-circle mr-2"></i>'
                };

                Swal.fire({
                    html: `<div class="flex items-center">${icons[type]}${message}</div>`,
                    icon: null,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    showCloseButton: true,
                    background: colors[type] === '#10b981' ? '#ecfdf5' :
                               colors[type] === '#ef4444' ? '#fef2f2' :
                               colors[type] === '#f59e0b' ? '#fffbeb' : '#eff6ff',
                    iconColor: colors[type],
                    customClass: {
                        popup: 'colored-toast',
                        title: `text-${type === 'success' ? 'green' : type === 'error' ? 'red' : type === 'warning' ? 'yellow' : 'blue'}-800 font-medium`
                    },
                    showClass: {
                        popup: 'animate__animated animate__fadeInDown animate__faster'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__fadeOutUp animate__faster'
                    }
                });
            }

            // Global utility functions for admin
            window.showLoading = function(message = 'Processing...') {
                Swal.fire({
                    title: message,
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            };

            window.showSuccess = function(message, title = 'Success') {
                return Swal.fire({
                    icon: 'success',
                    title: title,
                    text: message,
                    confirmButtonColor: '#10b981',
                    timer: 3000,
                    timerProgressBar: true,
                    showCloseButton: true
                });
            };

            window.showError = function(message, title = 'Error') {
                return Swal.fire({
                    icon: 'error',
                    title: title,
                    text: message,
                    confirmButtonColor: '#ef4444',
                    showCloseButton: true
                });
            };

            window.showWarning = function(message, title = 'Warning') {
                return Swal.fire({
                    icon: 'warning',
                    title: title,
                    text: message,
                    confirmButtonColor: '#f59e0b',
                    showCloseButton: true
                });
            };

            window.showInfo = function(message, title = 'Information') {
                return Swal.fire({
                    icon: 'info',
                    title: title,
                    text: message,
                    confirmButtonColor: '#3b82f6',
                    showCloseButton: true
                });
            };

            window.showDeleteConfirm = function(message, title = 'Are you sure?') {
                return Swal.fire({
                    title: title,
                    text: message,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: '<i class="fas fa-trash mr-2"></i>Yes, Delete!',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true
                });
            };

            // Set CSRF token globally
            window.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Override default alert, confirm, prompt with SweetAlert2
            window.alert = function(message) {
                Swal.fire({
                    title: 'Notice',
                    text: message,
                    icon: 'info',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#3b82f6',
                    customClass: {
                        confirmButton: 'btn btn-primary'
                    }
                });
            };

            window.confirm = function(message) {
                return new Promise((resolve) => {
                    Swal.fire({
                        title: 'Confirmation',
                        text: message,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Yes',
                        cancelButtonText: 'No',
                        confirmButtonColor: '#3b82f6',
                        cancelButtonColor: '#ef4444',
                        customClass: {
                            confirmButton: 'btn btn-primary',
                            cancelButton: 'btn btn-danger'
                        }
                    }).then((result) => {
                        resolve(result.isConfirmed);
                    });
                });
            };

            // Auto-activate any success or error alerts
            if (document.querySelector('.alert-success')) {
                const message = document.querySelector('.alert-success').innerText.trim();
                showNotification(message, 'success');
                document.querySelector('.alert-success').style.display = 'none';
            }

            if (document.querySelector('.alert-danger')) {
                const message = document.querySelector('.alert-danger').innerText.trim();
                showNotification(message, 'error');
                document.querySelector('.alert-danger').style.display = 'none';
            }

            // Add small animation to icons in sidebar on hover
            document.querySelectorAll('nav a').forEach(function(link) {
                link.addEventListener('mouseenter', function() {
                    const icon = this.querySelector('i, .w-8');
                    if (icon) {
                        icon.style.transform = 'scale(1.2)';
                        icon.style.transition = 'transform 0.3s ease';
                    }
                });
                link.addEventListener('mouseleave', function() {
                    const icon = this.querySelector('i, .w-8');
                    if (icon) {
                        icon.style.transform = '';
                    }
                });
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
