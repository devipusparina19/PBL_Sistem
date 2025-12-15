@extends('layouts.app')

@section('content')
<div class="container py-5">

    <!-- Header -->
    <div class="text-center mb-5">
        <h2 class="fw-semibold text-primary mb-2">Tambah Nilai Mahasiswa</h2>
        <p class="text-muted">Input nilai mahasiswa untuk semua komponen penilaian PBL</p>
        <hr class="mx-auto mt-3" style="width: 80px; height: 3px; background-color: #0d6efd; border: none;">
    </div>

    <!-- Form Card -->
    <div class="card shadow border-0 rounded-4">
        <div class="card-body px-5 py-4">
            <form action="{{ route('nilai.store') }}" method="POST">
                @csrf

                <!-- Pilih Mahasiswa -->
                <div class="mb-4">
                    <label for="mahasiswa_id" class="form-label fw-semibold">Pilih Mahasiswa <span class="text-danger">*</span></label>
                    <select name="mahasiswa_id" id="mahasiswa_id" class="form-select @error('mahasiswa_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Mahasiswa --</option>
                        @foreach($mahasiswa as $mhs)
                            <option value="{{ $mhs->id }}" data-kelas="{{ $mhs->kelas }}" {{ old('mahasiswa_id') == $mhs->id ? 'selected' : '' }}>
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
                            <option value="{{ $mk->id }}" data-kelas="{{ $mk->kelas }}" data-nama="{{ $mk->nama_mk }}" {{ old('mata_kuliah_id') == $mk->id ? 'selected' : '' }}>
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
                            value="{{ old('nilai') }}" min="0" max="100" step="0.01"
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
                                ['uts', 'UTS - 10%'],
                                ['uas', 'UAS - 10%'],
                                ['aktivitas_partisipatif', 'Aktivitas Partisipatif (10%)'],
                                ['nilai_kerja', 'Nilai Kerja (20%)'],
                                ['penyajian_dokumentasi', 'Penyajian & Dokumentasi (20%)'],
                                ['hasil_proyek', 'Hasil Proyek (30%)'],
                            ];
                        @endphp

                        @foreach($fields as [$id, $label])
                        <div class="col-md-6 mb-3">
                            <label for="{{ $id }}" class="form-label">{{ $label }}</label>
                            <input type="number" name="{{ $id }}" id="{{ $id }}"
                                   class="form-control" min="0" max="100" step="0.01" placeholder="0-100">
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

                    <!-- Aktivitas Partisipatif -->
                    <div class="card mb-3">
        <div class="card-header bg-info text-white">
            <strong>Aktivitas Partisipatif (45%)</strong>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nilai Kerja (60%)</label>
                    <!-- UBAH name dan id -->
                    <input type="number" class="form-control" name="nilai_kerja"
                        id="nilai_kerja" min="0" max="100" step="0.01">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nilai Laporan (40%)</label>
                    <input type="number" class="form-control" name="nilai_laporan"
                        id="nilai_laporan" min="0" max="100" step="0.01">
                </div>
            </div>
                            <div class="alert alert-info">
                                <strong>Preview Aktivitas:</strong> <span id="preview_aktivitas">0.00</span>
                            </div>
                        </div>
                    </div>

                    <!-- Hasil Project -->
                    <div class="card mb-3">
                        <div class="card-header bg-success text-white">
                            <strong>Hasil Project (25%)</strong>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Ujian Praktikum 1 (50%)</label>
                                    <input type="number" class="form-control" name="ujian_praktikum_1"
                                        id="ujian_praktikum_1" min="0" max="100" step="0.01">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Ujian Praktikum 2 (50%)</label>
                                    <input type="number" class="form-control" name="ujian_praktikum_2"
                                        id="ujian_praktikum_2" min="0" max="100" step="0.01">
                                </div>
                            </div>
                            <div class="alert alert-success">
                                <strong>Preview Project:</strong> <span id="preview_project">0.00</span>
                            </div>
                        </div>
                    </div>

                    <!-- UTS & UAS -->
                    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">UTS Teori (15%)</label>
            <input type="number" class="form-control" name="uts"
                id="integrasi_uts" min="0" max="100" step="0.01">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">UAS (15%)</label>
            <input type="number" class="form-control" name="uas"
                id="integrasi_uas" min="0" max="100" step="0.01">
        </div>
    </div>

                    <div class="alert alert-primary mt-3">
                        <h5>🎯 Nilai Akhir: <span id="preview_nilai_akhir_integrasi">0.00</span></h5>
                        <small>Grade: <span id="grade_integrasi">-</span></small>
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
                                   min="0" max="100" step="0.01" placeholder="0-100">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Presentasi (10%)</label>
                            <input type="number" class="form-control" name="it_presentasi" id="it_presentasi" 
                                   min="0" max="100" step="0.01" placeholder="0-100">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Objektivitas / Tanya Jawab (10%)</label>
                            <input type="number" class="form-control" name="it_proposal" id="it_proposal" 
                                   min="0" max="100" step="0.01" placeholder="0-100">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Laporan Progres (10%)</label>
                            <input type="number" class="form-control" name="it_progress_report" id="it_progress_report" 
                                   min="0" max="100" step="0.01" placeholder="0-100">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Laporan Akhir (10%)</label>
                            <input type="number" class="form-control" name="it_dokumentasi" id="it_dokumentasi" 
                                   min="0" max="100" step="0.01" placeholder="0-100">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Produk Aplikasi (40%)</label>
                            <input type="number" class="form-control" name="it_final_project" id="it_final_project" 
                                   min="0" max="100" step="0.01" placeholder="0-100">
                        </div>
                    </div>

                    <div class="alert alert-primary mt-3">
                        <h5>🎯 Nilai Akhir IT Project: <span id="preview_nilai_akhir_it">0.00</span></h5>
                        <small>Grade: <span id="grade_it">-</span></small>
                    </div>
                </div>

                <hr class="my-4">

                <!-- Tombol -->
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('nilai.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle me-2"></i>Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-2"></i>Simpan Nilai
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

{{-- ================= CSS ================= --}}
<style>
.form-label { font-size: 0.95rem; }
.form-control, .form-select { border-radius: 8px; }
.btn { border-radius: 8px; padding: 10px 20px; }
</style>

{{-- ================= SCRIPT ================= --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    const mahasiswaSelect = document.getElementById('mahasiswa_id');
    const mkSelect = document.getElementById('mata_kuliah_id');
    const mkContainer = mkSelect.closest('.mb-4');
    
    // Buat elemen untuk menampilkan nama mata kuliah jika dropdown di-hide
    const mkDisplay = document.createElement('div');
    mkDisplay.className = 'form-control bg-light';
    mkDisplay.style.display = 'none';
    mkDisplay.readOnly = true;
    mkContainer.appendChild(mkDisplay);

    function filterMataKuliah() {
        const selectedMhs = mahasiswaSelect.options[mahasiswaSelect.selectedIndex];
        // Gunakan trim() untuk membersihkan spasi berlebih
        const mhsKelas = (selectedMhs.getAttribute('data-kelas') || '').trim().toLowerCase();
        
        // Hapus console.log di produksi, tapi berguna untuk debugging jika tersangkut
        // console.log("Selected Student Class:", mhsKelas);

        if (!mhsKelas) {
            mkSelect.value = "";
            mkContainer.style.display = 'block';
            mkDisplay.style.display = 'none';
            toggleNilaiForm();
            return;
        }

        const options = mkSelect.options;
        let matchCount = 0;
        let lastMatchValue = "";
        let lastMatchText = "";

        for (let i = 0; i < options.length; i++) {
            const opt = options[i];
            if (opt.value === "") continue;

            const mkKelas = (opt.getAttribute('data-kelas') || '').trim().toLowerCase();
            
            // console.log("Comparing with Course Class:", mkKelas);

            if (mkKelas === mhsKelas) {
                opt.style.display = ''; // Show
                matchCount++;
                lastMatchValue = opt.value;
                lastMatchText = opt.text.trim();
            } else {
                opt.style.display = 'none'; // Hide
            }
        }

        if (matchCount === 1) {
            // Auto Select
            mkSelect.value = lastMatchValue;
            
            // Hide Select, Show Text
            mkSelect.style.display = 'none';
            mkDisplay.textContent = lastMatchText;
            mkDisplay.style.display = 'block';

            // Trigger Update Form
            toggleNilaiForm();
        } else {
            // Show Select if 0 or >1 matches
            mkSelect.style.display = 'block';
            mkDisplay.style.display = 'none';
            
            // Jika pilihan saat ini tidak cocok dengan kelas, reset selection
            const currentOpt = mkSelect.options[mkSelect.selectedIndex];
            const currentKelas = (currentOpt?.getAttribute('data-kelas') || '').trim().toLowerCase();
            
            if (currentOpt && currentKelas !== mhsKelas && mkSelect.value !== "") {
                mkSelect.value = "";
                toggleNilaiForm();
            }
        }
    }

    mahasiswaSelect.addEventListener('change', filterMataKuliah);

    // Run on load if mahasiswa selected (e.g. old input)
    if (mahasiswaSelect.value) {
        filterMataKuliah();
        // Ensure form is correct after auto-select might handle it, 
        // but toggleNilaiForm needs to run. 
        // filterMataKuliah calls toggleNilaiForm if matchCount === 1.
        // If matchCount > 1, we might need to manually call it if old value exists.
        toggleNilaiForm();
    }
});

function toggleNilaiForm() {
    const selectMK = document.getElementById('mata_kuliah_id');
    const namaMK = selectMK.options[selectMK.selectedIndex]?.getAttribute('data-nama')?.toLowerCase() || '';

    const formStandar = document.getElementById('form-nilai-standar');
    const formPK = document.getElementById('form-nilai-pengambilan-keputusan');
    const formIntegrasi = document.getElementById('form-integrasi-sistem');
    const formIT = document.getElementById('form-it-project');

    formStandar.style.display = 'none';
    formPK.style.display = 'none';
    formIntegrasi.style.display = 'none';
    formIT.style.display = 'none';

    if (namaMK.includes('pengambilan keputusan')) {
        formPK.style.display = 'block';
        calculateNilaiAkhir();
    } else if (namaMK.includes('integrasi sistem')) {
        formIntegrasi.style.display = 'block';
        setupIntegrasiSistemCalculation();
    } else if (namaMK.includes('pwl') || namaMK.includes('pemrograman web') || namaMK.includes('web lanjut')) {
        // PWL menggunakan form yang sama dengan IT Project
        formIT.style.display = 'block';
        setupITProjectCalculation();
    } else if (namaMK.includes('it project') || namaMK.includes('it proyek')) {
        formIT.style.display = 'block';
        setupITProjectCalculation();
    } else if (namaMK) {
        // Mata kuliah standar lainnya menggunakan form nilai tunggal
        formStandar.style.display = 'block';
    }
}

// ... rest of existing functions ...
// Kalkulasi Pengambilan Keputusan
function calculateNilaiAkhir() {
    const val = id => parseFloat(document.getElementById(id)?.value) || 0;
    const nilaiAkhir = (val('uts') * 0.1) + (val('uas') * 0.1) + (val('aktivitas_partisipatif') * 0.1)
                     + (val('nilai_kerja') * 0.2) + (val('penyajian_dokumentasi') * 0.2)
                     + (val('hasil_proyek') * 0.3);
    const el = document.getElementById('preview-nilai-akhir');
    if(el) el.textContent = nilaiAkhir.toFixed(2);
}

// document.addEventListener('DOMContentLoaded') removed here to avoid duplication with top script
// Instead, attach listeners:
['uts', 'uas', 'aktivitas_partisipatif', 'nilai_kerja', 'penyajian_dokumentasi', 'hasil_proyek'].forEach(id => {
    const el = document.getElementById(id);
    if (el) el.addEventListener('input', calculateNilaiAkhir);
});

// Kalkulasi Integrasi Sistem
function setupIntegrasiSistemCalculation() {
    ['nilai_kerja', 'nilai_laporan', 'ujian_praktikum_1', 'ujian_praktikum_2', 'integrasi_uts', 'integrasi_uas']
        .forEach(id => {
            const el = document.getElementById(id);
            if (el) el.addEventListener('input', calculateIntegrasiSistem);
        });
}

function calculateIntegrasiSistem() {
    const val = id => parseFloat(document.getElementById(id)?.value) || 0;

    const aktivitas = (val('nilai_kerja') * 0.6) + (val('nilai_laporan') * 0.4);
    const elAkt = document.getElementById('preview_aktivitas');
    if(elAkt) elAkt.textContent = aktivitas.toFixed(2);

    const project = (val('ujian_praktikum_1') * 0.5) + (val('ujian_praktikum_2') * 0.5);
    const elProj = document.getElementById('preview_project');
    if(elProj) elProj.textContent = project.toFixed(2);

    const nilaiAkhir = (aktivitas * 0.45) + (project * 0.25) + (val('integrasi_uts') * 0.15) + (val('integrasi_uas') * 0.15);
    const elAkhir = document.getElementById('preview_nilai_akhir_integrasi');
    if(elAkhir) elAkhir.textContent = nilaiAkhir.toFixed(2);

    let grade = '-';
    if (nilaiAkhir >= 85) grade = 'A';
    else if (nilaiAkhir >= 75) grade = 'B';
    else if (nilaiAkhir >= 65) grade = 'C';
    else if (nilaiAkhir >= 50) grade = 'D';
    else grade = 'E';

    const elGrade = document.getElementById('grade_integrasi');
    if(elGrade) elGrade.textContent = grade;
}


// Kalkulasi IT Project
function setupITProjectCalculation() {
    ['it_kontribusi', 'it_presentasi', 'it_proposal', 'it_progress_report', 'it_dokumentasi', 'it_final_project']
        .forEach(id => {
            const el = document.getElementById(id);
            if (el) el.addEventListener('input', calculateITProject);
        });
}

function calculateITProject() {
    const val = id => parseFloat(document.getElementById(id)?.value) || 0;
    
    const nilaiAkhir = (val('it_kontribusi') * 0.20) + 
                       (val('it_presentasi') * 0.10) +
                       (val('it_proposal') * 0.10) +
                       (val('it_progress_report') * 0.10) +
                       (val('it_dokumentasi') * 0.10) +
                       (val('it_final_project') * 0.40);
    
    const elAkhir = document.getElementById('preview_nilai_akhir_it');
    if(elAkhir) elAkhir.textContent = nilaiAkhir.toFixed(2);
    
    let grade = '-';
    if (nilaiAkhir >= 85) grade = 'A';
    else if (nilaiAkhir >= 75) grade = 'B';
    else if (nilaiAkhir >= 65) grade = 'C';
    else if (nilaiAkhir >= 50) grade = 'D';
    else grade = 'E';
    
    const elGrade = document.getElementById('grade_it');
    if(elGrade) elGrade.textContent = grade;
}
</script>
@endsection