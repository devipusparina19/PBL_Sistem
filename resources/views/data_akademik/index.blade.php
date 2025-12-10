@extends('layouts.app')

@section('content')
@php
    $user = auth()->user();
    $isMahasiswa = $user->role === 'mahasiswa';
@endphp

<div class="container-fluid mt-4">
    <!-- Header -->
    <div class="mb-4">
        <h1 class="mb-0">
            <i class="bi bi-collection-fill text-primary"></i> Data Akademik
        </h1>
        <p class="text-muted mt-2 mb-0">
            @if($isMahasiswa)
                Lihat data dosen, mata kuliah, dan mahasiswa untuk kelas {{ $kelas }}
            @else
                Lihat semua data dosen, mata kuliah, dan mahasiswa
            @endif
        </p>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- 3 Cards Layout -->
    <div class="row g-4">
        
        <!-- Card 1: Data Dosen -->
        <div class="col-12 col-xl-4">
            <div class="card shadow-sm h-100 border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-person-badge-fill"></i> Data Dosen
                    </h5>
                </div>
                <div class="card-body" style="max-height: 600px; overflow-y: auto;">
                    @if($isMahasiswa)
                        <!-- View for Mahasiswa: Show table -->
                        @if($dosens->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover table-sm">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Nama</th>
                                            <th>NIP</th>
                                            <th>Mata Kuliah</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($dosens as $dosen)
                                            <tr>
                                                <td><strong>{{ $dosen->nama }}</strong></td>
                                                <td>{{ $dosen->nip }}</td>
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
                                <p class="text-muted mt-2 mb-0">Belum ada data dosen.</p>
                            </div>
                        @endif
                    @else
                        <!-- View for Dosen/Admin: Show by class -->
                        @foreach($kelasList as $kelasItem)
                            <div class="mb-3">
                                <h6 class="text-primary fw-bold border-bottom pb-2">
                                    <i class="bi bi-mortarboard-fill"></i> Kelas {{ $kelasItem }}
                                    <span class="badge bg-primary ms-2">{{ $dosenByKelas[$kelasItem]->count() }}</span>
                                </h6>
                                @if($dosenByKelas[$kelasItem]->count() > 0)
                                    <ul class="list-unstyled ms-3">
                                        @foreach($dosenByKelas[$kelasItem]->take(3) as $item)
                                            <li class="mb-2">
                                                <strong>{{ $item->nama }}</strong>
                                                <br>
                                                <small class="text-muted">
                                                    <i class="bi bi-person-vcard"></i> {{ $item->nip }}
                                                </small>
                                            </li>
                                        @endforeach
                                        @if($dosenByKelas[$kelasItem]->count() > 3)
                                            <li class="text-muted">
                                                <small>+{{ $dosenByKelas[$kelasItem]->count() - 3 }} lainnya</small>
                                            </li>
                                        @endif
                                    </ul>
                                @else
                                    <p class="text-muted ms-3"><small>Belum ada data</small></p>
                                @endif
                            </div>
                        @endforeach
                    @endif
                </div>
                <div class="card-footer bg-light text-center">
                    <a href="{{ url('/data_dosen') }}" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-arrow-right-circle"></i> Lihat Detail
                    </a>
                </div>
            </div>
        </div>

        <!-- Card 2: Data Mata Kuliah -->
        <div class="col-12 col-xl-4">
            <div class="card shadow-sm h-100 border-0">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-book-fill"></i> Data Mata Kuliah
                    </h5>
                </div>
                <div class="card-body" style="max-height: 600px; overflow-y: auto;">
                    @if($isMahasiswa)
                        <!-- View for Mahasiswa: Show table -->
                        @if($mataKuliah->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover table-sm">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Kode</th>
                                            <th>Nama Mata Kuliah</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($mataKuliah as $mk)
                                            <tr>
                                                <td><span class="badge bg-success">{{ $mk->kode_mk }}</span></td>
                                                <td><strong>{{ $mk->nama_mk }}</strong></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                                <p class="text-muted mt-2 mb-0">Belum ada data mata kuliah.</p>
                            </div>
                        @endif
                    @else
                        <!-- View for Dosen/Admin: Show by class -->
                        @foreach($kelasList as $kelasItem)
                            <div class="mb-3">
                                <h6 class="text-success fw-bold border-bottom pb-2">
                                    <i class="bi bi-mortarboard-fill"></i> Kelas {{ $kelasItem }}
                                    <span class="badge bg-success ms-2">{{ $mataKuliahByKelas[$kelasItem]->count() }}</span>
                                </h6>
                                @if($mataKuliahByKelas[$kelasItem]->count() > 0)
                                    <ul class="list-unstyled ms-3">
                                        @foreach($mataKuliahByKelas[$kelasItem]->take(3) as $item)
                                            <li class="mb-2">
                                                <strong>{{ $item->nama_mk }}</strong>
                                                <br>
                                                <small class="text-muted">
                                                    <i class="bi bi-hash"></i> {{ $item->kode_mk }}
                                                </small>
                                            </li>
                                        @endforeach
                                        @if($mataKuliahByKelas[$kelasItem]->count() > 3)
                                            <li class="text-muted">
                                                <small>+{{ $mataKuliahByKelas[$kelasItem]->count() - 3 }} lainnya</small>
                                            </li>
                                        @endif
                                    </ul>
                                @else
                                    <p class="text-muted ms-3"><small>Belum ada data</small></p>
                                @endif
                            </div>
                        @endforeach
                    @endif
                </div>
                <div class="card-footer bg-light text-center">
                    <a href="{{ url('/mata_kuliah') }}" class="btn btn-sm btn-outline-success">
                        <i class="bi bi-arrow-right-circle"></i> Lihat Detail
                    </a>
                </div>
            </div>
        </div>

        <!-- Card 3: Data Mahasiswa -->
        <div class="col-12 col-xl-4">
            <div class="card shadow-sm h-100 border-0">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="bi bi-mortarboard-fill"></i> Data Mahasiswa
                    </h5>
                </div>
                <div class="card-body" style="max-height: 600px; overflow-y: auto;">
                    @if($isMahasiswa)
                        <!-- View for Mahasiswa: Show table -->
                        @if($mahasiswas->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover table-sm">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Nama</th>
                                            <th>NIM</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($mahasiswas as $mhs)
                                            <tr>
                                                <td><strong>{{ $mhs->name }}</strong></td>
                                                <td>{{ $mhs->nim_nip ?? '-' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                                <p class="text-muted mt-2 mb-0">Belum ada data mahasiswa.</p>
                            </div>
                        @endif
                    @else
                        <!-- View for Dosen/Admin: Show by class -->
                        @foreach($kelasList as $kelasItem)
                            <div class="mb-3">
                                <h6 class="text-warning fw-bold border-bottom pb-2">
                                    <i class="bi bi-mortarboard-fill"></i> Kelas {{ $kelasItem }}
                                    <span class="badge bg-warning text-dark ms-2">{{ $mahasiswaByKelas[$kelasItem]->count() }}</span>
                                </h6>
                                @if($mahasiswaByKelas[$kelasItem]->count() > 0)
                                    <ul class="list-unstyled ms-3">
                                        @foreach($mahasiswaByKelas[$kelasItem]->take(5) as $item)
                                            <li class="mb-2">
                                                <strong>{{ $item->name }}</strong>
                                                <br>
                                                <small class="text-muted">
                                                    <i class="bi bi-card-text"></i> {{ $item->nim_nip ?? '-' }}
                                                </small>
                                            </li>
                                        @endforeach
                                        @if($mahasiswaByKelas[$kelasItem]->count() > 5)
                                            <li class="text-muted">
                                                <small>+{{ $mahasiswaByKelas[$kelasItem]->count() - 5 }} lainnya</small>
                                            </li>
                                        @endif
                                    </ul>
                                @else
                                    <p class="text-muted ms-3"><small>Belum ada data</small></p>
                                @endif
                            </div>
                        @endforeach
                    @endif
                </div>
                <div class="card-footer bg-light text-center">
                    <a href="{{ url('/mahasiswa') }}" class="btn btn-sm btn-outline-warning">
                        <i class="bi bi-arrow-right-circle"></i> Lihat Detail
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
    .card {
        border-radius: 12px;
        overflow: hidden;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12) !important;
    }

    .card-header h5 {
        font-weight: 600;
        font-size: 1.1rem;
    }

    .table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
    }

    .card-body::-webkit-scrollbar {
        width: 8px;
    }

    .card-body::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .card-body::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 10px;
    }

    .card-body::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
</style>
@endsection
