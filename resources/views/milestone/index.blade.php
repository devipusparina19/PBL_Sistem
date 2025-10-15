@extends('layouts.app')

@section('content')

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
    <h1 class="mb-4">Daftar Milestone</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(auth()->user()->kelompok_id)
        <a href="{{ route('milestone.create', ['id' => auth()->user()->kelompok_id]) }}" class="btn btn-primary mb-3">
            Tambah Milestone
        </a>
    @endif

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Judul</th>
                <th>Deskripsi</th>
                <th>Minggu Ke</th>
                <th>Status</th>
                <th>Catatan Dosen</th>
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
                            <span class="badge bg-success">{{ ucfirst($milestone->status) }}</span>
                        @elseif($milestone->status == 'menunggu')
                            <span class="badge bg-warning text-dark">{{ ucfirst($milestone->status) }}</span>
                        @elseif($milestone->status == 'ditolak')
                            <span class="badge bg-danger">{{ ucfirst($milestone->status) }}</span>
                        @endif
                    </td>
                    <td>{{ $milestone->catatan_dosen ?? '-' }}</td>
                    <td>
                        <a href="{{ route('milestone.edit', $milestone->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Belum ada milestone.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection