@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Isi Logbook</h2>

    <form action="{{ route('milestones.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label>Tanggal</label>
            <input type="date" name="tanggal" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Judul</label>
            <input type="text" name="judul" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Kelompok</label>
            <input type="text" name="kelompok" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Rincian Kegiatan</label>
            <textarea name="rincian" class="form-control" rows="4" required></textarea>
        </div>

        <div class="mb-3">
            <label>Foto Dokumentasi</label>
            <input type="file" name="foto" class="form-control">
        </div>

        <button type="submit" class="btn btn-success">Simpan</button>
    </form>
</div>
@endsection