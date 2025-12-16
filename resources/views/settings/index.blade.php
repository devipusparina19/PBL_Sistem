@extends('layouts.app')

@section('content')
<div class="container py-5">

    <!-- Header -->
    <div class="text-center mb-5">
        <h2 class="fw-semibold text-primary mb-2">⚙️ Pengaturan Penilaian Kelompok</h2>
        <p class="text-muted">Atur bobot dan parameter penilaian kelompok PBL</p>
        <hr class="mx-auto mt-3" style="width: 80px; height: 3px; background-color: #0d6efd; border: none;">
    </div>

    <!-- Alert Success/Error -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Settings Form -->
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow border-0 rounded-4">
                <div class="card-body px-5 py-4">
                    <form action="{{ route('settings.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Bobot Penilaian -->
                        <h5 class="fw-bold text-dark mb-4">
                            <i class="bi bi-calculator me-2"></i>Bobot Penilaian
                        </h5>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Bobot Nilai Milestone (%)</label>
                                <input type="number" name="bobot_milestone" 
                                       class="form-control @error('bobot_milestone') is-invalid @enderror"
                                       value="{{ old('bobot_milestone', $settings['bobot_milestone']->value ?? 50) }}" 
                                       min="0" max="100" required>
                                <small class="text-muted">Persentase nilai dari milestone</small>
                                @error('bobot_milestone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Bobot Nilai Rata-rata Anggota (%)</label>
                                <input type="number" name="bobot_nilai_anggota" 
                                       class="form-control @error('bobot_nilai_anggota') is-invalid @enderror"
                                       value="{{ old('bobot_nilai_anggota', $settings['bobot_nilai_anggota']->value ?? 50) }}" 
                                       min="0" max="100" required>
                                <small class="text-muted">Persentase nilai dari mahasiswa</small>
                                @error('bobot_nilai_anggota')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="alert alert-info mb-4">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Total bobot harus = 100%</strong> 
                            (Milestone + Nilai Anggota)
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Minimum Milestone Disetujui</label>
                            <input type="number" name="minimum_milestone" 
                                   class="form-control @error('minimum_milestone') is-invalid @enderror"
                                   value="{{ old('minimum_milestone', $settings['minimum_milestone']->value ?? 1) }}" 
                                   min="1" required>
                            <small class="text-muted">Jumlah minimum milestone yang harus disetujui sebelum nilai bisa dihitung</small>
                            @error('minimum_milestone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4">

                        <!-- Pengaturan Bonus/Penalty -->
                        <h5 class="fw-bold text-dark mb-4">
                            <i class="bi bi-clock-history me-2"></i>Pengaturan Bonus/Penalty Waktu
                        </h5>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-arrow-up-circle text-success me-1"></i>
                                    Bonus per Minggu Lebih Cepat
                                </label>
                                <div class="input-group">
                                    <input type="number" name="bonus_per_minggu" 
                                           class="form-control @error('bonus_per_minggu') is-invalid @enderror"
                                           value="{{ old('bonus_per_minggu', $settings['bonus_per_minggu']->value ?? 5) }}" 
                                           min="0" max="50" required>
                                    <span class="input-group-text">poin</span>
                                </div>
                                <small class="text-muted">Nilai tambahan jika submit lebih cepat dari target</small>
                                @error('bonus_per_minggu')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-arrow-down-circle text-danger me-1"></i>
                                    Penalty per Minggu Terlambat
                                </label>
                                <div class="input-group">
                                    <input type="number" name="penalty_per_minggu" 
                                           class="form-control @error('penalty_per_minggu') is-invalid @enderror"
                                           value="{{ old('penalty_per_minggu', $settings['penalty_per_minggu']->value ?? 5) }}" 
                                           min="0" max="50" required>
                                    <span class="input-group-text">poin</span>
                                </div>
                                <small class="text-muted">Pengurangan nilai jika terlambat dari target</small>
                                @error('penalty_per_minggu')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="alert alert-warning mb-4">
                            <i class="bi bi-lightbulb me-2"></i>
                            <strong>Contoh:</strong> Jika bonus = 5, milestone target minggu 3 tapi submit minggu 2, 
                            nilai akhir = nilai dosen + 5 poin.
                        </div>

                        <hr class="my-4">

                        <!-- Bobot Komponen Per Mata Kuliah -->
                        <h5 class="fw-bold text-dark mb-4">
                            <i class="bi bi-sliders me-2"></i>Bobot Komponen Per Mata Kuliah
                        </h5>

                        <!-- Accordion for Course Components -->
                        <div class="accordion" id="courseWeightsAccordion">
                            
                            <!-- IT Project -->
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#itProjectWeights">
                                        🚀 IT Project
                                    </button>
                                </h2>
                                <div id="itProjectWeights" class="accordion-collapse collapse" data-bs-parent="#courseWeightsAccordion">
                                    <div class="accordion-body">
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label small">Aktivitas Partisipatif (%)</label>
                                                <input type="number" name="it_aktivitas_partisipatif" class="form-control form-control-sm"
                                                       value="{{ $settings['it_aktivitas_partisipatif']->value ?? 20 }}" min="0" max="100">
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label small">Presentasi (%)</label>
                                                <input type="number" name="it_presentasi" class="form-control form-control-sm"
                                                       value="{{ $settings['it_presentasi']->value ?? 10 }}" min="0" max="100">
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label small">Objektivitas/Tanya Jawab (%)</label>
                                                <input type="number" name="it_objektivitas" class="form-control form-control-sm"
                                                       value="{{ $settings['it_objektivitas']->value ?? 10 }}" min="0" max="100">
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label small">Laporan Progres (%)</label>
                                                <input type="number" name="it_laporan_progres" class="form-control form-control-sm"
                                                       value="{{ $settings['it_laporan_progres']->value ?? 10 }}" min="0" max="100">
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label small">Laporan Akhir (%)</label>
                                                <input type="number" name="it_laporan_akhir" class="form-control form-control-sm"
                                                       value="{{ $settings['it_laporan_akhir']->value ?? 10 }}" min="0" max="100">
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label small">Produk Aplikasi (%)</label>
                                                <input type="number" name="it_produk_aplikasi" class="form-control form-control-sm"
                                                       value="{{ $settings['it_produk_aplikasi']->value ?? 40 }}" min="0" max="100">
                                            </div>
                                        </div>
                                        <small class="text-muted">Total harus = 100%</small>
                                    </div>
                                </div>
                            </div>

                            <!-- PWL -->
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#pwlWeights">
                                        💻 Pemrograman Web Lanjut (PWL)
                                    </button>
                                </h2>
                                <div id="pwlWeights" class="accordion-collapse collapse" data-bs-parent="#courseWeightsAccordion">
                                    <div class="accordion-body">
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label small">Proposal (%)</label>
                                                <input type="number" name="pwl_proposal" class="form-control form-control-sm"
                                                       value="{{ $settings['pwl_proposal']->value ?? 15 }}" min="0" max="100">
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label small">Progress Report (%)</label>
                                                <input type="number" name="pwl_progress_report" class="form-control form-control-sm"
                                                       value="{{ $settings['pwl_progress_report']->value ?? 15 }}" min="0" max="100">
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label small">Final Project (%)</label>
                                                <input type="number" name="pwl_final_project" class="form-control form-control-sm"
                                                       value="{{ $settings['pwl_final_project']->value ?? 40 }}" min="0" max="100">
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label small">Presentasi (%)</label>
                                                <input type="number" name="pwl_presentasi" class="form-control form-control-sm"
                                                       value="{{ $settings['pwl_presentasi']->value ?? 20 }}" min="0" max="100">
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label small">Dokumentasi (%)</label>
                                                <input type="number" name="pwl_dokumentasi" class="form-control form-control-sm"
                                                       value="{{ $settings['pwl_dokumentasi']->value ?? 10 }}" min="0" max="100">
                                            </div>
                                        </div>
                                        <small class="text-muted">Total harus = 100%</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Integrasi Sistem -->
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#integrasiWeights">
                                        📊 Integrasi Sistem
                                    </button>
                                </h2>
                                <div id="integrasiWeights" class="accordion-collapse collapse" data-bs-parent="#courseWeightsAccordion">
                                    <div class="accordion-body">
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label small">Nilai Kerja (%)</label>
                                                <input type="number" name="integrasi_nilai_kerja" class="form-control form-control-sm"
                                                       value="{{ $settings['integrasi_nilai_kerja']->value ?? 27 }}" min="0" max="100" step="0.1">
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label small">Nilai Laporan (%)</label>
                                                <input type="number" name="integrasi_nilai_laporan" class="form-control form-control-sm"
                                                       value="{{ $settings['integrasi_nilai_laporan']->value ?? 18 }}" min="0" max="100" step="0.1">
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label small">Ujian Praktikum 1 (%)</label>
                                                <input type="number" name="integrasi_ujian_praktikum_1" class="form-control form-control-sm"
                                                       value="{{ $settings['integrasi_ujian_praktikum_1']->value ?? 12.5 }}" min="0" max="100" step="0.1">
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label small">Ujian Praktikum 2 (%)</label>
                                                <input type="number" name="integrasi_ujian_praktikum_2" class="form-control form-control-sm"
                                                       value="{{ $settings['integrasi_ujian_praktikum_2']->value ?? 12.5 }}" min="0" max="100" step="0.1">
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label small">UTS (%)</label>
                                                <input type="number" name="integrasi_uts" class="form-control form-control-sm"
                                                       value="{{ $settings['integrasi_uts']->value ?? 15 }}" min="0" max="100">
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label small">UAS (%)</label>
                                                <input type="number" name="integrasi_uas" class="form-control form-control-sm"
                                                       value="{{ $settings['integrasi_uas']->value ?? 15 }}" min="0" max="100">
                                            </div>
                                        </div>
                                        <small class="text-muted">Total harus = 100%</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Pengambilan Keputusan -->
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#tpkWeights">
                                        📈 Pengambilan Keputusan (TPK)
                                    </button>
                                </h2>
                                <div id="tpkWeights" class="accordion-collapse collapse" data-bs-parent="#courseWeightsAccordion">
                                    <div class="accordion-body">
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label small">UTS (%)</label>
                                                <input type="number" name="tpk_uts" class="form-control form-control-sm"
                                                       value="{{ $settings['tpk_uts']->value ?? 10 }}" min="0" max="100">
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label small">UAS (%)</label>
                                                <input type="number" name="tpk_uas" class="form-control form-control-sm"
                                                       value="{{ $settings['tpk_uas']->value ?? 10 }}" min="0" max="100">
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label small">Keaktifan (%)</label>
                                                <input type="number" name="tpk_aktivitas_partisipatif" class="form-control form-control-sm"
                                                       value="{{ $settings['tpk_aktivitas_partisipatif']->value ?? 10 }}" min="0" max="100">
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label small">Nilai Kerja (%)</label>
                                                <input type="number" name="tpk_nilai_kerja" class="form-control form-control-sm"
                                                       value="{{ $settings['tpk_nilai_kerja']->value ?? 20 }}" min="0" max="100">
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label small">Penyajian & Dokumentasi (%)</label>
                                                <input type="number" name="tpk_penyajian_dokumentasi" class="form-control form-control-sm"
                                                       value="{{ $settings['tpk_penyajian_dokumentasi']->value ?? 20 }}" min="0" max="100">
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label small">Hasil Proyek (%)</label>
                                                <input type="number" name="tpk_hasil_proyek" class="form-control form-control-sm"
                                                       value="{{ $settings['tpk_hasil_proyek']->value ?? 30 }}" min="0" max="100">
                                            </div>
                                        </div>
                                        <small class="text-muted">Total harus = 100%</small>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <hr class="my-4">

                        <!-- Submit Button -->
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-save me-2"></i>Simpan Pengaturan
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
