@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-warning text-dark rounded-top-4">
            <h4 class="mb-0"><i class="bi bi-pencil-square me-2"></i>Edit Logbook</h4>
        </div>
        <div class="card-body p-4">
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('logbook.update', $logbook->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal', $logbook->tanggal) }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Minggu ke</label>
                        <select name="minggu_ke" class="form-select" required>
                            <option value="">-- Pilih Minggu --</option>
                            @for ($i = 1; $i <= 16; $i++)
                                <option value="Minggu ke-{{ $i }}" {{ old('minggu_ke', $logbook->minggu_ke) == "Minggu ke-$i" ? 'selected' : '' }}>
                                    Minggu ke-{{ $i }}
                                </option>
                            @endfor
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Kelompok</label>
                    <input type="text" name="kelompok" class="form-control" value="{{ old('kelompok', $logbook->kelompok) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Judul Kegiatan</label>
                    <input type="text" name="judul" class="form-control" value="{{ old('judul', $logbook->judul) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Rincian Kegiatan</label>
                    <textarea name="rincian" class="form-control" rows="5" required>{{ old('rincian', $logbook->rincian) }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Foto Dokumentasi</label>
                    @if($logbook->foto)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $logbook->foto) }}" alt="Foto Logbook" class="img-thumbnail" style="max-height: 200px;">
                            <p class="text-muted small">Foto saat ini</p>
                        </div>
                    @endif
                    <input type="file" name="foto" class="form-control" accept="image/jpeg,image/png,image/jpg">
                    <small class="text-muted">Kosongkan jika tidak ingin mengubah foto. Format: JPG, JPEG, PNG (Maks. 2MB)</small>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-check-circle me-1"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('logbook.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
