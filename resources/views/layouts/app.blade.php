<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem PBL Mahasiswa TI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8fafc; 
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        main {
            flex: 1; 
        }
        .navbar {
            background: linear-gradient(90deg, #1e40af, #3b82f6); 
        }
        .navbar-brand {
            font-weight: bold;
            font-size: 1.2rem;
        }
        .nav-link {
            color: #e0f2fe !important;
            font-weight: 500;
        }
        .nav-link:hover, .nav-link.active {
            color: #fff !important;
            text-decoration: underline;
        }
        footer {
            background: #1e3a8a;
            color: white;
            text-align: center;
            padding: 0.8rem;
            margin-top: auto; 
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg shadow-sm px-3">
        <a class="navbar-brand text-white" href="{{ url('/dashboard') }}">
            Sistem PBL Mahasiswa TI
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="{{ url('/mahasiswa') }}">Akun Mahasiswa</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('/dosen') }}">Dosen</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('/milestone') }}">Milestone</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('/koor') }}">Koordinator PBL</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('/ranking') }}">Ranking</a></li>
            </ul>
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
