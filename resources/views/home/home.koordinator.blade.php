@extends('layouts.app')

@section('content')
<div class="container">
    <div class="text-center py-4">
        {{-- Tampilkan judul yang disesuaikan berdasarkan peran --}}
        @php
            $role = Auth::user()->role;
            $title = ($role == 'koor_prodi') ? 'Koordinator Program Studi' : 'Koordinator PBL';
        @endphp
        
        <h2 class="fw-bold text-warning">Dashboard {{ $title }}</h2>
        <p class="text-muted fs-5 mt-2">Selamat datang, {{ Auth::user()->name }}. Anda berada di Pusat Monitoring Kinerja.</p>
    </div>

    <div class="row text-center g-4 mt-3">
        
        {{-- Card 1: Monitoring Progres Umum --}}
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body">
                    <h5 class="mt-3">Progres PBL Umum</h5>
                    <p class="text-muted">Tinjau grafik persentase Logbook dan Milestone yang sudah tercapai oleh semua kelompok.</p>
                    {{-- Tombol Lihat/Akses Laporan --}}
                    <a href="{{ route('koordinator.progress_report') }}" class="btn btn-info text-white">Lihat Progres</a>
                </div>
            </div>
        </div>

        {{-- Card 2: Laporan Kinerja Dosen (Evaluasi) --}}
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body">
                    <h5 class="mt-3">Evaluasi Kinerja Dosen</h5>
                    <p class="text-muted">Akses laporan alokasi, beban bimbingan, dan ketepatan waktu penilaian dosen.</p>
                    {{-- Tombol Lihat/Akses Laporan --}}
                    <a href="{{ route('koordinator.dosen_report') }}" class="btn btn-primary">Lihat Kinerja</a>
                </div>
            </div>
        </div>

        {{-- Card 3: Laporan Nilai Akhir & Audit --}}
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body">
                    <h5 class="mt-3">Laporan Nilai Akhir</h5>
                    <p class="text-muted">Lihat dan unduh rekapitulasi nilai akhir PBL untuk semua kelompok dan mahasiswa.</p>
                    {{-- Tombol Lihat/Akses Laporan --}}
                    <a href="{{ route('koordinator.final_grades') }}" class="btn btn-success">Akses Laporan</a>
                </div>
            </div>
        </div>
        
    </div>
</div>
@endsection