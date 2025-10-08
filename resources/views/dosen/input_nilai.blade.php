@extends('layouts.app')

@section('content')
<div class="container py-5">

    <!-- Header -->
    <div class="text-center mb-5">
        <h2 class="fw-semibold text-primary mb-2">Input Nilai Mahasiswa</h2>
        <p class="text-muted fs-5">Form penilaian laporan, presentasi, dan kontribusi mahasiswa dalam proyek PBL</p>
        <div class="header-line mx-auto"></div>
    </div>

    <!-- Form Input Nilai -->
    <div class="card shadow border-0 rounded-4">
        <div class="card-body px-5 py-4">
            <form action="{{ route('nilai.store') }}" method="POST">
                @csrf

                <!-- Data Mahasiswa -->
                <div class="mb-4">
                    <label class="form-label fw-semibold text-dark">Pilih Mahasiswa</label>
                    <select name="mahasiswa_id" class="form-select form-select-lg rounded-3 shadow-sm" required>
                        <option value="" selected disabled>-- Pilih Mahasiswa --</option>
                        @foreach($mahasiswa as $mhs)
                            <option value="{{ $mhs->id }}">{{ $mhs->nama }} - {{ $mhs->nim }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Nilai Komponen -->
                <div class="row g-4">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-dark">Laporan Akhir (30%)</label>
                        <input type="number" name="laporan" class="form-control form-control-lg rounded-3 shadow-sm" placeholder="0 - 100" min="0" max="100" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-dark">Presentasi (30%)</label>
                        <input type="number" name="presentasi" class="form-control form-control-lg rounded-3 shadow-sm" placeholder="0 - 100" min="0" max="100" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-dark">Kontribusi (40%)</label>
                        <input type="number" name="kontribusi" class="form-control form-control-lg rounded-3 shadow-sm" placeholder="0 - 100" min="0" max="100" required>
                    </div>
                </div>

                <!-- Catatan Dosen -->
                <div class="mt-4">
                    <label class="form-label fw-semibold text-dark">Catatan Penilaian</label>
                    <textarea name="catatan" rows="4" class="form-control rounded-3 shadow-sm" placeholder="Tulis catatan atau evaluasi terhadap performa mahasiswa..."></textarea>
                </div>

                <!-- Tombol -->
                <div class="d-flex justify-content-end mt-4">
                    <button type="reset" class="btn btn-outline-secondary px-4 me-2 rounded-3">Reset</button>
                    <button type="submit" class="btn btn-primary px-4 rounded-3 fw-semibold shadow-sm">Simpan Nilai</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Daftar Nilai yang Sudah Diinput -->
    <div class="card shadow-sm border-0 rounded-4 mt-5">
        <div class="card-body p-4">
            <h5 class="fw-semibold mb-4 text-dark">Daftar Nilai Mahasiswa</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-primary">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>NIM</th>
                            <th>Laporan</th>
                            <th>Presentasi</th>
                            <th>Kontribusi</th>
                            <th>Rata-rata</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($nilai as $index => $n)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $n->mahasiswa->nama }}</td>
                                <td>{{ $n->mahasiswa->nim }}</td>
                                <td>{{ $n->laporan }}</td>
                                <td>{{ $n->presentasi }}</td>
                                <td>{{ $n->kontribusi }}</td>
                                <td class="fw-semibold text-primary">
                                    {{ number_format(($n->laporan*0.3 + $n->presentasi*0.3 + $n->kontribusi*0.4), 2) }}
                                </td>
                                <td>{{ $n->catatan ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    Belum ada nilai yang diinput.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Style -->
<style>
body {
    background-color: #f5f7fa;
    font-family: 'Poppins', sans-serif;
}
.header-line {
    width: 80px;
    height: 4px;
    background-color: #0d6efd;
    border-radius: 3px;
}
.form-label {
    font-size: 0.95rem;
}
input, select, textarea {
    border: 1px solid #dee2e6;
}
input:focus, select:focus, textarea:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.15rem rgba(13, 110, 253, 0.15);
}
.table th {
    color: #003366;
    font-weight: 600;
}
.table tbody tr:hover {
    background-color: #eef4ff;
    transition: 0.2s;
}
.btn-primary:hover {
    background-color: #0b5ed7;
}
</style>
@endsection
