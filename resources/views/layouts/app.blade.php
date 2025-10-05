<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Home') - Sistem PBL Mahasiswa TI</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
<<<<<<< HEAD
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
            width: 300px;
            background: #f3f4f6;
            min-height: calc(100vh - 56px); /* tinggi penuh dikurangi navbar */
            padding-top: 20px;
            display: flex;
            flex-direction: column;
            border-right: 1px solid #e2e8f0;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            gap: 20px;
            padding: 12px 20px;
            color: #1e293b;
            text-decoration: none;
            font-weight: 700;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: #001f54;
            color: white;
        }

        .sidebar a i {
            width: 30px;
            text-align: center;
        }

        .sidebar-footer {
            margin-top: auto;
            background: #001f54;
            padding: 5px 10px;
        }

        .sidebar-footer a {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .content-wrapper {
            flex: 1;
            display: flex;
        }

        .main-content {
            flex: 1;
            padding: 40px;
        }

        footer {
            background: #001f54;
            padding: 0.8rem;
            text-align: center;
            color: white;
            font-size: 0.9rem;
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
=======
        * { margin:0; padding:0; box-sizing:border-box; font-family:'Poppins', sans-serif; }
        body { background-color:#f4f4f4; color:#333; }

        /* Header */
        .header { width:100%; height:65px; background-color:#0a2259; display:flex; justify-content:space-between; align-items:center; padding:0 30px; color:white; }
        .header .logo { font-weight:600; font-size:18px; }
        .header nav a, .header nav button { color:white; text-decoration:none; margin-left:25px; font-weight:500; }
        .header nav button { background:none; border:none; cursor:pointer; }

        /* Layout utama */
        .main-layout { display:flex; flex-direction:row; height:calc(100vh - 65px); overflow:hidden; }

        /* Sidebar */
        .sidebar { width:250px; background-color:#f1f1f1; display:flex; flex-direction:column; justify-content:space-between; border-top-right-radius:15px; }
        .menu { padding:20px; }
        .menu-item { display:flex; align-items:center; padding:12px 10px; margin-bottom:10px; border-radius:8px; color:#000; text-decoration:none; transition:0.3s; font-size:15px; }
        .menu-item:hover, .menu-item.active { background-color:#fff; font-weight:600; }
        .menu-item i { font-size:20px; margin-right:10px; }

        .sidebar-bottom { background-color:#0a2259; color:white; padding:15px 20px; border-bottom-left-radius:15px; display:flex; align-items:center; }
        .sidebar-bottom i { margin-right:10px; font-size:18px; }

        /* Konten utama */
        .content { flex:1; background-color:white; padding:30px; border-top-left-radius:15px; overflow-y:auto; text-align:left; }

        /* Tabel responsive */
        .table-responsive { margin-top:20px; }

        /* Footer */
        footer { background:#0a2259; color:white; text-align:center; padding:10px; }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="logo">Sistem PBL Mahasiswa TI</div>
        <nav>
            <a href="{{ url('/about') }}">About</a>
            <a href="{{ url('/contact') }}">Contact</a>
            <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit">Log Out</button>
            </form>
        </nav>
    </header>

    <div class="main-layout">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="menu">
                <a href="{{ route('home') }}" class="menu-item {{ request()->routeIs('home') ? 'active' : '' }}"><i class="bi bi-house-fill"></i> Home</a>
                <a href="{{ route('dosen.index') }}" class="menu-item {{ request()->routeIs('dosen.*') ? 'active' : '' }}"><i class="bi bi-easel2"></i> Data Dosen</a>
                <a href="{{ route('mahasiswa.index') }}" class="menu-item {{ request()->routeIs('mahasiswa.*') ? 'active' : '' }}"><i class="bi bi-mortarboard"></i> Data Mahasiswa</a>
                <a href="{{ route('kelompok.index') }}" class="menu-item {{ request()->routeIs('kelompok.*') ? 'active' : '' }}"><i class="bi bi-people"></i> Kelompok PBL</a>
            </div>
            <div class="sidebar-bottom">
                <i class="bi bi-person"></i> <a href="{{ route('profile.show') }}" style="color:white; text-decoration:none;">Profil Saya</a>
            </div>
        </aside>

        <!-- Konten -->
        <main class="content">
            @yield('content')
        </main>
>>>>>>> 77ae7f0558866fb536e7c790c66db044f33b8084
    </div>

    <footer>
        🌐 Sistem Penilaian Kinerja Mahasiswa & Kelompok PBL TI © {{ date('Y') }}
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</body>
</html>
