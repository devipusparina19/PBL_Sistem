@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-header bg-warning text-dark text-center py-3 rounded-top-4">
            <h4 class="mb-0 fw-bold">Edit Nilai Mahasiswa</h4>
            <p class="mb-0 text-dark small">Perbarui nilai mahasiswa dengan data terbaru</p>
        </div>

        <div class="card-body p-4">
            <form action="{{ route('nilai.update', $nilai->id) }}" method="POST" class="row g-4">
                @csrf
                @method('PUT')

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nama Mahasiswa</label>
                    <input type="text" value="{{ $nilai->mahasiswa->nama }}" class="form-control shadow-sm" readonly>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Pemrograman Web</label>
                    <input type="number" name="pemrograman_web" value="{{ $nilai->pemrograman_web }}" class="form-control shadow-sm" min="0" max="100" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Integrasi Sistem</label>
                    <input type="number" name="integrasi_sistem" value="{{ $nilai->integrasi_sistem }}" class="form-control shadow-sm" min="0" max="100" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Pengambilan Keputusan</label>
                    <input type="number" name="pengambilan_keputusan" value="{{ $nilai->pengambilan_keputusan }}" class="form-control shadow-sm" min="0" max="100" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">IT Proyek</label>
                    <input type="number" name="it_proyek" value="{{ $nilai->it_proyek }}" class="form-control shadow-sm" min="0" max="100" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Kontribusi Kelompok</label>
                    <input type="number" name="kontribusi_kelompok" value="{{ $nilai->kontribusi_kelompok }}" class="form-control shadow-sm" min="0" max="100" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Penilaian Sejawat</label>
                    <input type="number" name="penilaian_sejawat" value="{{ $nilai->penilaian_sejawat }}" class="form-control shadow-sm" min="0" max="100" required>
                </div>

                <div class="col-12 text-center mt-4">
                    <button type="submit" class="btn btn-warning px-4 py-2 shadow-sm fw-semibold">
                        <i class="bi bi-pencil-square me-1"></i> Update Nilai
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
