@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <!-- Header -->
    <div class="text-center mb-5">
        <h2 class="fw-bold text-primary">Sistem Penilaian Kinerja Mahasiswa dan Kelompok PBL</h2>
        <p class="text-muted">Selamat datang, {{ Auth::user()->name }} (Koordinator PBL)</p>
    </div>

    <!-- Card Section -->
    <div class="row g-4">
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

        <!-- Monitoring Progres -->
        <div class="col-md-4">
            <div class="card border-0 shadow-lg h-100 rounded-4 hover-card">
                <div class="card-body text-center p-4">
                    <div class="mb-3 text-primary fs-1">
                        <i class="bi bi-bar-chart-line"></i>
                    </div>
                    <h5 class="fw-bold text-dark">Monitoring Progres</h5>
                    <p class="text-muted">Pantau progres seluruh kelompo
