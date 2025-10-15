@extends('layouts.app')

@section('content')
@php
    // Hanya admin yang bisa melihat tombol aksi dan tambah
    $restrictedRoles = ['mahasiswa', 'dosen', 'koordinator_prodi', 'koordinator_pbl'];
    $isRestricted = in_array(auth()->user()->role, $restrictedRoles);
@endphp

<div class="container mt-4">
    <h1 class="mb-4">Daftar Dosen</h1>

    {{-- Pesan sukses --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Form Pencarian dan Tombol Tambah --}}
    <div class="row mb-3">
        <div class="col-md-8">
            <form action="{{ route('data_dosen.index') }}" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control me-2"
                       placeholder="Cari nama / NIP / email / mata kuliah..."
                       value="{{ request('search') }}">
                <button type="submit" class="btn btn-secondary">Cari</button>
                @if(request('search'))
                    <a href="{{ route('data_dosen.index') }}" class="btn btn-outline-secondary ms-2">Reset</a>
                @endif
            </form>
        </div>

        {{-- Tombol Tambah hanya admin --}}
        <div class="col-md-4 text-end">
            @unless($isRestricted)
                <a href="{{ route('data_dosen.create') }}" class="btn btn-primary">Tambah Dosen</a>
            @endunless
        </div>
    </div>

    {{-- Tabel daftar dosen --}}
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>NIP</th>
                            <th>Email</th>
                            <th>No. Telepon</th>
                            <th>Kelas</th>
                            <th>Mata Kuliah</th>
                            @unless($isRestricted)
                                <th width="180">Aksi</th>
                            @endunless
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dosens as $item)
                            <tr>
                                <td class="text-center">{{ $loop->iteration + ($dosens->currentPage() - 1) * $dosens->perPage() }}</td>
                                <td>{{ $item->nama }}</td>
                                <td>{{ $item->nip }}</td>
                                <td>{{ $item->email }}</td>
                                <td>{{ $item->no_telp ?? '-' }}</td>
                                <td>{{ $item->kelas ?? '-' }}</td>
                                <td>{{ $item->mata_kuliah ?? '-' }}</td>

                                {{-- Kolom Aksi hanya admin --}}
                                @unless($isRestricted)
                                    <td class="text-center">
                                        <a href="{{ route('data_dosen.show', $item->id) }}" class="btn btn-sm btn-info">Lihat</a>
                                        <a href="{{ route('data_dosen.edit', $item->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                        <form action="{{ route('data_dosen.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger">Hapus</button>
                                        </form>
                                    </td>
                                @endunless
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $isRestricted ? 7 : 8 }}" class="text-center text-muted">Tidak ada data dosen</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="d-flex justify-content-center mt-3">
                {{ $dosens->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
