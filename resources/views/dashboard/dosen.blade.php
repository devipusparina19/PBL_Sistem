@extends('layouts.app')

@section('content')
<div class="row">
    <!-- Validasi Milestone -->
    <div class="col-md-4">
        <div class="card text-center shadow">
            <div class="card-body">
                <h5 class="card-title">Validasi Milestone</h5>
                <p>Periksa dan setujui progres mahasiswa</p>
                <a href="{{ url('/validasi-milestone') }}" class="btn btn-success">Validasi</a>
            </div>
        </div>
    </div>

    <!-- Input Nilai -->
    <div class="col-md-4">
        <div class="card text-center shadow">
            <div class="card-body">
                <h5 class="card-title">Input Nilai</h5>
                <p>Beri penilaian laporan, presentasi, kontribusi</p>
                <a href="{{ url('/nilai/input') }}" class="btn btn-primary">Input</a>
            </div>
        </div>
    </div>

    <!-- Monitoring Progres -->
    <div class="col-md-4">
        <div class="card text-center shadow">
            <div class="card-body">
                <h5 class="card-title">Monitoring Progres</h5>
                <p>Pantau logbook mahasiswa/kelompok</p>
                <a href="{{ url('/monitoring') }}" class="btn btn-info">Pantau</a>
            </div>
        </div>
    </div>
</div>
@endsection
