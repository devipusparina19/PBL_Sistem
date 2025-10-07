@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
<div class="container">
    <h1 class="fw-bold mb-3">Selamat Datang di Sistem PBL Mahasiswa TI</h1>
    <p class="text-secondary">
        Sistem ini digunakan untuk mengelola kegiatan <strong>Proyek Berbasis Pembelajaran (PBL)</strong>,
        mencakup data mahasiswa, dosen pembimbing, kelompok, dan penilaian.
    </p>

    <hr>

    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title fw-bold"><i class="bi bi-mortarboard-fill me-2"></i> Mahasiswa</h5>
                    <p class="card-text text-muted">Melihat kelompok, nilai, dan dosen pembimbing.</p>
                    <a href="{{ url('/mahasiswa') }}" class="btn btn-primary btn-sm">Kelola Data Mahasiswa</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title fw-bold"><i class="bi bi-person-badge-fill me-2"></i> Dosen</h5>
                    <p class="card-text text-muted">Mengelola bimbingan dan memberikan penilaian PBL.</p>
                    <a href="{{ url('/dosen') }}" class="btn btn-primary btn-sm">Kelola Data Dosen</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title fw-bold"><i class="bi bi-people-fill me-2"></i> Kelompok PBL</h5>
                    <p class="card-text text-muted">Mengatur pembagian kelompok mahasiswa dan dosen pembimbing.</p>
                    <a href="{{ url('/kelompok') }}" class="btn btn-primary btn-sm">Lihat Kelompok</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
