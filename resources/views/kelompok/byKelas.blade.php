@extends('layouts.app')

@section('content')
@php
    $restrictedRoles = ['mahasiswa', 'dosen', 'koordinator_prodi', 'koordinator_pbl'];
    $isRestricted = in_array(auth()->user()->role, $restrictedRoles);
@endphp

<div class="container-fluid mt-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-2">
                    <li class="breadcrumb-item">
                        <a href="{{ route('kelompok.index') }}" class="text-decoration-none">
                            <i class="bi bi-house-door"></i> Kelola Data Kelompok
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Kelas {{ $kelas }}</li>
                </ol>
            </nav>
            <h1 class="mb-0">
                <i class="bi bi-people-fill text-info"></i> Kelompok Kelas {{ $kelas }}
            </h1>
            <p class="text-muted mb-0">Total: {{ $kelompok->total() }} Kelompok</p>
        </div>
        @unless($isRestricted)
            <a href="{{ route('kelompok.create', ['kelas' => $kelas]) }}" class="btn btn-info btn-lg text-white">
                <i class="bi bi-plus-circle"></i> Tambah Kelompok
            </a>
        @endunless
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Tabel Kelompok -->
    <div class="card shadow-sm">
        <div class="card-body">
            @if($kelompok->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th width="5%">No</th>
                                <th width="15%">Nama Kelompok</th>
                                <th width="10%">Kode MK</th>
                                <th width="40%">Judul Proyek</th>
                                <th width="10%">Kelas</th>
                                @unless($isRestricted)
                                    <th width="15%" class="text-center">Aksi</th>
                                @endunless
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($kelompok as $item)
                                <tr>
                                    <td>{{ $loop->iteration + ($kelompok->currentPage() - 1) * $kelompok->perPage() }}</td>
                                    <td><strong>{{ $item->nama_kelompok }}</strong></td>
                                    <td><span class="badge bg-info text-dark">{{ $item->kode_mk }}</span></td>
                                    <td>{{ $item->judul_proyek }}</td>
                                    <td><span class="badge bg-info">{{ $item->kelas }}</span></td>
                                    @unless($isRestricted)
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <a href="{{ route('kelompok.show', $item->id_kelompok) }}" 
                                                   class="btn btn-sm btn-info text-white" title="Lihat Detail">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('kelompok.edit', $item->id_kelompok) }}" 
                                                   class="btn btn-sm btn-warning" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="{{ route('kelompok.destroy', $item->id_kelompok) }}" 
                                                      method="POST" 
                                                      onsubmit="return confirm('Yakin ingin menghapus kelompok {{ $item->nama_kelompok }}?')"
                                                      class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-danger" title="Hapus">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    @endunless
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $kelompok->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-inbox" style="font-size: 4rem; color: #ccc;"></i>
                    <h4 class="mt-3 text-muted">Belum Ada Kelompok</h4>
                    <p class="text-muted">Belum ada kelompok di kelas {{ $kelas }}</p>
                    @unless($isRestricted)
                        <a href="{{ route('kelompok.create', ['kelas' => $kelas]) }}" class="btn btn-info text-white mt-3">
                            <i class="bi bi-plus-circle"></i> Tambah Kelompok Pertama
                        </a>
                    @endunless
                </div>
            @endif
        </div>
    </div>

    <!-- Tombol Kembali -->
    <div class="mt-4">
        <a href="{{ route('kelompok.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar Kelas
        </a>
    </div>
</div>

<style>
.btn-sm { padding: 5px 10px; font-size: 0.85rem; border-radius: 5px; }
.table-hover tbody tr:hover { background-color: rgba(23, 162, 184, 0.05); }
.card { border: none; border-radius: 12px; }
.btn-info { background-color: #0dcaf0; border: none; }
.btn-info:hover { background-color: #0bb4d8; color: #fff; }
.btn-warning { color: #000; }
.btn-danger { color: #fff; }
</style>
@endsection
