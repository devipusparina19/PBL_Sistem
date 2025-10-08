@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Input Milestone Kelompok {{ $id }}</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('milestone.store', $id) }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="judul" class="form-label">Judul Milestone</label>
            <input type="text" name="judul" id="judul" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="deskripsi" class="form-label">Deskripsi</label>
            <textarea name="deskripsi" id="deskripsi" rows="4" class="form-control" required></textarea>
        </div>

        <div class="mb-3">
            <label for="minggu_ke" class="form-label">Minggu ke-</label>
            <select name="minggu_ke" id="minggu_ke" class="form-select" required>
                <option value="">-- Pilih Minggu --</option>
                @for ($i = 1; $i <= 16; $i++)
                    <option value="{{ $i }}">Minggu {{ $i }}</option>
                @endfor
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Kirim Milestone</button>
    </form>
</div>
@endsection
