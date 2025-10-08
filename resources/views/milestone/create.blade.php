@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Input Milestone Kelompok {{ $kelompok_id }}</h2>

    {{-- Pesan sukses --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Pesan error --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('milestone.store', $kelompok_id) }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="judul" class="form-label">Judul Milestone</label>
                    <input type="text" name="judul" id="judul" class="form-control" value="{{ old('judul') }}" required>
                </div>

                <div class="mb-3">
                    <label for="deskripsi" class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi" rows="4" class="form-control" required>{{ old('deskripsi') }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="minggu_ke" class="form-label">Minggu ke-</label>
                    <select name="minggu_ke" id="minggu_ke" class="form-select" required>
                        <option value="">-- Pilih Minggu --</option>
                        @for ($i = 1; $i <= 16; $i++)
                            <option value="{{ $i }}" {{ old('minggu_ke') == $i ? 'selected' : '' }}>Minggu {{ $i }}</option>
                        @endfor
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Kirim Milestone</button>
            </form>
        </div>
    </div>
</div>
@endsection
