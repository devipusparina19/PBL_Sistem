@extends('layouts.app')

@section('content')
<div class="container py-5">
    <!-- Header -->
    <div class="text-center mb-5">
        <h2 class="fw-bold text-primary mb-2">📊 Laporan Penilaian Akhir Mahasiswa</h2>
        <p class="text-muted">Rekap data nilai akhir mahasiswa dan kelompok PBL</p>
    </div>

    <!-- Card -->
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-body">
            @if($penilaian->isEmpty())
                <div class="text-center py-5">
                    <i class="bi bi-info-circle fs-1 text-secondary mb-3"></i>
                    <h5 class="text-muted">Belum ada data penilaian yang tersedia.</h5>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle text-center">
                        <thead class="bg-primary text-white rounded-top">
                            <tr>
                                <th>No</th>
                                <th>Nama Mahasiswa</th>
                                <th>Kelompok</th>
                                <th>Nilai Akhir</th>
                                <th>Keterangan</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($penilaian as $index => $p)
                                <tr class="table-row-hover">
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $p->mahasiswa->nama ?? '-' }}</td>
                                    <td>{{ $p->kelompok->nama_kelompok ?? '-' }}</td>
                                    <td>
                                        <span class="badge 
                                            @if($p->nilai_akhir >= 85) bg-success 
                                            @elseif($p->nilai_akhir >= 70) bg-warning 
                                            @else bg-danger @endif
                                            px-3 py-2 fs-6">
                                            {{ $p->nilai_akhir ?? '-' }}
                                        </span>
                                    </td>
                                    <td>{{ $p->keterangan ?? '-' }}</td>
                                    <td>{{ $p->created_at->format('d M Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Custom Styles -->
<style>
.table-row-hover:hover {
    background-color: #f8f9fa;
    transform: scale(1.01);
    transition: all 0.2s ease;
}
.card {
    transition: all 0.3s ease;
}
.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
}
</style>
@endsection
