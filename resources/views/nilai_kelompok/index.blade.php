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
                <table class="table table-bordered table-hover align-middle table-sm">
                    <thead class="table-light text-center align-middle">
                        <tr>
                            <th width="3%">No</th>
                            <th>Nama Kelompok</th>
                            <th>Judul Proyek</th>
                            <th width="6%">Anggota</th>
                            <th width="6%">Milestone</th>
                            <th width="7%">Nilai Milestone</th>
                            <th width="7%">Nilai Anggota</th>
                            <th width="8%">Hasil Akhir</th>
                            @if(Auth::user()->role === 'dosen')
                                <th width="10%">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kelompoks as $index => $kelompok)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td class="fw-semibold">{{ $kelompok->nama_kelompok }}</td>
                                <td class="small">{{ $kelompok->judul_proyek ?? '-' }}</td>
                                <td class="text-center small">{{ $kelompok->mahasiswa->count() }} orang</td>
                                <td class="text-center">
                                    <span class="badge bg-info">{{ $kelompok->milestone_approved_count ?? 0 }}</span>
                                </td>
                                <td class="text-center">
                                    {{ $kelompok->nilai_milestone_avg !== null ? number_format($kelompok->nilai_milestone_avg, 1) : '-' }}
                                </td>
                                <td class="text-center">
                                    {{ $kelompok->nilai_rata_anggota !== null ? number_format($kelompok->nilai_rata_anggota, 1) : '-' }}
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
                                        <span class="badge bg-secondary">Belum</span>
                                    @endif
                                </td>
                                @if(Auth::user()->role === 'dosen')
                                    <td class="text-center">
                                        @if($kelompok->hasil_akhir !== null)
                                            <div class="d-flex gap-1 justify-content-center">
                                                <a href="{{ route('nilai_kelompok.edit', $kelompok->id_kelompok) }}" 
                                                   class="btn btn-warning btn-sm" title="Edit">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                                <form action="{{ route('nilai_kelompok.destroy', $kelompok->id_kelompok) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-danger btn-sm" 
                                                            onclick="return confirm('Yakin ingin menghapus nilai kelompok ini?')" title="Hapus">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <a href="{{ route('nilai_kelompok.create') }}?kelompok_id={{ $kelompok->id_kelompok }}" 
                                               class="btn btn-primary btn-sm btn-block w-100">
                                                <i class="bi bi-plus-circle me-1"></i> Input
                                            </a>
                                        @endif
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ Auth::user()->role === 'dosen' ? 9 : 8 }}" class="text-center text-muted">
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
