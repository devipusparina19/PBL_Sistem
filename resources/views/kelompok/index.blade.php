@extends('layouts.app')

@section('content')
@php
    // Role yang tidak boleh lihat tombol aksi
    $restrictedRoles = ['mahasiswa', 'dosen', 'koordinator_prodi', 'koordinator_pbl'];
    $isRestricted = in_array(auth()->user()->role, $restrictedRoles);
@endphp

<div class="container-fluid mt-4">
    <h1 class="mb-4">Kelola Data Kelompok</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row g-4">
        @foreach($kelasList as $kelas)
            <div class="col-12 col-md-6 col-xl-4">
                <a href="{{ route('kelompok.byKelas', $kelas) }}" class="text-decoration-none">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Kelas {{ $kelas }}</h5>
                            <span class="badge bg-light text-primary">{{ $kelompokByKelas[$kelas]->count() }} Kelompok</span>
                        </div>
                        <div class="card-body">
                            @if($kelompokByKelas[$kelas]->count() > 0)
                                <ul class="list-group list-group-flush">
                                    @foreach($kelompokByKelas[$kelas] as $item)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <strong>{{ $item->nama_kelompok }}</strong><br>
                                                <small>{{ $item->kode_mk }} | {{ $item->judul_proyek }}</small>
                                            </div>

                                            @if(!$isRestricted)
                                                <div class="btn-group-vertical">
                                                    <a href="{{ route('kelompok.show', $item->id_kelompok) }}" class="btn btn-sm btn-outline-info">Lihat</a>
                                                    <a href="{{ route('kelompok.edit', $item->id_kelompok) }}" class="btn btn-sm btn-outline-warning">Edit</a>
                                                    <form action="{{ route('kelompok.destroy', $item->id_kelompok) }}" method="POST" onsubmit="return confirm('Yakin ingin hapus?')">
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
                                <p class="text-muted text-center mt-3">Belum ada kelompok di kelas {{ $kelas }}</p>
                            @endif
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</div>
@endsection
