@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Detail Dosen</h1>

    <div class="mb-3">
        <label class="form-label">Nama</label>
        <input type="text" class="form-control" value="{{ $dosen->nama }}" readonly>
    </div>

    <div class="mb-3">
        <label class="form-label">NIP</label>
        <input type="text" class="form-control" value="{{ $dosen->nip }}" readonly>
    </div>

    <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" class="form-control" value="{{ $dosen->email }}" readonly>
    </div>

    <div class="mb-3">
        <label class="form-label">No. Telepon</label>
        <input type="text" class="form-control" value="{{ $dosen->no_telepon ?? '-' }}" readonly>
    </div>

    <div class="mb-3">
        <label class="form-label">Kelas</label>
        <input type="text" class="form-control" value="{{ $dosen->kelas ?? '-' }}" readonly>
    </div>

    <div class="mb-3">
        <label class="form-label">Mata Kuliah</label>
        <input type="text" class="form-control" value="{{ $dosen->mata_kuliah ?? '-' }}" readonly>
    </div>

    <div class="d-flex gap-2 mt-4">
        <a href="{{ route('data_dosen.index') }}" class="btn btn-secondary">Kembali</a>

        {{-- Tombol Edit hanya untuk admin --}}
        @if(Auth::user()->role === 'admin')
            <a href="{{ route('data_dosen.edit', $dosen) }}" class="btn btn-primary">Edit</a>
        @endif
    </div>
</div>
@endsection
