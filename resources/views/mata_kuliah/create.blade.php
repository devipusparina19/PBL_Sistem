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

        {{-- NIP Dosen Pengampu --}}
        <div class="mb-3">
            <label for="nip" class="form-label">NIP Dosen Pengampu</label>
            <input type="text" class="form-control @error('nip') is-invalid @enderror"
                   id="nip" name="nip" value="{{ old('nip') }}" required>
            @error('nip')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Semester --}}
        <div class="mb-3">
            <label for="semester" class="form-label">Semester</label>
            <select class="form-control @error('semester') is-invalid @enderror"
                    id="semester" name="semester" required>
                <option value="">-- Pilih Semester --</option>
                @for ($i = 1; $i <= 8; $i++)
                    <option value="{{ $i }}" {{ old('semester') == $i ? 'selected' : '' }}>
                        Semester {{ $i }}
                    </option>
                @endfor
            </select>
            @error('semester')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('mata_kuliah.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
