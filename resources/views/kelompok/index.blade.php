@extends('layouts.app')

@section('content')
@php
    $restrictedRoles = ['mahasiswa', 'dosen', 'koordinator_prodi', 'koordinator_pbl'];
    $isRestricted = in_array(auth()->user()->role, $restrictedRoles);
@endphp

<div class="container-fluid mt-4">
    <div class="mb-4">
        <h1 class="mb-0">Kelola Data Kelompok</h1>
        <p class="text-muted mt-2 mb-0">Klik pada card kelas untuk melihat dan mengelola kelompok</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Card Layout untuk Kelas -->
    <div class="row g-4">
        @foreach($kelasList as $kelas)
            <div class="col-12 col-md-6 col-xl-4">
                <a href="{{ route('kelompok.byKelas', $kelas) }}" class="text-decoration-none">
                    <div class="card shadow-sm kelas-card h-100">
                        <div class="card-header bg-primary text-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    <i class="bi bi-people-fill"></i> Kelas {{ $kelas }}
                                </h5>
                                <span class="badge bg-light text-primary">
                                    {{ $kelompokByKelas[$kelas]->count() }} Kelompok
                                </span>
                            </div>
                        </div>

                        <div class="card-body">
                            @if($kelompokByKelas[$kelas]->count() > 0)
                                <div class="list-group list-group-flush">
                                    @foreach($kelompokByKelas[$kelas] as $item)
                                        <div class="list-group-item px-0 border-start-0 border-end-0">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1 fw-bold">{{ $item->nama_kelompok }}</h6>
                                                    <p class="mb-1 text-muted small">
                                                        <i class="bi bi-book"></i> {{ $item->kode_mk }}
                                                    </p>
                                                    <p class="mb-1 small text-truncate" style="max-width: 300px;">
                                                        <i class="bi bi-folder"></i> {{ $item->judul_proyek }}
                                                    </p>
                                                </div>

                                                @unless($isRestricted)
                                                    <div class="btn-group-vertical" role="group">
                                                        <a href="{{ route('kelompok.show', $item->id_kelompok) }}" 
                                                           class="btn btn-sm btn-outline-info" 
                                                           title="Lihat Detail">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                        <a href="{{ route('kelompok.edit', $item->id_kelompok) }}" 
                                                           class="btn btn-sm btn-outline-warning" 
                                                           title="Edit">
                                                            <i class="bi bi-pencil"></i>
                                                        </a>
                                                        <form action="{{ route('kelompok.destroy', $item->id_kelompok) }}" 
                                                              method="POST" 
                                                              onsubmit="return confirm('Yakin ingin menghapus kelompok {{ $item->nama_kelompok }}?')"
                                                              class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button class="btn btn-sm btn-outline-danger w-100" 
                                                                    title="Hapus">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                @endunless
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center text-muted py-4">
                                    <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                                    <p class="mb-0 mt-2">Belum ada kelompok di kelas {{ $kelas }}</p>
                                </div>
                            @endif
                        </div>

                        <div class="card-footer text-center bg-light">
                            <small class="text-muted">
                                <i class="bi bi-arrow-right-circle"></i> Klik untuk lihat detail
                            </small>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</div>

<style>
    .kelas-card {
        border: none;
        border-radius: 12px;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .kelas-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15) !important;
    }

    a:has(.kelas-card) {
        display: block;
        height: 100%;
    }

    .kelas-card .card-header {
        border-radius: 12px 12px 0 0 !important;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        padding: 1rem;
    }

    .kelas-card .card-body {
        padding: 0.5rem 1rem 1rem;
        max-height: 500px;
        overflow-y: auto;
    }

    .list-group-item {
        transition: background-color 0.2s ease;
        border-radius: 8px;
        margin-bottom: 0.5rem;
    }

    .list-group-item:hover {
        background-color: #f8f9fa;
    }

    .btn-group-vertical .btn {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }

    .text-truncate {
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Custom scrollbar for card body */
    .kelas-card .card-body::-webkit-scrollbar {
        width: 6px;
    }

    .kelas-card .card-body::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .kelas-card .card-body::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 10px;
    }

    .kelas-card .card-body::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
</style>
@endsection
