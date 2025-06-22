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

    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
      <!-- DataTables CSS (compatible with Tailwind) -->
    <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    @stack('styles')
</head>

<body class="bg-gray-50 font-sans">
    <div class="flex">
        <!-- Sidebar -->
        <nav class="w-64 bg-gradient-to-b from-blue-600 to-blue-800 min-h-screen shadow-lg">
            <div class="p-6">
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-bold text-white">SkillLearn</h2>
                    <p class="text-blue-200 text-sm">Admin Panel</p>
                </div>
                
                <ul class="space-y-2">                    <li>
                        <a class="flex items-center px-4 py-3 text-blue-100 hover:text-white hover:bg-white hover:bg-opacity-10 rounded-lg transition-all duration-200 {{ request()->routeIs('dashboard.admin') ? 'text-white bg-white bg-opacity-20' : '' }}" 
                           href="{{ route('dashboard.admin') }}">
                            <i class="fas fa-tachometer-alt mr-3 w-5"></i>
                            Dashboard
                        </a>
                    </li>                    <li>
                        <a class="flex items-center px-4 py-3 text-blue-100 hover:text-white hover:bg-white hover:bg-opacity-10 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.courses.*') ? 'text-white bg-white bg-opacity-20' : '' }}" 
                           href="{{ route('admin.courses.index') }}">
                            <i class="fas fa-graduation-cap mr-3 w-5"></i>
                            Course Management
                        </a>
                    </li>
                    <li>
                        <a class="flex items-center px-4 py-3 text-blue-100 hover:text-white hover:bg-white hover:bg-opacity-10 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.feedback.*') ? 'text-white bg-white bg-opacity-20' : '' }}" 
                           href="{{ route('admin.feedback.index') }}">
                            <i class="fas fa-comments mr-3 w-5"></i>
                            Kelola Feedback
                        </a>
                    </li>
                    <li>
                        <a class="flex items-center px-4 py-3 text-blue-100 hover:text-white hover:bg-white hover:bg-opacity-10 rounded-lg transition-all duration-200 {{ request()->routeIs('courses.*') ? 'text-white bg-white bg-opacity-20' : '' }}" 
                           href="{{ route('courses.index') }}?admin_mode=1">
                            <i class="fas fa-eye mr-3 w-5"></i>
                            Monitor Pembelajaran
                            <span class="ml-auto bg-yellow-500 text-xs px-2 py-1 rounded-full text-black font-medium">ADMIN</span>
                        </a>
                    </li>
                    <li>
                        <a class="flex items-center px-4 py-3 text-blue-100 hover:text-white hover:bg-white hover:bg-opacity-10 rounded-lg transition-all duration-200" 
                           href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt mr-3 w-5"></i>
                            Logout
                        </a>
                    </li>
                </ul>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
            </div>
        </nav>

        <!-- Main content -->
        <main class="flex-1 min-h-screen">
            <!-- Top navbar -->
            <div class="bg-white shadow-sm border-b border-gray-200 px-6 py-4">
                <div class="flex justify-between items-center">
                    <div class="flex items-center">
                        <h1 class="text-xl font-semibold text-gray-800">Welcome, {{ Auth::user()->username ?? 'Admin' }}</h1>
                    </div>                    <div class="flex space-x-3">
                        <div class="text-sm text-gray-500">
                            Admin Panel - {{ now()->format('d M Y') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Page content -->
            <div class="p-6">
                @yield('content')
            </div>
        </main>
    </div>    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

    <!-- CSRF Token setup for AJAX -->
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Initialize DataTables with Tailwind styling
        $(document).ready(function() {
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
        });
    </script>

    @stack('scripts')
</body>
</html>
