@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <!-- Header -->
    <div class="text-center mb-5">
        <h2 class="fw-bold text-primary">Sistem Penilaian Kinerja Mahasiswa dan Kelompok PBL</h2>
        <p class="text-muted">Selamat datang, {{ Auth::user()->name }} (Mahasiswa)</p>
    </div>

    <div class="row g-4">
        <!-- Milestone / Logbook -->
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0 rounded-3">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="bi bi-journal-text text-success" style="font-size:2rem;"></i>
                    </div>
                    <h5 class="card-title">Milestone / Logbook</h5>
                    <p class="text-muted">Isi progres mingguan proyek Anda bersama kelompok.</p>
                    <a href="{{ url('/milestone') }}" class="btn btn-success w-100">Isi Logbook</a>
                </div>
            </div>
        </div>

        <!-- Nilai & Umpan Balik -->
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0 rounded-3">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="bi bi-bar-chart-line text-primary" style="font-size:2rem;"></i>
                    </div>
                    <h5 class="card-title">Nilai & Umpan Balik</h5>
                    <p class="text-muted">Lihat hasil penilaian dari dosen pembimbing dan penguji.</p>
                    <a href="{{ url('/nilai') }}" class="btn btn-primary w-100">Lihat Nilai</a>
                </div>
            </div>
        </div>

        <!-- Ranking -->
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0 rounded-3">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="bi bi-trophy text-warning" style="font-size:2rem;"></i>
                    </div>
                    <h5 class="card-title">Ranking</h5>
                    <p class="text-muted">Cek posisi Anda dan kelompok dalam peringkat PBL.</p>
                    <a href="{{ url('/ranking') }}" class="btn btn-warning w-100 text-white">Lihat Ranking</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
