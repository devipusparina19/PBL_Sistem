@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-header bg-primary text-white text-center py-3 rounded-top-4">
            <h4 class="mb-0 fw-bold">Input Nilai Mahasiswa</h4>
            <p class="mb-0 text-light small">Silakan isi nilai untuk setiap aspek penilaian mahasiswa</p>
        </div>

        <div class="card-body p-4">
            <form action="{{ route('nilai.store') }}" method="POST" class="row g-4">
                @csrf

                <!-- Nama Mahasiswa -->
                <div class="col-md-6">
                    <label for="mahasiswa_id" class="form-label fw-semibold">Nama Mahasiswa</label>
                    <select name="mahasiswa_id" id="mahasiswa_id" class="form-select shadow-sm" required>
                        <option value="">-- Pilih Mahasiswa --</option>
                        @foreach($mahasiswa as $mhs)
                            <option value="{{ $mhs->id }}">{{ $mhs->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Pemrograman Web -->
                <div class="col-md-6">
                    <label for="pemrograman_web" class="form-label fw-semibold">Pemrograman Web</label>
                    <input type="number" name="pemrograman_web" id="pemrograman_web" class="form-control shadow-sm" placeholder="0 - 100" min="0" max="100" required>
                </div>

                <!-- Integrasi Sistem -->
                <div class="col-md-6">
                    <label for="integrasi_sistem" class="form-label fw-semibold">Integrasi Sistem</label>
                    <input type="number" name="integrasi_sistem" id="integrasi_sistem" class="form-control shadow-sm" placeholder="0 - 100" min="0" max="100" required>
                </div>

                <!-- Pengambilan Keputusan -->
                <div class="col-md-6">
                    <label for="pengambilan_keputusan" class="form-label fw-semibold">Pengambilan Keputusan</label>
                    <input type="number" name="pengambilan_keputusan" id="pengambilan_keputusan" class="form-control shadow-sm" placeholder="0 - 100" min="0" max="100" required>
                </div>

                <!-- IT Proyek -->
                <div class="col-md-6">
                    <label for="it_proyek" class="form-label fw-semibold">IT Proyek</label>
                    <input type="number" name="it_proyek" id="it_proyek" class="form-control shadow-sm" placeholder="0 - 100" min="0" max="100" required>
                </div>

                <!-- Kontribusi Kelompok -->
                <div class="col-md-6">
                    <label for="kontribusi_kelompok" class="form-label fw-semibold">Kontribusi Kelompok</label>
                    <input type="number" name="kontribusi_kelompok" id="kontribusi_kelompok" class="form-control shadow-sm" placeholder="0 - 100" min="0" max="100" required>
                </div>

                <!-- Penilaian Sejawat -->
                <div class="col-md-6">
                    <label for="penilaian_sejawat" class="form-label fw-semibold">Penilaian Sejawat</label>
                    <input type="number" name="penilaian_sejawat" id="penilaian_sejawat" class="form-control shadow-sm" placeholder="0 - 100" min="0" max="100" required>
                </div>

                <!-- Tombol Simpan -->
                <div class="col-12 text-center mt-4">
                    <button type="submit" class="btn btn-success px-4 py-2 shadow-sm fw-semibold">
                        <i class="bi bi-save me-1"></i> Simpan Nilai
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
