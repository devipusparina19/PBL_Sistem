@extends('layouts.app')

@section('content')
@php
    $restrictedRoles = ['mahasiswa', 'dosen', 'koordinator_prodi', 'koordinator_pbl'];
    $isRestricted = in_array(auth()->user()->role, $restrictedRoles);
@endphp

<div class="container-fluid mt-4">
    <div class="mb-4">
        <h1 class="mb-0">Data Mata Kuliah</h1>
        <p class="text-muted mt-2 mb-0">Klik pada card kelas untuk melihat dan mengelola mata kuliah per kelas</p>
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
                <a href="{{ route('mata_kuliah.kelas', $kelas) }}" class="text-decoration-none">
                    <div class="card shadow-sm kelas-card h-100">
                        <div class="card-header bg-warning text-dark">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    <i class="bi bi-book-fill"></i> Kelas {{ $kelas }}
                                </h5>
                                <span class="badge bg-light text-dark">
                                    {{ $mataKuliahByKelas[$kelas]->count() }} Mata Kuliah
                                </span>
                            </div>
                        </div>
                        <div class="card-body">
                            @if($mataKuliahByKelas[$kelas]->count() > 0)
                                <div class="list-group list-group-flush">
                                    @foreach($mataKuliahByKelas[$kelas]->take(5) as $item)
                                        <div class="list-group-item px-0 border-start-0 border-end-0">
                                            <div class="d-flex align-items-start">
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1 fw-bold">{{ $item->nama_mk }}</h6>
                                                    <p class="mb-1 text-muted small">
                                                        <i class="bi bi-hash"></i> {{ $item->kode_mk }}
                                                    </p>
                                                    @if($item->nip_dosen)
                                                        <p class="mb-0 text-muted small">
                                                            <i class="bi bi-person-badge"></i> {{ $item->nip_dosen }}
                                                        </p>
                                                    @endif
                                                </div>
                                                <span class="badge bg-warning text-dark">{{ $item->kelas }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                    @if($mataKuliahByKelas[$kelas]->count() > 5)
                                        <div class="list-group-item px-0 border-0 text-center">
                                            <small class="text-muted">
                                                +{{ $mataKuliahByKelas[$kelas]->count() - 5 }} mata kuliah lainnya
                                            </small>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="text-center text-muted py-4">
                                    <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                                    <p class="mb-0 mt-2">Belum ada mata kuliah di kelas {{ $kelas }}</p>
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
        background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%) !important;
        padding: 1rem;
    }

    .kelas-card .card-body {
        padding: 0.5rem 1rem 1rem;
        max-height: 400px;
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

    /* Custom scrollbar */
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

    .card-footer {
        border-radius: 0 0 12px 12px !important;
    }
</style>
@endsection
