<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Mahasiswa</title>
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

        form {
            max-width: 500px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
            color: #444;
        }

        input, select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 6px;
            border: 1px solid #ccc;
            transition: border 0.3s;
        }

        input:focus, select:focus {
            border-color: #2563eb;
            outline: none;
        }

        input[readonly] {
            background: #f3f4f6;
            cursor: not-allowed;
        }

        button {
            background: #16a34a;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            margin-right: 10px;
        }

        button:hover {
            background: #15803d;
        }

        a {
            text-decoration: none;
            color: #555;
            padding: 10px 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
            background: #f9fafb;
        }

        a:hover {
            background: #e5e7eb;
        }
    </style>
</head>
<body>
    <h1>✏️ Edit Mahasiswa</h1>

    <form action="{{ route('mahasiswa.update', $mahasiswa) }}" method="POST">
        @csrf
        @method('PUT')

        <label>NIM</label>
        <input type="text" name="nim" value="{{ $mahasiswa->nim }}" readonly>

        <label>Nama</label>
        <input type="text" name="nama" value="{{ $mahasiswa->nama }}" required>

        <label>Kelas</label>
        <select name="kelas" required>
            <option value="">-- Pilih Kelas --</option>
            <option value="TI 3A" {{ old('kelas') == 'TI 3A' ? 'selected' : '' }}>TI 3A</option>
            <option value="TI 3B" {{ old('kelas') == 'TI 3B' ? 'selected' : '' }}>TI 3B</option>
            <option value="TI 3C" {{ old('kelas') == 'TI 3C' ? 'selected' : '' }}>TI 3C</option>
            <option value="TI 3D" {{ old('kelas') == 'TI 3D' ? 'selected' : '' }}>TI 3D</option>
            <option value="TI 3E" {{ old('kelas') == 'TI 3E' ? 'selected' : '' }}>TI 3E</option>
        </select>

        <label>Angkatan</label>
        <input type="text" name="angkatan" value="{{ $mahasiswa->angkatan }}" required>

        <label>Email</label>
        <input type="email" name="email" value="{{ $mahasiswa->email }}" required>

        <label>Password (isi kalau mau ganti)</label>
        <input type="password" name="password">

        <button type="submit">Update</button>
        <a href="{{ route('mahasiswa.index') }}">Kembali</a>
    </form>
</body>
</html>
