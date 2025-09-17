<h1>Edit Mahasiswa</h1>

<form action="{{ route('mahasiswa.update', $mahasiswa) }}" method="POST">
    @csrf
    @method('PUT')

    <label>NIM</label>
    <input type="text" name="nim" value="{{ $mahasiswa->nim }}" readonly><br>

    <label>Nama</label>
    <input type="text" name="nama" value="{{ $mahasiswa->nama }}" required><br>

    <label>Angkatan</label>
    <input type="text" name="angkatan" value="{{ $mahasiswa->angkatan }}" required><br>

    <label>Email</label>
    <input type="email" name="email" value="{{ $mahasiswa->email }}" required><br>

    <label>Password (isi kalau mau ganti)</label>
    <input type="password" name="password"><br>

    <button type="submit">Update</button>
    <a href="{{ route('mahasiswa.index') }}">Kembali</a>
</form>
