@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <!-- Header -->
    <div class="text-center mb-5">
        <h2 class="fw-bold text-primary mb-2">Sistem Penilaian Kinerja Mahasiswa & Kelompok PBL</h2>
        <p class="text-muted fs-5">Selamat datang, <strong>{{ Auth::user()->name }}</strong> (Koordinator PBL)</p>
        <hr class="w-50 mx-auto mb-4">
    </div>

    <!-- Card Section -->
    <div class="row justify-content-center g-4">
        <!-- Manajemen Kelompok -->
        <div class="col-md-4">
            <div class="card border-0 shadow-lg h-100 rounded-4 hover-card">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <i class="bi bi-people-fill fs-1 text-primary"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Manajemen Kelompok</h5>
                    <p class="text-muted mb-4">Atur pembentukan & anggota kelompok</p>
                    <a href="{{ url('/kelompok') }}" class="btn btn-primary w-100 text-white">Kelola</a>
                </div>
            </div>
        </div>

        <!-- Monitoring Progres -->
        <div class="col-md-4">
            <div class="card border-0 shadow-lg h-100 rounded-4 hover-card">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <i class="bi bi-bar-chart-line-fill fs-1 text-success"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Monitoring Progres</h5>
                    <p class="text-muted mb-4">Lihat progres seluruh kelompok</p>
                    <a href="{{ url('/monitoring') }}" class="btn btn-primary w-100 text-white">Pantau</a>
                </div>
            </div>
        </div>

        <!-- Rekap Laporan -->
        <div class="col-md-4">
            <div class="card border-0 shadow-lg h-100 rounded-4 hover-card">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <i class="bi bi-file-earmark-text-fill fs-1 text-warning"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Rekap Laporan</h5>
                    <p class="text-muted mb-4">Rekap nilai & progres untuk evaluasi PBL</p>
                    <a href="{{ url('/laporan') }}" class="btn btn-primary w-100 text-white">Lihat</a>
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
