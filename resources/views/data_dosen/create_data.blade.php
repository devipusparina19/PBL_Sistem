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

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror"
                   id="email" name="email" value="{{ old('email') }}" required>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="mata_kuliah" class="form-label">Mata Kuliah</label>
            <input type="text" class="form-control @error('mata_kuliah') is-invalid @enderror"
                   id="mata_kuliah" name="mata_kuliah" value="{{ old('mata_kuliah') }}" required>
            @error('mata_kuliah')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('data_dosen.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
