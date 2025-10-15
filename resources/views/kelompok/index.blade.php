@extends('layouts.app')

@section('content')
@php
    $restrictedRoles = ['mahasiswa', 'dosen', 'koordinator_prodi', 'koordinator_pbl'];
    $isRestricted = in_array(auth()->user()->role, $restrictedRoles);
@endphp

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Daftar Kelompok</h1>
        @unless($isRestricted)
            <a href="{{ route('kelompok.create') }}" class="btn btn-primary">
                Tambah Kelompok
            </a>
        @endunless
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Kode MK</th>
                    <th>Nama Kelompok</th>
                    <th>Judul Proyek</th>
                    <th>Kelas</th> {{-- ✅ Tambahan kolom kelas --}}
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
                        <td>{{ $item->kelas ?? '-' }}</td> {{-- ✅ tampilkan kelas --}}
                        
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
                        <td colspan="{{ $isRestricted ? 5 : 6 }}" class="text-center">Belum ada data kelompok</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center">
        {{ $kelompok->links() }}
    </div>
</div>

<style>
    .btn-sm {
        padding: 5px 10px;
        font-size: 0.85rem;
        border-radius: 5px;
        color: #000 !important;
        border: none;
        white-space: nowrap;
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
