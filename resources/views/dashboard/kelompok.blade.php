@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <!-- Header -->
    <div class="text-center mb-5">
        <h2 class="fw-bold text-dark mb-2">Dashboard Kelompok PBL</h2>
        <p class="text-muted fs-5">
            Selamat datang, 
            <strong>{{ Auth::user()->name ?? 'Mahasiswa' }}</strong>
        </p>
        <hr class="header-line mx-auto mb-4">
    </div>

    <!-- Card Section -->
    <div class="row justify-content-center g-4">
        <!-- Milestone -->
        <div class="col-md-5">
            <div class="card border-0 shadow-sm h-100 rounded-4 hover-card">
                <div class="card-body text-center p-4">
                    <div class="icon-circle bg-primary text-white mb-3">
                        <i class="bi bi-journal-check fs-3"></i>
                    </div>
                    <h5 class="fw-semibold text-dark mb-2">Input Milestone</h5>
                    <p class="text-secondary small mb-4">
                        Laporkan progres dan pencapaian proyek setiap minggu.
                    </p>

                    @if(auth()->user()->role_di_kelompok === 'ketua')
                        <a href="{{ url('/milestone/input/' . auth()->user()->role_kelompok) }}" 
                           class="btn btn-primary w-100 fw-semibold">
                            Isi Milestone
                        </a>
                    @else
                        <a href="{{ url('/milestone/view/' . auth()->user()->role_kelompok) }}" 
                           class="btn btn-outline-primary w-100 fw-semibold">
                            Lihat Milestone
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Penilaian Teman Sejawat -->
        <div class="col-md-5">
            <div class="card border-0 shadow-sm h-100 rounded-4 hover-card">
                <div class="card-body text-center p-4">
                    <div class="icon-circle bg-success text-white mb-3">
                        <i class="bi bi-people-fill fs-3"></i>
                    </div>
                    <h5 class="fw-semibold text-dark mb-2">Penilaian Teman Sejawat</h5>
                    <p class="text-secondary small mb-4">
                        Nilai kinerja rekan satu kelompok secara objektif dan transparan.
                    </p>
                    <a href="{{ url('/penilaian/sejawat') }}" 
                       class="btn btn-success w-100 fw-semibold">
                        Beri Penilaian
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Style untuk tampilan profesional -->
<style>
body {
    background-color: #f8f9fa;
    font-family: "Segoe UI", Arial, sans-serif;
}

.header-line {
    width: 80px;
    height: 3px;
    background-color: #0d6efd;
    border: none;
    border-radius: 2px;
}

.card {
    background-color: #ffffff;
    border: 1px solid #e9ecef;
}

.hover-card {
    transition: all 0.25s ease;
}

.hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 18px rgba(0, 0, 0, 0.08);
}

/* Lingkaran ikon di atas setiap card */
.icon-circle {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
}

/* Tombol */
.btn {
    border-radius: 8px;
    padding: 10px 0;
    transition: all 0.25s ease;
}

.btn:hover {
    opacity: 0.9;
    transform: scale(1.02);
}
</style>
@endsection
