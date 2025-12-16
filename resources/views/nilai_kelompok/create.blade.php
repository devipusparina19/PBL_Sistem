@extends('layouts.app')

@section('content')
<div class="container py-5">

    <!-- Header -->
    <div class="text-center mb-5">
        <h2 class="fw-semibold text-primary mb-2">Input Nilai Kelompok</h2>
        <p class="text-muted">Input nilai kelompok untuk semua komponen penilaian PBL</p>
        <hr class="mx-auto mt-3" style="width: 80px; height: 3px; background-color: #0d6efd; border: none;">
    </div>

    <!-- Form Card -->
    <div class="card shadow border-0 rounded-4">
        <div class="card-body px-5 py-4">
            <form action="{{ route('nilai_kelompok.store') }}" method="POST">
                @csrf

                <!-- Pilih Kelompok -->
                <div class="mb-4">
                    <label for="kelompok_id" class="form-label fw-semibold">Pilih Kelompok <span class="text-danger">*</span></label>
                    <select name="kelompok_id" id="kelompok_id" class="form-select @error('kelompok_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Kelompok --</option>
                        @foreach($kelompoks as $klp)
                            <option value="{{ $klp->id_kelompok }}" 
                                    {{ old('kelompok_id', request('kelompok_id')) == $klp->id_kelompok ? 'selected' : '' }}>
                                {{ $klp->nama_kelompok }} 
                                @if($klp->judul_proyek)
                                    - {{ $klp->judul_proyek }}
                                @endif
                                ({{ $klp->mahasiswa->count() }} anggota)
                            </option>
                        @endforeach
                    </select>
                    @error('kelompok_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <hr class="my-4">

                <h5 class="fw-bold text-dark mb-4">Komponen Penilaian Kelompok</h5>

                <div class="row">
                    <!-- Note about automated values -->
                    <div class="col-12 mb-3">
                        <div class="alert alert-secondary">
                            <i class="bi bi-gear-fill me-2"></i>
                            Nilai untuk <strong>Pemrograman Web, Integrasi Sistem, Pengambilan Keputusan, dan IT Proyek</strong> 
                            akan diambil secara <strong>otomatis</strong> dari rata-rata nilai mata kuliah anggota kelompok.
                        </div>
                    </div>

                    <!-- Kontribusi Kelompok -->
                    <div class="col-md-6 mb-3">
                        <label for="kontribusi_kelompok" class="form-label">Kontribusi Kelompok <span class="text-danger">*</span></label>
                        <input type="number" name="kontribusi_kelompok" id="kontribusi_kelompok" 
                               class="form-control @error('kontribusi_kelompok') is-invalid @enderror" 
                               value="{{ old('kontribusi_kelompok', 0) }}" 
                               min="0" max="100" step="0.01" required>
                        <small class="text-muted">Nilai kontribusi kelompok secara keseluruhan (0-100)</small>
                        @error('kontribusi_kelompok')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Penilaian Dosen -->
                    <div class="col-md-6 mb-3">
                        <label for="penilaian_dosen" class="form-label">Penilaian Dosen <span class="text-danger">*</span></label>
                        <input type="number" name="penilaian_dosen" id="penilaian_dosen" 
                               class="form-control @error('penilaian_dosen') is-invalid @enderror" 
                               value="{{ old('penilaian_dosen', 0) }}" 
                               min="0" max="100" step="0.01" required>
                        <small class="text-muted">Penilaian subjektif dosen pembimbing (0-100)</small>
                        @error('penilaian_dosen')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Hasil Akhir Proyek -->
                    <div class="col-md-12 mb-3">
                        <label for="hasil_akhir" class="form-label">Hasil Akhir Proyek <span class="text-danger">*</span></label>
                        <input type="number" name="hasil_akhir" id="hasil_akhir" 
                               class="form-control @error('hasil_akhir') is-invalid @enderror" 
                               value="{{ old('hasil_akhir', 0) }}" 
                               min="0" max="100" step="0.01" required>
                        <small class="text-muted">Nilai akhir proyek secara keseluruhan (0-100)</small>
                        @error('hasil_akhir')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>



                <hr class="my-4">

                <!-- Info -->
                <div class="alert alert-info" role="alert">
                    <i class="bi bi-info-circle-fill me-2"></i>
                    <strong>Catatan:</strong> Nilai Kontribusi dan Hasil Akhir Proyek akan digunakan untuk perhitungan ranking mahasiswa (AHP/SAW).
                </div>

                <!-- Buttons -->
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('nilai_kelompok.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle me-2"></i>Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-2"></i>Simpan Nilai Kelompok
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

<style>
.form-label {
    font-size: 0.95rem;
}

.form-control, .form-select {
    border-radius: 8px;
    border: 1px solid #ced4da;
}

.form-control:focus, .form-select:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
}

.btn {
    border-radius: 8px;
    padding: 10px 20px;
}

small.text-muted {
    font-size: 0.8rem;
}
</style>
@endsection
