@extends('layouts.app')

@section('content')
<div class="container py-5">
    <!-- Header -->
    <div class="text-center mb-5">
        <h2 class="fw-semibold text-primary mb-2" style="font-size: 1.9rem;">
            Sistem Penilaian Kinerja Mahasiswa dan Kelompok PBL
        </h2>
        <p class="text-muted" style="font-size: 1rem;">
            Selamat datang, <span class="fw-semibold text-dark">{{ Auth::user()->name }}</span>
            ({{ ucfirst(Auth::user()->role) }})
        </p>
        <hr class="mx-auto mt-3" style="width: 80px; height: 3px; background-color: #0d6efd; border: none;">
    </div>

    <!-- Cards Section -->
    <div class="row g-4 justify-content-center">
        
        <!-- Validasi Milestone -->
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm rounded-4 hover-card">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <i class="bi bi-check2-circle text-primary" style="font-size: 2.5rem;"></i>
                    </div>
                    <h5 class="fw-semibold mb-2 text-dark">Validasi Milestone</h5>
                    <p class="text-secondary mb-4" style="font-size: 0.95rem;">
                        Periksa dan setujui progres mahasiswa.
                    </p>
                    <a href="{{ route('milestone.validasi') }}" class="btn btn-primary w-100 py-2 rounded-3 fw-medium">
                        Validasi
                    </a>
                </div>
            </div>
        </div>

        <!-- Input Nilai Mahasiswa (Individu) -->
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm rounded-4 hover-card">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <i class="bi bi-person-check text-primary" style="font-size: 2.5rem;"></i>
                    </div>
                    <h5 class="fw-semibold mb-2 text-dark">Nilai Mahasiswa</h5>
                    <p class="text-secondary mb-4" style="font-size: 0.95rem;">
                        Beri nilai individu mahasiswa (6 komponen).
                    </p>
                    <a href="{{ route('nilai.index') }}" class="btn btn-primary w-100 py-2 rounded-3 fw-medium">
                        Kelola Nilai Mahasiswa
                    </a>
                </div>
            </div>
        </div>

        <!-- Monitoring Progres -->
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm rounded-4 hover-card">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <i class="bi bi-graph-up text-primary" style="font-size: 2.5rem;"></i>
                    </div>
                    <h5 class="fw-semibold mb-2 text-dark">Monitoring Progres</h5>
                    <p class="text-secondary mb-4" style="font-size: 0.95rem;">
                        Pantau logbook mahasiswa dan progres kelompok.
                    </p>

                    @if(Auth::user()->role == 'admin')
                        <a href="{{ url('/monitoring') }}" class="btn btn-primary w-100 py-2 rounded-3 fw-medium">
                            Lihat / Edit / Hapus
                        </a>
                    @else
                        <a href="{{ url('/monitoring') }}" class="btn btn-primary w-100 py-2 rounded-3 fw-medium">
                            Pantau
                        </a>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Style tambahan -->
<style>
    body {
        background-color: #f5f7fa;
        font-family: 'Poppins', sans-serif;
        color: #212529;
    }

    .card {
        background-color: #ffffff;
        border-radius: 12px;
        transition: all 0.3s ease;
    }

    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 24px rgba(13, 110, 253, 0.1);
    }

    .btn-primary {
        background-color: #0d6efd;
        border: none;
        font-size: 0.95rem;
        transition: background-color 0.3s ease, transform 0.2s ease;
    }

    .btn-primary:hover {
        background-color: #0b5ed7;
        transform: translateY(-1px);
    }

    h2, h5 {
        letter-spacing: 0.3px;
    }

    hr {
        border-radius: 5px;
        opacity: 0.9;
    }

    .text-muted {
        color: #6c757d !important;
    }
</style>
@endsection
