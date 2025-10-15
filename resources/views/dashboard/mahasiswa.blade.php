@extends('layouts.app')

@section('content')
<div class="container py-5">

    <!-- Header -->
    <div class="text-center mb-5">
        <h2 class="fw-semibold text-primary mb-2" style="font-size: 1.9rem;">
            Sistem Penilaian Kinerja Mahasiswa dan Kelompok PBL
        </h2>
        <p class="text-muted" style="font-size: 1rem;">
            Selamat datang, <span class="fw-semibold text-dark">{{ Auth::user()->name }}</span> (Mahasiswa)
        </p>
        <hr class="mx-auto mt-3" style="width: 80px; height: 3px; background-color: #0d6efd; border: none;">
    </div>

    <!-- Cards Section -->
    <div class="row g-4 justify-content-center">
        
        <!-- Logbook -->
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm rounded-4 hover-card">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <i class="bi bi-journal-text text-primary" style="font-size: 2.5rem;"></i>
                    </div>
                    <h5 class="fw-semibold mb-2 text-dark">Logbook</h5>
                    <p class="text-secondary mb-4" style="font-size: 0.95rem;">
                        Isi progres mingguan proyek Anda bersama kelompok.
                    </p>
                    <a href="{{ url('/logbook') }}" class="btn btn-primary w-100 py-2 rounded-3 fw-medium">
                        Isi Logbook
                    </a>
                </div>
            </div>
        </div>

        <!-- Penilaian -->
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm rounded-4 hover-card">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <i class="bi bi-bar-chart-line text-primary" style="font-size: 2.5rem;"></i>
                    </div>
                    <h5 class="fw-semibold mb-2 text-dark">Nilai</h5>
                    <p class="text-secondary mb-4" style="font-size: 0.95rem;">
                        Lihat hasil penilaian dari dosen pembimbing dan penguji.
                    </p>
                    <a href="{{ url('/nilai') }}" class="btn btn-primary w-100 py-2 rounded-3 fw-medium">
                        Lihat Nilai
                    </a>
                </div>
            </div>
        </div>

        <!-- Ranking Mahasiswa -->
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm rounded-4 hover-card">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <i class="bi bi-trophy text-primary" style="font-size: 2.5rem;"></i>
                    </div>
                    <h5 class="fw-semibold mb-2 text-dark">Ranking Mahasiswa</h5>
                    <p class="text-secondary mb-4" style="font-size: 0.95rem;">
                        Cek peringkat Anda berdasarkan nilai individu.
                    </p>
                    <a href="{{ route('mahasiswa.rangking') }}" class="btn btn-primary w-100 py-2 rounded-3 fw-medium">
                        Lihat Ranking
                    </a>
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
