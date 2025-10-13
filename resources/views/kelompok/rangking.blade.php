@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <!-- Header -->
    <div class="text-center mb-5">
        <h2 class="fw-bold text-primary">Rangking Kelompok PBL</h2>
        <p class="text-muted">Berikut adalah peringkat kelompok berdasarkan penilaian akhir dan progres proyek.</p>
    </div>

    <!-- Card Wrapper -->
    <div class="card border-0 shadow-lg rounded-4">
        <div class="card-body p-4">
            <!-- Tabel Rangking -->
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-primary text-center">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Nama Kelompok</th>
                            <th scope="col">Judul Proyek</th>
                            <th scope="col">Anggota</th>
                            <th scope="col">Nilai Akhir</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        @php
                            // contoh data dummy (bisa diganti dengan data dari controller)
                            $kelompoks = [
                                ['nama' => 'Kelompok 1', 'judul' => 'Aplikasi Manajemen PBL', 'anggota' => 'Devi, Rina, Putri', 'nilai' => 95],
                                ['nama' => 'Kelompok 2', 'judul' => 'Sistem Informasi Desa', 'anggota' => 'Dian, Yusuf, Budi', 'nilai' => 89],
                                ['nama' => 'Kelompok 3', 'judul' => 'E-Library Politala', 'anggota' => 'Ardi, Fani, Reza', 'nilai' => 83],
                                ['nama' => 'Kelompok 4', 'judul' => 'Website Wisata Daerah', 'anggota' => 'Wulan, Yani, Tono', 'nilai' => 78],
                                ['nama' => 'Kelompok 5', 'judul' => 'Smart Trash Bin', 'anggota' => 'Andi, Rafi, Lina', 'nilai' => 72],
                            ];

                            $no = 1;
                        @endphp

                        @foreach($kelompoks as $kelompok)
                            <tr>
                                <td><strong>{{ $no++ }}</strong></td>
                                <td class="fw-semibold">{{ $kelompok['nama'] }}</td>
                                <td>{{ $kelompok['judul'] }}</td>
                                <td>{{ $kelompok['anggota'] }}</td>
                                <td class="fw-bold text-primary">{{ $kelompok['nilai'] }}</td>
                                <td>
                                    @if($kelompok['nilai'] >= 90)
                                        <span class="badge bg-success px-3 py-2">Sangat Baik</span>
                                    @elseif($kelompok['nilai'] >= 80)
                                        <span class="badge bg-info px-3 py-2">Baik</span>
                                    @elseif($kelompok['nilai'] >= 70)
                                        <span class="badge bg-warning text-dark px-3 py-2">Cukup</span>
                                    @else
                                        <span class="badge bg-danger px-3 py-2">Perlu Bimbingan</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Keterangan -->
            <div class="mt-4 text-muted small">
                <p><strong>Keterangan:</strong></p>
                <ul>
                    <li><span class="badge bg-success">Sangat Baik</span> — Nilai ≥ 90</li>
                    <li><span class="badge bg-info">Baik</span> — Nilai 80–89</li>
                    <li><span class="badge bg-warning text-dark">Cukup</span> — Nilai 70–79</li>
                    <li><span class="badge bg-danger">Perlu Bimbingan</span> — Nilai < 70</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Style tambahan -->
<style>
body {
    background-color: #f9fafc;
    font-family: "Segoe UI", Arial, sans-serif;
}

.card {
    border-radius: 16px;
    background-color: #ffffff;
}

.table thead th {
    font-weight: 600;
    letter-spacing: 0.5px;
}

.table-hover tbody tr:hover {
    background-color: #f1f7ff;
    transition: all 0.3s ease;
}

.badge {
    border-radius: 10px;
    font-size: 0.85rem;
}
</style>
@endsection
