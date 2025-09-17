<!-- resources/views/mahasiswa/index.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Daftar Mahasiswa</title>
</head>
<body>
    <h1>Daftar Mahasiswa</h1>

    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    <a href="{{ route('mahasiswa.create') }}">Tambah Mahasiswa</a>

    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>NIM</th>
                <th>Nama</th>
                <th>Angkatan</th>
                <th>Email</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($mahasiswas as $m)
            <tr>
                <td>{{ $m->nim }}</td>
                <td>{{ $m->nama }}</td>
                <td>{{ $m->angkatan }}</td>
                <td>{{ $m->email }}</td>
                <td>
                    <a href="{{ route('mahasiswa.show', $m->id) }}">Lihat</a> |
                    <a href="{{ route('mahasiswa.edit', $m->id) }}">Edit</a> |
                    <form action="{{ route('mahasiswa.destroy', $m->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Yakin hapus?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
