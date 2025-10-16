@extends('layouts.app')

@section('content')

{{-- Styling badge --}}
<style>
    .badge {
        font-size: 0.9rem;
        padding: 6px 10px;
        border-radius: 8px;
    }
    .bg-success {
        background-color: #28a745 !important;
        color: white;
    }
    .bg-warning {
        background-color: #ffc107 !important;
        color: black;
    }
    .bg-danger {
        background-color: #dc3545 !important;
        color: white;
    }
</style>

<div class="container mt-4">
    <h1 class="mb-4">Daftar Milestone Kelompok</h1>

    {{-- Pesan sukses --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Tombol tambah milestone --}}
    @if(auth()->user()->kelompok_id)
        <a href="{{ route('milestone.create', ['kelompok_id' => auth()->user()->kelompok_id]) }}" class="btn btn-primary mb-3">
            Tambah Milestone
        </a>
    @else
        <div class="alert alert-warning">
            Anda belum tergabung dalam kelompok. Silakan hubungi admin atau koordinator PBL.
        </div>
    @endif

    {{-- Tabel milestone --}}
    <table class="table table-bordered align-middle">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Judul</th>
                <th>Deskripsi</th>
                <th>Minggu Ke</th>
                <th>Status</th>
                <th>Catatan Dosen</th>
                <th>Dosen Validator</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($milestones as $index => $milestone)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $milestone->judul }}</td>
                    <td>{{ $milestone->deskripsi }}</td>
                    <td>{{ $milestone->minggu_ke }}</td>
                    <td>
                        @if($milestone->status == 'disetujui')
                            <span class="badge bg-success">Disetujui</span>
                        @elseif($milestone->status == 'menunggu')
                            <span class="badge bg-warning text-dark">Menunggu</span>
                        @elseif($milestone->status == 'ditolak')
                            <span class="badge bg-danger">Ditolak</span>
                        @else
                            <span class="badge bg-secondary">Belum Divalidasi</span>
                        @endif
                    </td>
                    <td>{{ $milestone->catatan_dosen ?? '-' }}</td>

                    {{-- Menampilkan nama dosen validator jika ada relasi --}}
                    <td>{{ $milestone->dosen->name ?? '-' }}</td>

                    <td>
                        {{-- Hanya mahasiswa yang membuat bisa edit --}}
                        @if(auth()->id() == $milestone->user_id && $milestone->status != 'disetujui')
                            <a href="{{ route('milestone.edit', $milestone->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">Belum ada milestone.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
