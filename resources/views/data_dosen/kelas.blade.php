@extends('layouts.app')

@section('content')
@php
    $restrictedRoles = ['mahasiswa', 'dosen', 'koordinator_prodi', 'koordinator_pbl'];
    $isRestricted = in_array(auth()->user()->role, $restrictedRoles);
@endphp

<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-2">
                    <li class="breadcrumb-item">
                        <a href="{{ route('data_dosen.index') }}" class="text-decoration-none">
                            <i class="bi bi-house-door"></i> Data Dosen
                        </a>
                    </li>
                    <li class="breadcrumb-item active">Kelas {{ $kelas }}</li>
                </ol>
            </nav>
            <h1 class="mb-0">
                <i class="bi bi-person-badge-fill text-info"></i> Dosen Kelas {{ $kelas }}
            </h1>
            <p class="text-muted mb-0">Total: {{ $dosens->total() }} Dosen</p>
        </div>
        @unless($isRestricted)
            <a href="{{ route('data_dosen.create', ['kelas' => $kelas]) }}" class="btn btn-info btn-lg text-white">
                <i class="bi bi-plus-circle"></i> Tambah Dosen
            </a>
        @endunless
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            @if($dosens->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th width="5%">No</th>
                                <th width="20%">Nama</th>
                                <th width="15%">NIP</th>
                                <th width="18%">Email</th>
                                <th width="12%">No. Telepon</th>
                                <th width="10%">Kelas</th>
                                <th width="15%">Mata Kuliah</th>
                                @unless($isRestricted)
                                    <th width="15%" class="text-center">Aksi</th>
                                @endunless
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dosens as $item)
                                <tr>
                                    <td>{{ $loop->iteration + ($dosens->currentPage() - 1) * $dosens->perPage() }}</td>
                                    <td><strong>{{ $item->nama }}</strong></td>
                                    <td><span class="badge bg-info text-dark">{{ $item->nip }}</span></td>
                                    <td><small><i class="bi bi-envelope"></i> {{ $item->email }}</small></td>
                                    <td>
                                        @if($item->no_telp)
                                            <small><i class="bi bi-telephone"></i> {{ $item->no_telp }}</small>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td><span class="badge bg-info">{{ $item->kelas }}</span></td>
                                    <td>
                                        @if($item->mata_kuliah)
                                            <small>{{ $item->mata_kuliah }}</small>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    @unless($isRestricted)
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('data_dosen.show', $item->id) }}" class="btn btn-sm btn-info text-white"><i class="bi bi-eye"></i></a>
                                                <a href="{{ route('data_dosen.edit', $item->id) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                                                <form action="{{ route('data_dosen.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin hapus {{ $item->nama }}?')" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                                </form>
                                            </div>
                                        </td>
                                    @endunless
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center mt-4">
                    {{ $dosens->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-inbox" style="font-size: 4rem; color: #ccc;"></i>
                    <h4 class="mt-3 text-muted">Belum Ada Dosen</h4>
                    <p class="text-muted">Belum ada dosen di kelas {{ $kelas }}</p>
                    @unless($isRestricted)
                        <a href="{{ route('data_dosen.create', ['kelas' => $kelas]) }}" class="btn btn-info text-white mt-3">
                            <i class="bi bi-plus-circle"></i> Tambah Dosen Pertama
                        </a>
                    @endunless
                </div>
            @endif
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('data_dosen.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<style>
.btn-sm { padding: 5px 10px; font-size: 0.85rem; border-radius: 5px; }
.table-hover tbody tr:hover { background-color: rgba(23, 162, 184, 0.05); }
.card { border: none; border-radius: 12px; }
</style>
@endsection
