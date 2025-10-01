<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sistem PBL Mahasiswa TI</title>
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
      background: #ffffff; /* putih bersih */
      color: #1e293b;
    }

    /* header */
    header {
      width: 100%;
      padding: 1rem 5%;
      background: #001f54; /* NAVY */
      display: flex;
      justify-content: space-between;
      align-items: center;
      color: white;
      position: relative;
    }

    header .logo {
      font-weight: bold;
      font-size: 1.2rem;
      color: white;
    }

    header nav {
      display: flex;
      gap: 1.5rem;
    }

    header nav a {
      color: white;
      font-weight: 600;
      text-decoration: none;
    }

    header nav a:hover {
      text-decoration: underline;
      color: #60a5fa;
    }

    /* hamburger button */
    .menu-toggle {
      display: none;
      flex-direction: column;
      cursor: pointer;
    }

    .menu-toggle span {
      height: 3px;
      width: 25px;
      background: white;
      margin: 4px 0;
      border-radius: 2px;
    }

    /* konten utama */
    main {
      flex: 1;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 2rem 8%;
      flex-wrap: wrap;
      gap: 2rem;
    }

    .welcome-section {
      max-width: 450px;
      color: #0a2a66;
    }

    .welcome-section h2 {
      font-size: 2.5rem;
      font-weight: 800;
      margin-bottom: 1rem;
      line-height: 1.2;
    }

    .welcome-section p {
      font-size: 1.1rem;
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

    /* footer */
    footer {
      background: #001f54; /* NAVY */
      padding: 0.8rem;
      text-align: center;
      color: white;
      font-size: 0.9rem;
    }

    /* responsif */
    @media (max-width: 768px) {
      header nav {
        display: none;
        flex-direction: column;
        background: #001f54;
        position: absolute;
        top: 60px;
        right: 5%;
        padding: 1rem;
        border-radius: 8px;
      }

      header nav.active {
        display: flex;
      }

      .menu-toggle {
        display: flex;
      }

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
    <div class="logo">Sistem PBL Mahasiswa TI</div>
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
      <form action="{{ route('user.login') }}" method="POST">
        @csrf
        <div class="form-group">
          <label>Email</label>
          <input type="email" name="email" required>
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

  <!-- footer -->
  <footer>
    🌐 Sistem Penilaian Kinerja Mahasiswa & Kelompok © {{ date('Y') }}
  </footer>

  <!-- script toggle menu -->
  <script>
    const toggle = document.getElementById('menu-toggle');
    const navbar = document.getElementById('navbar');

    toggle.addEventListener('click', () => {
      navbar.classList.toggle('active');
    });
  </script>
</body>
</html>
