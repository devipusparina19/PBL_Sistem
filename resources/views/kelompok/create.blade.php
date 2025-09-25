<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Mahasiswa</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            padding: 25px; 
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px; 
            font-size: 1.5rem; 
        }

        form {
            max-width: 450px; 
            margin: auto;
            background: white;
            padding: 24px 28px; 
            border-radius: 10px;
            box-shadow: 0 3px 8px rgba(0,0,0,0.05);
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
            color: #444;
            font-size: 0.9rem;
        }

        input {
            width: 100%;
            padding: 9px 12px; 
            margin-bottom: 14px;
            border-radius: 6px;
            border: 1px solid #ccc;
            transition: border 0.3s, box-shadow 0.3s;
            font-size: 0.9rem;
        }

        input:focus {
            border-color: #2563eb;
            outline: none;
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.07);
        }

        button {
            background: #2563eb;
            color: white;
            padding: 9px 16px; 
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            margin-right: 6px;
            font-size: 0.9rem;
        }

        button:hover {
            background: #1e40af;
        }

        a {
            text-decoration: none;
            color: #555;
            padding: 9px 16px; 
            border: 1px solid #ccc;
            border-radius: 6px;
            background: #f9fafb;
            font-size: 0.9rem;
        }

        a:hover {
            background: #e5e7eb;
        }
    </style>
</head>
<body>
    <h1>➕ Tambah Mahasiswa</h1>

    <form action="{{ route('mahasiswa.store') }}" method="POST">
        @csrf
        <label>NIM</label>
        <input type="text" name="nim" required>

        <label>Nama</label>
        <input type="text" name="nama" required>

        <label>Angkatan</label>
        <input type="text" name="angkatan" required>

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit">Simpan</button>
        <a href="{{ route('mahasiswa.index') }}">Kembali</a>
    </form>
</body>
</html>