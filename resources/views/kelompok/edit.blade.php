@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Edit Kelompok</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('kelompok.update', $kelompok->id_kelompok) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- ID Kelompok (hidden) -->
        <input type="hidden" name="id_kelompok" value="{{ $kelompok->id_kelompok }}">

        <div class="mb-3">
            <label for="kode_mk" class="form-label">Kode MK</label>
            <input type="text" id="kode_mk" name="kode_mk" class="form-control @error('kode_mk') is-invalid @enderror" 
                   value="{{ old('kode_mk', $kelompok->kode_mk) }}" required>
            @error('kode_mk')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="nama_kelompok" class="form-label">Nama Kelompok</label>
            <input type="text" id="nama_kelompok" name="nama_kelompok" class="form-control @error('nama_kelompok') is-invalid @enderror" 
                   value="{{ old('nama_kelompok', $kelompok->nama_kelompok) }}" required>
            @error('nama_kelompok')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- ✅ Ganti NIP & Deskripsi menjadi Kelas --}}
        <div class="mb-3">
            <label for="kelas" class="form-label">Kelas</label>
            <input type="text" id="kelas" name="kelas" class="form-control @error('kelas') is-invalid @enderror" 
                   value="{{ old('kelas', $kelompok->kelas) }}" placeholder="Contoh: TI-3A" required>
            @error('kelas')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="judul_proyek" class="form-label">Judul Proyek</label>
            <input type="text" id="judul_proyek" name="judul_proyek" class="form-control @error('judul_proyek') is-invalid @enderror" 
                   value="{{ old('judul_proyek', $kelompok->judul_proyek) }}" required>
            @error('judul_proyek')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex justify-content-between">
            <a href="{{ route('kelompok.index') }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-primary">Update</button>
        </div>
    </form>
</div>
@endsection
