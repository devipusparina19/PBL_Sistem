@extends('layouts.app')

@section('content')
<div class="container">

    <!-- Hero Section -->
    <div class="text-center py-4">
        <h1 class="fw-bold text-primary">Selamat Datang di Sistem PBL Mahasiswa TI</h1>
        <p class="text-muted fs-5 mt-3">
            Platform untuk memantau kinerja mahasiswa, dosen, dan kelompok PBL secara efisien.
    </div>

    <hr class="my-5">

    <!-- Fitur Utama -->
    <div class="row text-center g-3">
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body">
                    <i class="bi bi-people text-primary" style="font-size:2.5rem;"></i>
                    <h5 class="mt-3">Data Mahasiswa</h5>
                    <p class="text-muted">Kelola informasi mahasiswa dengan mudah dan cepat.</p>
                    <a href="{{ route('mahasiswa.index') }}" class="btn btn-outline-primary">Lihat</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body">
                    <i class="bi bi-person-badge text-success" style="font-size:2.5rem;"></i>
                    <h5 class="mt-3">Data Dosen</h5>
                    <p class="text-muted">Akses data dosen pembimbing & penguji PBL.</p>
                    <a href="{{ route('data_dosen.index') }}" class="btn btn-outline-success">Lihat</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body">
                    <i class="bi bi-diagram-3 text-warning" style="font-size:2.5rem;"></i>
                    <h5 class="mt-3">Kelompok PBL</h5>
                    <p class="text-muted">Pantau progress & kerja sama kelompok proyek Anda.</p>
                    <a href="{{ route('kelompok.index') }}" class="btn btn-outline-warning">Lihat</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Info Section -->
    <div class="my-5 text-center">
        <div class="alert alert-info shadow-sm w-75 mx-auto">
            <strong>ℹ️ Info:</strong> Sistem ini dibuat untuk membantu monitoring PBL agar lebih terstruktur, transparan, dan mudah digunakan oleh mahasiswa maupun dosen.
        </div>
    </div>
</div>
@endsection
