@extends('layouts.app')

@section('content')
<div class="container py-5">

    <!-- Header -->
    <div class="text-center mb-5">
        <h2 class="fw-semibold text-primary mb-2">Input Nilai Mahasiswa</h2>
        <p class="text-muted fs-5">Form penilaian laporan, presentasi, dan kontribusi mahasiswa dalam proyek PBL</p>
        <hr class="mx-auto mt-3" style="width: 80px; height: 3px; background-color: #0d6efd; border: none;">
    </div>

    <!-- Form Input Nilai -->
    <div class="card shadow border-0 rounded-4 mb-5">
        <div class="card-body px-5 py-4">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form action="{{ route('nilai.store') }}" method="POST">
                @csrf

                <!-- Pilih Mahasiswa -->
                <div class="mb-4">
                    <label class="form-label fw-semibold text-dark">Pilih Mahasiswa</label>
                    <select name="mahasiswa_id" class="form-select form-select-lg rounded-3 shadow-sm" required>
                        <option value="" selected disabled>-- Pilih Mahasiswa --</option>
                        @foreach($mahasiswa as $mhs)
                            <option value="{{ $mhs->id }}">{{ $mhs->nama }} - {{ $mhs->nim }}</option>
                        @endforeach
                    </select>
                    @error('mahasiswa_id')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <!-- Komponen Nilai -->
                <div class="row g-4">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-dark">Laporan Akhir (30%)</label>
                        <input type="number" name="laporan" class="form-control form-control-lg rounded-3 shadow-sm" placeholder="0 - 100" min="0" max="100" required>
                        @error('laporan')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
