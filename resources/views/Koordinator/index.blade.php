@extends('layouts.app')

@section('content')
@php
    // Role yang tidak diizinkan melakukan aksi
    $restrictedRoles = ['mahasiswa', 'dosen', 'koordinator_prodi', 'koordinator_pbl'];
    $isRestricted = in_array(auth()->user()->role, $restrictedRoles);
@endphp

<div class="container mt-4">
    <h1 class="mb-4">Daftar Kelompok PBL</h1>

    {{-- Pesan sukses --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Form Pencarian dan Tombol Tambah --}}
    <div class="row mb-3">
        <div class="col-md-8">
            <form action="{{ route('koordinator.index') }}" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control me-2"
                       placeholder="Cari nama kelompok / judul proyek..."
                       value="{{ request('search') }}">
                <button type="submit" class="btn btn-secondary">Cari</button>
                @if(request('search'))
                    <a href="{{ route('koordinator.index') }}" class="btn btn-outline-secondary ms-2">Reset</a>
                @endif
            </form>
        </div>

        {{-- Tombol Tambah hanya jika bukan restricted --}}
        <div class="col-md-4 text-end">
            @unless($isRestricted)
                <a href="{{ route('koordinator.create') }}" class="btn btn-primary">Tambah Kelompok</a>
            @endunless
        </div>
    </div>

    {{-- Tabel daftar kelompok --}}
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>No</th>
                            <th>Nama Kelompok</th>
                            <th>Judul Proyek</th>
                            <th>Status</th>
                            <th>Terakhir Diperbarui</th>
                            @unless($isRestricted)
                                <th width="180">Aksi</th>
                            @endunless
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kelompoks as $index => $kelompok)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>{{ $kelompok->nama_kelompok }}</td>
                                <td>{{ $kelompok->judul_proyek ?? '-' }}</td>
                                <td class="text-center">
                                    @if($kelompok->status == 'Selesai')
                                        <span class="badge bg-success px-3 py-2">Selesai</span>
                                    @elseif($kelompok->status == 'Berjalan')
                                        <span class="badge bg-warning text-dark px-3 py-2">Berjalan</span>
                                    @else
                                        <span class="badge bg-secondary px-3 py-2">Belum Mulai</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    {{ \Carbon\Carbon::parse($kelompok->updated_at)->format('d M Y') }}
                                </td>

                                {{-- Kolom Aksi hanya admin --}}
                                @unless($isRestricted)
                                    <td class="text-center">
                                        <a href="{{ route('koordinator.show', $kelompok->id_kelompok) }}" class="btn btn-sm btn-info">Lihat</a>
                                        <a href="{{ route('koordinator.edit', $kelompok->id_kelompok) }}" class="btn btn-sm btn-warning">Edit</a>
                                        <form action="{{ route('koordinator.destroy', $kelompok->id_kelompok) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger">Hapus</button>
                                        </form>
                                    </td>
                                @endunless
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $isRestricted ? 5 : 6 }}" class="text-center text-muted">
                                    Tidak ada data kelompok PBL
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination (opsional) --}}
            @if(method_exists($kelompoks, 'links'))
                <div class="d-flex justify-content-center mt-3">
                    {{ $kelompoks->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
