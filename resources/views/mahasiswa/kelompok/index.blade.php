@extends('layouts.app')

@section('title', 'Data Kelompok')

@section('content')
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Data Kelompok</h2>
        <a href="{{ route('kelompok.create') }}" class="btn btn-primary">Tambah Kelompok</a>
    </div>

    <!-- Notifikasi Sukses -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Nama Kelompok</th>
                    <th>Deskripsi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kelompoks as $index => $kelompok)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $kelompok->nama }}</td>
                        <td>{{ $kelompok->deskripsi }}</td>
                        <td>
                            <a href="{{ route('kelompok.edit', $kelompok->id) }}" class="btn btn-warning btn-sm">Edit</a>

                            <form action="{{ route('kelompok.destroy', $kelompok->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah yakin ingin menghapus?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">Data kelompok tidak tersedia.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
