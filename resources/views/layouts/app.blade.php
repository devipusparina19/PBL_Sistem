<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistem PBL Mahasiswa TI')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
    html, body {
        height: 100%;
        background: #ffffff;
        color: #1e293b;
        display: flex;
        flex-direction: column;
    }

    .navbar {
        background: #001f54;
    }

    .navbar-brand {
        font-weight: bold;
        font-size: 1.2rem;
        color: white !important;
    }

    .nav-link {
        color: white !important;
        font-weight: 600;
    }

    .nav-link:hover, .nav-link.active {
        text-decoration: underline;
        color: #60a5fa !important;
    }

    /* ==========================
       SIDEBAR STYLING BARU
    =========================== */
    .sidebar {
        width: 300px;
        background: #0a1a40;
        min-height: calc(100vh - 56px);
        padding-top: 0;
        display: flex;
        flex-direction: column;
        border-right: 1px solid #1a2c55;
        box-shadow: 3px 0 6px rgba(0, 0, 0, 0.1);
    }

    .sidebar-header {
        background: #001f54;
        color: #fff;
        font-weight: 700;
        font-size: 1.1rem;
        padding: 20px 25px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 1px solid #1a2c55;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .sidebar a {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 14px 26px;
        color: #e5e7eb;
        text-decoration: none;
        font-weight: 500;
        font-size: 1rem;
        transition: all 0.25s ease-in-out;
    }

    .sidebar a i {
        width: 22px;
        text-align: center;
        font-size: 1.2rem;
        color: #93c5fd;
        transition: color 0.2s ease-in-out;
    }

    .sidebar a:hover {
        background: #1e3a8a;
        color: #fff;
        padding-left: 30px;
    }

    .sidebar a:hover i {
        color: #fff;
    }

    .sidebar a.active {
        background: #1d4ed8;
        color: #fff;
        font-weight: 600;
        border-left: 5px solid #60a5fa;
    }

    .sidebar a.active i {
        color: #fff;
    }

    .content-wrapper {
        flex: 1;
        display: flex;
    }

    .main-content {
        flex: 1;
        padding: 40px;
        background-color: #f9fafb;
    }

    footer {
        background: #001f54;
        padding: 0.8rem;
        text-align: center;
        color: white;
        font-size: 0.9rem;
        margin-top: auto;
    }

    .table thead.bg-dark th {
        background-color: #212529 !important;
        color: #ffffff !important;
    }

    .table.table-striped tbody tr:nth-of-type(odd) {
        background-color: #ffffff !important;
    }

    .table.table-striped tbody tr:nth-of-type(even) {
        background-color: #f8f9fa !important;
    }

    .table.table-striped tbody tr:hover {
        background-color: #e9ecef !important;
        transition: background-color 0.2s ease-in-out;
    }

    .card {
        border: none !important;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    }

    .table th, .table td {
        vertical-align: middle !important;
    }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid px-4">
            <a class="navbar-brand fw-bold text-white" href="#">Sistem PBL Mahasiswa TI</a>
            <button class="navbar-toggler text-white" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link text-white" href="{{ url('/about') }}">About</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="{{ url('/contact') }}">Contact</a></li>
                    <li class="nav-item">
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-link nav-link text-white px-0" style="text-decoration:none;">
                                <i class="bi bi-box-arrow-right"></i> Log Out
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Layout 2 kolom -->
    <div class="content-wrapper">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <i class="bi bi-mortarboard-fill me-2"></i> Menu Utama
            </div>

            <a href="{{ url('/home') }}" class="{{ request()->is('home') ? 'active' : '' }}">
                <i class="bi bi-house-door-fill"></i> Home
            </a>

            {{-- hanya tampil jika role mahasiswa --}}
            @if(auth()->user() && auth()->user()->role === 'mahasiswa')
                <a href="{{ url('dashboard/kelompok') }}" class="{{ request()->is('dashboard/kelompok') ? 'active' : '' }}">
                    <i class="bi bi-people-fill"></i> Dashboard Kelompok
                </a>
            @endif

            <a href="{{ url('/data_akademik') }}" class="{{ request()->is('data_akademik*') ? 'active' : '' }}">
                <i class="bi bi-collection-fill"></i> Data Akademik
            </a>
            <a href="{{ url('/kelompok') }}" class="{{ request()->is('kelompok') ? 'active' : '' }}">
                <i class="bi bi-people"></i> Kelompok PBL
            </a>
            <a href="{{ url('/profile') }}" class="{{ request()->is('profile') ? 'active' : '' }}">
                <i class="bi bi-person-circle"></i> Profile
            </a>
        </div>

        <!-- Konten -->
        <div class="main-content">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @elseif (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <footer>
        🌐 Sistem Penilaian Kinerja Mahasiswa & Kelompok PBL TI © {{ date('Y') }}
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
