@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Tambah Dosen</h1>

    {{-- Tampilkan error validasi --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('data_dosen.store') }}" method="POST">
        @csrf

        {{-- Bagian Identitas --}}
        <div class="mb-3">
            <label for="nama" class="form-label">Nama</label>
            <input type="text" class="form-control @error('nama') is-invalid @enderror"
                   id="nama" name="nama" value="{{ old('nama') }}" required>
            @error('nama')
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

        {{-- Bagian Kontak --}}
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror"
                   id="email" name="email" value="{{ old('email') }}" required>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="no_telp" class="form-label">No. Telepon</label>
            <input type="text" class="form-control @error('no_telp') is-invalid @enderror"
                   id="no_telp" name="no_telp" value="{{ old('no_telp') }}" required>
            @error('no_telp')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Bagian Akademik --}}
        <div class="mb-3">
            <label for="kelas" class="form-label">Kelas</label>
            <select class="form-select @error('kelas') is-invalid @enderror"
                    id="kelas" name="kelas" required>
                <option value="" disabled selected>Pilih Kelas</option>
                <option value="TI 3A" {{ old('kelas') == 'TI 3A' ? 'selected' : '' }}>TI 3A</option>
                <option value="TI 3B" {{ old('kelas') == 'TI 3B' ? 'selected' : '' }}>TI 3B</option>
                <option value="TI 3C" {{ old('kelas') == 'TI 3C' ? 'selected' : '' }}>TI 3C</option>
                <option value="TI 3D" {{ old('kelas') == 'TI 3D' ? 'selected' : '' }}>TI 3D</option>
                <option value="TI 3E" {{ old('kelas') == 'TI 3E' ? 'selected' : '' }}>TI 3E</option>
            </select>
            @error('kelas')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="mata_kuliah" class="form-label">Mata Kuliah</label>
            <select class="form-select @error('mata_kuliah') is-invalid @enderror"
                    id="mata_kuliah" name="mata_kuliah" required>
                <option value="" disabled selected>Pilih Mata Kuliah</option>
                <option value="Integrasi Sistem" {{ old('mata_kuliah') == 'Integrasi Sistem' ? 'selected' : '' }}>Integrasi Sistem</option>
                <option value="Pemrograman Web Lanjut" {{ old('mata_kuliah') == 'Pemrograman Web Lanjut' ? 'selected' : '' }}>Pemrograman Web Lanjut</option>
                <option value="TPK" {{ old('mata_kuliah') == 'TPK' ? 'selected' : '' }}>TPK</option>
                <option value="IT Project" {{ old('mata_kuliah') == 'IT Project' ? 'selected' : '' }}>IT Project</option>
            </select>
            @error('mata_kuliah')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex justify-content-between">
            <a href="{{ route('data_dosen.index') }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>
</div>
@endsection
