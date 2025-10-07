@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <!-- Header -->
    <div class="text-center mb-5">
        <h2 class="fw-bold text-primary">Sistem Penilaian Kinerja Mahasiswa dan Kelompok PBL</h2>
        <p class="text-muted">Selamat datang, {{ Auth::user()->name }} (Koordinatot PBL)</p>
    </div>
<div class="row">
    <!-- Manajemen Kelompok -->
    <div class="col-md-4">
        <div class="card text-center shadow">
            <div class="card-body">
                <h5 class="card-title">Manajemen Kelompok</h5>
                <p>Atur pembentukan & anggota kelompok</p>
                <a href="{{ url('/kelompok') }}" class="btn btn-primary text-white">Kelola</a>
            </div>
        </div>
    </div>

    <!-- Monitoring Progres -->
    <div class="col-md-4">
        <div class="card text-center shadow">
            <div class="card-body">
                <h5 class="card-title">Monitoring Progres</h5>
                <p>Lihat progres seluruh kelompok</p>
                <a href="{{ url('/monitoring') }}" class="btn btn-primary text-white">Pantau</a>
            </div>
        </div>
    </div>

    <!-- Rekap Laporan -->
    <div class="col-md-4">
        <div class="card text-center shadow">
            <div class="card-body">
                <h5 class="card-title">Rekap Laporan</h5>
                <p>Rekap nilai & progres untuk evaluasi PBL</p>
                <a href="{{ url('/laporan') }}" class="btn btn-primary text-white">Lihat</a>
            </div>
        </div>
    </div>
</div>
@endsection
