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
                    <h5 class="mb-3">📈 Komponen Penilaian Pengambilan Keputusan</h5>
                    
                    <div id="tpk-weight-alert" class="alert alert-info mb-3">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Tip:</strong> Anda dapat mengubah bobot (%) setiap komponen. Pastikan total = 100%
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">UTS</label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="uts" id="tpk_uts" 
                                       min="0" max="100" step="0.01" placeholder="0-100">
                                <input type="number" name="w_tpk_uts" class="form-control text-center" style="max-width: 70px;"
                                       value="{{ $settings['tpk_uts']->value ?? 10 }}" min="0" max="100">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">UAS</label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="uas" id="tpk_uas" 
                                       min="0" max="100" step="0.01" placeholder="0-100">
                                <input type="number" name="w_tpk_uas" class="form-control text-center" style="max-width: 70px;"
                                       value="{{ $settings['tpk_uas']->value ?? 10 }}" min="0" max="100">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Keaktifan</label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="aktivitas_partisipatif" id="tpk_aktivitas_partisipatif" 
                                       min="0" max="100" step="0.01" placeholder="0-100">
                                <input type="number" name="w_tpk_keaktifan" class="form-control text-center" style="max-width: 70px;"
                                       value="{{ $settings['tpk_aktivitas_partisipatif']->value ?? 10 }}" min="0" max="100">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nilai Kerja</label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="nilai_kerja" id="tpk_nilai_kerja" 
                                       min="0" max="100" step="0.01" placeholder="0-100">
                                <input type="number" name="w_tpk_nilai_kerja" class="form-control text-center" style="max-width: 70px;"
                                       value="{{ $settings['tpk_nilai_kerja']->value ?? 20 }}" min="0" max="100">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Penyajian & Dokumentasi</label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="penyajian_dokumentasi" id="tpk_penyajian_dokumentasi" 
                                       min="0" max="100" step="0.01" placeholder="0-100">
                                <input type="number" name="w_tpk_penyajian" class="form-control text-center" style="max-width: 70px;"
                                       value="{{ $settings['tpk_penyajian_dokumentasi']->value ?? 20 }}" min="0" max="100">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Hasil Proyek</label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="hasil_proyek" id="tpk_hasil_proyek" 
                                       min="0" max="100" step="0.01" placeholder="0-100">
                                <input type="number" name="w_tpk_hasil_proyek" class="form-control text-center" style="max-width: 70px;"
                                       value="{{ $settings['tpk_hasil_proyek']->value ?? 30 }}" min="0" max="100">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-primary mt-3">
                        <h5>🎯 Nilai Akhir: <span id="preview-nilai-akhir">0.00</span></h5>
                        <small>Grade: <span id="grade_tpk">-</span></small>
                    </div>
                </div>

                <!-- Form Integrasi Sistem -->
                <div id="form-integrasi-sistem" style="display: none;">
                    <h5 class="mb-3">📊 Komponen Penilaian Integrasi Sistem</h5>
                    
                    <div id="integrasi-weight-alert" class="alert alert-info mb-3">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Tip:</strong> Anda dapat mengubah bobot (%) setiap komponen. Pastikan total = 100%
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nilai Kerja</label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="nilai_kerja" id="nilai_kerja" 
                                       min="0" max="100" step="0.01" placeholder="0-100">
                                <input type="number" name="w_integrasi_nilai_kerja" class="form-control text-center" style="max-width: 70px;"
                                       value="{{ $settings['integrasi_nilai_kerja']->value ?? 27 }}" min="0" max="100" step="0.1">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nilai Laporan</label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="nilai_laporan" id="nilai_laporan" 
                                       min="0" max="100" step="0.01" placeholder="0-100">
                                <input type="number" name="w_integrasi_nilai_laporan" class="form-control text-center" style="max-width: 70px;"
                                       value="{{ $settings['integrasi_nilai_laporan']->value ?? 18 }}" min="0" max="100" step="0.1">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Ujian Praktikum 1</label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="ujian_praktikum_1" id="ujian_praktikum_1" 
                                       min="0" max="100" step="0.01" placeholder="0-100">
                                <input type="number" name="w_integrasi_up1" class="form-control text-center" style="max-width: 70px;"
                                       value="{{ $settings['integrasi_ujian_praktikum_1']->value ?? 12.5 }}" min="0" max="100" step="0.1">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Ujian Praktikum 2</label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="ujian_praktikum_2" id="ujian_praktikum_2" 
                                       min="0" max="100" step="0.01" placeholder="0-100">
                                <input type="number" name="w_integrasi_up2" class="form-control text-center" style="max-width: 70px;"
                                       value="{{ $settings['integrasi_ujian_praktikum_2']->value ?? 12.5 }}" min="0" max="100" step="0.1">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">UTS Teori</label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="uts" id="integrasi_uts" 
                                       min="0" max="100" step="0.01" placeholder="0-100">
                                <input type="number" name="w_integrasi_uts" class="form-control text-center" style="max-width: 70px;"
                                       value="{{ $settings['integrasi_uts']->value ?? 15 }}" min="0" max="100">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">UAS</label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="uas" id="integrasi_uas" 
                                       min="0" max="100" step="0.01" placeholder="0-100">
                                <input type="number" name="w_integrasi_uas" class="form-control text-center" style="max-width: 70px;"
                                       value="{{ $settings['integrasi_uas']->value ?? 15 }}" min="0" max="100">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-primary mt-3">
                        <h5>🎯 Nilai Akhir: <span id="preview_nilai_akhir_integrasi">0.00</span></h5>
                        <small>Grade: <span id="grade_integrasi">-</span></small>
                    </div>
                </div>



                <!-- Form PWL -->
                <div id="form-pwl" style="display: none;">
                    <h5 class="mb-3">💻 Komponen Penilaian PWL</h5>
                    
                    <div id="pwl-weight-alert" class="alert alert-info mb-3">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Tip:</strong> Anda dapat mengubah bobot (%) setiap komponen. Pastikan total = 100%
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Proposal</label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="it_proposal" id="pwl_proposal" 
                                       min="0" max="100" step="0.01" placeholder="0-100">
                                <input type="number" name="w_pwl_proposal" class="form-control text-center" style="max-width: 70px;"
                                       value="{{ $settings['pwl_proposal']->value ?? 15 }}" min="0" max="100">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Progress Report</label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="it_progress_report" id="pwl_progress_report" 
                                       min="0" max="100" step="0.01" placeholder="0-100">
                                <input type="number" name="w_pwl_progress_report" class="form-control text-center" style="max-width: 70px;"
                                       value="{{ $settings['pwl_progress_report']->value ?? 15 }}" min="0" max="100">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Presentasi</label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="it_presentasi" id="pwl_presentasi" 
                                       min="0" max="100" step="0.01" placeholder="0-100">
                                <input type="number" name="w_pwl_presentasi" class="form-control text-center" style="max-width: 70px;"
                                       value="{{ $settings['pwl_presentasi']->value ?? 20 }}" min="0" max="100">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Dokumentasi</label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="it_dokumentasi" id="pwl_dokumentasi" 
                                       min="0" max="100" step="0.01" placeholder="0-100">
                                <input type="number" name="w_pwl_dokumentasi" class="form-control text-center" style="max-width: 70px;"
                                       value="{{ $settings['pwl_dokumentasi']->value ?? 10 }}" min="0" max="100">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Final Project</label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="it_final_project" id="pwl_final_project" 
                                       min="0" max="100" step="0.01" placeholder="0-100">
                                <input type="number" name="w_pwl_final_project" class="form-control text-center" style="max-width: 70px;"
                                       value="{{ $settings['pwl_final_project']->value ?? 40 }}" min="0" max="100">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-primary mt-3">
                        <h5>🎯 Nilai Akhir PWL: <span id="preview_nilai_akhir_pwl">0.00</span></h5>
                        <small>Grade: <span id="grade_pwl">-</span></small>
                    </div>
                </div>

                <!-- Form IT Project -->
                <div id="form-it-project" style="display: none;">
                    <h5 class="mb-3">🚀 Komponen Penilaian IT Project</h5>
                    
                    <div id="it-weight-alert" class="alert alert-info mb-3">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Tip:</strong> Anda dapat mengubah bobot (%) setiap komponen. Pastikan total = 100%
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Aktivitas Partisipatif</label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="kontribusi" id="it_kontribusi" 
                                       min="0" max="100" step="0.01" placeholder="0-100">
                                <input type="number" name="w_it_aktivitas" class="form-control text-center" style="max-width: 70px;"
                                       value="{{ $settings['it_aktivitas_partisipatif']->value ?? 20 }}" min="0" max="100">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Presentasi</label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="it_presentasi" id="it_presentasi" 
                                       min="0" max="100" step="0.01" placeholder="0-100">
                                <input type="number" name="w_it_presentasi" class="form-control text-center" style="max-width: 70px;"
                                       value="{{ $settings['it_presentasi']->value ?? 10 }}" min="0" max="100">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Objektivitas / Tanya Jawab</label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="it_proposal" id="it_proposal" 
                                       min="0" max="100" step="0.01" placeholder="0-100">
                                <input type="number" name="w_it_objektivitas" class="form-control text-center" style="max-width: 70px;"
                                       value="{{ $settings['it_objektivitas']->value ?? 10 }}" min="0" max="100">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Laporan Progres</label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="it_progress_report" id="it_progress_report" 
                                       min="0" max="100" step="0.01" placeholder="0-100">
                                <input type="number" name="w_it_laporan_progres" class="form-control text-center" style="max-width: 70px;"
                                       value="{{ $settings['it_laporan_progres']->value ?? 10 }}" min="0" max="100">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Laporan Akhir</label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="it_dokumentasi" id="it_dokumentasi" 
                                       min="0" max="100" step="0.01" placeholder="0-100">
                                <input type="number" name="w_it_laporan_akhir" class="form-control text-center" style="max-width: 70px;"
                                       value="{{ $settings['it_laporan_akhir']->value ?? 10 }}" min="0" max="100">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Produk Aplikasi</label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="it_final_project" id="it_final_project" 
                                       min="0" max="100" step="0.01" placeholder="0-100">
                                <input type="number" name="w_it_produk" class="form-control text-center" style="max-width: 70px;"
                                       value="{{ $settings['it_produk_aplikasi']->value ?? 40 }}" min="0" max="100">
                                <span class="input-group-text">%</span>
                            </div>
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
                    <button type="submit" class="btn btn-primary" onclick="prepareSubmit()">
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

            // ✅ FIX: Jika mata kuliah tidak punya kelas, tampilkan untuk semua mahasiswa
            if (!mkKelas || mkKelas === mhsKelas) {
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
    const formPWL = document.getElementById('form-pwl');
    const formIT = document.getElementById('form-it-project');

    formStandar.style.display = 'none';
    formPK.style.display = 'none';
    formIntegrasi.style.display = 'none';
    if(formPWL) formPWL.style.display = 'none';
    formIT.style.display = 'none';

    // Cek untuk "Teknik Pengambilan Keputusan" (bukan hanya "pengambilan keputusan")
    if (namaMK.includes('pengambilan keputusan') || namaMK.includes('teknik pengambilan')) {
        formPK.style.display = 'block';
        calculateNilaiAkhir();
    } else if (namaMK.includes('integrasi sistem')) {
        formIntegrasi.style.display = 'block';
        setupIntegrasiSistemCalculation();
    // Cek untuk "Pemrograman Web Lanjut" (ada typo di database: pero-G-raman)
    } else if (namaMK.includes('pwl') || namaMK.includes('pemrograman web') || namaMK.includes('perograman web') || namaMK.includes('web lanjut')) {
        formPWL.style.display = 'block';
        setupPWLCalculation();
    // ✅ Enhanced IT Project detection - more variations
    } else if (namaMK.includes('it project') || 
               namaMK.includes('it proyek') || 
               namaMK.includes('itproject') || 
               namaMK.includes('it-project') || 
               namaMK.includes('project it') || 
               namaMK.includes('proyek it')) {
        formIT.style.display = 'block';
        setupITProjectCalculation();
    } else if (namaMK) {
        // Mata kuliah standar lainnya menggunakan form nilai tunggal
        formStandar.style.display = 'block';
    }
}

// Kalkulasi Pengambilan Keputusan
function calculateNilaiAkhir() {
    const val = id => parseFloat(document.getElementById(id)?.value) || 0;
    const nilaiAkhir = (val('tpk_uts') * 0.1) + (val('tpk_uas') * 0.1) + (val('tpk_aktivitas_partisipatif') * 0.1)
                     + (val('tpk_nilai_kerja') * 0.2) + (val('tpk_penyajian_dokumentasi') * 0.2)
                     + (val('tpk_hasil_proyek') * 0.3);
    const el = document.getElementById('preview-nilai-akhir');
    if(el) el.textContent = nilaiAkhir.toFixed(2);
}

// Setup listeners for TPK fields
['tpk_uts', 'tpk_uas', 'tpk_aktivitas_partisipatif', 'tpk_nilai_kerja', 'tpk_penyajian_dokumentasi', 'tpk_hasil_proyek'].forEach(id => {
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

// Kalkulasi PWL
function setupPWLCalculation() {
    ['pwl_proposal', 'pwl_progress_report', 'pwl_presentasi', 'pwl_dokumentasi', 'pwl_final_project']
        .forEach(id => {
            const el = document.getElementById(id);
            if (el) el.addEventListener('input', calculatePWL);
        });
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
    
    let grade = '-';
    if (nilaiAkhir >= 85) grade = 'A';
    else if (nilaiAkhir >= 75) grade = 'B';
    else if (nilaiAkhir >= 65) grade = 'C';
    else if (nilaiAkhir >= 50) grade = 'D';
    else grade = 'E';
    
    document.getElementById('grade_pwl').textContent = grade;
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
    
    document.getElementById('preview_nilai_akhir_it').textContent = nilaiAkhir.toFixed(2);
    
    let grade = '-';
    if (nilaiAkhir >= 85) grade = 'A';
    else if (nilaiAkhir >= 75) grade = 'B';
    else if (nilaiAkhir >= 65) grade = 'C';
    else if (nilaiAkhir >= 50) grade = 'D';
    else grade = 'E';
    
    document.getElementById('grade_it').textContent = grade;
}

// Fungsi untuk disable fields yang tersembunyi sebelum submit
function prepareSubmit() {
    const formSections = [
        'form-nilai-standar',
        'form-nilai-pengambilan-keputusan', 
        'form-integrasi-sistem',
        'form-pwl',
        'form-it-project'
    ];
    
    formSections.forEach(sectionId => {
        const section = document.getElementById(sectionId);
        if (section && section.style.display === 'none') {
            // Disable all inputs in hidden sections so they don't get submitted
            const inputs = section.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                input.disabled = true;
            });
        }
    });
    
    console.log('Form prepared for submission - hidden fields disabled');
}

// ===== WEIGHT VALIDATION FOR ALL COURSES =====
function validateWeights(formId, weightInputNames, alertId) {
    const weightInputs = weightInputNames.map(name => document.querySelector(`#${formId} input[name="${name}"]`));
    const alertDiv = document.getElementById(alertId);
    
    if (!alertDiv || weightInputs.some(input => !input)) return;
    
    const total = weightInputs.reduce((sum, input) => sum + (parseFloat(input.value) || 0), 0);
    
    if (total > 100) {
        alertDiv.className = 'alert alert-danger mb-3';
        alertDiv.innerHTML = `<i class="bi bi-exclamation-triangle-fill me-1"></i> <strong>Peringatan:</strong> Total bobot melebihi 100%! (${total}%)`;
    } else if (total < 100) {
        alertDiv.className = 'alert alert-warning mb-3';
        alertDiv.innerHTML = `<i class="bi bi-exclamation-triangle me-1"></i> Total bobot kurang dari 100% (${total}%)`;
    } else {
        alertDiv.className = 'alert alert-success mb-3';
        alertDiv.innerHTML = `<i class="bi bi-check-circle-fill me-1"></i> Total bobot sudah tepat 100%`;
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // IT Project validation
    const itWeightInputs = ['w_it_aktivitas', 'w_it_presentasi', 'w_it_objektivitas', 'w_it_laporan_progres', 'w_it_laporan_akhir', 'w_it_produk'];
    itWeightInputs.forEach(name => {
        const input = document.querySelector(`input[name="${name}"]`);
        if (input) {
            input.addEventListener('input', () => validateWeights('form-it-project', itWeightInputs, 'it-weight-alert'));
        }
    });
    
    // PWL validation  
    const pwlWeightInputs = ['w_pwl_proposal', 'w_pwl_progress_report', 'w_pwl_presentasi', 'w_pwl_dokumentasi', 'w_pwl_final_project'];
    pwlWeightInputs.forEach(name => {
        const input = document.querySelector(`input[name="${name}"]`);
        if (input) {
            input.addEventListener('input', () => validateWeights('form-pwl', pwlWeightInputs, 'pwl-weight-alert'));
        }
    });
    
    // Integrasi Sistem validation
    const integrasiWeightInputs = ['w_integrasi_nilai_kerja', 'w_integrasi_nilai_laporan', 'w_integrasi_up1', 'w_integrasi_up2', 'w_integrasi_uts', 'w_integrasi_uas'];
    integrasiWeightInputs.forEach(name => {
        const input = document.querySelector(`input[name="${name}"]`);
        if (input) {
            input.addEventListener('input', () => validateWeights('form-integrasi-sistem', integrasiWeightInputs, 'integrasi-weight-alert'));
        }
    });
    
    // TPK validation
    const tpkWeightInputs = ['w_tpk_uts', 'w_tpk_uas', 'w_tpk_keaktifan', 'w_tpk_nilai_kerja', 'w_tpk_penyajian', 'w_tpk_hasil_proyek'];
    tpkWeightInputs.forEach(name => {
        const input = document.querySelector(`input[name="${name}"]`);
        if (input) {
            input.addEventListener('input', () => validateWeights('form-nilai-pengambilan-keputusan', tpkWeightInputs, 'tpk-weight-alert'));
        }
    });
});
</script>
@endsection