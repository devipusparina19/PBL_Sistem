@extends('layouts.app')

@section('content')
@php
    $restrictedRoles = ['mahasiswa', 'dosen', 'koordinator_prodi', 'koordinator_pbl'];
    $isRestricted = in_array(auth()->user()->role, $restrictedRoles);
@endphp

<div class="container-fluid mt-4">
    <div class="mb-4">
        <h1 class="mb-0">Data Mata Kuliah</h1>
        @if(isset($isStudent) && $isStudent)
            <p class="text-muted mt-2 mb-0">Berikut adalah mata kuliah untuk kelas {{ $studentKelas ?? '-' }}</p>
        @else
            <p class="text-muted mt-2 mb-0">Klik pada card kelas untuk melihat dan mengelola mata kuliah per kelas</p>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(isset($isStudent) && $isStudent)
        {{-- Student View: Show Course Cards --}}
        <div class="row g-4">
            @forelse($mataKuliahList as $mk)
                <div class="col-12 col-md-6 col-xl-4">
                    <a href="{{ route('mata_kuliah.detail', $mk->id) }}" class="text-decoration-none">
                        <div class="card shadow-sm course-card h-100">
                            <div class="card-header bg-primary text-white">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">
                                        <i class="bi bi-book-fill"></i> {{ $mk->nama_mk }}
                                    </h5>
                                    <span class="badge bg-light text-primary">
                                        {{ $mk->kelas }}
                                    </span>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <p class="mb-2">
                                        <strong><i class="bi bi-hash"></i> Kode MK:</strong><br>
                                        <span class="text-muted">{{ $mk->kode_mk }}</span>
                                    </p>
                                </div>
                                @if($mk->nip_dosen)
                                    <div class="mb-0">
                                        <p class="mb-2">
                                            <strong><i class="bi bi-person-badge"></i> Dosen Pengampu:</strong><br>
                                            @foreach($mk->dosens as $dosen)
                                                <div class="d-flex align-items-center mb-1">
                                                    <span class="badge bg-secondary me-2">{{ $dosen->nip }}</span>
                                                    <span class="text-muted small">{{ $dosen->nama }}</span>
                                                </div>
                                            @endforeach
                                        </p>
                                    </div>
                                @endif
                            </div>
                            <div class="card-footer bg-transparent border-primary text-center">
                                <small class="text-primary">
                                    <i class="bi bi-arrow-right-circle"></i> Klik untuk lihat detail
                                </small>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info text-center" role="alert">
                        <i class="bi bi-info-circle" style="font-size: 2rem;"></i>
                        <p class="mb-0 mt-2">Belum ada mata kuliah untuk kelas Anda.</p>
                    </div>
                </div>
            @endforelse
        </div>
    @else
        {{-- Admin/Dosen View: Show Class Cards --}}
        <div class="row g-4">
            @foreach($kelasList as $kelas)
                <div class="col-12 col-md-6 col-xl-4">
                    <a href="{{ route('mata_kuliah.kelas', $kelas) }}" class="text-decoration-none">
                        <div class="card shadow-sm kelas-card h-100">
                            <div class="card-header bg-info text-white">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">
                                        <i class="bi bi-book-fill"></i> Kelas {{ $kelas }}
                                    </h5>
                                    <span class="badge bg-light text-info">
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
                                                                <i class="bi bi-person-badge"></i> 
                                                                @foreach($item->dosens as $dosen)
                                                                    {{ $dosen->nama }}@if(!$loop->last), @endif
                                                                @endforeach
                                                            </p>
                                                        @endif
                                                    </div>
                                                    <span class="badge bg-info text-white">{{ $item->kelas }}</span>
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
                            <div class="card-footer bg-transparent border-info text-center">
                                <small class="text-info">
                                    <i class="bi bi-arrow-right-circle"></i> Klik untuk lihat detail
                                </small>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    @endif
</div>

<style>
    .kelas-card, .course-card {
        border: none;
        border-radius: 12px;
        transition: all 0.3s ease;
        cursor: pointer;
        overflow: hidden;
    }

    .kelas-card:hover, .course-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15) !important;
    }

    .kelas-card .card-header {
        border-radius: 12px 12px 0 0 !important;
        background: linear-gradient(135deg, #17a2b8 0%, #138496 100%) !important;
        padding: 1.25rem;
    }

    .course-card .card-header {
        border-radius: 12px 12px 0 0 !important;
        background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%) !important;
        padding: 1.25rem;
    }

    .kelas-card .card-body, .course-card .card-body {
        padding: 1rem 1.25rem;
        max-height: 400px;
        overflow-y: auto;
    }

    .list-group-item {
        transition: background-color 0.2s ease;
        border-color: rgba(0, 0, 0, 0.05) !important;
        padding: 0.75rem 0;
    }

    .list-group-item:hover {
        background-color: #f8f9fa;
    }

    /* Scrollbar */
    .kelas-card .card-body::-webkit-scrollbar, .course-card .card-body::-webkit-scrollbar {
        width: 6px;
    }

    .kelas-card .card-body::-webkit-scrollbar-track, .course-card .card-body::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .kelas-card .card-body::-webkit-scrollbar-thumb {
        background: #17a2b8;
        border-radius: 10px;
    }

    .course-card .card-body::-webkit-scrollbar-thumb {
        background: #0d6efd;
        border-radius: 10px;
    }

    .kelas-card .card-body::-webkit-scrollbar-thumb:hover {
        background: #138496;
    }

    .course-card .card-body::-webkit-scrollbar-thumb:hover {
        background: #0a58ca;
    }

    .card-footer {
        border-radius: 0 0 12px 12px !important;
        padding: 0.75rem 1.25rem;
    }

    a.text-decoration-none:hover {
        text-decoration: none !important;
    }
</style>
@endsection
