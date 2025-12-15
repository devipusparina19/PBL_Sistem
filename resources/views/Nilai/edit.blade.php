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

                <!-- Form Pengambilan Keputusan -->
                <div id="form-nilai-pengambilan-keputusan" style="display: none;">
                    <div class="alert alert-info">
                        <strong>Komponen Penilaian Pengambilan Keputusan:</strong><br>
                        • UTS (10%)<br>
                        • UAS (10%)<br>
                        • Keaktifan (10%)<br>
                        • Nilai Kerja (20%)<br>
                        • Penyajian & Dokumentasi (20%)<br>
                        • Hasil Proyek (30%)
                    </div>

                    <div class="row">
                        @php
                            $fields = [
                                ['uts', 'UTS - 10%', $nilai->uts],
                                ['uas', 'UAS - 10%', $nilai->uas],
                                ['aktivitas_partisipatif', 'Aktivitas Partisipatif (10%)', $nilai->presentasi],
                                ['nilai_kerja', 'Nilai Kerja (20%)', $nilai->kontribusi],
                                ['penyajian_dokumentasi', 'Penyajian & Dokumentasi (20%)', $nilai->laporan],
                                ['hasil_proyek', 'Hasil Proyek (30%)', $nilai->hasil_proyek],
                            ];
                        @endphp

                        @foreach($fields as [$id, $label, $val])
                        <div class="col-md-6 mb-3">
                            <label for="{{ $id }}" class="form-label">{{ $label }}</label>
                            <input type="number" name="{{ $id }}" id="{{ $id }}"
                                   class="form-control" min="0" max="100" step="0.01" 
                                   value="{{ old($id, $val) }}">
                        </div>
                        @endforeach
                    </div>

                    <div class="alert alert-success">
                        <strong>Nilai Akhir:</strong> <span id="preview-nilai-akhir">0</span>
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
                                id="integrasi_uts" min="0" max="100" step="0.01"
                                value="{{ old('uts', $nilai->uts) }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">UAS (15%)</label>
                            <input type="number" class="form-control" name="uas"
                                id="integrasi_uas" min="0" max="100" step="0.01"
                                value="{{ old('uas', $nilai->uas) }}">
                        </div>
                    </div>
                </div>



                <!-- Form PWL -->
                <div id="form-pwl" style="display: none;">
                    <h5 class="mb-3">💻 Komponen Penilaian PWL</h5>
                    
                    <div class="alert alert-info">
                        <strong>Komponen Penilaian Pemrograman Web Lanjut:</strong><br>
                        • Proposal (15%)<br>
                        • Progress Report (15%)<br>
                        • Final Project (40%)<br>
                        • Presentasi (20%)<br>
                        • Dokumentasi (10%)
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Proposal (15%)</label>
                            <input type="number" class="form-control" name="it_proposal" id="pwl_proposal" 
                                   min="0" max="100" step="0.01" value="{{ old('it_proposal', $nilai->it_proposal) }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Progress Report (15%)</label>
                            <input type="number" class="form-control" name="it_progress_report" id="pwl_progress_report" 
                                   min="0" max="100" step="0.01" value="{{ old('it_progress_report', $nilai->it_progress_report) }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Presentasi (20%)</label>
                            <input type="number" class="form-control" name="it_presentasi" id="pwl_presentasi" 
                                   min="0" max="100" step="0.01" value="{{ old('it_presentasi', $nilai->it_presentasi) }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Dokumentasi (10%)</label>
                            <input type="number" class="form-control" name="it_dokumentasi" id="pwl_dokumentasi" 
                                   min="0" max="100" step="0.01" value="{{ old('it_dokumentasi', $nilai->it_dokumentasi) }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Final Project (40%)</label>
                            <input type="number" class="form-control" name="it_final_project" id="pwl_final_project" 
                                   min="0" max="100" step="0.01" value="{{ old('it_final_project', $nilai->it_final_project) }}">
                        </div>
                    </div>

                    <div class="alert alert-primary mt-3">
                        <h5>🎯 Nilai Akhir PWL: <span id="preview_nilai_akhir_pwl">0.00</span></h5>
                    </div>
                </div>

                <!-- Form IT Project -->
                <div id="form-it-project" style="display: none;">
                    <h5 class="mb-3">🚀 Komponen Penilaian IT Project</h5>
                    
                    <div class="alert alert-info">
                        <strong>Komponen Penilaian IT Project:</strong><br>
                        • Aktivitas Partisipatif (20%) - Dinamika, Kerjasama<br>
                        • Presentasi (10%) - Keruntutan, Penguasaan Materi<br>
                        • Objektivitas / Tanya Jawab (10%) - Ketepatan Jawaban<br>
                        • Laporan Progres (10%) - Kerapian, Dokumen<br>
                        • Laporan Akhir (10%) - Kerapian, Dokumen<br>
                        • Produk Aplikasi (40%) - Hasil Proyek
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Aktivitas Partisipatif (20%)</label>
                            <input type="number" class="form-control" name="kontribusi" id="it_kontribusi" 
                                   min="0" max="100" step="0.01" value="{{ old('kontribusi', $nilai->kontribusi) }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Presentasi (10%)</label>
                            <input type="number" class="form-control" name="it_presentasi" id="it_presentasi" 
                                   min="0" max="100" step="0.01" value="{{ old('it_presentasi', $nilai->it_presentasi) }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Objektivitas / Tanya Jawab (10%)</label>
                            <input type="number" class="form-control" name="it_proposal" id="it_proposal" 
                                   min="0" max="100" step="0.01" value="{{ old('it_proposal', $nilai->it_proposal) }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Laporan Progres (10%)</label>
                            <input type="number" class="form-control" name="it_progress_report" id="it_progress_report" 
                                   min="0" max="100" step="0.01" value="{{ old('it_progress_report', $nilai->it_progress_report) }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Laporan Akhir (10%)</label>
                            <input type="number" class="form-control" name="it_dokumentasi" id="it_dokumentasi" 
                                   min="0" max="100" step="0.01" value="{{ old('it_dokumentasi', $nilai->it_dokumentasi) }}">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Produk Aplikasi (40%)</label>
                            <input type="number" class="form-control" name="it_final_project" id="it_final_project" 
                                   min="0" max="100" step="0.01" value="{{ old('it_final_project', $nilai->it_final_project) }}">
                        </div>
                    </div>
                    
                    <div class="alert alert-primary mt-3">
                        <h5>🎯 Nilai Akhir IT Project: <span id="preview_nilai_akhir_it">0.00</span></h5>
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
    const formPK = document.getElementById('form-nilai-pengambilan-keputusan');
    const formIntegrasi = document.getElementById('form-integrasi-sistem');
    const formPWL = document.getElementById('form-pwl');
    const formIT = document.getElementById('form-it-project');

    formStandar.style.display = 'none';
    if(formPK) formPK.style.display = 'none';
    formIntegrasi.style.display = 'none';
    if(formPWL) formPWL.style.display = 'none';
    formIT.style.display = 'none';

    if (namaMK.includes('pengambilan keputusan') || namaMK.includes('teknik pengambilan')) {
        formPK.style.display = 'block';
        calculateNilaiAkhir();
    } else if (namaMK.includes('integrasi sistem')) {
        formIntegrasi.style.display = 'block';
        setupIntegrasiSistemCalculation();
    } else if (namaMK.includes('pwl') || namaMK.includes('pemrograman web') || namaMK.includes('perograman web') || namaMK.includes('web lanjut')) {
        formPWL.style.display = 'block';
        setupPWLCalculation();
    } else if (namaMK.includes('it project') || namaMK.includes('it proyek')) {
        formIT.style.display = 'block';
        setupITProjectCalculation();
    } else if (namaMK) {
        // Mata kuliah standar lainnya menggunakan form nilai tunggal
        formStandar.style.display = 'block';
    }
}

// Kalkulasi PWL
function setupPWLCalculation() {
    ['pwl_proposal', 'pwl_progress_report', 'pwl_presentasi', 'pwl_dokumentasi', 'pwl_final_project']
        .forEach(id => {
            const el = document.getElementById(id);
            if (el) el.addEventListener('input', calculatePWL);
        });
    // Trigger on load
    calculatePWL();
}

function calculatePWL() {
    const val = id => parseFloat(document.getElementById(id)?.value) || 0;
    
    // Proposal 15%
    // Progress Report 15%
    // Final Project 40%
    // Presentasi 20%
    // Dokumentasi 10%
    const nilaiAkhir = (val('pwl_proposal') * 0.15) + 
                       (val('pwl_progress_report') * 0.15) + 
                       (val('pwl_final_project') * 0.40) + 
                       (val('pwl_presentasi') * 0.20) + 
                       (val('pwl_dokumentasi') * 0.10);
    
    document.getElementById('preview_nilai_akhir_pwl').textContent = nilaiAkhir.toFixed(2);
}

// Kalkulasi Pengambilan Keputusan
function calculateNilaiAkhir() {
    const val = id => parseFloat(document.getElementById(id)?.value) || 0;
    const nilaiAkhir = (val('uts') * 0.1) + (val('uas') * 0.1) + (val('aktivitas_partisipatif') * 0.1)
                     + (val('nilai_kerja') * 0.2) + (val('penyajian_dokumentasi') * 0.2)
                     + (val('hasil_proyek') * 0.3);
    const el = document.getElementById('preview-nilai-akhir');
    if(el) el.textContent = nilaiAkhir.toFixed(2);
}

document.addEventListener('DOMContentLoaded', () => {
    toggleNilaiForm();
    ['uts', 'uas', 'aktivitas_partisipatif', 'nilai_kerja', 'penyajian_dokumentasi', 'hasil_proyek'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.addEventListener('input', calculateNilaiAkhir);
    });
});

</script>
@endsection
