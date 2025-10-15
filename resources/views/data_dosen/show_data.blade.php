@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Detail Dosen</h1>

    {{-- Detail Data Dosen --}}
    <div class="card shadow-sm p-4">
        <div class="mb-3">
            <label class="form-label fw-bold">Nama</label>
            <input type="text" class="form-control" value="{{ $dosen->nama }}" readonly>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">NIP</label>
            <input type="text" class="form-control" value="{{ $dosen->nip }}" readonly>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Email</label>
            <input type="email" class="form-control" value="{{ $dosen->email }}" readonly>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">No. Telepon</label>
            <input type="text" class="form-control" value="{{ $dosen->no_telp ?? '-' }}" readonly>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Kelas</label>
            <input type="text" class="form-control" value="{{ $dosen->kelas ?? '-' }}" readonly>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Mata Kuliah</label>
            <input type="text" class="form-control" value="{{ $dosen->mata_kuliah ?? '-' }}" readonly>
        </div>
    </div>

    {{-- Tombol Aksi --}}
    <div class="d-flex gap-2 mt-4">
        <a href="{{ route('data_dosen.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>

        {{-- Tombol Edit hanya untuk admin --}}
        @if (Auth::user()->role === 'admin')
            <a href="{{ route('data_dosen.edit', $dosen->id) }}" class="btn btn-primary">
                <i class="bi bi-pencil-square"></i> Edit
            </a>
        @endif
    </div>
</div>
@endsection
