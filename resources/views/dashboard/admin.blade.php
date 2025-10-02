@extends('layouts.app')

@section('content')
<div class="text-center mb-5">
        <h2 class="fw-bold text-primary">Sistem Penilaian Kinerja Mahasiswa dan Kelompok PBL</h2>
        <p class="text-muted">Selamat datang, {{ Auth::user()->name }} (Admin)</p>
    </div>


    <div class="row g-4">
        <!-- Manajemen Akun -->
        <div class="col-md-3">
            <div class="card shadow-lg border-0 h-100">
                <div class="card-body text-center">
                    <div class="mb-3 text-primary fs-1">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <h5 class="card-title fw-bold">Manajemen Akun</h5>
                    <p class="text-muted">Kelola akun pengguna sistem</p>
                    <a href="{{ url('/akun') }}" class="btn btn-primary w-100">Kelola</a>
                </div>
            </div>
        </div>

        <!-- Data Mahasiswa -->
        <div class="col-md-3">
            <div class="card shadow-lg border-0 h-100">
                <div class="card-body text-center">
                    <div class="mb-3 text-success fs-1">
                        <i class="bi bi-person-badge-fill"></i>
                    </div>
                    <h5 class="card-title fw-bold">Data Mahasiswa</h5>
                    <p class="text-muted">Tambah/edit data mahasiswa</p>
                    <a href="{{ url('/mahasiswa') }}" class="btn btn-success w-100">Kelola</a>
                </div>
            </div>
        </div>

        <!-- Data Dosen -->
        <div class="col-md-3">
            <div class="card shadow-lg border-0 h-100">
                <div class="card-body text-center">
                    <div class="mb-3 text-info fs-1">
                        <i class="bi bi-person-video3"></i>
                    </div>
                    <h5 class="card-title fw-bold">Data Dosen</h5>
                    <p class="text-muted">Tambah/edit data dosen</p>
                    <a href="{{ url('/data_dosen') }}" class="btn btn-info w-100">Kelola</a>
                </div>
            </div>
        </div>

        <!-- Data Kelompok -->
        <div class="col-md-3">
            <div class="card shadow-lg border-0 h-100">
                <div class="card-body text-center">
                    <div class="mb-3 text-warning fs-1">
                        <i class="bi bi-people"></i>
                    </div>
                    <h5 class="card-title fw-bold">Data Kelompok</h5>
                    <p class="text-muted">Atur kelompok mahasiswa</p>
                    <a href="{{ url('/kelompok') }}" class="btn btn-warning w-100 text-white">Kelola</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
