@extends('layouts.app')

@section('content')
<div class="row">
    <!-- Milestone -->
    <div class="col-md-4">
        <div class="card text-center shadow">
            <div class="card-body">
                <h5 class="card-title">Milestone / Logbook</h5>
                <p>Isi progres mingguan proyek Anda</p>
                <a href="{{ url('/milestone') }}" class="btn btn-success">Isi</a>
            </div>
        </div>
    </div>

    <!-- Nilai -->
    <div class="col-md-4">
        <div class="card text-center shadow">
            <div class="card-body">
                <h5 class="card-title">Nilai & Umpan Balik</h5>
                <p>Lihat hasil penilaian dosen</p>
                <a href="{{ url('/nilai') }}" class="btn btn-primary">Lihat</a>
            </div>
        </div>
    </div>

    <!-- Ranking -->
    <div class="col-md-4">
        <div class="card text-center shadow">
            <div class="card-body">
                <h5 class="card-title">Ranking</h5>
                <p>Posisi Anda dan kelompok dalam sistem</p>
                <a href="{{ url('/ranking') }}" class="btn btn-info">Lihat</a>
            </div>
        </div>
    </div>
</div>
@endsection
