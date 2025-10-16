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
                        <a href="{{ route('mahasiswa.index') }}" class="text-decoration-none">
                            <i class="bi bi-house-door"></i> Data Mahasiswa
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Kelas {{ $kelas }}</li>
                </ol>
            </nav>
            <h1 class="mb-0">
                <i class="bi bi-mortarboard-fill text-success"></i> Mahasiswa Kelas {{ $kelas }}
            </h1>
            <p class="text-muted mb-0">Total: {{ $mahasiswa->total() }} Mahasiswa</p>
        </div>
        @unless($isRestricted)
            <a href="{{ route('mahasiswa.create', ['kelas' => $kelas]) }}" class="btn btn-success btn-lg">
                <i class="bi bi-plus-circle"></i> Tambah Mahasiswa
            </a>
        @endunless
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Tabel Mahasiswa -->
    <div class="card shadow-sm">
        <div class="card-body">
            @if($mahasiswa->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th width="5%">No</th>
                                <th width="12%">NIM</th>
                                <th width="20%">Nama</th>
                                <th width="8%">Kelas</th>
                                <th width="10%">Angkatan</th>
                                <th width="20%">Email</th>
                                @unless($isRestricted)
                                    <th width="15%" class="text-center">Aksi</th>
                                @endunless
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($mahasiswa as $item)
                                <tr>
                                    <td>{{ $loop->iteration + ($mahasiswa->currentPage() - 1) * $mahasiswa->perPage() }}</td>
                                    <td>
                                        <strong>{{ $item->nim }}</strong>
                                    </td>
                                    <td>{{ $item->nama }}</td>
                                    <td>
                                        <span class="badge bg-success">{{ $item->kelas }}</span>
                                    </td>
                                    <td>{{ $item->angkatan }}</td>
                                    <td>
                                        <i class="bi bi-envelope text-muted"></i> {{ $item->email }}
                                    </td>
                                    @unless($isRestricted)
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('mahasiswa.show', $item->id) }}" 
                                                   class="btn btn-sm btn-info" 
                                                   title="Lihat Detail">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('mahasiswa.edit', $item->id) }}" 
                                                   class="btn btn-sm btn-warning" 
                                                   title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="{{ route('mahasiswa.destroy', $item->id) }}" 
                                                      method="POST" 
                                                      onsubmit="return confirm('Yakin ingin menghapus mahasiswa {{ $item->nama }}?')"
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
                    {{ $mahasiswa->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-inbox" style="font-size: 4rem; color: #ccc;"></i>
                    <h4 class="mt-3 text-muted">Belum Ada Mahasiswa</h4>
                    <p class="text-muted">Belum ada mahasiswa di kelas {{ $kelas }}</p>
                    @unless($isRestricted)
                        <a href="{{ route('mahasiswa.create', ['kelas' => $kelas]) }}" class="btn btn-success mt-3">
                            <i class="bi bi-plus-circle"></i> Tambah Mahasiswa Pertama
                        </a>
                    @endunless
                </div>
            @endif
        </div>
    </div>

    <!-- Button Kembali -->
    <div class="mt-4">
        <a href="{{ route('mahasiswa.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar Kelas
        </a>
    </div>
</div>

<style>
    .btn-sm {
        padding: 5px 10px;
        font-size: 0.85rem;
        border-radius: 5px;
        border: none;
        white-space: nowrap;
    }

    .btn-info { 
        background-color: #0dcaf0; 
        color: #000;
    }
    
    .btn-warning { 
        background-color: #ffc107; 
        color: #000;
    }
    
    .btn-danger { 
        background-color: #dc3545; 
        color: #fff;
    }

    .btn-info:hover { 
        background-color: #0bb4d8 !important; 
        color: #000 !important; 
    }
    
    .btn-warning:hover { 
        background-color: #e0a800 !important; 
        color: #000 !important; 
    }
    
    .btn-danger:hover { 
        background-color: #bb2d3b !important; 
        color: #fff !important; 
    }

    .table-hover tbody tr:hover {
        background-color: rgba(40, 167, 69, 0.05);
    }

    .breadcrumb {
        background-color: transparent;
        padding: 0;
    }

    .card {
        border: none;
        border-radius: 12px;
    }

    .card-body {
        padding: 1.5rem;
    }

    .btn-group .btn {
        margin-right: 2px;
    }

    .btn-group .btn:last-child {
        margin-right: 0;
    }
</style>
@endsection
