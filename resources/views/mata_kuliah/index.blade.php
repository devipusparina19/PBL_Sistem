@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Data Mata Kuliah</h1>

        {{-- Tombol Tambah --}}
        <a href="{{ route('mata_kuliah.create') }}" class="btn btn-primary">
            Tambah Mata Kuliah
        </a>
    </div>

    {{-- Form pencarian --}}
    <form method="GET" action="{{ route('mata_kuliah.index') }}" class="mb-3">
        <input type="text" name="search" class="form-control"
               placeholder="Cari kode, nama mata kuliah, atau NIP dosen..."
               value="{{ request('search') }}">
    </form>

    {{-- Tabel data mata kuliah --}}
    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead class="table-dark text-center">
                <tr>
                    <th width="5%">No</th>
                    <th>Kode MK</th>
                    <th>Nama Mata Kuliah</th>
                    <th>NIP Dosen</th>
                    <th>Semester</th>
                    <th width="25%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($mataKuliah as $mk)
                    <tr class="text-center">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $mk->kode_mk }}</td>
                        <td>{{ $mk->nama_mk }}</td>
                        <td>{{ $mk->nip ?? '-' }}</td>
                        <td>{{ $mk->semester ?? '-' }}</td>
                        <td>
                            {{-- Tombol Lihat/Show --}}
                            <a href="{{ route('mata_kuliah.show', $mk->id) }}" class="btn btn-info btn-sm me-1">
                                Lihat
                            </a>

                            {{-- Tombol Edit --}}
                            <a href="{{ route('mata_kuliah.edit', $mk->id) }}" class="btn btn-warning btn-sm me-1">
                                Edit
                            </a>

                            {{-- Tombol Hapus --}}
                            <form action="{{ route('mata_kuliah.destroy', $mk->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Yakin ingin menghapus mata kuliah ini?')">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Belum ada data mata kuliah</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="d-flex justify-content-center mt-3">
        {{ $mataKuliah->links() }}
    </div>
</div>

{{-- Tambahan CSS --}}
<style>
    .table td, .table th {
        vertical-align: middle;
        text-align: center;
    }

    .table td:last-child {
        white-space: nowrap;
    }

    .btn-sm {
        padding: 5px 10px;
        margin: 2px;
        font-size: 0.85rem;
        border-radius: 5px;
        color: #000 !important; /* Teks hitam */
    }

    /* Warna tombol tetap beda tapi teks hitam */
    .btn-info {
        background-color: #0dcaf0;
        border: none;
    }
    .btn-warning {
        background-color: #ffc107;
        border: none;
    }
    .btn-danger {
        background-color: #dc3545;
        border: none;
    }

    /* Hover warna tombol lebih gelap tapi teks tetap hitam */
    .btn-info:hover { background-color: #0bb4d8; color: #000 !important; }
    .btn-warning:hover { background-color: #e0a800; color: #000 !important; }
    .btn-danger:hover { background-color: #bb2d3b; color: #000 !important; }

    .table-responsive {
        overflow-x: auto;
    }
</style>
@endsection
