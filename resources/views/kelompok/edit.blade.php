<!-- resources/views/kelompok/edit.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Edit Kelompok</title>
</head>
<body>
    <h1>Edit Kelompok</h1>

    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('kelompok.update', $kelompok->id) }}" method="POST">
        @csrf
        @method('PUT')

        <label>Nama Kelompok:</label><br>
     <input type="text" name="nama_kelompok" value="{{ old('nama_kelompok', $kelompok->nama_kelompok ?? '') }}">


        <label>Deskripsi:</label><br>
        <textarea name="deskripsi">{{ old('deskripsi', $kelompok->deskripsi) }}</textarea><br><br>

        <button type="submit">Update</button>
    </form>

    <a href="{{ route('kelompok.index') }}">Kembali</a>
</body>
</html>
