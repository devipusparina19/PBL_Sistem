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
                            <option value="{{ $mhs->id }}" {{ old('mahasiswa_id') == $mhs->id ? 'selected' : '' }}>
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
                            <option value="{{ $mk->id }}" data-nama="{{ $mk->nama_mk }}" {{ old('mata_kuliah_id') == $mk->id ? 'selected' : '' }}>
                                {{ $mk->nama_mk }}
                            </option>
                        @endforeach
                    </select>
                    @error('mata_kuliah_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Pilih salah satu mata kuliah yang Anda ampu</small>
                </div>

                <!-- Form Nilai Standar (untuk mata kuliah selain Pengambilan Keputusan) -->
                <div id="form-nilai-standar" style="display: none;">
                    <div class="mb-4">
                        <label for="nilai" class="form-label fw-semibold">Nilai (0-100) <span class="text-danger">*</span></label>
                        <input type="number" name="nilai" id="nilai" 
                               class="form-control @error('nilai') is-invalid @enderror" 
                               value="{{ old('nilai') }}" 
                               min="0" max="100" step="0.01"
                               placeholder="Masukkan nilai (contoh: 85.5)">
                        @error('nilai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Form Nilai Pengambilan Keputusan (4 Komponen) -->
                <div id="form-nilai-pengambilan-keputusan" style="display: none;">
                    <div class="alert alert-info">
                        <strong>Komponen Penilaian Pengambilan Keputusan:</strong><br>
                        • UTS (10%)<br>
                        • UAS (10%)<br>
                        • Aktivitas Partisipatif / Keaktifan (10%)<br>
                        • Nilai Kerja (20%)<br>
                        • Penyajian dan Dokumentasi (20%)<br>
                        • Hasil Proyek (30%)<br>
                        <small class="text-muted">Nilai akhir akan dihitung otomatis berdasarkan bobot masing-masing komponen.</small>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="uts" class="form-label">UTS - 10%</label>
                            <input type="number" name="uts" id="uts" 
                                   class="form-control @error('uts') is-invalid @enderror" 
                                   value="{{ old('uts') }}" 
                                   min="0" max="100" step="0.01"
                                   placeholder="0-100">
                            @error('uts')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="uas" class="form-label">UAS - 10%</label>
                            <input type="number" name="uas" id="uas" 
                                   class="form-control @error('uas') is-invalid @enderror" 
                                   value="{{ old('uas') }}" 
                                   min="0" max="100" step="0.01"
                                   placeholder="0-100">
                            @error('uas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="aktivitas_partisipatif" class="form-label">Aktivitas Partisipatif (Keaktifan) - 10%</label>
                            <input type="number" name="aktivitas_partisipatif" id="aktivitas_partisipatif" 
                                   class="form-control @error('aktivitas_partisipatif') is-invalid @enderror" 
                                   value="{{ old('aktivitas_partisipatif') }}" 
                                   min="0" max="100" step="0.01"
                                   placeholder="0-100">
                            @error('aktivitas_partisipatif')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="nilai_kerja" class="form-label">Nilai Kerja - 20%</label>
                            <input type="number" name="nilai_kerja" id="nilai_kerja" 
                                   class="form-control @error('nilai_kerja') is-invalid @enderror" 
                                   value="{{ old('nilai_kerja') }}" 
                                   min="0" max="100" step="0.01"
                                   placeholder="0-100">
                            @error('nilai_kerja')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="penyajian_dokumentasi" class="form-label">Penyajian dan Dokumentasi - 20%</label>
                            <input type="number" name="penyajian_dokumentasi" id="penyajian_dokumentasi" 
                                   class="form-control @error('penyajian_dokumentasi') is-invalid @enderror" 
                                   value="{{ old('penyajian_dokumentasi') }}" 
                                   min="0" max="100" step="0.01"
                                   placeholder="0-100">
                            @error('penyajian_dokumentasi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="hasil_proyek" class="form-label">Hasil Proyek - 30%</label>
                            <input type="number" name="hasil_proyek" id="hasil_proyek" 
                                   class="form-control @error('hasil_proyek') is-invalid @enderror" 
                                   value="{{ old('hasil_proyek') }}" 
                                   min="0" max="100" step="0.01"
                                   placeholder="0-100">
                            @error('hasil_proyek')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="alert alert-success">
                        <strong>Nilai Akhir:</strong> <span id="preview-nilai-akhir">0</span>
                    </div>
                </div>

                <hr class="my-4">

                <!-- Info -->
                <div class="alert alert-info" role="alert">
                    <i class="bi bi-info-circle-fill me-2"></i>
                    <strong>Catatan:</strong> Pilih mata kuliah yang Anda ampu, kemudian input nilai mahasiswa untuk mata kuliah tersebut (skala 0-100).
                </div>

                <!-- Buttons -->
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
// Toggle form berdasarkan mata kuliah yang dipilih
function toggleNilaiForm() {
    const selectMK = document.getElementById('mata_kuliah_id');
    const selectedOption = selectMK.options[selectMK.selectedIndex];
    const namaMK = selectedOption.getAttribute('data-nama');
    
    const formStandar = document.getElementById('form-nilai-standar');
    const formPengambilanKeputusan = document.getElementById('form-nilai-pengambilan-keputusan');
    
    if (namaMK && namaMK.toLowerCase().includes('pengambilan keputusan')) {
        // Tampilkan form pengambilan keputusan
        formStandar.style.display = 'none';
        formPengambilanKeputusan.style.display = 'block';
        
        // Set required untuk komponen pengambilan keputusan
        document.getElementById('nilai').removeAttribute('required');
        document.getElementById('uts').setAttribute('required', 'required');
        document.getElementById('uas').setAttribute('required', 'required');
        document.getElementById('aktivitas_partisipatif').setAttribute('required', 'required');
        document.getElementById('nilai_kerja').setAttribute('required', 'required');
        document.getElementById('penyajian_dokumentasi').setAttribute('required', 'required');
        document.getElementById('hasil_proyek').setAttribute('required', 'required');
        
        // Hitung nilai akhir
        calculateNilaiAkhir();
    } else if (namaMK) {
        // Tampilkan form standar
        formStandar.style.display = 'block';
        formPengambilanKeputusan.style.display = 'none';
        
        // Set required untuk nilai standar
        document.getElementById('nilai').setAttribute('required', 'required');
        document.getElementById('uts').removeAttribute('required');
        document.getElementById('uas').removeAttribute('required');
        document.getElementById('aktivitas_partisipatif').removeAttribute('required');
        document.getElementById('nilai_kerja').removeAttribute('required');
        document.getElementById('penyajian_dokumentasi').removeAttribute('required');
        document.getElementById('hasil_proyek').removeAttribute('required');
    } else {
        // Sembunyikan semua form
        formStandar.style.display = 'none';
        formPengambilanKeputusan.style.display = 'none';
    }
}

// Hitung nilai akhir untuk Pengambilan Keputusan
function calculateNilaiAkhir() {
    const uts = parseFloat(document.getElementById('uts').value) || 0;
    const uas = parseFloat(document.getElementById('uas').value) || 0;
    const keaktifan = parseFloat(document.getElementById('aktivitas_partisipatif').value) || 0;
    const nilaiKerja = parseFloat(document.getElementById('nilai_kerja').value) || 0;
    const penyajian = parseFloat(document.getElementById('penyajian_dokumentasi').value) || 0;
    const hasilProyek = parseFloat(document.getElementById('hasil_proyek').value) || 0;
    
    // Formula: (UTS × 0.1) + (UAS × 0.1) + (Keaktifan × 0.1) + (Nilai Kerja × 0.2) + (Penyajian × 0.2) + (Hasil Proyek × 0.3)
    const nilaiAkhir = (uts * 0.1) + (uas * 0.1) + (keaktifan * 0.1) + (nilaiKerja * 0.2) + (penyajian * 0.2) + (hasilProyek * 0.3);
    
    document.getElementById('preview-nilai-akhir').textContent = nilaiAkhir.toFixed(2);
}

// Event listeners untuk auto-calculate
document.addEventListener('DOMContentLoaded', function() {
    // Jika ada old input, trigger toggle
    toggleNilaiForm();
    
    // Add event listeners untuk komponen pengambilan keputusan
    ['uts', 'uas', 'aktivitas_partisipatif', 'nilai_kerja', 'penyajian_dokumentasi', 'hasil_proyek'].forEach(id => {
        const input = document.getElementById(id);
        if (input) {
            input.addEventListener('input', calculateNilaiAkhir);
        }
    });
});
</script>
@endsection
