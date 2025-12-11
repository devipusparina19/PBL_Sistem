@extends('layouts.app')

@section('content')
<div class="container py-5">

    <!-- Header -->
    <div class="text-center mb-5">
        <h2 class="fw-semibold text-primary mb-2">Nilai Kelompok PBL</h2>
        <p class="text-muted">Kelola nilai kelompok berdasarkan performa proyek dan kontribusi kelompok</p>
        <hr class="mx-auto mt-3" style="width: 80px; height: 3px; background-color: #0d6efd; border: none;">
    </div>

    <!-- Alert Success -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Button Tambah / Input Nilai Kelompok (Khusus Dosen) -->
    @if(Auth::user()->role === 'dosen')
        <div class="mb-4">
            <a href="{{ route('nilai_kelompok.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Input Nilai Kelompok
            </a>
        </div>
    @endif

    <!-- Tabel Nilai Kelompok -->
    <div class="card shadow border-0 rounded-4">
        <div class="card-body px-5 py-4">
            <h5 class="fw-bold text-dark mb-4">Daftar Nilai Kelompok</h5>
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center">No</th>
                            <th>Nama Kelompok</th>
                            <th>Judul Proyek</th>
                            <th class="text-center">Jumlah Anggota</th>
                            <th class="text-center">Pemrograman Web</th>
                            <th class="text-center">Integrasi Sistem</th>
                            <th class="text-center">Pengambilan Keputusan</th>
                            <th class="text-center">IT Proyek</th>
                            <th class="text-center">Kontribusi Kelompok</th>
                            <th class="text-center">Penilaian Dosen</th>
                            <th class="text-center">Hasil Akhir</th>
                            @if(Auth::user()->role === 'dosen')
                                <th class="text-center">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kelompoks as $index => $kelompok)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td class="fw-semibold">{{ $kelompok->nama_kelompok }}</td>
                                <td>{{ $kelompok->judul_proyek ?? '-' }}</td>
                                <td class="text-center">{{ $kelompok->mahasiswa->count() }} orang</td>
                                <td class="text-center">
                                    {{ $kelompok->pemrograman_web !== null ? number_format($kelompok->pemrograman_web, 1) : '-' }}
                                </td>
                                <td class="text-center">
                                    {{ $kelompok->integrasi_sistem !== null ? number_format($kelompok->integrasi_sistem, 1) : '-' }}
                                </td>
                                <td class="text-center">
                                    {{ $kelompok->pengambilan_keputusan !== null ? number_format($kelompok->pengambilan_keputusan, 1) : '-' }}
                                </td>
                                <td class="text-center">
                                    {{ $kelompok->it_proyek !== null ? number_format($kelompok->it_proyek, 1) : '-' }}
                                </td>
                                <td class="text-center">
                                    {{ $kelompok->kontribusi_kelompok !== null ? number_format($kelompok->kontribusi_kelompok, 1) : '-' }}
                                </td>
                                <td class="text-center">
                                    {{ $kelompok->penilaian_dosen !== null ? number_format($kelompok->penilaian_dosen, 1) : '-' }}
                                </td>
                                <td class="text-center">
                                    @if($kelompok->hasil_akhir !== null)
                                        <span class="badge 
                                            @if($kelompok->hasil_akhir >= 85) bg-success
                                            @elseif($kelompok->hasil_akhir >= 75) bg-primary
                                            @elseif($kelompok->hasil_akhir >= 65) bg-warning
                                            @else bg-danger
                                            @endif">
                                            {{ number_format($kelompok->hasil_akhir, 2) }}
                                        </span>
                                    @else
                                        <span class="text-muted">Belum dinilai</span>
                                    @endif
                                </td>
                                @if(Auth::user()->role === 'dosen')
                                    <td class="text-center">
                                        @if($kelompok->hasil_akhir !== null)
                                            <a href="{{ route('nilai_kelompok.edit', $kelompok->id_kelompok) }}" 
                                               class="btn btn-warning btn-sm me-1">
                                                <i class="bi bi-pencil-square"></i> Edit
                                            </a>
                                            <form action="{{ route('nilai_kelompok.destroy', $kelompok->id_kelompok) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger btn-sm" 
                                                        onclick="return confirm('Yakin ingin menghapus nilai kelompok ini?')">
                                                    <i class="bi bi-trash"></i> Hapus
                                                </button>
                                            </form>
                                        @else
                                            <a href="{{ route('nilai_kelompok.create') }}?kelompok_id={{ $kelompok->id_kelompok }}" 
                                               class="btn btn-primary btn-sm">
                                                <i class="bi bi-plus-circle"></i> Input Nilai
                                            </a>
                                        @endif
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ Auth::user()->role === 'dosen' ? 13 : 12 }}" class="text-center text-muted">
                                    Belum ada data kelompok.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<style>
.table th {
    font-weight: 600;
    font-size: 0.9rem;
}

.table td {
    font-size: 0.9rem;
}

.badge {
    font-size: 0.95rem;
    padding: 0.4rem 0.7rem;
}

.btn-sm {
    padding: 0.35rem 0.7rem;
    font-size: 0.85rem;
}
</style>
@endsection
