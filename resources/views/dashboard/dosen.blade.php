@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold text-primary">Sistem Penilaian Kinerja Mahasiswa dan Kelompok PBL</h2>
        <p class="text-muted">Selamat datang, {{ Auth::user()->name }} (Dosen)</p>
    </div>

    <div class="row g-4 justify-content-center">
        <!-- Validasi Milestone -->
        <div class="col-md-4">
            <div class="card text-center shadow h-100">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">Validasi Milestone</h5>
                    <p class="card-text flex-grow-1">Periksa dan setujui progres mahasiswa</p>
                    <a href="{{ url('milestone/validasi') }}" class="btn btn-primary text-white mt-auto">Validasi</a>
                </div>
            </div>
        </div>

        <!-- Input Nilai -->
        <div class="col-md-4">
            <div class="card text-center shadow h-100">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">Input Nilai</h5>
                    <p class="card-text flex-grow-1">Beri penilaian laporan, presentasi, kontribusi</p>
                    <a href="{{ url('/nilai/input') }}" class="btn btn-primary text-white mt-auto">Input</a>
                </div>
            </div>
        </div>

        <!-- Monitoring Progres -->
        <div class="col-md-4">
            <div class="card text-center shadow h-100">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">Monitoring Progres</h5>
                    <p class="card-text flex-grow-1">Pantau logbook mahasiswa/kelompok</p>
                    <a href="{{ url('/monitoring') }}" class="btn btn-primary text-white mt-auto">Pantau</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
