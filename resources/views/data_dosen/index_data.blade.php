@extends('layouts.app')

@section('content')
@php
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
            <form action="{{ route('dosen.index') }}" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control me-2"
                       placeholder="Cari nama / NIP / email / mata kuliah..."
                       value="{{ request('search') }}">
                <button type="submit" class="btn btn-secondary">Cari</button>
                @if(request('search'))
                    <a href="{{ route('dosen.index') }}" class="btn btn-outline-secondary ms-2">Reset</a>
                @endif
            </form>
        </div>

        {{-- Tombol Tambah --}}
        <div class="col-md-4 text-end">
            @unless($isRestricted)
                <a href="{{ route('dosen.create') }}" class="btn btn-primary">➕ Tambah Dosen</a>
            @endunless
        </div>
    </div>

    {{-- Tabel daftar dosen --}}
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>NIP</th>
                            <th>Email</th>
                            <th>No. Telepon</th>
                            <th>Mata Kuliah</th>
                            @unless($isRestricted)
                                <th width="180">Aksi</th>
                            @endunless
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dosens as $item)
                            <tr>
                                <td>{{ $loop->iteration + ($dosens->currentPage() - 1) * $dosens->perPage() }}</td>
                                <td>{{ $item->nama }}</td>
                                <td>{{ $item->nip }}</td>
                                <td>{{ $item->email }}</td>
                                <td>{{ $item->no_telepon ?? '-' }}</td>
                                <td>
                                    @if($item->mataKuliah && $item->mataKuliah->count() > 0)
                                        @foreach($item->mataKuliah as $mk)
                                            <span class="badge bg-info me-1">{{ $mk->nama_mk }}</span>
                                        @endforeach
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>

                                {{-- Kolom Aksi hanya jika bukan role terbatas --}}
                                @unless($isRestricted)
                                    <td>
                                        <a href="{{ route('dosen.show', $item->id) }}" class="btn btn-sm btn-info">Lihat</a>
                                        <a href="{{ route('dosen.edit', $item->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                        <form action="{{ route('dosen.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger">Hapus</button>
                                        </form>
                                    </td>
                                @endunless
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $isRestricted ? 6 : 7 }}" class="text-center text-muted">Tidak ada data dosen</td>
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
