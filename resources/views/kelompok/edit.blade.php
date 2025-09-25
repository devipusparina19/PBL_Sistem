@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Edit Kelompok</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('kelompok.update', $kelompok->id_kelompok) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- ID Kelompok (hidden) -->
        <input type="hidden" name="id_kelompok" value="{{ $kelompok->id_kelompok }}">

        <div class="mb-3">
            <label for="nip" class="form-label">NIP</label>
            <input type="text" id="nip" name="nip" class="form-control" value="{{ old('nip', $kelompok->nip) }}" required>
        </div>

        <div class="mb-3">
            <label for="kode_mk" class="form-label">Kode MK</label>
            <input type="text" id="kode_mk" name="kode_mk" class="form-control" value="{{ old('kode_mk', $kelompok->kode_mk) }}" required>
        </div>

        <div class="mb-3">
            <label for="nama_kelompok" class="form-label">Nama Kelompok</label>
            <input type="text" id="nama_kelompok" name="nama_kelompok" class="form-control" value="{{ old('nama_kelompok', $kelompok->nama_kelompok) }}" required>
        </div>

        <div class="mb-3">
            <label for="deskripsi" class="form-label">Deskripsi</label>
            <textarea id="deskripsi" name="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $kelompok->deskripsi) }}</textarea>
        </div>

        <div class="mb-3">
            <label for="judul_proyek" class="form-label">Judul Proyek</label>
            <input type="text" id="judul_proyek" name="judul_proyek" class="form-control" value="{{ old('judul_proyek', $kelompok->judul_proyek) }}" required>
        </div>

        <div class="d-flex justify-content-between">
            <a href="{{ route('kelompok.index') }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-primary">Update</button>
        </div>
    </form>
</div>
@endsection
