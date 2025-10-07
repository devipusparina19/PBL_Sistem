<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem PBL Mahasiswa TI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            min-height: 100vh;
            background: #ffffff;
            color: #1e293b;
            display: flex;
            flex-direction: column;
        }

        .navbar {
            background: #001f54; /* NAVY */
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
            color: #60a5fa !important; /* hover biru muda */
        }

        /* --- Sidebar --- */
        .sidebar {
            width: 300px; /* diperlebar dari 250px */
            background: #f3f4f6;
            min-height: calc(100vh - 56px); /* tinggi penuh dikurangi navbar */
            padding-top: 25px;
            display: flex;
            flex-direction: column;
            border-right: 1px solid #e2e8f0;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 28px; /* diperlebar padding agar lebih lega */
            color: #1e293b;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.95rem;
            transition: all 0.2s ease-in-out;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: #001f54;
            color: white;
        }

        .sidebar a i {
            width: 22px;
            text-align: center;
            font-size: 1.1rem;
        }

        .sidebar-footer {
            margin-top: auto;
            background: #001f54;
            padding: 14px 28px;
        }

        .sidebar-footer a {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .content-wrapper {
            flex: 1;
            display: flex;
        }

        .main-content {
            flex: 1;
            padding: 50px;
        }

        footer {
            background: #001f54;
            padding: 0.8rem;
            text-align: center;
            color: white;
            font-size: 0.9rem;
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid px-4">
            <a class="navbar-brand fw-bold text-white" href="#">Sistem PBL Mahasiswa TI</a>
            <button class="navbar-toggler text-white" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link text-white" href="{{ url('/about') }}">About</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="{{ url('/contact') }}">Contact</a></li>
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-link nav-link text-white" style="text-decoration:none;">Log Out</button>
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
            <a href="{{ url('/home') }}" class="{{ request()->is('home') ? 'active' : '' }}">
                <i class="bi bi-house-door-fill"></i> Home
            </a>
            <a href="{{ url('/data-dosen') }}" class="{{ request()->is('data-dosen') ? 'active' : '' }}">
                <i class="bi bi-person-badge-fill"></i> Data Dosen
            </a>
            <a href="{{ url('/data-mahasiswa') }}" class="{{ request()->is('data-mahasiswa') ? 'active' : '' }}">
                <i class="bi bi-mortarboard-fill"></i> Data Mahasiswa
            </a>
            <a href="{{ url('/kelompok-pbl') }}" class="{{ request()->is('kelompok-pbl') ? 'active' : '' }}">
                <i class="bi bi-people-fill"></i> Kelompok PBL
            </a>
            <a href="{{ url('/profile') }}" class="{{ request()->is('profile') ? 'active' : '' }}">
                <i class="bi bi-person-circle me-2 fs-5"></i> Profile
            </a>
        </div>

        <!-- Konten -->
        <div class="main-content">
            @yield('content')
        </div>
    </div>

    <!-- Footer -->
    <footer>
        🌐 Sistem Penilaian Kinerja Mahasiswa & Kelompok PBL TI © {{ date('Y') }}
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</body>
</html>
