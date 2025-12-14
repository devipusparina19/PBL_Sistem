@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <h2 class="fw-semibold text-primary mb-2">🏆 Perangkingan Mahasiswa</h2>
        <p class="text-muted">Berdasarkan Nilai Akademik, Nilai Proyek, dan Penilaian Sejawat</p>
        <hr class="mx-auto mt-3" style="width: 80px; height: 3px; background-color: #0d6efd; border: none;">
    </div>

    <div class="card shadow border-0 rounded-4">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center">Rank</th>
                            <th>NIM</th>
                            <th>Nama Mahasiswa</th>
                            <th>Kelas</th>
                            <th>Kelompok</th>
                            <th class="text-center">Nilai Akademik</th>
                            <th class="text-center">Nilai Proyek</th>
                            <th class="text-center">Nilai Sejawat</th>
                            <th class="text-center fw-bold text-primary">Total Skor</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rankings as $index => $rank)
                        <tr>
                            <td class="text-center fw-bold">
                                @if($index + 1 == 1) 🥇
                                @elseif($index + 1 == 2) 🥈
                                @elseif($index + 1 == 3) 🥉
                                @else {{ $index + 1 }}
                                @endif
                            </td>
                            <td>{{ $rank['nim'] }}</td>
                            <td>{{ $rank['nama'] }}</td>
                            <td>{{ $rank['kelas'] }}</td>
                            <td>{{ $rank['kelompok'] }}</td>
                            <td class="text-center">{{ $rank['score_academic'] }}</td>
                            <td class="text-center">{{ $rank['score_project'] }}</td>
                            <td class="text-center">{{ $rank['score_peer'] }}</td>
                            <td class="text-center fw-bold text-primary" style="font-size: 1.1em;">{{ $rank['total_score'] }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">Belum ada data nilai.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4">
                <div class="alert alert-info d-flex align-items-center">
                    <i class="bi bi-info-circle-fill me-2 fs-4"></i>
                    <div>
                        <strong>Keterangan Perhitungan:</strong><br>
                        Total Skor = (Nilai Akademik + Nilai Proyek + Nilai Sejawat) / 3<br>
                        <small>
                        • <strong>Nilai Akademik:</strong> Rata-rata dari IT Project, Pengambilan Keputusan, Integrasi Sistem, Pemrograman Web.<br>
                        • <strong>Nilai Proyek:</strong> Hasil Akhir dari Penilaian Kelompok.<br>
                        • <strong>Nilai Sejawat:</strong> Rata-rata Penilaian Sejawat (Peer Assessment).
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
