@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Edit Milestone</h1>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('milestone.update', $milestone->id) }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="judul" class="form-label">Judul</label>
            <input type="text" name="judul" class="form-control" value="{{ old('judul', $milestone->judul) }}" required>
        </div>

        <div class="mb-3">
            <label for="deskripsi" class="form-label">Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="4" required>{{ old('deskripsi', $milestone->deskripsi) }}</textarea>
        </div>

        <div class="mb-3">
            <label for="minggu_ke" class="form-label">Minggu Ke</label>
            <input type="number" name="minggu_ke" class="form-control" value="{{ old('minggu_ke', $milestone->minggu_ke) }}" min="1" required>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" class="form-select" required>
                <option value="pending" {{ $milestone->status == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="selesai" {{ $milestone->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                <option value="ditolak" {{ $milestone->status == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="catatan_dosen" class="form-label">Catatan Dosen</label>
            <textarea name="catatan_dosen" class="form-control" rows="3">{{ old('catatan_dosen', $milestone->catatan_dosen) }}</textarea>
        </div>

        <button type="submit" class="btn btn-success">Update</button>
        <a href="{{ route('milestone.view') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
