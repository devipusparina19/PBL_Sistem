@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Daftar Milestone</h1>

    {{-- Pesan sukses --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Tombol tambah milestone --}}
    @if(auth()->user()->kelompok_id)
        <a href="{{ route('milestone.create', ['id' => auth()->user()->kelompok_id]) }}" class="btn btn-primary mb-3">
            Tambah Milestone
        </a>
    @endif

    {{-- Tabel milestone --}}
    <table class="table table-bordered">
        <thead>
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
                        @if($milestone->status == 'selesai')
                            <span class="badge bg-success">{{ ucfirst($milestone->status) }}</span>
                        @elseif($milestone->status == 'pending')
                            <span class="badge bg-warning text-dark">{{ ucfirst($milestone->status) }}</span>
                        @elseif($milestone->status == 'ditolak')
                            <span class="badge bg-danger">{{ ucfirst($milestone->status) }}</span>
                        @endif
                    </td>
                    <td>{{ $milestone->catatan_dosen ?? '-' }}</td>
                    <td>
                        {{-- Link edit --}}
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
