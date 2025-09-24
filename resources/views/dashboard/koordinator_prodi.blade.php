@extends('layouts.app')

@section('content')
<div class="row">
    <!-- Monitoring Keseluruhan -->
    <div class="col-md-6">
        <div class="card text-center shadow">
            <div class="card-body">
                <h5 class="card-title">Monitoring Keseluruhan</h5>
                <p>Pantau progres mahasiswa & kelompok</p>
                <a href="{{ url('/monitoring/all') }}" class="btn btn-info">Pantau</a>
            </div>
        </div>
    </div>

    <!-- Laporan Penilaian -->
    <div class="col-md-6">
        <div class="card text-center shadow">
            <div class="card-body">
                <h5 class="card-title">Laporan Penilaian</h5>
                <p>Akses rekap penilaian akhir untuk evaluasi</p>
                <a href="{{ url('/laporan/akhir') }}" class="btn btn-success">Lihat</a>
            </div>
        </div>
    </div>
</div>
@endsection
