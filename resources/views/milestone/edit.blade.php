@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">
        <i class="bi bi-pencil-square text-primary"></i> Edit Milestone
    </h2>

    {{-- Pesan Error --}}
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
            <form action="{{ route('milestone.update', $milestone->id) }}" method="POST">
                @csrf

                {{-- Judul --}}
                <div class="mb-3">
                    <label for="judul" class="form-label fw-semibold">Judul Milestone</label>
                    <input type="text" name="judul" id="judul" 
                           class="form-control" 
                           value="{{ old('judul', $milestone->judul) }}" 
                           required>
                </div>

                {{-- Deskripsi --}}
                <div class="mb-3">
                    <label for="deskripsi" class="form-label fw-semibold">Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi" rows="4" 
                              class="form-control" required>{{ old('deskripsi', $milestone->deskripsi) }}</textarea>
                </div>

                {{-- Minggu ke --}}
                <div class="mb-3">
                    <label for="minggu_ke" class="form-label fw-semibold">Minggu ke-</label>
                    <select name="minggu_ke" id="minggu_ke" class="form-select" required>
                        @for ($i = 1; $i <= 16; $i++)
                            <option value="{{ $i }}" 
                                {{ old('minggu_ke', $milestone->minggu_ke) == $i ? 'selected' : '' }}>
                                Minggu {{ $i }}
                            </option>
                        @endfor
                    </select>
                </div>

                {{-- Status & Catatan Dosen (hanya untuk dosen) --}}
                @if(auth()->user()->role === 'dosen')
                    <div class="mb-3">
                        <label for="status" class="form-label fw-semibold">Status</label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="menunggu" {{ $milestone->status == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                            <option value="disetujui" {{ $milestone->status == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                            <option value="ditolak" {{ $milestone->status == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="catatan_dosen" class="form-label fw-semibold">Catatan Dosen</label>
                        <textarea name="catatan_dosen" id="catatan_dosen" rows="3" 
                                  class="form-control" 
                                  placeholder="Tambahkan catatan jika ada...">{{ old('catatan_dosen', $milestone->catatan_dosen) }}</textarea>
                    </div>
                @endif

                {{-- Tombol --}}
                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('milestone.view') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-save"></i> Simpan Perubahan
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
