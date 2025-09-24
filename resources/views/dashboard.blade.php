@extends('layouts.app')

@section('content')
<div class="row">
    <!-- Akun Pribadi -->
    <div class="col-md-3">
        <div class="card text-center shadow">
            <div class="card-body">
                <h5 class="card-title">Akun Pribadi</h5>
                <p>Kelola akun pribadi Anda</p>
                <a href="{{ url('/akun-pribadi') }}" class="btn btn-primary">Lihat</a>
            </div>
        </div>
    </div>

    <!-- Akun Mahasiswa -->
    <div class="col-md-3">
        <div class="card text-center shadow">
            <div class="card-body">
                <h5 class="card-title">Akun Mahasiswa</h5>
                <p>Kelola data mahasiswa peserta PBL</p>
                <a href="{{ url('/mahasiswa') }}" class="btn btn-primary">Lihat</a>
            </div>
        </div>
    </div>
    
    <!-- Dosen -->
    <div class="col-md-3">
        <div class="card text-center shadow">
            <div class="card-body">
                <h5 class="card-title">Dosen</h5>
                <p>Data dosen pembimbing dan penilai</p>
                <a href="{{ url('/dosen') }}" class="btn btn-primary">Lihat</a>
            </div>
        </div>
    </div>
    
    <!-- Milestone -->
    <div class="col-md-3">
        <div class="card text-center shadow">
            <div class="card-body">
                <h5 class="card-title">Milestone</h5>
                <p>Progress tahapan PBL</p>
                <a href="{{ url('/milestone/create') }}" class="btn btn-success">Tambah</a>
            </div>
        </div>
    </div>
    
    <!-- Koordinator PBL -->
    <div class="col-md-3 mt-3">
        <div class="card text-center shadow">
            <div class="card-body">
                <h5 class="card-title">Koordinator PBL</h5>
                <p>Informasi & akun koordinator</p>
                <a href="{{ url('/koor') }}" class="btn btn-primary">Lihat</a>
            </div>
        </div>
    </div>
</div>
@endsection
