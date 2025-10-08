@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <!-- Header -->
    <div class="text-center mb-5">
        <h2 class="fw-bold text-primary mb-2">Dashboard Kelompok PBL</h2>
        <<p class="text-muted fs-5">
    Selamat datang,
    <strong>{{ Auth::user()->name ?? 'Mahasiswa' }}</strong>
    (Ketua / Anggota Kelompok)
</p>
        <hr class="w-50 mx-auto mb-4">
    </div>

    <!-- Card Section -->
    <div class="row justify-content-center g-4">
        <!-- Input Milestone -->
        <div class="col-md-5">
            <div class="card border-0 shadow-lg h-100 rounded-4 hover-card">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <i class="bi bi-flag-fill fs-1 text-primary"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Input Milestone</h5>
                    <p class="text-muted mb-4">Laporkan progres dan pencapaian proyek setiap minggu.</p>
                    <a href="{{ url('/milestone/input') }}" class="btn btn-primary w-100 text-white">Isi Milestone</a>
                </div>
            </div>
        </div>

        <!-- Penilaian Teman Sejawat -->
        <div class="col-md-5">
            <div class="card border-0 shadow-lg h-100 rounded-4 hover-card">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <i class="bi bi-people-fill fs-1 text-success"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Penilaian Teman Sejawat</h5>
                    <p class="text-muted mb-4">Nilai kinerja rekan satu kelompok secara objektif.</p>
                    <a href="{{ url('/penilaian/sejawat') }}" class="btn btn-success w-100 text-white">Beri Penilaian</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Efek hover -->
<style>
.hover-card {
    transition: transform 0.25s ease, box-shadow 0.25s ease;
}
.hover-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
}
</style>
@endsection
