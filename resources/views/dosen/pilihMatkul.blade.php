@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="text-center mb-4">
        <h2 class="fw-bold">Pilih Mata Kuliah</h2>
        <p class="text-muted">Silakan pilih mata kuliah yang telah didaftarkan oleh admin untuk menginput nilai mahasiswa.</p>
    </div>

    <div class="row justify-content-center">
        @forelse ($mataKuliah as $mk)
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-0 h-100 hover-card">
                    <div class="card-body text-center d-flex flex-column justify-content-between">
                        <div>
                            <h5 class="fw-bold">{{ $mk->nama_mk }}</h5>
                            <p class="text-muted mb-1"><strong>Kode:</strong> {{ $mk->kode_mk }}</p>
                            <p class="text-muted mb-1"><strong>Semester:</strong> {{ $mk->semester ?? '-' }}</p>
                            <p class="text-muted"><strong>Dosen:</strong> {{ $mk->nip ?? '-' }}</p>
                        </div>
                        <a href="{{ url('/nilai/input/' . $mk->id) }}" class="btn btn-primary mt-3">
                            <i class="bi bi-pencil-square me-1"></i> Input Nilai
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center mt-4">
                <div class="alert alert-info">
                    Belum ada data mata kuliah yang didaftarkan oleh admin.
                </div>
            </div>
        @endforelse
    </div>
</div>

{{-- Tambahan CSS biar tampilan modern dan interaktif --}}
<style>
    body {
        background-color: #f8f9fa;
    }

    .hover-card {
        transition: all 0.3s ease-in-out;
        border-radius: 15px;
    }

    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 15px rgba(0,0,0,0.1);
    }

    .btn-primary {
        background-color: #0d6efd;
        border: none;
        border-radius: 8px;
        padding: 8px 14px;
        font-weight: 500;
    }

    .btn-primary:hover {
        background-color: #0b5ed7;
    }

    .card-body h5 {
        color: #333;
    }
</style>
@endsection
