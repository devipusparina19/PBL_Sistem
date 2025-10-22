@extends('layouts.app')

@section('content')
@php
    $restrictedRoles = ['mahasiswa', 'dosen', 'koordinator_prodi', 'koordinator_pbl'];
    $isRestricted = in_array(auth()->user()->role, $restrictedRoles);
@endphp

<div class="container-fluid mt-4">
    <div class="mb-4">
        <h1 class="mb-0">Data Mahasiswa</h1>
        <p class="text-muted mt-2 mb-0">Klik pada card kelas untuk melihat dan mengelola data mahasiswa per kelas</p>
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
                <a href="{{ route('mahasiswa.kelas', $kelas) }}" class="text-decoration-none">
                    <div class="card shadow-sm kelas-card h-100">
                        <div class="card-header bg-info text-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    <i class="bi bi-mortarboard-fill"></i> Kelas {{ $kelas }}
                                </h5>
                                <span class="badge bg-light text-info">
                                    {{ $mahasiswaByKelas[$kelas]->count() }} Mahasiswa
                                </span>
                            </div>
                        </div>

                        <div class="card-body">
                            @if($mahasiswaByKelas[$kelas]->count() > 0)
                                <div class="list-group list-group-flush">
                                    @foreach($mahasiswaByKelas[$kelas]->take(5) as $item)
                                        <div class="list-group-item px-0 border-start-0 border-end-0">
                                            <h6 class="mb-1 fw-bold">{{ $item->nama }}</h6>
                                            <p class="mb-1 text-muted small">
                                                <i class="bi bi-card-text"></i> {{ $item->nim }}
                                            </p>
                                            <p class="mb-0 small text-truncate" style="max-width: 300px;">
                                                <i class="bi bi-book"></i> {{ $item->kelas }}
                                            </p>
                                        </div>
                                    @endforeach
                                </div>

                                @if($mahasiswaByKelas[$kelas]->count() > 5)
                                    <div class="text-center mt-3">
                                        <small class="text-muted">
                                            +{{ $mahasiswaByKelas[$kelas]->count() - 5 }} mahasiswa lainnya
                                        </small>
                                    </div>
                                @endif
                            @else
                                <div class="text-center py-4">
                                    <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                                    <p class="text-muted mt-2 mb-0">Belum ada mahasiswa</p>
                                </div>
                            @endif
                        </div>

                        <div class="card-footer bg-transparent border-info">
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-info">
                                    <i class="bi bi-arrow-right-circle"></i> Klik untuk lihat detail
                                </small>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</div>

<style>
    .kelas-card {
        transition: transform 0.2s, box-shadow 0.2s;
        border: none;
        border-radius: 12px;
        overflow: hidden;
    }

    .kelas-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15) !important;
    }

    .kelas-card .card-header {
        background: linear-gradient(135deg, #17a2b8 0%, #138496 100%) !important;
        padding: 1.25rem;
    }

    .kelas-card .card-body {
        padding: 1.25rem;
    }

    .kelas-card .list-group-item {
        border-color: rgba(0, 0, 0, 0.05) !important;
        padding: 0.75rem 0;
    }

    .kelas-card .list-group-item:first-child {
        border-top: none !important;
        padding-top: 0;
    }

    .kelas-card .list-group-item:last-child {
        border-bottom: none !important;
    }

    .kelas-card .card-footer {
        padding: 0.75rem 1.25rem;
    }

    a.text-decoration-none:hover {
        text-decoration: none !important;
    }
</style>
@endsection
