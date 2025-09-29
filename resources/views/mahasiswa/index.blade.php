<!-- resources/views/mahasiswa/index.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Mahasiswa</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            padding: 30px;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        a {
            color: #2563eb;
            text-decoration: none;
            font-weight: bold;
        }
        a:hover {
            text-decoration: underline;
        }

        .btn {
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
            border: none;
        }
        .btn-add {
            background: #2563eb;
            color: white;
            margin-bottom: 15px;
            display: inline-block;
        }
        .btn-add:hover {
            background: #1e40af;
        }
        .btn-danger {
            background: #dc2626;
            color: white;
        }
        .btn-danger:hover {
            background: #991b1b;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
            border-radius: 8px;
            overflow: hidden;
        }

        thead {
            background: #2563eb;
            color: white;
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }

        tr:hover {
            background: #f9fafb;
        }

        .aksi a {
            margin-right: 8px;
            color: #2563eb;
        }
        .aksi a:hover {
            color: #1e40af;
        }
    </style>
</head>
<body>
    <h1> Daftar Mahasiswa</h1>

    @if(session('success'))
        <p style="color: green; text-align:center;">{{ session('success') }}</p>
    @endif

    <a href="{{ route('mahasiswa.create') }}" class="btn btn-add">+ Tambah Mahasiswa</a>

    <table>
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
                <td class="aksi">
                    <a href="{{ route('mahasiswa.show', $m->id) }}">Lihat</a>
                    <a href="{{ route('mahasiswa.edit', $m->id) }}">Edit</a>
                    <form action="{{ route('mahasiswa.destroy', $m->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin hapus?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
