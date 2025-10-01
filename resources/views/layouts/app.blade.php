<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem PBL Mahasiswa TI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background: #ffffff;
            color: #1e293b;
        }
        main {
            flex: 1; 
        }
        .navbar {
            background: #001f54;  /* NAVY */
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
        .card-body {
            display: flex;             
            flex-direction: column;    /* Tata konten secara vertikal */
        }
        .card-body p.text-muted {
            min-height: 2.5em; 
            display: flex;     
            align-items: center; 
            justify-content: center; 
        }
        .card-body .btn {
            margin-top: auto;          /* Dorong tombol ke bagian bawah */ 
        }
        footer {
            background: #001f54; /* NAVY */
            padding: 0.8rem; 
            text-align: center;
            color: white; 
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg" style="background-color:#001f54;">
    <div class="container">
        <a class="navbar-brand fw-bold text-white" href="#">Sistem PBL Mahasiswa TI</a>
        <button class="navbar-toggler text-white" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link text-white {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/home') }}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ request()->is('about') ? 'active' : '' }}" href="{{ url('/about') }}">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ request()->is('contact') ? 'active' : '' }}" href="{{ url('/contact') }}">Contact</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ request()->is('profile') ? 'active' : '' }}" href="{{ route('profile.show') }}">Profil Saya</a>
                </li>
                <li class="nav-item">
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-link nav-link text-white" style="text-decoration:none;">Logout</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>
    
    <!-- Konten dinamis -->
    <main class="container mt-4">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer>
        🌐 Sistem Penilaian Kinerja Mahasiswa & Kelompok PBL TI © {{ date('Y') }}
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
