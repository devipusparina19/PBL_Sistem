<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Sistem PBL</title>
    <style>
        * {
            margin: 0; padding: 0; box-sizing: border-box;
            font-family: Arial, sans-serif;
        }
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background: linear-gradient(to bottom right, #dbeafe, #60a5fa);
        }
        header {
            width: 100%;
            padding: 1rem 5%;
            background: rgba(255,255,255,0.3);
            display: flex; justify-content: space-between; align-items: center;
            backdrop-filter: blur(6px);
        }
        header .logo { font-weight: bold; font-size: 1.2rem; color: navy; }
        header nav a {
            margin-left: 1.5rem;
            color: navy; font-weight: 600; text-decoration: none;
        }
        header nav a:hover { color: navy; }

        main {
            flex: 1;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 8%;
        }

        .welcome-section {
            max-width: 450px;
            text-align: left;
            color: #0a2a66;
        }
        .welcome-section h2 {
            font-size: 3rem; font-weight: 800;
            margin-bottom: 1rem; line-height: 1.2;
        }
        .welcome-section p {
            font-size: 1.2rem; line-height: 1.6;
        }

        .register-card {
            background: #fff;
            padding: 1.5rem; 
            border-radius: 15px;
            box-shadow: 0px 6px 20px rgba(0,0,0,0.15);
            width: 80%;
            max-width: 360px; 
            text-align: center;
        }

        .register-card h3 {
            margin-bottom: 1.2rem; 
            font-size: 1.5rem;
            color: #0057e7;
        }

        .form-group {
            text-align: left;
            margin-bottom: 1rem; 
        }
        .form-group label {
            display: block; font-size: 0.9rem;
            margin-bottom: 0.3rem; color: #333;
        }
        .form-group input, .form-group select {
            width: 100%; padding: 0.8rem; 
            border: 1px solid #ccc; border-radius: 8px;
            font-size: 0.9rem;
        }

        .register-card button {
            width: 100%; padding: 0.8rem; 
            background: #0057e7; color: white;
            border: none; border-radius: 8px;
            font-size: 1rem; font-weight: bold;
            cursor: pointer; margin-top: 0.6rem;
        }
        .register-card button:hover { background: #0041b3; }

        .login-link { margin-top: 0.8rem; font-size: 0.85rem; }
        .login-link a { color: #0057e7; font-weight: bold; text-decoration: none; }

        footer {
            background: rgba(0,0,0,0.2);
            padding: 0.8rem; text-align: center;
            color: white; font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">Sistem PBL</div>
        <nav>
            <a href="#">Home</a>
            <a href="#">About</a>
            <a href="#">Contact</a>
        </nav>
    </header>

    <main>
        <section class="welcome-section">
            <h2>Daftar Akun</h2>
            <p>
                Silahkan Daftar akun untuk mengakses <br>
                Sistem Penilaian Kinerja Mahasiswa <br>
                & Kelompok <strong>PBL TI</strong>
            </p>
        </section>

        <section class="register-card">
            <h3>Register</h3>

            @if ($errors->any())
                <div style="background:#fee2e2;color:#991b1b;padding:0.8rem;border-radius:8px;text-align:left;margin-bottom:1rem;">
                    <ul style="margin:0;padding-left:1.2rem;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('user.register') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Nama</label>
                    <input type="text" name="name" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>
                <div class="form-group">
                    <label>Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" required>
                </div>
                <div class="form-group">
                    <label>Role</label>
                    <select name="role" required>
                        <option value="">-- Pilih Role --</option>
                        <option value="mahasiswa">Mahasiswa</option>
                        <option value="dosen">Dosen</option>
                        <option value="admin">Admin</option>
                        <option value="koordinator_pbl">Koordinator PBL</option>
                        <option value="koordinator_prodi">Koordinator Prodi</option>
                    </select>
                </div>
                <button type="submit">Daftar</button>
                <p class="login-link">
                    Sudah punya akun? <a href="{{ route('user.showLogin') }}">Login di sini</a>
                </p>
            </form>
        </section>
    </main>

    <footer>
        🌐 Sistem PBL Mahasiswa TI
    </footer>
</body>
</html>