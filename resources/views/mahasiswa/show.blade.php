@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Detail Mahasiswa</h1>

    <div class="mb-3">
        <label class="form-label">NIM</label>
        <input type="text" class="form-control" value="{{ $mahasiswa->nim }}" readonly>
    </div>

    <div class="mb-3">
        <label class="form-label">Nama</label>
        <input type="text" class="form-control" value="{{ $mahasiswa->nama }}" readonly>
    </div>

    <div class="mb-3">
        <label class="form-label">Kelas</label>
        <input type="text" class="form-control" value="{{ $mahasiswa->kelas }}" readonly>
    </div>

    <div class="mb-3">
        <label class="form-label">Angkatan</label>
        <input type="text" class="form-control" value="{{ $mahasiswa->angkatan }}" readonly>
    </div>

    <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" class="form-control" value="{{ $mahasiswa->email }}" readonly>
    </div>

    <a href="{{ route('mahasiswa.index') }}" class="btn btn-secondary">Kembali</a>
    <a href="{{ route('mahasiswa.edit', $mahasiswa) }}" class="btn btn-primary">Edit</a>
</div>
@endsection
