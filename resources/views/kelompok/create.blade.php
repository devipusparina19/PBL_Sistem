<<<<<<< Updated upstream
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
=======
@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Tambah Kelompok</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('kelompok.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="kode_mk" class="form-label">Kode MK</label>
            <input type="text" class="form-control @error('kode_mk') is-invalid @enderror" 
                   id="kode_mk" name="kode_mk" value="{{ old('kode_mk') }}" required>
            @error('kode_mk')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="nama_kelompok" class="form-label">Nama Kelompok</label>
            <input type="text" class="form-control @error('nama_kelompok') is-invalid @enderror" 
                   id="nama_kelompok" name="nama_kelompok" value="{{ old('nama_kelompok') }}" required>
            @error('nama_kelompok')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="judul_proyek" class="form-label">Judul Proyek</label>
            <input type="text" class="form-control @error('judul_proyek') is-invalid @enderror" 
                   id="judul_proyek" name="judul_proyek" value="{{ old('judul_proyek') }}" required>
            @error('judul_proyek')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="nip" class="form-label">NIP</label>
            <input type="text" class="form-control @error('nip') is-invalid @enderror" 
                   id="nip" name="nip" value="{{ old('nip') }}" required>
            @error('nip')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="deskripsi" class="form-label">Deskripsi</label>
            <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                      id="deskripsi" name="deskripsi">{{ old('deskripsi') }}</textarea>
            @error('deskripsi')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('kelompok.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
>>>>>>> Stashed changes
