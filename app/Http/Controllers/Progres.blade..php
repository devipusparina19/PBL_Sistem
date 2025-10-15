@extends('layouts.app')

@section('content')
@php
    // Role yang tidak boleh menambah atau mengedit data
    $restrictedRoles = ['mahasiswa', 'dosen', 'koordinator_prodi', 'koordinator_pbl'];
    $isRestricted = in_array(auth()->user()->role, $restrictedRoles);
@endphp

<div class="container mt-4">
    <h1 class="mb-4">Data Progres Kelompok PBL</h1>

    {{-- Pesan sukses --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Form pencarian --}}
    <div class="row mb-3">
        <div class="col-md-8">
            <form action="{{ route('progres.index') }}" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control me-2"
                       placeholder="Cari nama mahasiswa / kelompok..."
                       value="{{ request('search') }}">
                <button type="submit" class="btn btn-secondary">Cari</button>
                @if(request('search'))
                    <a href="{{ route('progres.index') }}" class="btn btn-outline-secondary ms-2">Reset</a>
                @endif
            </form>
        </div>

        {{-- Tombol tambah progres --}}
        <div class="col-md-4 text-end">
            @unless($isRestricted)
                <a href="{{ route('progres.create') }}" class="btn btn-primary">Tambah Progres</a>
            @endunless
        </div>
    </div>

    {{-- Tabel data progres --}}
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>No</th>
                            <th>Nama Mahasiswa</th>
                            <th>Kelompok</th>
                            <th>Deskripsi Progres</th>
                            <th>Status</th>
                            <th>Tanggal Update</th>
                            @unless($isRestricted)
                                <th width="180">Aksi</th>
                            @endunless
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($progres as $item)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $item->mahasiswa->nama ?? '-' }}</td>
                                <td>{{ $item->kelompok->nama ?? '-' }}</td>
                                <td>{{ $item->deskripsi ?? '-' }}</td>
                                <td class="text-center">
                                    @if($item->status == 'Selesai')
                                        <span class="badge bg-success">Selesai</span>
                                    @elseif($item->status == 'Proses')
                                        <span class="badge bg-warning text-dark">Proses</span>
                                    @else
                                        <span class="badge bg-secondary">Belum Dimulai</span>
                                    @endif
                                </td>
                                <td class="text-center">{{ \Carbon\Carbon::parse($item->updated_at)->format('d-m-Y') }}</td>

                                {{-- Tombol aksi --}}
                                @unless($isRestricted)
                                    <td class="text-center">
                                        <a href="{{ route('progres.show', $item->id) }}" class="btn btn-sm btn-info">Lihat</a>
                                        <a href="{{ route('progres.edit', $item->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                        <form action="{{ route('progres.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger">Hapus</button>
                                        </form>
                                    </td>
                                @endunless
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $isRestricted ? 6 : 7 }}" class="text-center text-muted">Tidak ada data progres</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="d-flex justify-content-center mt-3">
                {{ $progres->links() }}
            </div>
        </div>
    </div>
</div>

{{-- Warna tabel dan card disamakan dengan halaman Data Dosen --}}
@push('styles')
<style>
.table thead.table-dark th {
    background-color: #212529 !important;
    color: #ffffff !important;
}
.table-striped tbody tr:nth-of-type(odd) {
    background-color: #ffffff !important;
}
.table-striped tbody tr:nth-of-type(even) {
    background-color: #f8f9fa !important;
}
.table-striped tbody tr:hover {
    background-color: #e9ecef !important;
}
.card {
    border: none !important;
    background-color: #ffffff !important;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
}
.btn-primary {
    background-color: #0d6efd;
    border-color: #0d6efd;
}
.btn-secondary {
    background-color: #6c757d;
    border-color: #6c757d;
}
</style>
@endpush
@endsection
