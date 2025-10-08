@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <!-- Header -->
    <div class="text-center mb-5">
        <h2 class="fw-bold text-primary">Sistem Penilaian Kinerja Mahasiswa dan Kelompok PBL</h2>
        <p class="text-muted">Selamat datang, {{ Auth::user()->name }} (Koordinator Prodi)</p>
    </div>

    <!-- Card Section -->
    <div class="row justify-content-center g-4">
        <!-- Monitoring Keseluruhan -->
        <div class="col-md-5">
            <div class="card border-0 shadow-lg h-100 rounded-4 hover-card">
                <div class="card-body text-center p-4">
                    <div class="mb-3 text-primary fs-1">
                        <i class="bi bi-bar-chart-line"></i>
                    </div>
                    <h5 class="fw-bold text-dark">Monitoring Keseluruhan</h5>
                    <p class="text-muted">Pantau progres mahasiswa dan kelompok secara real-time</p>
                    <a href="{{ url('/monitoring/all') }}" class="btn btn-primary w-100 text-white">Pantau</a>
                </div>
            </div>
        </div>

        <!-- Laporan Penilaian -->
        <div class="col-md-5">
            <div class="card border-0 shadow-lg h-100 rounded-4 hover-card">
                <div class="card-body text-center p-4">
                    <div class="mb-3 text-primary fs-1">
                        <i class="bi bi-file-earmark-bar-graph"></i>
                    </div>
                    <h5 class="fw-bold text-dark">Laporan Penilaian</h5>
                    <p class="text-muted">Akses rekap penilaian akhir untuk keperluan evaluasi</p>
                    <a href="{{ url('/laporan/akhir') }}" class="btn btn-primary w-100 text-white">Lihat</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Efek Hover -->
<style>
.hover-card {
    transition: all 0.3s ease;
}
.hover-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}
.card-body i {
    transition: color 0.3s ease;
}
.hover-card:hover i {
    color: #0d6efd !important;
}
</style>
@endsection
