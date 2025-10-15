@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <!-- Header -->
    <div class="text-center mb-5">
        <h2 class="fw-bold text-primary">Dashboard Kelompok PBL</h2>
        <p class="text-muted">Selamat datang, <strong>{{ Auth::user()->name ?? 'Mahasiswa' }}</strong></p>
    </div>

    <!-- Card Section -->
    <div class="row justify-content-center g-4">
        <!-- Milestone -->
        <div class="col-md-4">
            <div class="card border-0 shadow-lg h-100 rounded-4 hover-card">
                <div class="card-body text-center p-4">
                    <div class="mb-3 text-primary fs-1">
                        <i class="bi bi-journal-check"></i>
                    </div>
                    <h5 class="fw-bold text-dark">Input Milestone</h5>
                    <p class="text-muted small mb-4">Laporkan progres dan pencapaian proyek setiap minggu.</p>

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
        <div class="col-md-4">
            <div class="card border-0 shadow-lg h-100 rounded-4 hover-card">
                <div class="card-body text-center p-4">
                    <div class="mb-3 text-primary fs-1">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <h5 class="fw-bold text-dark">Penilaian Teman Sejawat</h5>
                    <p class="text-muted small mb-4">Nilai kinerja rekan satu kelompok secara objektif dan transparan.</p>
                    <a href="{{ url('/penilaian/sejawat') }}" 
                       class="btn btn-primary w-100 fw-semibold">
                        Beri Penilaian
                    </a>
                </div>
            </div>
        </div>

        <!-- Ranking Kelompok -->
        <div class="col-md-4">
            <div class="card border-0 shadow-lg h-100 rounded-4 hover-card">
                <div class="card-body text-center p-4">
                    <div class="mb-3 text-primary fs-1">
                        <i class="bi bi-trophy-fill"></i>
                    </div>
                    <h5 class="fw-bold text-dark">Ranking Kelompok</h5>
                    <p class="text-muted small mb-4">Lihat peringkat kelompok berdasarkan nilai rata-rata kelompok PBL.</p>
                    <a href="{{ route('kelompok.rangking') }}" 
                       class="btn btn-primary w-100 fw-semibold">
                        Lihat Ranking Kelompok
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Style Profesional Konsisten -->
<style>
body {
    background-color: #ffffff;
    font-family: "Segoe UI", Arial, sans-serif;
}

.card {
    background-color: #ffffff;
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
}

.hover-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
}

.card-body i {
    transition: color 0.3s ease;
}
.hover-card:hover i {
    color: #0d6efd !important;
}

.btn {
    border-radius: 10px;
    padding: 10px 0;
    transition: all 0.25s ease;
}
.btn:hover {
    transform: scale(1.02);
    opacity: 0.95;
}
</style>
@endsection
