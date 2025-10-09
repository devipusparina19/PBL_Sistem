@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <!-- Header -->
    <div class="text-center mb-5">
        <h2 class="fw-bold text-primary">Sistem Penilaian Kinerja Mahasiswa dan Kelompok PBL</h2>
        <p class="text-muted">Selamat datang, {{ Auth::user()->name }} (Koordinator PBL)</p>
    </div>

    <!-- Card Section -->
    <div class="row g-4 justify-content-center">
        <!-- Monitoring Progres (Koordinator only) -->
        <div class="col-md-4">
            <div class="card border-0 shadow-lg h-100 rounded-4 hover-card">
                <div class="card-body text-center p-4">
                    <div class="mb-3 text-primary fs-1">
                        <i class="bi bi-bar-chart-line"></i>
                    </div>
                    <h5 class="fw-bold text-dark">Monitoring Progres</h5>
                    <p class="text-muted">Pantau laporan dan statistik seluruh kelompok</p>
                    <a href="{{ url('/monitoring') }}" class="btn w-100 text-white" style="background-color: #001f5b;">Pantau</a>
                </div>
            </div>
        </div>

        <!-- Progres (Koordinator only) -->
        <div class="col-md-4">
            <div class="card border-0 shadow-lg h-100 rounded-4 hover-card">
                <div class="card-body text-center p-4">
                    <div class="mb-3 text-primary fs-1">
                        <i class="bi bi-graph-up"></i>
                    </div>
                    <h5 class="fw-bold text-dark">Progres</h5>
                    <p class="text-muted">Pantau progres per kelompok dan mahasiswa</p>
                    <a href="{{ url('/milestone/view') }}" class="btn w-100 text-white" style="background-color: #001f5b;">Pantau</a>
                </div>
            </div>
        </div>

        <!-- Opsi lain hanya untuk admin -->
        @if(Auth::user()->role == 'admin')
            <!-- Manajemen Kelompok -->
            <div class="col-md-4">
                <div class="card border-0 shadow-lg h-100 rounded-4 hover-card">
                    <div class="card-body text-center p-4">
                        <div class="mb-3 text-primary fs-1">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <h5 class="fw-bold text-dark">Manajemen Kelompok</h5>
                        <p class="text-muted">Atur pembentukan dan anggota kelompok</p>
                        <a href="{{ url('/kelompok') }}" class="btn btn-primary w-100 text-white">Kelola</a>
                    </div>
                </div>
            </div>

            <!-- Penilaian Mahasiswa -->
            <div class="col-md-4">
                <div class="card border-0 shadow-lg h-100 rounded-4 hover-card">
                    <div class="card-body text-center p-4">
                        <div class="mb-3 text-primary fs-1">
                            <i class="bi bi-pencil-square"></i>
                        </div>
                        <h5 class="fw-bold text-dark">Penilaian Mahasiswa</h5>
                        <p class="text-muted">Berikan nilai dan validasi laporan mahasiswa</p>
                        <a href="{{ url('/nilai') }}" class="btn btn-primary w-100 text-white">Nilai</a>
                    </div>
                </div>
            </div>

            <!-- Manajemen Akun -->
            <div class="col-md-4">
                <div class="card border-0 shadow-lg h-100 rounded-4 hover-card">
                    <div class="card-body text-center p-4">
                        <div class="mb-3 text-primary fs-1">
                            <i class="bi bi-person-badge-fill"></i>
                        </div>
                        <h5 class="fw-bold text-dark">Manajemen Akun</h5>
                        <p class="text-muted">Kelola akun pengguna dan hak akses</p>
                        <a href="{{ url('/manajemen_akun') }}" class="btn btn-primary w-100 text-white">Kelola</a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<style>
.hover-card:hover {
    transform: translateY(-5px);
    transition: all 0.3s ease;
}
</style>
@endsection
