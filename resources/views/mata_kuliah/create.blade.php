@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Tambah Mata Kuliah</h1>

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

    <form action="{{ route('mata_kuliah.store') }}" method="POST">
        @csrf

        {{-- Kode Mata Kuliah --}}
        <div class="mb-3">
            <label for="kode_mk" class="form-label">Kode Mata Kuliah</label>
            <input type="text" class="form-control @error('kode_mk') is-invalid @enderror"
                   id="kode_mk" name="kode_mk" value="{{ old('kode_mk') }}" required>
            @error('kode_mk')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Nama Mata Kuliah --}}
        <div class="mb-3">
            <label for="nama_mk" class="form-label">Nama Mata Kuliah</label>
            <input type="text" class="form-control @error('nama_mk') is-invalid @enderror"
                   id="nama_mk" name="nama_mk" value="{{ old('nama_mk') }}" required>
            @error('nama_mk')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Kelas --}}
        <div class="mb-3">
            <label for="kelas" class="form-label">Kelas</label>
            <select class="form-control @error('kelas') is-invalid @enderror"
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

        {{-- NIP Dosen Pengampu --}}
        <div class="mb-3">
            <label for="nip_dosen" class="form-label">NIP Dosen Pengampu</label>
            <input type="text" class="form-control @error('nip_dosen') is-invalid @enderror"
                   id="nip_dosen" name="nip_dosen" value="{{ old('nip_dosen') }}" required>
            @error('nip_dosen')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('mata_kuliah.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
