<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem PBL Mahasiswa TI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* Background halaman mirip register.blade.php */
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background: #ffffff; /* putih bersih */
            color: #1e293b;
        }
     
        main {
            flex: 1; 
        }

        /* Navbar mirip header register.blade.php */
        .navbar {
            background: #60a5fa;  
            backdrop-filter: blur(6px);        /* efek blur */
        }

        .navbar-brand {
            font-weight: bold;
            font-size: 1.2rem;
            color: navy !important;            /* warna teks logo */
        }

        .nav-link {
            color: navy !important;            /* warna teks menu */
            font-weight: 600;
        }

        .nav-link:hover, .nav-link.active {
            text-decoration: underline;
            color: navy !important;
        }

        /* Footer mirip register.blade.php */
        footer {
            background: #60a5fa; /* samakan dengan header */
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
                <li class="nav-item"><a class="nav-link" href="{{ url('/mahasiswa') }}">Akun Mahasiswa</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('/kelompok') }}">Kelompok</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('/dosen') }}">Dosen</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('/milestones') }}">Milestones</a></li>
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
