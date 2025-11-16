@extends('layouts.app') 
// Memanggil layout utama yang bernama app.blade.php

@section('content') 
// Bagian konten yang akan ditempatkan di area @yield('content') dalam layout

<div class="container py-5"> 
// Membuat container Bootstrap dengan padding atas-bawah 5

    <!-- Header -->
    <div class="text-center mb-5">
        <h2 class="fw-semibold text-primary mb-2">Tambah Nilai Mahasiswa</h2>  // Judul halaman
        <p class="text-muted">Input nilai mahasiswa untuk semua komponen penilaian PBL</p>
        <hr class="mx-auto mt-3" style="width: 80px; height: 3px; background-color: #0d6efd; border: none;"> 
        // Garis dekoratif
    </div>

    <!-- Form Card -->
    <div class="card shadow border-0 rounded-4"> 
    // Card Bootstrap sebagai container form

        <div class="card-body px-5 py-4"> 
        // Padding untuk tampilan rapi

            <form action="{{ route('nilai.store') }}" method="POST"> 
            // Form disubmit ke route nilai.store

                @csrf 
                // Untuk keamanan Form Laravel (Cross Site Request Forgery)

                <!-- Pilih Mahasiswa -->
                <div class="mb-4">
                    <label for="mahasiswa_id" class="form-label fw-semibold">Pilih Mahasiswa <span class="text-danger">*</span></label>

                    <select name="mahasiswa_id" id="mahasiswa_id" 
                        class="form-select @error('mahasiswa_id') is-invalid @enderror" required>
                        // Dropdown mahasiswa
                        
                        <option value="">-- Pilih Mahasiswa --</option>

                        @foreach($mahasiswa as $mhs) 
                        // Looping daftar mahasiswa
                        
                            <option value="{{ $mhs->id }}" 
                                {{ old('mahasiswa_id') == $mhs->id ? 'selected' : '' }}>
                                // Menjaga nilai tetap dipilih jika form gagal

                                {{ $mhs->nim }} - {{ $mhs->nama }} ({{ $mhs->kelas ?? 'Kelas tidak ada' }})
                                // Menampilkan NIM - Nama - Kelas
                            </option>
                        @endforeach
                    </select>

                    @error('mahasiswa_id')
                        <div class="invalid-feedback">{{ $message }}</div> 
                        // Pesan error jika validasi gagal
                    @enderror
                </div>

                <hr class="my-4">

                <h5 class="fw-bold text-dark mb-4">Pilih Mata Kuliah & Input Nilai</h5>

                <!-- Pilih Mata Kuliah -->
                <div class="mb-4">

                    <label for="mata_kuliah_id" class="form-label fw-semibold">Mata Kuliah <span class="text-danger">*</span></label>

                    <select name="mata_kuliah_id" id="mata_kuliah_id" 
                        class="form-select @error('mata_kuliah_id') is-invalid @enderror"
                        required onchange="toggleNilaiForm()">
                        // onchange dipakai untuk menampilkan form nilai yang sesuai

                        <option value="">-- Pilih Mata Kuliah --</option>

                        @foreach($mataKuliah->unique('nama_mk') as $mk) 
                        // ★ FIX DUPLIKAT: hanya tampil satu jika nama sama
                        
                            <option value="{{ $mk->id }}" 
                                data-nama="{{ $mk->nama_mk }}" 
                                {{ old('mata_kuliah_id') == $mk->id ? 'selected' : '' }}>
                                // data-nama digunakan JS untuk deteksi jenis MK

                                {{ $mk->nama_mk }} 
                                // Nama MK ditampilkan
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
                    // Form ini muncul jika mata kuliah bukan PK atau Integrasi Sistem

                    <div class="mb-4">
                        <label for="nilai" class="form-label fw-semibold">Nilai (0-100) <span class="text-danger">*</span></label>

                        <input type="number" name="nilai" id="nilai"
                            class="form-control @error('nilai') is-invalid @enderror"
                            value="{{ old('nilai') }}" min="0" max="100" step="0.01"
                            placeholder="Masukkan nilai (contoh: 85.5)">
                        // Input nilai standar 0-100

                        @error('nilai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Form Pengambilan Keputusan -->
                <div id="form-nilai-pengambilan-keputusan" style="display: none;">
                    // Form khusus jika mata kuliah adalah Pengambilan Keputusan
                    ...
                </div>

                <!-- Form Integrasi Sistem -->
                <div id="form-integrasi-sistem" style="display: none;">
                    // Form khusus Integrasi Sistem
                    ...
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

<style>
.form-label { font-size: 0.95rem; }  // Ukuran label
.form-control, .form-select { border-radius: 8px; }  // Membulatkan field
.btn { border-radius: 8px; padding: 10px 20px; }  // Membulatkan tombol
</style>

<script>
// === Fungsi untuk menampilkan form sesuai jenis mata kuliah ===
function toggleNilaiForm() {
    const selectMK = document.getElementById('mata_kuliah_id');  // Ambil dropdown MK
    const namaMK = selectMK.options[selectMK.selectedIndex]?.getAttribute('data-nama')?.toLowerCase() || '';
    // Ambil atribut data-nama untuk identifikasi jenis MK

    const formStandar = document.getElementById('form-nilai-standar');
    const formPK = document.getElementById('form-nilai-pengambilan-keputusan');
    const formIntegrasi = document.getElementById('form-integrasi-sistem');

    // Sembunyikan semua form dulu
    formStandar.style.display = 'none';
    formPK.style.display = 'none';
    formIntegrasi.style.display = 'none';

    // Jika mata kuliah Pengambilan Keputusan
    if (namaMK.includes('pengambilan keputusan')) {
        formPK.style.display = 'block';
        calculateNilaiAkhir();
    } 
    // Jika mata kuliah Integrasi Sistem
    else if (namaMK.includes('integrasi sistem')) {
        formIntegrasi.style.display = 'block';
        setupIntegrasiSistemCalculation();
    } 
    // Jika mata kuliah biasa
    else if (namaMK) {
        formStandar.style.display = 'block';
    }
}

// === Kalkulasi Pengambilan Keputusan ===
function calculateNilaiAkhir() { ... }

// Trigger perhitungan jika field berubah
document.addEventListener('DOMContentLoaded', () => { ... });

// === Kalkulasi Integrasi Sistem ===
function setupIntegrasiSistemCalculation() { ... }
function calculateIntegrasiSistem() { ... }
</script>

@endsection