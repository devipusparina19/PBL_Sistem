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

                <h5 class="fw-bold text-dark mb-4">Pilih Mata Kuliah & Input Nilai</h5>

                <!-- Pilih Mata Kuliah -->
                <div class="mb-4">
                    <label for="mata_kuliah_id" class="form-label fw-semibold">Mata Kuliah <span class="text-danger">*</span></label>
                    <select name="mata_kuliah_id" id="mata_kuliah_id" class="form-select @error('mata_kuliah_id') is-invalid @enderror" required onchange="toggleNilaiForm()">
                        <option value="">-- Pilih Mata Kuliah --</option>
                        @foreach($mataKuliah as $mk)
                            <option value="{{ $mk->id }}" data-nama="{{ $mk->nama_mk }}" {{ old('mata_kuliah_id', $nilai->mata_kuliah_id) == $mk->id ? 'selected' : '' }}>
                                {{ $mk->nama_mk }}
                            </option>
                        @endforeach
                    </select>
                    @error('mata_kuliah_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Pilih salah satu mata kuliah yang Anda ampu</small>
                </div>

                <!-- Form Nilai Standar -->
                <div id="form-nilai-standar" style="display: none;">
                    <div class="mb-4">
                        <label for="nilai" class="form-label fw-semibold">Nilai (0-100) <span class="text-danger">*</span></label>
                        <input type="number" name="nilai" id="nilai"
                            class="form-control @error('nilai') is-invalid @enderror"
                            value="{{ old('nilai', $nilai->laporan) }}" min="0" max="100" step="0.01"
                            placeholder="Masukkan nilai (contoh: 85.5)">
                        @error('nilai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Form Integrasi Sistem -->
                <div id="form-integrasi-sistem" style="display: none;">
                    <h5 class="mb-3">📊 Komponen Penilaian Integrasi Sistem</h5>

                    <div class="card mb-3">
                        <div class="card-header bg-info text-white">
                            <strong>Aktivitas Partisipatif (45%)</strong>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nilai Kerja (60%)</label>
                                    <input type="number" class="form-control" name="nilai_kerja"
                                        id="nilai_kerja" min="0" max="100" step="0.01" 
                                        value="{{ old('nilai_kerja', $nilai->nilai_kerja) }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nilai Laporan (40%)</label>
                                    <input type="number" class="form-control" name="nilai_laporan"
                                        id="nilai_laporan" min="0" max="100" step="0.01"
                                        value="{{ old('nilai_laporan', $nilai->nilai_laporan) }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-3">
                        <div class="card-header bg-success text-white">
                            <strong>Hasil Project (25%)</strong>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Ujian Praktikum 1 (50%)</label>
                                    <input type="number" class="form-control" name="ujian_praktikum_1"
                                        id="ujian_praktikum_1" min="0" max="100" step="0.01"
                                        value="{{ old('ujian_praktikum_1', $nilai->ujian_praktikum_1) }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Ujian Praktikum 2 (50%)</label>
                                    <input type="number" class="form-control" name="ujian_praktikum_2"
                                        id="ujian_praktikum_2" min="0" max="100" step="0.01"
                                        value="{{ old('ujian_praktikum_2', $nilai->ujian_praktikum_2) }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">UTS Teori (15%)</label>
                            <input type="number" class="form-control" name="uts"
                                id="uts" min="0" max="100" step="0.01"
                                value="{{ old('uts', $nilai->uts) }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">UAS (15%)</label>
                            <input type="number" class="form-control" name="uas"
                                id="uas" min="0" max="100" step="0.01"
                                value="{{ old('uas', $nilai->uas) }}">
                        </div>
                    </div>
                </div>

                <!-- Form PWL -->
                <div id="form-pwl" style="display: none;">
                    <h5 class="mb-3">💻 Komponen Penilaian PWL</h5>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">UTS (15%)</label>
                            <input type="number" class="form-control" name="uts" id="pwl_uts" 
                                   min="0" max="100" step="0.01" value="{{ old('uts', $nilai->uts) }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">UAS (15%)</label>
                            <input type="number" class="form-control" name="uas" id="pwl_uas" 
                                   min="0" max="100" step="0.01" value="{{ old('uas', $nilai->uas) }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Tugas/Quiz (20%)</label>
                            <input type="number" class="form-control" name="pwl_tugas_quiz" id="pwl_tugas_quiz" 
                                   min="0" max="100" step="0.01" value="{{ old('pwl_tugas_quiz', $nilai->pwl_tugas_quiz) }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Project (30%)</label>
                            <input type="number" class="form-control" name="pwl_project" id="pwl_project" 
                                   min="0" max="100" step="0.01" value="{{ old('pwl_project', $nilai->pwl_project) }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Presentasi (20%)</label>
                            <input type="number" class="form-control" name="pwl_presentasi" id="pwl_presentasi" 
                                   min="0" max="100" step="0.01" value="{{ old('pwl_presentasi', $nilai->pwl_presentasi) }}">
                        </div>
                    </div>
                </div>

                <!-- Form IT Project -->
                <div id="form-it-project" style="display: none;">
                    <h5 class="mb-3">🚀 Komponen Penilaian IT Project</h5>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Proposal (15%)</label>
                            <input type="number" class="form-control" name="it_proposal" id="it_proposal" 
                                   min="0" max="100" step="0.01" value="{{ old('it_proposal', $nilai->it_proposal) }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Progress Report (15%)</label>
                            <input type="number" class="form-control" name="it_progress_report" id="it_progress_report" 
                                   min="0" max="100" step="0.01" value="{{ old('it_progress_report', $nilai->it_progress_report) }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Final Project (40%)</label>
                            <input type="number" class="form-control" name="it_final_project" id="it_final_project" 
                                   min="0" max="100" step="0.01" value="{{ old('it_final_project', $nilai->it_final_project) }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Presentasi (20%)</label>
                            <input type="number" class="form-control" name="it_presentasi" id="it_presentasi" 
                                   min="0" max="100" step="0.01" value="{{ old('it_presentasi', $nilai->it_presentasi) }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Dokumentasi (10%)</label>
                            <input type="number" class="form-control" name="it_dokumentasi" id="it_dokumentasi" 
                                   min="0" max="100" step="0.01" value="{{ old('it_dokumentasi', $nilai->it_dokumentasi) }}">
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <!-- Info -->
                <div class="alert alert-info" role="alert">
                    <i class="bi bi-info-circle-fill me-2"></i>
                    <strong>Catatan:</strong> Pilih mata kuliah yang Anda ampu, kemudian perbarui nilai mahasiswa untuk mata kuliah tersebut (skala 0-100).
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

<script>
function toggleNilaiForm() {
    const selectMK = document.getElementById('mata_kuliah_id');
    const namaMK = selectMK.options[selectMK.selectedIndex]?.getAttribute('data-nama')?.toLowerCase() || '';

    const formStandar = document.getElementById('form-nilai-standar');
    const formIntegrasi = document.getElementById('form-integrasi-sistem');
    const formPWL = document.getElementById('form-pwl');
    const formIT = document.getElementById('form-it-project');

    formStandar.style.display = 'none';
    formIntegrasi.style.display = 'none';
    formPWL.style.display = 'none';
    formIT.style.display = 'none';

    if (namaMK.includes('integrasi sistem')) {
        formIntegrasi.style.display = 'block';
    } else if (namaMK.includes('it project') || namaMK.includes('it proyek')) {
        formIT.style.display = 'block';
    } else if (namaMK) {
        // PWL dan mata kuliah standar lainnya menggunakan form nilai tunggal
        formStandar.style.display = 'block';
    }
}

document.addEventListener('DOMContentLoaded', () => {
    toggleNilaiForm();
});
</script>
@endsection
