@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <!-- Header -->
    <div class="text-center mb-5">
        <h2 class="fw-bold text-primary mb-2" style="font-size: 1.9rem;">
            Ranking Mahasiswa PBL
        </h2>
        <p class="text-muted">Peringkat mahasiswa berdasarkan nilai akhir individu</p>
        <hr class="mx-auto mt-3" style="width: 80px; height: 3px; background-color: #0d6efd; border: none;">
    </div>

    <!-- Alert Error -->
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Card Wrapper -->
    <div class="card border-0 shadow-lg rounded-4">
        <div class="card-body p-4">
            <!-- Tabel Ranking -->
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-primary text-center">
                        <tr>
                            <th scope="col" style="width: 80px;">Peringkat</th>
                            <th scope="col">NIM</th>
                            <th scope="col">Nama Mahasiswa</th>
                            <th scope="col">Kelas</th>
                            <th scope="col">Kelompok</th>
                            <th scope="col" style="width: 100px;">Nilai Akhir</th>
                            <th scope="col" style="width: 150px;">Status</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        @forelse($mahasiswas as $index => $mhs)
                            <tr>
                                <td>
                                    <strong class="fs-5">
                                        @if($index == 0)
                                            <span class="badge bg-warning text-dark px-3 py-2">
                                                <i class="bi bi-trophy-fill"></i> #{{ $index + 1 }}
                                            </span>
                                        @elseif($index == 1)
                                            <span class="badge bg-secondary px-3 py-2">
                                                <i class="bi bi-trophy-fill"></i> #{{ $index + 1 }}
                                            </span>
                                        @elseif($index == 2)
                                            <span class="badge bg-danger px-3 py-2">
                                                <i class="bi bi-trophy-fill"></i> #{{ $index + 1 }}
                                            </span>
                                        @else
                                            #{{ $index + 1 }}
                                        @endif
                                    </strong>
                                </td>
                                <td class="fw-medium">{{ $mhs['nim'] }}</td>
                                <td class="text-start fw-semibold">{{ $mhs['nama'] }}</td>
                                <td>{{ $mhs['kelas'] }}</td>
                                <td><span class="badge bg-info text-dark">{{ $mhs['kelompok'] }}</span></td>
                                <td class="fw-bold text-primary fs-5">{{ $mhs['nilai'] }}</td>
                                <td>
                                    @if($mhs['nilai'] >= 85)
                                        <span class="badge bg-success px-3 py-2">
                                            <i class="bi bi-star-fill"></i> Sangat Baik
                                        </span>
                                    @elseif($mhs['nilai'] >= 75)
                                        <span class="badge bg-info px-3 py-2">
                                            <i class="bi bi-star-half"></i> Baik
                                        </span>
                                    @elseif($mhs['nilai'] >= 65)
                                        <span class="badge bg-warning text-dark px-3 py-2">
                                            <i class="bi bi-dash-circle"></i> Cukup
                                        </span>
                                    @elseif($mhs['nilai'] > 0)
                                        <span class="badge bg-danger px-3 py-2">
                                            <i class="bi bi-exclamation-circle"></i> Kurang
                                        </span>
                                    @else
                                        <span class="badge bg-secondary px-3 py-2">
                                            <i class="bi bi-question-circle"></i> Belum Dinilai
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-5">
                                    <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                                    <p class="mt-3 mb-0">Belum ada data mahasiswa atau nilai</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Keterangan -->
            <div class="mt-4 p-3 bg-light rounded">
                <p class="fw-bold mb-2"><i class="bi bi-info-circle-fill text-primary"></i> Keterangan Status:</p>
                <div class="row">
                    <div class="col-md-3">
                        <span class="badge bg-success">Sangat Baik</span> = Nilai ≥ 85
                    </div>
                    <div class="col-md-3">
                        <span class="badge bg-info">Baik</span> = Nilai 75–84
                    </div>
                    <div class="col-md-3">
                        <span class="badge bg-warning text-dark">Cukup</span> = Nilai 65–74
                    </div>
                    <div class="col-md-3">
                        <span class="badge bg-danger">Kurang</span> = Nilai < 65
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Back Button -->
    <div class="text-center mt-4">
        <a href="{{ url('/home') }}" class="btn btn-outline-secondary px-4">
            <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>
</div>

<!-- Style tambahan -->
<style>
    body {
        background-color: #f9fafc;
        font-family: "Segoe UI", Arial, sans-serif;
    }

    .card {
        border-radius: 16px;
        background-color: #ffffff;
    }

    .table thead th {
        font-weight: 600;
        letter-spacing: 0.5px;
        background-color: #e7f1ff !important;
        color: #0d6efd !important;
        border: none;
    }

    .table-hover tbody tr:hover {
        background-color: #f1f7ff;
        transition: all 0.3s ease;
        transform: scale(1.005);
    }

    .badge {
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 500;
    }

    hr {
        border-radius: 5px;
        opacity: 0.9;
    }
</style>
@endsection
