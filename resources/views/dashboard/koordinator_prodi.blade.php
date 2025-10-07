@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <!-- Header -->
    <div class="text-center mb-5">
        <h2 class="fw-bold text-primary mb-2">Sistem Penilaian Kinerja Mahasiswa & Kelompok PBL</h2>
        <p class="text-muted fs-5">Selamat datang, <strong>{{ Auth::user()->name }}</strong> (Koordinator Prodi)</p>
        <hr class="w-50 mx-auto mb-4">
    </div>

    <!-- Card Section -->
    <div class="row justify-content-center g-4">
        <!-- Monitoring Keseluruhan -->
        <div class="col-md-5">
            <div class="card border-0 shadow-lg h-100 rounded-4 hover-card">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <i class="bi bi-bar-chart-line fs-1 text-primary"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Monitoring Keseluruhan</h5>
                    <p class="text-muted mb-4">Pantau progres mahasiswa dan kelompok secara real-time</p>
                    <a href="{{ url('/monitoring/all') }}" class="btn btn-primary w-100 text-white">Pantau</a>
                </div>
            </div>
        </div>

        <!-- Laporan Penilaian -->
        <div class="col-md-5">
            <div class="card border-0 shadow-lg h-100 rounded-4 hover-card">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <i class="bi bi-file-earmark-bar-graph fs-1 text-success"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Laporan Penilaian</h5>
                    <p class="text-muted mb-4">Akses rekap penilaian akhir untuk keperluan evaluasi</p>
                    <a href="{{ url('/laporan/akhir') }}" class="btn btn-primary w-100 text-white">Lihat</a>
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
