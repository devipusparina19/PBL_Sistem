@extends('layouts.app')

@section('content')
<div class="container py-5">

    <!-- Header -->
    <div class="text-center mb-5">
        <h2 class="fw-semibold text-primary mb-2">Edit Nilai Kelompok</h2>
        <p class="text-muted">Perbarui nilai kelompok untuk komponen penilaian PBL</p>
        <hr class="mx-auto mt-3" style="width: 80px; height: 3px; background-color: #0d6efd; border: none;">
    </div>

    <!-- Form Card -->
    <div class="card shadow border-0 rounded-4">
        <div class="card-body px-5 py-4">
            <form action="{{ route('nilai_kelompok.update', $kelompok->id_kelompok) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Info Kelompok -->
                <div class="alert alert-secondary mb-4">
                    <h6 class="fw-bold mb-2">Informasi Kelompok</h6>
                    <p class="mb-1"><strong>Nama Kelompok:</strong> {{ $kelompok->nama_kelompok }}</p>
                    <p class="mb-1"><strong>Judul Proyek:</strong> {{ $kelompok->judul_proyek ?? '-' }}</p>
                    <p class="mb-0"><strong>Jumlah Anggota:</strong> {{ $kelompok->mahasiswas->count() }} orang</p>
                </div>

                <hr class="my-4">

                <h5 class="fw-bold text-dark mb-4">Komponen Penilaian Kelompok</h5>

                <div class="row">
                    <!-- Note about automated values -->
                    <div class="col-12 mb-3">
                        <div class="alert alert-secondary">
                            <i class="bi bi-gear-fill me-2"></i>
                            Nilai untuk <strong>Pemrograman Web, Integrasi Sistem, Pengambilan Keputusan, IT Proyek, dan Kontribusi Kelompok</strong> 
                            akan diambil secara <strong>otomatis</strong> dari rata-rata nilai mata kuliah anggota kelompok.
                        </div>
                    </div>

                    <!-- Penilaian Dosen -->
                    <div class="col-md-12 mb-3">
                        <label for="penilaian_dosen" class="form-label">Penilaian Dosen <span class="text-danger">*</span></label>
                        <input type="number" name="penilaian_dosen" id="penilaian_dosen" 
                               class="form-control @error('penilaian_dosen') is-invalid @enderror" 
                               value="{{ old('penilaian_dosen', $kelompok->penilaian_dosen) }}" 
                               min="0" max="100" step="0.01" required>
                        <small class="text-muted">Penilaian subjektif dosen pembimbing (0-100)</small>
                        @error('penilaian_dosen')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <hr class="my-4">

                <!-- Info -->
                <div class="alert alert-info" role="alert">
                    <i class="bi bi-info-circle-fill me-2"></i>
                    <strong>Catatan:</strong> Hasil akhir akan dihitung ulang otomatis sebagai rata-rata dari 6 komponen penilaian.
                </div>

                <!-- Current Hasil Akhir -->
                <div class="alert alert-secondary" role="alert">
                    <strong>Hasil Akhir Saat Ini:</strong> 
                    {{ $kelompok->hasil_akhir !== null ? number_format($kelompok->hasil_akhir, 2) : 'Belum ada nilai' }}
                </div>

                <!-- Buttons -->
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('nilai_kelompok.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle me-2"></i>Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-2"></i>Update Nilai Kelompok
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
