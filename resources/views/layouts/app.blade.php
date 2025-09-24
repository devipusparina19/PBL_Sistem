<!-- layouts.app -->
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard PBL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark px-3">
        <a class="navbar-brand text-white" href="#">Dashboard PBL</a>
        <div class="collapse navbar-collapse justify-content-end">
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
    <div class="container mt-4">
        @yield('content')
    </div>
</body>
</html>
