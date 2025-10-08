@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Detail Mata Kuliah</h1>

    <div class="mb-3">
        <label class="form-label">Kode MK</label>
        <input type="text" class="form-control" value="{{ $mataKuliah->kode_mk }}" readonly>
    </div>

    <div class="mb-3">
        <label class="form-label">Nama Mata Kuliah</label>
        <input type="text" class="form-control" value="{{ $mataKuliah->nama_mk }}" readonly>
    </div>

    <div class="mb-3">
        <label class="form-label">NIP Dosen Pengampu</label>
        <input type="text" class="form-control" value="{{ $mataKuliah->nip ?? '-' }}" readonly>
    </div>

    <div class="mb-3">
        <label class="form-label">Semester</label>
        <input type="text" class="form-control" value="{{ $mataKuliah->semester ?? '-' }}" readonly>
    </div>

    <div class="mb-3">
        <label class="form-label">Dibuat Pada</label>
        <input type="text" class="form-control" value="{{ $mataKuliah->created_at->format('d M Y H:i') }}" readonly>
    </div>

    <div class="mb-3">
        <label class="form-label">Terakhir Diperbarui</label>
        <input type="text" class="form-control" value="{{ $mataKuliah->updated_at->format('d M Y H:i') }}" readonly>
    </div>

    <a href="{{ route('mata_kuliah.index') }}" class="btn btn-secondary">Kembali</a>
    <a href="{{ route('mata_kuliah.edit', $mataKuliah) }}" class="btn btn-primary">Edit</a>
</div>
@endsection
