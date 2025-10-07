@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <!-- Header -->
    <div class="text-center mb-5">
        <h2 class="fw-bold text-primary">Sistem Penilaian Kinerja Mahasiswa dan Kelompok PBL</h2>
        <p class="text-muted">Selamat datang, {{ Auth::user()->name }} (Koordinator Prodi)</p>
    </div>
<div class="row">
    <!-- Monitoring Keseluruhan -->
    <div class="col-md-6">
        <div class="card text-center shadow">
            <div class="card-body">
                <h5 class="card-title">Monitoring Keseluruhan</h5>
                <p>Pantau progres mahasiswa & kelompok</p>
                <a href="{{ url('/monitoring/all') }}" class="btn btn-primary text-white">Pantau</a>
            </div>
        </div>
    </div>

    <!-- Laporan Penilaian -->
    <div class="col-md-6">
        <div class="card text-center shadow">
            <div class="card-body">
                <h5 class="card-title">Laporan Penilaian</h5>
                <p>Akses rekap penilaian akhir untuk evaluasi</p>
                <a href="{{ url('/laporan/akhir') }}" class="btn btn-primary text-white">Lihat</a>
            </div>
        </div>
    </div>
</div>
@endsection
