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
                            </option>
                        @endforeach
                    </select>
                    @error('kelompok_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <hr class="my-4">

<h5 class="fw-bold text-dark mb-4">Penilaian Tambahan</h5>

<div class="row">

    <div class="col-md-4 mb-3">
        <label for="presentasi" class="form-label">Presentasi <span class="text-danger">*</span></label>
        <input type="number" name="presentasi" id="presentasi"
               class="form-control @error('presentasi') is-invalid @enderror"
               value="{{ old('presentasi') }}" 
               min="0" max="100" step="0.01" required>
        @error('presentasi')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4 mb-3">
        <label for="laporan" class="form-label">Laporan <span class="text-danger">*</span></label>
        <input type="number" name="laporan" id="laporan"
               class="form-control @error('laporan') is-invalid @enderror"
               value="{{ old('laporan') }}" 
               min="0" max="100" step="0.01" required>
        @error('laporan')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4 mb-3">
        <label for="kerjasama" class="form-label">Kerjasama <span class="text-danger">*</span></label>
        <input type="number" name="kerjasama" id="kerjasama"
               class="form-control @error('kerjasama') is-invalid @enderror"
               value="{{ old('kerjasama') }}" 
               min="0" max="100" step="0.01" required>
        @error('kerjasama')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

</div>

                <h5 class="fw-bold text-dark mb-4">Komponen Penilaian Kelompok</h5>

                <div class="row">
                    <!-- Pemrograman Web -->
                    <div class="col-md-6 mb-3">
                        <label for="pemrograman_web" class="form-label">Pemrograman Web <span class="text-danger">*</span></label>
                        <input type="number" name="pemrograman_web" id="pemrograman_web" 
                               class="form-control @error('pemrograman_web') is-invalid @enderror" 
                               value="{{ old('pemrograman_web') }}" 
                               min="0" max="100" step="0.01" required>
                        <small class="text-muted">Kualitas implementasi web application</small>
                        @error('pemrograman_web')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Integrasi Sistem -->
                    <div class="col-md-6 mb-3">
                        <label for="integrasi_sistem" class="form-label">Integrasi Sistem <span class="text-danger">*</span></label>
                        <input type="number" name="integrasi_sistem" id="integrasi_sistem" 
                               class="form-control @error('integrasi_sistem') is-invalid @enderror" 
                               value="{{ old('integrasi_sistem') }}" 
                               min="0" max="100" step="0.01" required>
                        <small class="text-muted">Integrasi antar modul/komponen sistem</small>
                        @error('integrasi_sistem')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Pengambilan Keputusan -->
                    <div class="col-md-6 mb-3">
                        <label for="pengambilan_keputusan" class="form-label">Pengambilan Keputusan <span class="text-danger">*</span></label>
                        <input type="number" name="pengambilan_keputusan" id="pengambilan_keputusan" 
                               class="form-control @error('pengambilan_keputusan') is-invalid @enderror" 
                               value="{{ old('pengambilan_keputusan') }}" 
                               min="0" max="100" step="0.01" required>
                        <small class="text-muted">Kemampuan problem solving dan decision making</small>
                        @error('pengambilan_keputusan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- IT Proyek -->
                    <div class="col-md-6 mb-3">
                        <label for="it_proyek" class="form-label">IT Proyek <span class="text-danger">*</span></label>
                        <input type="number" name="it_proyek" id="it_proyek" 
                               class="form-control @error('it_proyek') is-invalid @enderror" 
                               value="{{ old('it_proyek') }}" 
                               min="0" max="100" step="0.01" required>
                        <small class="text-muted">Manajemen proyek IT (timeline, task management)</small>
                        @error('it_proyek')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Kontribusi Kelompok -->
                    <div class="col-md-6 mb-3">
                        <label for="kontribusi_kelompok" class="form-label">Kontribusi Kelompok <span class="text-danger">*</span></label>
                        <input type="number" name="kontribusi_kelompok" id="kontribusi_kelompok" 
                               class="form-control @error('kontribusi_kelompok') is-invalid @enderror" 
                               value="{{ old('kontribusi_kelompok') }}" 
                               min="0" max="100" step="0.01" required>
                        <small class="text-muted">Kontribusi kelompok secara keseluruhan</small>
                        @error('kontribusi_kelompok')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Penilaian Dosen -->
                    <div class="col-md-6 mb-3">
                        <label for="penilaian_dosen" class="form-label">Penilaian Dosen <span class="text-danger">*</span></label>
                        <input type="number" name="penilaian_dosen" id="penilaian_dosen" 
                               class="form-control @error('penilaian_dosen') is-invalid @enderror" 
                               value="{{ old('penilaian_dosen') }}" 
                               min="0" max="100" step="0.01" required>
                        <small class="text-muted">Penilaian subjektif dosen pembimbing</small>
                        @error('penilaian_dosen')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <hr class="my-4">

                <!-- Info -->
                <div class="alert alert-info" role="alert">
                    <i class="bi bi-info-circle-fill me-2"></i>
                    <strong>Catatan:</strong> Hasil akhir akan dihitung otomatis sebagai rata-rata dari 6 komponen penilaian kelompok.
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
