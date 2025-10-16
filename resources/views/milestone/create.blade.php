@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">
        <i class="bi bi-flag-fill text-primary"></i>
        Tambah Milestone — Kelompok {{ $kelompok_id }}
    </h2>

    {{-- Pesan sukses --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Pesan error --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Terjadi kesalahan:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li><i class="bi bi-exclamation-circle"></i> {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body">
            <form action="{{ route('milestone.store', $kelompok_id) }}" method="POST">
                @csrf

                {{-- Nama Penginput --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Mahasiswa</label>
                    <input type="text" class="form-control" value="{{ auth()->user()->name }}" readonly>
                </div>

                {{-- Kelompok --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Kelompok ID</label>
                    <input type="text" class="form-control" value="{{ $kelompok_id }}" readonly>
                </div>

                {{-- Judul --}}
                <div class="mb-3">
                    <label for="judul" class="form-label fw-semibold">Judul Milestone</label>
                    <input type="text" name="judul" id="judul" class="form-control" 
                           placeholder="Masukkan judul milestone..." 
                           value="{{ old('judul') }}" required>
                </div>

                {{-- Deskripsi --}}
                <div class="mb-3">
                    <label for="deskripsi" class="form-label fw-semibold">Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi" rows="4" 
                              class="form-control" 
                              placeholder="Tuliskan deskripsi milestone..." 
                              required>{{ old('deskripsi') }}</textarea>
                </div>

                {{-- Minggu ke --}}
                <div class="mb-3">
                    <label for="minggu_ke" class="form-label fw-semibold">Minggu ke-</label>
                    <select name="minggu_ke" id="minggu_ke" class="form-select" required>
                        <option value="">-- Pilih Minggu --</option>
                        @for ($i = 1; $i <= 16; $i++)
                            <option value="{{ $i }}" {{ old('minggu_ke') == $i ? 'selected' : '' }}>
                                Minggu {{ $i }}
                            </option>
                        @endfor
                    </select>
                </div>

                {{-- Tombol Aksi --}}
                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('milestone.view') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-send-fill"></i> Kirim Milestone
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Styling tambahan --}}
<style>
    .card {
        border-radius: 12px;
        transition: 0.2s ease;
    }
    .card:hover {
        box-shadow: 0 4px 18px rgba(0, 0, 0, 0.1);
    }
    .form-label {
        font-weight: 600;
    }
</style>
@endsection
