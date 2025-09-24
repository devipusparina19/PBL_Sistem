@extends('layouts.app')

@section('content')
<div class="row">
    <!-- Manajemen Akun -->
    <div class="col-md-3">
        <div class="card text-center shadow">
            <div class="card-body">
                <h5 class="card-title">Manajemen Akun</h5>
                <p>Kelola akun pengguna sistem</p>
                <a href="{{ url('/akun') }}" class="btn btn-primary">Kelola</a>
            </div>
        </div>
    </div>

    <!-- Data Mahasiswa -->
    <div class="col-md-3">
        <div class="card text-center shadow">
            <div class="card-body">
                <h5 class="card-title">Data Mahasiswa</h5>
                <p>Tambah/edit data mahasiswa</p>
                <a href="{{ url('/mahasiswa') }}" class="btn btn-primary">Kelola</a>
            </div>
        </div>
    </div>

    <!-- Data Dosen -->
    <div class="col-md-3">
        <div class="card text-center shadow">
            <div class="card-body">
                <h5 class="card-title">Data Dosen</h5>
                <p>Tambah/edit data dosen</p>
                <a href="{{ url('/dosen') }}" class="btn btn-primary">Kelola</a>
            </div>
        </div>
    </div>

    <!-- Data Kelompok -->
    <div class="col-md-3">
        <div class="card text-center shadow">
            <div class="card-body">
                <h5 class="card-title">Data Kelompok</h5>
                <p>Atur kelompok awal mahasiswa</p>
                <a href="{{ url('/kelompok') }}" class="btn btn-primary">Kelola</a>
            </div>
        </div>
    </div>
</div>
@endsection
