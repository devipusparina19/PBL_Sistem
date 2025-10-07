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
                        <td>{{ $item->deskripsi ?? '-' }}</td>

                        {{-- Kolom Aksi hanya untuk role yang diperbolehkan --}}
                        @unless($isRestricted)
                            <td>
                                <a href="{{ route('kelompok.edit', $item->id_kelompok) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('kelompok.destroy', $item->id_kelompok) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Hapus</button>
                                </form>
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
@endsection
