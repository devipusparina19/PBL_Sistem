<h1>Detail Mahasiswa</h1>
<p>NIM: {{ $mahasiswa->nim }}</p>
<p>Nama: {{ $mahasiswa->nama }}</p>
<p>Kelas: {{ $mahasiswa->kelas }}</p>
<p>Angkatan: {{ $mahasiswa->angkatan }}</p>
<p>Email: {{ $mahasiswa->email }}</p>

<a href="{{ route('mahasiswa.index') }}">Kembali</a>
<a href="{{ route('mahasiswa.edit', $mahasiswa) }}">Edit</a>
