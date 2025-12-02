@extends('layouts.app')

@section('content')
@php
    // Role yang tidak boleh lihat tombol aksi
    $restrictedRoles = ['mahasiswa', 'dosen', 'koordinator_prodi', 'koordinator_pbl'];
    $isRestricted = in_array(auth()->user()->role, $restrictedRoles);
@endphp

<div class="container-fluid mt-4">

    <div class="mb-4">
        <h1 class="mb-0">Data Kelompok</h1>
        <p class="text-muted mt-2 mb-0">Klik pada card kelas untuk melihat dan mengelola kelompok berdasarkan kelas</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        @foreach($kelasList as $kelas)
            <div class="col-12 col-md-6 col-xl-4">
                <a href="{{ route('kelompok.byKelas', $kelas) }}" class="text-decoration-none">
                    <div class="card shadow-sm kelas-card h-100">

                        {{-- Header --}}
                        <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="bi bi-people-fill"></i> Kelas {{ $kelas }}</h5>
                            <span class="badge bg-light text-info">
                                {{ $kelompokByKelas[$kelas]->count() }} Kelompok
                            </span>
                        </div>

                        {{-- Body --}}
                        <div class="card-body">
                            @if($kelompokByKelas[$kelas]->count() > 0)
                                <ul class="list-group list-group-flush">
                                    @foreach($kelompokByKelas[$kelas] as $item)
                                        <li class="list-group-item d-flex justify-content-between align-items-start border-0 border-bottom py-2">

                                            {{-- Info --}}
                                            <div>
                                                <strong class="d-block">{{ $item->nama_kelompok }}</strong>

                                                <small class="text-muted d-block">
                                                    <i class="bi bi-book"></i> {{ $item->kode_mk }} <br>
                                                    <i class="bi bi-journal-text"></i> {{ $item->judul_proyek }}
                                                </small>

                                                {{-- Tambahan: Ketua & jumlah anggota --}}
                                                <small class="d-block mt-1">
                                                    <i class="bi bi-person-badge"></i>
                                                    Ketua:
                                                    <strong>
                                                        {{ $item->ketua ? $item->ketua->nama : '-' }}
                                                    </strong>
                                                    <br>
                                                    <i class="bi bi-people"></i>
                                                    Anggota:
                                                    <strong>{{ $item->mahasiswa->count() }}</strong>
                                                </small>
                                            </div>

                                            {{-- Tombol Aksi --}}
                                            @if(!$isRestricted)
                                                <div class="btn-group-vertical ms-2">
                                                    <a href="{{ route('kelompok.show', $item->id_kelompok) }}"
                                                       class="btn btn-sm btn-outline-info">Lihat</a>

                                                    <a href="{{ route('kelompok.edit', $item->id_kelompok) }}"
                                                       class="btn btn-sm btn-outline-warning">Edit</a>

                                                    <form action="{{ route('kelompok.destroy', $item->id_kelompok) }}"
                                                          method="POST"
                                                          onsubmit="return confirm('Yakin ingin hapus?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                                    </form>
                                                </div>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <div class="text-center text-muted py-4">
                                    <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                                    <p class="mb-0 mt-2">Belum ada kelompok di kelas {{ $kelas }}</p>
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
</div>

{{-- CSS --}}
<style>
    .kelas-card {
        border: none;
        border-radius: 12px;
        transition: all 0.3s ease;
        overflow: hidden;
    }

    .kelas-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15) !important;
    }

    .kelas-card .card-header {
        background: linear-gradient(135deg, #17a2b8 0%, #138496 100%) !important;
        padding: 1.25rem;
        border-bottom: none;
    }

    .kelas-card .card-body {
        padding: 1.25rem;
        max-height: 400px;
        overflow-y: auto;
    }

    .kelas-card .list-group-item:hover {
        background-color: #f8f9fa;
    }

    .kelas-card .card-body::-webkit-scrollbar {
        width: 6px;
    }

    .kelas-card .card-body::-webkit-scrollbar-thumb {
        background: #17a2b8;
        border-radius: 10px;
    }
</style>
@endsection
