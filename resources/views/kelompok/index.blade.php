@extends('layouts.app')

@section('content')
@php
    $restrictedRoles = ['mahasiswa', 'dosen', 'koordinator_prodi', 'koordinator_pbl'];
    $isRestricted = in_array(auth()->user()->role, $restrictedRoles);
@endphp

<div class="container mt-4">
    <h1 class="mb-4">Daftar Kelompok</h1>

    {{-- Pesan sukses --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Tombol Tambah (hanya untuk role tertentu) --}}
    @unless($isRestricted)
        <div class="mb-3">
            <a href="{{ route('kelompok.create') }}" class="btn btn-primary">Tambah Kelompok</a>
        </div>
    @endunless

    {{-- Tabel daftar kelompok --}}
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Kode MK</th>
                    <th>Nama Kelompok</th>
                    <th>Judul Proyek</th>
                    <th>NIP</th>
                    <th>Deskripsi</th>
                    @unless($isRestricted)
                        <th>Aksi</th>
                    @endunless
                </tr>
            </thead>
            <tbody>
                @forelse($kelompok as $item)
                    <tr>
                        <td>{{ $loop->iteration + ($kelompok->currentPage() - 1) * $kelompok->perPage() }}</td>
                        <td>{{ $item->kode_mk }}</td>
                        <td>{{ $item->nama_kelompok }}</td>
                        <td>{{ $item->judul_proyek }}</td>
                        <td>{{ $item->nip }}</td>
                        <td style="white-space: pre-line;">{{ $item->deskripsi ?? '-' }}</td>

                        {{-- Kolom Aksi hanya untuk role yang diperbolehkan --}}
                        @unless($isRestricted)
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('kelompok.show', $item->id_kelompok) }}" class="btn btn-sm btn-info">Lihat</a>
                                    <a href="{{ route('kelompok.edit', $item->id_kelompok) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('kelompok.destroy', $item->id_kelompok) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        @endunless
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ $isRestricted ? 6 : 7 }}" class="text-center">Belum ada data kelompok</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="d-flex justify-content-center">
        {{ $kelompok->links() }}
    </div>
</div>

{{-- CSS khusus tombol --}}
<style>
    .btn-sm {
        padding: 5px 10px;
        font-size: 0.85rem;
        border-radius: 5px;
        color: #000 !important;
        border: none;
        white-space: nowrap; /* tombol tidak turun ke baris baru */
        margin: 2px;
    }

    .btn-info { background-color: #0dcaf0; }
    .btn-warning { background-color: #ffc107; }
    .btn-danger { background-color: #dc3545; }

    .btn-info:hover { background-color: #0bb4d8 !important; color: #000 !important; }
    .btn-warning:hover { background-color: #e0a800 !important; color: #000 !important; }
    .btn-danger:hover { background-color: #bb2d3b !important; color: #000 !important; }
</style>
@endsection
