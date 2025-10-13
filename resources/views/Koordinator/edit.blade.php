@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3 class="fw-bold mb-4 text-center text-primary">Edit Data Kelompok</h3>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('koordinator.update', $kelompok->id_kelompok) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="nama_kelompok" class="form-label fw-semibold">Nama Kelompok</label>
                    <input type="text" name="nama_kelompok" id="nama_kelompok" class="form-control" value="{{ $kelompok->nama_kelompok }}" required>
                </div>

                <div class="mb-3">
                    <label for="judul_proyek" class="form-label fw-semibold">Judul Proyek</label>
                    <input type="text" name="judul_proyek" id="judul_proyek" class="form-control" value="{{ $kelompok->judul_proyek }}" required>
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label fw-semibold">Status</label>
                    <select name="status" id="status" class="form-select">
                        <option value="Belum Mulai" {{ $kelompok->status == 'Belum Mulai' ? 'selected' : '' }}>Belum Mulai</option>
                        <option value="Berjalan" {{ $kelompok->status == 'Berjalan' ? 'selected' : '' }}>Berjalan</option>
                        <option value="Selesai" {{ $kelompok->status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>

                <div class="text-end">
                    <a href="{{ route('koordinator.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-warning">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
