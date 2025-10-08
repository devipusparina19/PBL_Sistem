@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Detail Kelompok</h1>

    <div class="mb-3">
        <label class="form-label">ID Kelompok</label>
        <input type="text" class="form-control" value="{{ $kelompok->id_kelompok }}" readonly>
    </div>

    <div class="mb-3">
        <label class="form-label">NIP</label>
        <input type="text" class="form-control" value="{{ $kelompok->nip }}" readonly>
    </div>

    <div class="mb-3">
        <label class="form-label">Kode MK</label>
        <input type="text" class="form-control" value="{{ $kelompok->kode_mk }}" readonly>
    </div>

    <div class="mb-3">
        <label class="form-label">Nama Kelompok</label>
        <input type="text" class="form-control" value="{{ $kelompok->nama_kelompok }}" readonly>
    </div>

    <div class="mb-3">
        <label class="form-label">Deskripsi</label>
        <textarea class="form-control" rows="3" readonly>{{ $kelompok->deskripsi }}</textarea>
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
