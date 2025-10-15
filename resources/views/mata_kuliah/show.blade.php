@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4 text-center">Detail Mata Kuliah</h1>

    <div class="card shadow-sm rounded-3">
        <div class="card-body">

            <div class="mb-3">
                <label class="form-label fw-semibold">Kode MK</label>
                <input type="text" class="form-control" value="{{ $mataKuliah->kode_mk }}" readonly>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Nama Mata Kuliah</label>
                <input type="text" class="form-control" value="{{ $mataKuliah->nama_mk }}" readonly>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">NIP Dosen Pengampu</label>
                <input type="text" class="form-control" value="{{ $mataKuliah->nip ?? '-' }}" readonly>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Semester</label>
                <input type="text" class="form-control" 
                       value="{{ $mataKuliah->semester ? 'Semester ' . $mataKuliah->semester : '-' }}" readonly>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Dibuat Pada</label>
                <input type="text" class="form-control" 
                       value="{{ $mataKuliah->created_at ? $mataKuliah->created_at->format('d M Y H:i') : '-' }}" readonly>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">Terakhir Diperbarui</label>
                <input type="text" class="form-control" 
                       value="{{ $mataKuliah->updated_at ? $mataKuliah->updated_at->format('d M Y H:i') : '-' }}" readonly>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('mata_kuliah.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left-circle"></i> Kembali
                </a>
                <a href="{{ route('mata_kuliah.edit', $mataKuliah) }}" class="btn btn-primary">
                    <i class="bi bi-pencil-square"></i> Edit
                </a>
            </div>

        </div>
    </div>
</div>
@endsection
