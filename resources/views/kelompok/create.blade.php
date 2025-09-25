<!-- resources/views/kelompok/create.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Tambah Kelompok</title>
</head>
<body>
    <h1>Tambah Kelompok</h1>

    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('kelompok.store') }}" method="POST">
        @csrf
        <label>Nama Kelompok:</label><br>
        <input type="text" name="nama_kelompok" value="{{ old('nama_kelompok', $kelompok->nama_kelompok ?? '') }}">

        <label>Deskripsi:</label><br>
        <textarea name="deskripsi">{{ old('deskripsi') }}</textarea><br><br>

        <button type="submit">Simpan</button>
    </form>

    <a href="{{ route('kelompok.index') }}">Kembali</a>
</body>
</html>
