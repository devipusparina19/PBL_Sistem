<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem PBL Mahasiswa TI - Login</title>
    <style>
        /* reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
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
            display: flex;
            justify-content: space-between;
            align-items: center;
            backdrop-filter: blur(6px);
        }

        header .logo {
            font-weight: bold;
            font-size: 1.2rem;
            color: navy;
        }

        header nav a {
            margin-left: 1.5rem;
            color: navy;
            font-weight: 600;
            text-decoration: none;
        }

        /* konten utama */
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
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 1rem;
            line-height: 1.2;
        }

        .welcome-section p {
            font-size: 1.2rem;
            line-height: 1.6;
        }

        .login-card {
            background: #fff;
            padding: 2.5rem;
            border-radius: 15px;
            box-shadow: 0px 6px 20px rgba(0,0,0,0.15);
            width: 100%;
            max-width: 380px;
            text-align: center;
        }

        .login-card h3 {
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
            color: #0057e7;
        }

        .form-group {
            text-align: left;
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            font-size: 0.9rem;
            margin-bottom: 0.3rem;
            color: #333;
        }

        .form-group input {
            width: 100%;
            padding: 0.7rem;
            border: 1px solid #ccc;
            border-radius: 8px;
        }

        .login-card button {
            width: 100%;
            padding: 0.8rem;
            background: #0057e7;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            margin-top: 0.5rem;
        }

        .login-card button:hover {
            background: #0041b3;
        }

        .register-link {
            margin-top: 1rem;
            font-size: 0.9rem;
        }

        .register-link a {
            color: #0057e7;
            font-weight: bold;
            text-decoration: none;
        }

        footer {
            background: rgba(0,0,0,0.2);
            padding: 0.8rem;
            text-align: center;
            color: white;
            font-size: 0.9rem;
        }

        .error {
            color: red;
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">Sistem PBL Mahasiswa TI</div>
        <nav>
            <a href="#">Home</a>
            <a href="#">About</a>
            <a href="#">Contact</a>
        </nav>
    </header>

    <main>
        <section class="welcome-section">
            <h2>Selamat Datang</h2>
            <p>
                Sistem Penilaian Kinerja Mahasiswa <br>
                & Kelompok <strong>PBL TI</strong>
            </p>
        </section>

        <section class="login-card">
            <h3>Login</h3>

            {{-- tampilkan error jika login gagal --}}
            @if($errors->any())
                <div class="error">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('user.login') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>
                <button type="submit">Login</button>
                <p class="register-link">
                    Belum punya akun? <a href="{{ route('user.showRegister') }}">Daftar sekarang</a>
                </p>
            </form>
        </section>
    </main>

    <footer>
        🌐 Sistem Penilaian Kinerja Mahasiswa & Kelompok PBL TI © {{ date('Y') }}
    </footer>
</body>
</html>
