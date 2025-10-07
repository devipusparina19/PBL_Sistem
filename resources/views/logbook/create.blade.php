@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Isi Logbook</h2>

    <form action="{{ route('logbook.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        @php
            $today = now();
            $weekOfMonth = ceil($today->day / 7); // hitung minggu ke-berapa dalam bulan
        @endphp

        <div class="mb-3">
            <label>Tanggal</label>
            <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" readonly>
        </div>

        <div class="mb-3">
            <label>Minggu ke</label>
            <input type="text" name="minggu_ke" class="form-control" value="{{ $weekOfMonth }}" readonly>
            <small class="text-muted">Minggu ke-{{ $weekOfMonth }} dari bulan ini</small>
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
