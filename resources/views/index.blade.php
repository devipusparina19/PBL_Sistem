<h1>Daftar Mahasiswa</h1>
<a href="{{ route('mahasiswa.create') }}">Tambah Mahasiswa</a>

@if (session('success'))
    <p>{{ session('success') }}</p>
@endif

<table border="1">
    <tr>
        <th>No</th>
        <th>NIM</th>
        <th>Nama</th>
        <th>Angkatan</th>
        <th>Email</th>
        <th>Aksi</th>
    </tr>
    @foreach ($mahasiswas as $mhs)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $mhs->nim }}</td>
        <td>{{ $mhs->nama }}</td>
        <td>{{ $mhs->angkatan }}</td>
        <td>{{ $mhs->email }}</td>
        <td>
            <a href="{{ route('mahasiswa.show', $mhs) }}">Lihat</a>
            <a href="{{ route('mahasiswa.edit', $mhs) }}">Edit</a>
            <form action="{{ route('mahasiswa.destroy', $mhs) }}" method="POST" style="display:inline">
                @csrf
                @method('DELETE')
                <button type="submit" onclick="return confirm('Yakin hapus data ini?')">Delete</button>
            </form>
        </td>
    </tr>
    @endforeach
</table>

{{ $mahasiswas->links() }}
