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
            background: #60a5fa;  
            backdrop-filter: blur(6px);
        }
        .navbar-brand {
            font-weight: bold;
            font-size: 1.2rem;
            color: navy !important;
        }
        .nav-link {
            color: navy !important;
            font-weight: 600;
        }
        .nav-link:hover, .nav-link.active {
            text-decoration: underline;
            color: navy !important;
        }
        footer {
            background: #60a5fa;
            padding: 0.8rem; 
            text-align: center;
            color: white; 
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg shadow-sm px-3">
        <a class="navbar-brand" href="{{ url('/dashboard') }}">
            Sistem PBL Mahasiswa TI
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="{{ url('/') }}">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('/about') }}">About</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('/contact') }}">Contact</a></li>
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
