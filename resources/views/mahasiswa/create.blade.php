<h1>Tambah Mahasiswa</h1>

<form action="{{ route('mahasiswa.store') }}" method="POST">
    @csrf
    <label>NIM</label>
    <input type="text" name="nim" required><br>

    <label>Nama</label>
    <input type="text" name="nama" required><br>

    <label>Angkatan</label>
    <input type="text" name="angkatan" required><br>

    <label>Email</label>
    <input type="email" name="email" required><br>

    <label>Password</label>
    <input type="password" name="password" required><br>

    <button type="submit">Simpan</button>
    <a href="{{ route('mahasiswa.index') }}">Kembali</a>
</form>
