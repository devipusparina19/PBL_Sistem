@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Detail Kelompok</h1>

    <div class="mb-3">
        <label class="form-label">ID Kelompok</label>
        <input type="text" class="form-control" value="{{ $kelompok->id_kelompok }}" readonly>
    </div>

    <div class="mb-3">
        <label class="form-label">Kode MK</label>
        <input type="text" class="form-control" value="{{ $kelompok->kode_mk }}" readonly>
    </div>

    <div class="mb-3">
        <label class="form-label">Nama Kelompok</label>
        <input type="text" class="form-control" value="{{ $kelompok->nama_kelompok }}" readonly>
    </div>

    {{-- ✅ Tambahan: tampilkan nama kelas --}}
    <div class="mb-3">
        <label class="form-label">Kelas</label>
        <input type="text" class="form-control" 
               value="{{ $kelompok->kelas ? $kelompok->kelas->nama_kelas : '-' }}" readonly>
    </div>

    <div class="mb-3">
        <label class="form-label">Judul Proyek</label>
        <input type="text" class="form-control" value="{{ $kelompok->judul_proyek }}" readonly>
    </div>

    <div class="d-flex justify-content-start">
        <a href="{{ route('kelompok.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
</div>
@endsection
