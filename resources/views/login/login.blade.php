<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sistem PBL Mahasiswa TI</title>
  <style>
    * {
        margin: 0; padding: 0; box-sizing: border-box;
        font-family: Arial, sans-serif;
    }
    body {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
        background: url('background-pbl.jpg') no-repeat center center fixed;
        background-size: cover;
        color: white;
        position: relative;
        overflow: hidden;
    }

    /* Blur ringan agar foto tetap kelihatan */
    body::before {
        content: "";
        position: fixed;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background: inherit;
        filter: blur(4px) brightness(0.85);
        z-index: -1;
    }

    header {
        width: 100%;
        padding: 1rem 5%;
        background: rgba(0, 31, 84, 0.85);
        display: flex; 
        justify-content: space-between; 
        align-items: center;
        color: white;
        box-shadow: 0 2px 8px rgba(0,0,0,0.25);
    }
    header .logo { font-weight: bold; font-size: 1.2rem; color: white; }

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
        color: white;
        text-shadow: 0 2px 6px rgba(0,0,0,0.5);
    }
    .welcome-section h2 {
        font-size: 3rem; font-weight: 800;
        margin-bottom: 1rem; line-height: 1.2;
    }
    .welcome-section p {
        font-size: 1.2rem; line-height: 1.6;
    }

    /* login card */
    .login-card {
      background: rgba(255, 255, 255, 0.93);
      padding: 2.5rem;
      border-radius: 15px;
      box-shadow: 0 8px 25px rgba(0,0,0,0.25);
      width: 100%;
      max-width: 380px;
      text-align: center;
      color: #1e293b;
    }

    .login-card h3 {
      margin-bottom: 1.5rem;
      font-size: 1.6rem;
      color: #003399;
      font-weight: 700;
    }

    .error-message {
      color: red;
      font-size: 0.9rem;
      margin-bottom: 0.8rem;
      text-align: center;
    }

    .form-group {
      text-align: left;
      margin-bottom: 1rem;
    }

    .form-group label {
      display: block;
      font-size: 0.9rem;
      margin-bottom: 0.3rem;
      color: #111827;
      font-weight: 500;
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
      transition: 0.3s;
    }

    .login-card button:hover {
      background: #0041b3;
      transform: scale(1.02);
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

    /* footer */
    footer {
      background: rgba(0, 31, 84, 0.85);
      padding: 0.8rem;
      text-align: center;
      color: white;
      font-size: 0.9rem;
      box-shadow: 0 -3px 10px rgba(0,0,0,0.2);
    }

    /* responsif */
    @media (max-width: 768px) {
      main {
        flex-direction: column;
        text-align: center;
      }

      .welcome-section {
        max-width: 100%;
      }
    }
  </style>
</head>
<body>
  <!-- header -->
  <header>
    <div class="logo fw-bold text-white" href="#">Sistem PBL Mahasiswa TI</div>
    <div class="menu-toggle" id="menu-toggle">
      <span></span>
      <span></span>
      <span></span>
    </div>
  </header>

  <!-- konten utama -->
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

      <!-- tampilkan error global -->
      @if(session('error'))
        <p class="error-message">{{ session('error') }}</p>
      @endif

      <form action="{{ route('user.login') }}" method="POST">
        @csrf
        <div class="form-group">
          <label>Email</label>
          <input type="email" name="email" value="{{ old('email') }}" required>
          @error('email')
            <p class="error-message">{{ $message }}</p>
          @enderror
        </div>
        <div class="form-group">
          <label>Password</label>
          <input type="password" name="password" required>
          @error('password')
            <p class="error-message">{{ $message }}</p>
          @enderror
        </div>
        <button type="submit">Login</button>
        <p class="register-link">
          Belum punya akun? <a href="{{ route('user.showRegister') }}">Daftar sekarang</a>
        </p>
      </form>
    </section>
  </main>

  <!-- footer -->
  <footer>
    🌐 Sistem Penilaian Kinerja Mahasiswa & Kelompok © {{ date('Y') }}
  </footer>
</body>
</html>
