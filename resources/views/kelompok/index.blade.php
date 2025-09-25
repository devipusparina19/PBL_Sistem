@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Daftar Kelompok</h1>

    {{-- Pesan sukses --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Tombol Tambah --}}
    <div class="mb-3">
        <a href="{{ route('kelompok.create') }}" class="btn btn-primary">Tambah Kelompok</a>
    </div>

    {{-- Tabel daftar kelompok --}}
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Kode MK</th>
                <th>Nama Kelompok</th>
                <th>Judul Proyek</th>
                <th>NIP</th>
                <th>Deskripsi</th>
                <th>Aksi</th>
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
                    <td>
                        {{-- Tombol Edit --}}
                        <a href="{{ route('kelompok.edit', $item->id_kelompok) }}" class="btn btn-sm btn-warning">Edit</a>

                        {{-- Tombol Hapus --}}
                        <form action="{{ route('kelompok.destroy', $item->id_kelompok) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Belum ada data kelompok</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Pagination --}}
    <div class="d-flex justify-content-center">
        {{ $kelompok->links() }}
    </div>
</div>
@endsection
