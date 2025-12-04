@extends('layouts.app')

@section('content')
@php
    $restrictedRoles = ['mahasiswa', 'dosen', 'koordinator_prodi', 'koordinator_pbl'];
    $isRestricted = in_array(auth()->user()->role, $restrictedRoles);
@endphp

<div class="container mt-4">

    <h2 class="fw-bold mb-3">
        <i class="bi bi-people-fill"></i> Kelompok Kelas {{ $kelas }}
    </h2>
    <p class="text-muted">Daftar seluruh kelompok pada kelas {{ $kelas }}.</p>

    {{-- Tombol Tambah Kelompok (hanya untuk role yang boleh) --}}
    @if(!$isRestricted)
        <a href="{{ route('kelompok.create', ['kelas' => $kelas]) }}"
           class="btn btn-primary mb-3">
            <i class="bi bi-plus-circle"></i> Tambah Kelompok
        </a>
    @endif

    {{-- Alert --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show">
            {{ session('warning') }}
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show">
            {{ session('info') }}
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Card List --}}
    <div class="row g-4 mt-3">
        @forelse($kelompok as $item)
            <div class="col-md-6 col-lg-4">
                <div class="card shadow-sm border-0 h-100 kelompok-card">

                    <div class="card-header bg-primary text-white py-3">
                        <h5 class="mb-0">{{ $item->nama_kelompok }}</h5>
                    </div>

                    <div class="card-body">

                        {{-- Kode MK --}}
                        <p class="mb-1">
                            <strong><i class="bi bi-book"></i> Kode MK:</strong>
                            <br> {{ $item->kode_mk }}
                        </p>

                        {{-- Judul Proyek --}}
                        <p class="mb-1">
                            <strong><i class="bi bi-journal-text"></i> Judul Proyek:</strong>
                            <br> {{ $item->judul_proyek }}
                        </p>

                        {{-- Ketua --}}
                        <p class="mb-1">
                            <strong><i class="bi bi-person-badge"></i> Ketua:</strong>
                            <br>
                            {{ $item->ketua ? $item->ketua->nama : '-' }}
                        </p>

                        {{-- Jumlah Anggota --}}
                        <p class="mb-1">
                            <strong><i class="bi bi-people"></i> Anggota:</strong>
                            <br> {{ $item->mahasiswa->count() }} orang
                        </p>

                    </div>

                    {{-- Action Buttons --}}
                    <div class="card-footer bg-white text-center py-3">

                        <a href="{{ route('kelompok.show', $item->id_kelompok) }}"
                           class="btn btn-sm btn-outline-info me-1">
                            <i class="bi bi-eye"></i> Detail
                        </a>

                        @if(!$isRestricted)
                            <a href="{{ route('kelompok.edit', $item->id_kelompok) }}"
                               class="btn btn-sm btn-outline-warning me-1">
                                <i class="bi bi-pencil-square"></i> Edit
                            </a>

                            <form action="{{ route('kelompok.destroy', $item->id_kelompok) }}"
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('Yakin ingin menghapus kelompok ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
                            </form>
                        @endif

                    </div>

                </div>
            </div>
        @empty
            <div class="col-12 text-center text-muted py-5">
                <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                <p class="mt-2">Belum ada kelompok di kelas ini.</p>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-4 d-flex justify-content-center">
        {{ $kelompok->links() }}
    </div>

    <a href="{{ route('kelompok.index') }}" class="btn btn-secondary mt-3">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>

</div>

{{-- CSS --}}
<style>
    .kelompok-card {
        border-radius: 12px;
        transition: 0.3s;
    }
    .kelompok-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 18px rgba(0,0,0,0.15);
    }
    .card-header {
        background: linear-gradient(135deg, #0d6efd, #0b5ed7) !important;
        border-bottom: none;
    }
</style>

@endsection
