@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Edit Kelompok</h1>

    {{-- ✅ Tampilkan error validasi --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- ✅ Form Update --}}
    <form action="{{ route('kelompok.update', $kelompok->id_kelompok) }}" method="POST">
        @csrf
        @method('PUT')

        <input type="hidden" name="id_kelompok" value="{{ $kelompok->id_kelompok }}">

        {{-- Kode MK --}}
        <div class="mb-3">
            <label for="kode_mk" class="form-label">Kode MK</label>
            <input type="text" id="kode_mk" name="kode_mk" 
                   class="form-control @error('kode_mk') is-invalid @enderror" 
                   value="{{ old('kode_mk', $kelompok->kode_mk) }}" required>
            @error('kode_mk')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Nama Kelompok --}}
        <div class="mb-3">
            <label for="nama_kelompok" class="form-label">Nama Kelompok</label>
            <input type="text" id="nama_kelompok" name="nama_kelompok" 
                   class="form-control @error('nama_kelompok') is-invalid @enderror" 
                   value="{{ old('nama_kelompok', $kelompok->nama_kelompok) }}" required>
            @error('nama_kelompok')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Kelas --}}
        <div class="mb-3">
            <label for="kelas" class="form-label">Kelas</label>
            <select class="form-select @error('kelas') is-invalid @enderror" id="kelas" name="kelas" required>
                <option value="">-- Pilih Kelas --</option>
                <option value="3A" {{ old('kelas', $kelompok->kelas) == '3A' ? 'selected' : '' }}>3A</option>
                <option value="3B" {{ old('kelas', $kelompok->kelas) == '3B' ? 'selected' : '' }}>3B</option>
                <option value="3C" {{ old('kelas', $kelompok->kelas) == '3C' ? 'selected' : '' }}>3C</option>
                <option value="3D" {{ old('kelas', $kelompok->kelas) == '3D' ? 'selected' : '' }}>3D</option>
                <option value="3E" {{ old('kelas', $kelompok->kelas) == '3E' ? 'selected' : '' }}>3E</option>
            </select>
            @error('kelas')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Deskripsi (Opsional) --}}
        <div class="mb-3">
            <label for="deskripsi" class="form-label">Deskripsi</label>
            <textarea id="deskripsi" name="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $kelompok->deskripsi) }}</textarea>
        </div>

        {{-- Judul Proyek --}}
        <div class="mb-3">
            <label for="judul_proyek" class="form-label">Judul Proyek</label>
            <input type="text" id="judul_proyek" name="judul_proyek" 
                   class="form-control @error('judul_proyek') is-invalid @enderror" 
                   value="{{ old('judul_proyek', $kelompok->judul_proyek) }}" required>
            @error('judul_proyek')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Tombol --}}
        <div class="d-flex justify-content-between">
            <a href="{{ route('kelompok.index') }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-primary">Update</button>
        </div>
    </form>
</div>
@endsection
