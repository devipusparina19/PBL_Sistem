@extends('layouts.app')

@section('content')
@php
    // Jika user bukan admin, redirect ke halaman index dosen
    if (Auth::user()->role !== 'admin') {
        echo "<script>window.location='" . route('data_dosen.index') . "';</script>";
        exit;
    }
@endphp

<div class="container mt-5">
    <h1 class="mb-4">Edit Dosen</h1>

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

    <form action="{{ route('data_dosen.update', $dosen->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Nama --}}
        <div class="mb-3">
            <label for="nama" class="form-label">Nama</label>
            <input 
                type="text" 
                id="nama" 
                name="nama" 
                class="form-control @error('nama') is-invalid @enderror"
                value="{{ old('nama', $dosen->nama) }}" 
                required>
            @error('nama')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- NIP --}}
        <div class="mb-3">
            <label for="nip" class="form-label">NIP</label>
            <input 
                type="text" 
                id="nip" 
                name="nip" 
                class="form-control @error('nip') is-invalid @enderror"
                value="{{ old('nip', $dosen->nip) }}" 
                required>
            @error('nip')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Email --}}
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input 
                type="email" 
                id="email" 
                name="email" 
                class="form-control @error('email') is-invalid @enderror"
                value="{{ old('email', $dosen->email) }}" 
                required>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- No. Telepon --}}
        <div class="mb-3">
            <label for="no_telp" class="form-label">No. Telepon</label>
            <input 
                type="text" 
                id="no_telp" 
                name="no_telp" 
                class="form-control @error('no_telp') is-invalid @enderror"
                value="{{ old('no_telp', $dosen->no_telp) }}">
            @error('no_telp')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Kelas --}}
        <div class="mb-3">
            <label for="kelas" class="form-label">Kelas Bimbingan (Opsional)</label>
            <select 
                id="kelas" 
                name="kelas" 
                class="form-select @error('kelas') is-invalid @enderror">
                <option value="">-- Tidak Ada --</option>
                @foreach (['TI 3A', 'TI 3B', 'TI 3C', 'TI 3D', 'TI 3E'] as $kelas)
                    <option value="{{ $kelas }}" {{ old('kelas', $dosen->kelas) == $kelas ? 'selected' : '' }}>
                        {{ $kelas }}
                    </option>
                @endforeach
            </select>
            @error('kelas')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Mata Kuliah --}}
        <div class="mb-3">
            <label for="mata_kuliah" class="form-label">Mata Kuliah</label>
            <select 
                id="mata_kuliah" 
                name="mata_kuliah" 
                class="form-select @error('mata_kuliah') is-invalid @enderror" 
                required>
                <option value="">-- Pilih Mata Kuliah --</option>
                @foreach (['Integrasi Sistem', 'Pemrograman Web Lanjut', 'TPK', 'IT Project'] as $matkul)
                    <option value="{{ $matkul }}" {{ old('mata_kuliah', $dosen->mata_kuliah) == $matkul ? 'selected' : '' }}>
                        {{ $matkul }}
                    </option>
                @endforeach
            </select>
            @error('mata_kuliah')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Tombol Aksi --}}
        <div class="d-flex gap-2 mt-4">
            <a href="{{ route('data_dosen.index') }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-primary">Update</button>
        </div>
    </form>
</div>
@endsection
