@extends('layouts.app')

@section('content')
@php
    $restrictedRoles = ['mahasiswa', 'dosen', 'koordinator_prodi', 'koordinator_pbl'];
    $isRestricted = in_array(auth()->user()->role, $restrictedRoles);
@endphp

<div class="container-fluid mt-4">
    <div class="mb-4">
        <h1 class="mb-0">Data Dosen</h1>
        <p class="text-muted mt-2 mb-0">Klik pada card kelas untuk melihat dan mengelola data dosen per kelas</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(isset($isStudent) && $isStudent)
        {{-- Tampilan Tabel untuk Mahasiswa --}}
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-people-fill"></i> Data Dosen Kelas {{ $kelas ?? '-' }}</h5>
            </div>
            <div class="card-body">
                @if(isset($dosens) && $dosens->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Dosen</th>
                                    <th>NIP</th>
                                    <th>Email</th>
                                    <th>No. Telepon</th>
                                    <th>Mata Kuliah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dosens as $index => $dosen)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td><strong>{{ $dosen->nama }}</strong></td>
                                        <td>{{ $dosen->nip }}</td>
                                        <td>{{ $dosen->email }}</td>
                                        <td>{{ $dosen->no_telp ?? '-' }}</td>
                                        <td>
                                            @php
                                                $mkList = collect(preg_split('/[,;\n]+/', $dosen->mata_kuliah))
                                                    ->map(fn($mk) => trim($mk))
                                                    ->filter()
                                                    ->unique();
                                            @endphp
                                            @foreach($mkList as $mk)
                                                <span class="badge bg-info text-dark mb-1">{{ $mk }}</span><br>
                                            @endforeach
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                        <p class="text-muted mt-2 mb-0">Belum ada data dosen untuk kelas ini.</p>
                    </div>
                @endif
            </div>
        </div>
    @else
        <!-- Card Layout untuk Kelas (Admin/Dosen) -->
        <div class="row g-4">
            @foreach($kelasList as $kelas)
                <div class="col-12 col-md-6 col-xl-4">
                    <a href="{{ route('data_dosen.kelas', $kelas) }}" class="text-decoration-none">
                        <div class="card shadow-sm kelas-card h-100">
                            <div class="card-header bg-info text-white">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">
                                        <i class="bi bi-person-badge-fill"></i> Kelas {{ $kelas }}
                                    </h5>
                                    <span class="badge bg-light text-info">
                                        {{ $dosenByKelas[$kelas]->count() }} Dosen
                                    </span>
                                </div>
                            </div>

                            <div class="card-body">
                                @if($dosenByKelas[$kelas]->count() > 0)
                                    <div class="list-group list-group-flush">
                                        @foreach($dosenByKelas[$kelas]->take(5) as $item)
                                            <div class="list-group-item px-0 border-start-0 border-end-0">
                                                <h6 class="mb-1 fw-bold">{{ $item->nama }}</h6>
                                                <p class="mb-1 text-muted small">
                                                    <i class="bi bi-person-vcard"></i> {{ $item->nip }}<br>
                                                    <i class="bi bi-envelope"></i> {{ $item->email }}
                                                </p>

                                                @php
                                                    // Pastikan bisa memuat banyak matkul
                                                    $mataKuliahList = collect(
                                                        preg_split('/[,;\n]+/', $item->mata_kuliah)
                                                    )->map(fn($mk) => trim($mk))
                                                     ->filter()
                                                     ->unique(); // hindari duplikat matkul
                                                @endphp

                                                @if($mataKuliahList->isNotEmpty())
                                                    <ul class="mb-0 small ps-3">
                                                        @foreach($mataKuliahList as $mk)
                                                            <li class="text-truncate" style="max-width: 300px;">
                                                                <i class="bi bi-book"></i> {{ $mk }}
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>

                                    @if($dosenByKelas[$kelas]->count() > 5)
                                        <div class="text-center mt-3">
                                            <small class="text-muted">
                                                +{{ $dosenByKelas[$kelas]->count() - 5 }} dosen lainnya
                                            </small>
                                        </div>
                                    @endif
                                @else
                                    <div class="text-center py-4">
                                        <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                                        <p class="text-muted mt-2 mb-0">Belum ada dosen</p>
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
    @endif
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
