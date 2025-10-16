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
                        <a href="{{ route('mata_kuliah.index') }}" class="text-decoration-none">
                            <i class="bi bi-house-door"></i> Data Mata Kuliah
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Kelas {{ $kelas }}</li>
                </ol>
            </nav>
            <h1 class="mb-0">
                <i class="bi bi-book-fill text-warning"></i> Mata Kuliah Kelas {{ $kelas }}
            </h1>
            <p class="text-muted mb-0">Total: {{ $mataKuliah->total() }} Mata Kuliah</p>
        </div>
        @unless($isRestricted)
            <a href="{{ route('mata_kuliah.create', ['kelas' => $kelas]) }}" class="btn btn-warning btn-lg">
                <i class="bi bi-plus-circle"></i> Tambah Mata Kuliah
            </a>
        @endunless
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Tabel Mata Kuliah -->
    <div class="card shadow-sm">
        <div class="card-body">
            @if($mataKuliah->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th width="5%">No</th>
                                <th width="12%">Kode MK</th>
                                <th width="30%">Nama Mata Kuliah</th>
                                <th width="10%">Kelas</th>
                                <th width="20%">NIP Dosen</th>
                                @unless($isRestricted)
                                    <th width="15%" class="text-center">Aksi</th>
                                @endunless
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($mataKuliah as $item)
                                <tr>
                                    <td>{{ $loop->iteration + ($mataKuliah->currentPage() - 1) * $mataKuliah->perPage() }}</td>
                                    <td>
                                        <span class="badge bg-warning text-dark">{{ $item->kode_mk }}</span>
                                    </td>
                                    <td><strong>{{ $item->nama_mk }}</strong></td>
                                    <td>
                                        <span class="badge bg-warning">{{ $item->kelas }}</span>
                                    </td>
                                    <td>
                                        @if($item->nip_dosen)
                                            <i class="bi bi-person-badge text-muted"></i> {{ $item->nip_dosen }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    @unless($isRestricted)
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('mata_kuliah.show', $item->id) }}" 
                                                   class="btn btn-sm btn-info" 
                                                   title="Lihat Detail">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('mata_kuliah.edit', $item->id) }}" 
                                                   class="btn btn-sm btn-warning" 
                                                   title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="{{ route('mata_kuliah.destroy', $item->id) }}" 
                                                      method="POST" 
                                                      onsubmit="return confirm('Yakin ingin menghapus mata kuliah {{ $item->nama_mk }}?')"
                                                      class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-danger" 
                                                            title="Hapus">
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
                    {{ $mataKuliah->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-inbox" style="font-size: 4rem; color: #ccc;"></i>
                    <h4 class="mt-3 text-muted">Belum Ada Mata Kuliah</h4>
                    <p class="text-muted">Belum ada mata kuliah di kelas {{ $kelas }}</p>
                    @unless($isRestricted)
                        <a href="{{ route('mata_kuliah.create', ['kelas' => $kelas]) }}" class="btn btn-warning mt-3">
                            <i class="bi bi-plus-circle"></i> Tambah Mata Kuliah Pertama
                        </a>
                    @endunless
                </div>
            @endif
        </div>
    </div>

    <!-- Button Kembali -->
    <div class="mt-4">
        <a href="{{ route('mata_kuliah.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar Kelas
        </a>
    </div>
</div>
@endsection
