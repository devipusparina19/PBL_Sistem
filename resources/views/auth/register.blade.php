<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register - Sistem PBL Mahasiswa TI</title>
<style>
/* Reset & font */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: Arial, sans-serif;
}
body {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  background:
    linear-gradient(rgba(0, 25, 75, 0.45), rgba(0, 25, 75, 0.45)),
    url('background-pbl.jpg') no-repeat center center/cover;
  color: #ffffff;
}

/* Header */
header {
  width: 100%;
  padding: 1rem 5%;
  background: rgba(0, 31, 84, 0.8);
  backdrop-filter: blur(6px);
  display: flex;
  justify-content: space-between;
  align-items: center;
  box-shadow: 0 2px 10px rgba(0,0,0,0.25);
}
header .logo { font-weight: bold; font-size: 1.2rem; }

/* Main layout */
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
  text-shadow: 1px 1px 4px rgba(0,0,0,0.5);
}
.welcome-section h2 { font-size: 3rem; margin-bottom: 1rem; line-height:1.2; }
.welcome-section p { font-size: 1.5rem; line-height:1.6; }

/* Register card */
.register-card {
  background: rgba(255,255,255,0.98);
  backdrop-filter: saturate(180%) brightness(1.05);
  padding: 1.8rem; 
  border-radius: 18px;
  box-shadow: 0 8px 25px rgba(0,0,0,0.18);
  width: 80%;
  max-width: 360px;
  text-align: center;
  color: #1e293b;
  border: 1px solid rgba(255,255,255,0.7);
  transition: transform 0.3s ease, box-shadow 0.3s ease;

  max-height: 500px; /* tinggi tetap */
  overflow-y: auto; /* scroll internal */
}
.register-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 10px 28px rgba(0,0,0,0.25);
}
.register-card h3 { margin-bottom: 1.2rem; font-size: 1.5rem; color:#0057e7; }

/* Form */
.form-group { text-align: left; margin-bottom: 1rem; }
.form-group label { display: block; font-size: 0.9rem; margin-bottom:0.3rem; color:#333; }
.form-group input, .form-group select {
  width: 100%; padding:0.8rem; border-radius:8px; border:1px solid #ccc;
  font-size:0.9rem; color:#1e293b;
}
.register-card button {
  width:100%; padding:0.8rem; margin-top:0.6rem;
  background:#0057e7; color:white; border:none; border-radius:8px;
  font-weight:bold; font-size:1rem; cursor:pointer; transition:0.3s;
}
.register-card button:hover { background:#0041b3; }
.login-link { margin-top:0.8rem; font-size:0.85rem; }
.login-link a { color:#0057e7; font-weight:bold; text-decoration:none; }
.login-link a:hover { text-decoration:underline; }

/* Footer */
footer {
  background: rgba(0, 31, 84, 0.85);
  padding: 0.8rem; text-align:center; font-size:0.9rem;
  box-shadow: 0 -2px 8px rgba(0,0,0,0.25);
}

/* Field Mahasiswa animasi */
#mahasiswa-fields {
  opacity: 0;
  max-height: 0;
  overflow: hidden;
  transition: opacity 0.3s ease, max-height 0.3s ease;
}
#mahasiswa-fields.show {
  opacity: 1;
  max-height: 300px; /* sesuaikan jumlah field */
}

/* Scrollbar kecil opsional */
.register-card::-webkit-scrollbar { width:6px; }
.register-card::-webkit-scrollbar-thumb { background: rgba(0,0,0,0.2); border-radius:3px; }
</style>
</head>
<body>

<header>
  <div class="logo">Sistem PBL Mahasiswa TI</div>
</header>

<main>
  <section class="welcome-section">
    <h2>Daftar Akun</h2>
    <p>
      Silahkan daftar akun untuk mengakses <br>
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
    <label>Nim/Nip</label>
    <input type="text" name="nim_nip" required>
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
    <select name="role" id="role" required>
      <option value="">-- Pilih Role --</option>
      <option value="mahasiswa">Mahasiswa</option>
      <option value="dosen">Dosen</option>
      <option value="admin">Admin</option>
      <option value="koordinator_pbl">Koordinator PBL</option>
      <option value="koordinator_prodi">Koordinator Prodi</option>
    </select>
  </div>

  <!-- Field Mahasiswa -->
   <div class="form-group mt-3" id="kelasGroup" style="display: none;">
    <label for="kelas">Kelas</label>
    <select name="kelas" id="kelas" class="form-control">
        <option value="">Pilih Kelas</option>
        <option value="3A">3A</option>
        <option value="3B">3B</option>
        <option value="3C">3C</option>
        <option value="3D">3D</option>
        <option value="3E">3E</option>
    </select>
</div>

  <div id="mahasiswa-fields">
    <div class="form-group">
      <label>Kelompok</label>
      <select name="role_kelompok">
        <option value="">-- Pilih Kelompok --</option>
        <option value="1">Kelompok 1</option>
        <option value="2">Kelompok 2</option>
        <option value="3">Kelompok 3</option>
        <option value="4">Kelompok 4</option>
        <option value="5">Kelompok 5</option>
        <option value="6">Kelompok 6</option>
      </select>
    </div>
    <div class="form-group">
      <label>Role di Kelompok</label>
      <select name="role_di_kelompok">
        <option value="">-- Pilih Role --</option>
        <option value="ketua">Ketua</option>
        <option value="anggota">Anggota</option>
      </select>
    </div>
  </div>

  <button type="submit">Daftar</button>

  <p class="login-link">
    Sudah punya akun? <a href="{{ route('login') }}">Login sekarang</a>
  </p>
</form>

  </section>
</main>

<footer>
  🌐 Sistem Penilaian Kinerja Mahasiswa & Kelompok © {{ date('Y') }}
</footer>

<script>
const roleSelect = document.getElementById('role');
const mahasiswaFields = document.getElementById('mahasiswa-fields');

roleSelect.addEventListener('change', function() {
    if(this.value === 'mahasiswa') {
        mahasiswaFields.classList.add('show');
        mahasiswaFields.querySelectorAll('select').forEach(f => f.required = true);
    } else {
        mahasiswaFields.classList.remove('show');
        mahasiswaFields.querySelectorAll('select').forEach(f => f.required = false);
    }
});

// ✅ Tambahan khusus untuk menampilkan dropdown kelas
const kelasGroup = document.getElementById('kelasGroup'); // pastikan id="kelasGroup" di div kelas kamu
const kelasSelect = document.getElementById('kelas'); // pastikan id="kelas" di select kelas kamu

roleSelect.addEventListener('change', function() {
    if (this.value === 'mahasiswa') {
        kelasGroup.style.display = 'block';
        kelasSelect.required = true;
    } else {
        kelasGroup.style.display = 'none';
        kelasSelect.required = false;
        kelasSelect.value = ''; // reset kalau bukan mahasiswa
    }
});

// Jalankan saat pertama kali halaman dimuat
document.addEventListener('DOMContentLoaded', function() {
    if (roleSelect.value === 'mahasiswa') {
        kelasGroup.style.display = 'block';
    } else {
        kelasGroup.style.display = 'none';
    }
});
</script>
</body>
</html>
