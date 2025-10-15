@extends('layouts.app')

@section('content')
<div class="container py-5">

    <!-- Header -->
    <div class="text-center mb-5">
        <h2 class="fw-semibold text-primary mb-2">Edit Nilai Mahasiswa</h2>
        <p class="text-muted">Perbarui nilai mahasiswa untuk komponen penilaian PBL</p>
        <hr class="mx-auto mt-3" style="width: 80px; height: 3px; background-color: #0d6efd; border: none;">
    </div>

    <!-- Form Card -->
    <div class="card shadow border-0 rounded-4">
        <div class="card-body px-5 py-4">
            <form action="{{ route('nilai.update', $nilai->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Pilih Mahasiswa -->
                <div class="mb-4">
                    <label for="mahasiswa_id" class="form-label fw-semibold">Pilih Mahasiswa <span class="text-danger">*</span></label>
                    <select name="mahasiswa_id" id="mahasiswa_id" class="form-select @error('mahasiswa_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Mahasiswa --</option>
                        @foreach($mahasiswa as $mhs)
                            <option value="{{ $mhs->id }}" {{ old('mahasiswa_id', $nilai->mahasiswa_id) == $mhs->id ? 'selected' : '' }}>
                                {{ $mhs->nim }} - {{ $mhs->nama }} ({{ $mhs->kelas ?? 'Kelas tidak ada' }})
                            </option>
                        @endforeach
                    </select>
                    @error('mahasiswa_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <hr class="my-4">

                <h5 class="fw-bold text-dark mb-4">Komponen Penilaian</h5>

                <div class="row">
                    <!-- Pemrograman Web -->
                    <div class="col-md-6 mb-3">
                        <label for="pemrograman_web" class="form-label">Pemrograman Web <span class="text-danger">*</span></label>
                        <input type="number" name="pemrograman_web" id="pemrograman_web" 
                               class="form-control @error('pemrograman_web') is-invalid @enderror" 
                               value="{{ old('pemrograman_web', $nilai->pemrograman_web) }}" 
                               min="0" max="100" step="0.01" required>
                        @error('pemrograman_web')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Integrasi Sistem -->
                    <div class="col-md-6 mb-3">
                        <label for="integrasi_sistem" class="form-label">Integrasi Sistem <span class="text-danger">*</span></label>
                        <input type="number" name="integrasi_sistem" id="integrasi_sistem" 
                               class="form-control @error('integrasi_sistem') is-invalid @enderror" 
                               value="{{ old('integrasi_sistem', $nilai->integrasi_sistem) }}" 
                               min="0" max="100" step="0.01" required>
                        @error('integrasi_sistem')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Pengambilan Keputusan -->
                    <div class="col-md-6 mb-3">
                        <label for="pengambilan_keputusan" class="form-label">Pengambilan Keputusan <span class="text-danger">*</span></label>
                        <input type="number" name="pengambilan_keputusan" id="pengambilan_keputusan" 
                               class="form-control @error('pengambilan_keputusan') is-invalid @enderror" 
                               value="{{ old('pengambilan_keputusan', $nilai->pengambilan_keputusan) }}" 
                               min="0" max="100" step="0.01" required>
                        @error('pengambilan_keputusan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- IT Proyek -->
                    <div class="col-md-6 mb-3">
                        <label for="it_proyek" class="form-label">IT Proyek <span class="text-danger">*</span></label>
                        <input type="number" name="it_proyek" id="it_proyek" 
                               class="form-control @error('it_proyek') is-invalid @enderror" 
                               value="{{ old('it_proyek', $nilai->it_proyek) }}" 
                               min="0" max="100" step="0.01" required>
                        @error('it_proyek')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Kontribusi Kelompok -->
                    <div class="col-md-6 mb-3">
                        <label for="kontribusi_kelompok" class="form-label">Kontribusi Kelompok <span class="text-danger">*</span></label>
                        <input type="number" name="kontribusi_kelompok" id="kontribusi_kelompok" 
                               class="form-control @error('kontribusi_kelompok') is-invalid @enderror" 
                               value="{{ old('kontribusi_kelompok', $nilai->kontribusi_kelompok) }}" 
                               min="0" max="100" step="0.01" required>
                        @error('kontribusi_kelompok')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Penilaian Sejawat -->
                    <div class="col-md-6 mb-3">
                        <label for="penilaian_sejawat" class="form-label">Penilaian Sejawat <span class="text-danger">*</span></label>
                        <input type="number" name="penilaian_sejawat" id="penilaian_sejawat" 
                               class="form-control @error('penilaian_sejawat') is-invalid @enderror" 
                               value="{{ old('penilaian_sejawat', $nilai->penilaian_sejawat) }}" 
                               min="0" max="100" step="0.01" required>
                        @error('penilaian_sejawat')
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
                    <strong>Hasil Akhir Saat Ini:</strong> {{ number_format($nilai->hasil_akhir, 2) }}
                </div>

                <!-- Buttons -->
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('nilai.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle me-2"></i>Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-2"></i>Update Nilai
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
</style>
@endsection
