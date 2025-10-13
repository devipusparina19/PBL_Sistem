@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3 class="fw-bold mb-4 text-center text-primary">Tambah Kelompok Baru</h3>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('koordinator.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="nama_kelompok" class="form-label fw-semibold">Nama Kelompok</label>
                    <input type="text" name="nama_kelompok" id="nama_kelompok" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="judul_proyek" class="form-label fw-semibold">Judul Proyek</label>
                    <input type="text" name="judul_proyek" id="judul_proyek" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label fw-semibold">Status</label>
                    <select name="status" id="status" class="form-select">
                        <option value="Belum Mulai">Belum Mulai</option>
                        <option value="Berjalan">Berjalan</option>
                        <option value="Selesai">Selesai</option>
                    </select>
                </div>

                <div class="text-end">
                    <a href="{{ route('koordinator.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
