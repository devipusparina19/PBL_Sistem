@extends('layouts.app')

@section('title', 'Data Mahasiswa')

@section('content')
@php
    $restrictedRoles = ['mahasiswa','dosen','koordinator_prodi','koordinator_pbl'];
    $isRestricted = in_array(auth()->user()->role, $restrictedRoles);
@endphp

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Data Mahasiswa</h2>

        {{-- Tombol Tambah --}}
        @unless($isRestricted)
            <a href="{{ route('mahasiswa.create') }}" class="btn btn-primary">Tambah Data Mahasiswa</a>
        @endunless
    </div>

    {{-- Notifikasi Sukses --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>NIM</th>
                    <th>Nama</th>
                    <th>Angkatan</th>
                    <th>Email</th>
                    @unless($isRestricted)
                        <th>Aksi</th>
                    @endunless
                </tr>
            </thead>
            <tbody>
                @forelse($mahasiswas as $m)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $m->nim }}</td>
                        <td>{{ $m->nama }}</td>
                        <td>{{ $m->angkatan }}</td>
                        <td>{{ $m->email }}</td>
                        @unless($isRestricted)
                            <td>
                                <a href="{{ route('mahasiswa.show', $m->id) }}" class="btn btn-info btn-sm">Lihat</a>
                                <a href="{{ route('mahasiswa.edit', $m->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('mahasiswa.destroy', $m->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah yakin ingin menghapus data ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </td>
                        @endunless
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ $isRestricted ? 5 : 6 }}" class="text-center">Data mahasiswa tidak tersedia.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
