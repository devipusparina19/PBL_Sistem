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
            <label for="kelas" class="form-label">Kelas</label>
            <select class="form-select @error('kelas') is-invalid @enderror" 
                    id="kelas" name="kelas" required>
                <option value="">-- Pilih Kelas --</option>
                <option value="3A" {{ old('kelas', $kelasDefault ?? '3A') == '3A' ? 'selected' : '' }}>3A</option>
                <option value="3B" {{ old('kelas', $kelasDefault ?? '3A') == '3B' ? 'selected' : '' }}>3B</option>
                <option value="3C" {{ old('kelas', $kelasDefault ?? '3A') == '3C' ? 'selected' : '' }}>3C</option>
                <option value="3D" {{ old('kelas', $kelasDefault ?? '3A') == '3D' ? 'selected' : '' }}>3D</option>
                <option value="3E" {{ old('kelas', $kelasDefault ?? '3A') == '3E' ? 'selected' : '' }}>3E</option>
            </select>
            @error('kelas')
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

        {{-- ✅ Ganti NIP dan Deskripsi jadi Kelas --}}
        <div class="mb-3">
            <label for="kelas" class="form-label">Kelas</label>
            <input type="text" class="form-control @error('kelas') is-invalid @enderror" 
                   id="kelas" name="kelas" value="{{ old('kelas') }}" placeholder="Contoh: TI-3A" required>
            @error('kelas')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('kelompok.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
