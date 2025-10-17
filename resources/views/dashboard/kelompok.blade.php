@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <!-- Header -->
    <div class="text-center mb-5">
        <h2 class="fw-bold text-primary display-6 border-bottom pb-2" style="display:inline-block;">
            Dashboard Kelompok PBL
        </h2>
        <p class="text-muted fs-5 mt-2">
            Selamat datang, <strong>{{ Auth::user()->name ?? 'Mahasiswa' }}</strong>
        </p>
    </div>

    <!-- Card Section -->
    <div class="row justify-content-center g-4">

        @if(Auth::user()->role == 'dosen')
            <!-- Dosen hanya melihat dan validasi milestone -->
            <div class="col-md-6 col-lg-4 mx-auto">
                <div class="card shadow-sm border-0 rounded-4 card-dashboard">
                    <div class="card-header bg-soft-purple d-flex justify-content-between align-items-center rounded-top-4 px-4 py-3">
                        <h5 class="mb-0 fw-semibold text-dark">
                            <i class="bi bi-eye-fill text-purple me-2"></i> Validasi Milestone
                        </h5>
                    </div>
                    <div class="card-body text-center py-4">
                        <p class="text-muted small mb-4">Pantau dan validasi milestone kelompok bimbingan Anda.</p>
                        <a href="{{ route('milestone.dosenView') }}" 
                           class="btn btn-outline-secondary fw-semibold w-100 rounded-pill">
                            <i class="bi bi-check2-circle me-1"></i> Validasi Milestone
                        </a>
                    </div>
                </div>
            </div>
        @else
            <!-- Mahasiswa: input milestone, penilaian sejawat, ranking -->
            <!-- Input Milestone -->
            <div class="col-md-5 col-lg-4">
                <div class="card shadow-sm border-0 rounded-4 card-dashboard">
                    <div class="card-header bg-soft-blue d-flex justify-content-between align-items-center rounded-top-4 px-4 py-3">
                        <h5 class="mb-0 fw-semibold text-dark">
                            <i class="bi bi-journal-check text-primary me-2"></i> Input Milestone
                        </h5>
                    </div>
                    <div class="card-body text-center py-4">
                        <p class="text-muted small mb-4">Laporkan progres dan pencapaian proyek setiap minggu.</p>
                        @if(auth()->user()->role_di_kelompok === 'ketua')
                            <a href="{{ route('milestone.create') }}" 
                               class="btn btn-outline-primary fw-semibold w-100 rounded-pill">
                                <i class="bi bi-plus-circle me-1"></i> Isi Milestone
                            </a>
                        @else
                            <a href="{{ route('milestone.view') }}" 
                               class="btn btn-outline-secondary fw-semibold w-100 rounded-pill">
                                <i class="bi bi-eye me-1"></i> Lihat Milestone
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Lihat Milestone (hanya ketua) -->
            @if(auth()->user()->role_di_kelompok === 'ketua')
            <div class="col-md-5 col-lg-4">
                <div class="card shadow-sm border-0 rounded-4 card-dashboard">
                    <div class="card-header bg-soft-purple d-flex justify-content-between align-items-center rounded-top-4 px-4 py-3">
                        <h5 class="mb-0 fw-semibold text-dark">
                            <i class="bi bi-eye-fill text-purple me-2"></i> Lihat Milestone
                        </h5>
                    </div>
                    <div class="card-body text-center py-4">
                        <p class="text-muted small mb-4">Pantau milestone kelompok Anda.</p>
                        <a href="{{ route('milestone.view') }}" 
                           class="btn btn-outline-secondary fw-semibold w-100 rounded-pill">
                            <i class="bi bi-eye me-1"></i> Lihat Milestone
                        </a>
                    </div>
                </div>
            </div>
            @endif

            <!-- Penilaian Teman Sejawat -->
            <div class="col-md-5 col-lg-4">
                <div class="card shadow-sm border-0 rounded-4 card-dashboard">
                    <div class="card-header bg-soft-green d-flex justify-content-between align-items-center rounded-top-4 px-4 py-3">
                        <h5 class="mb-0 fw-semibold text-dark">
                            <i class="bi bi-people-fill text-success me-2"></i> Penilaian Teman Sejawat
                        </h5>
                    </div>
                    <div class="card-body text-center py-4">
                        <p class="text-muted small mb-4">Nilai kinerja rekan satu kelompok secara objektif dan transparan.</p>
                        <a href="{{ url('/penilaian/sejawat') }}" 
                           class="btn btn-outline-success fw-semibold w-100 rounded-pill">
                            <i class="bi bi-pencil-square me-1"></i> Beri Penilaian
                        </a>
                    </div>
                </div>
            </div>

            <!-- Ranking / Input Nilai -->
            <div class="col-md-5 col-lg-4">
                <div class="card shadow-sm border-0 rounded-4 card-dashboard">
                    <div class="card-header bg-soft-orange d-flex justify-content-between align-items-center rounded-top-4 px-4 py-3">
                        <h5 class="mb-0 fw-semibold text-dark">
                            <i class="bi bi-trophy-fill text-warning me-2"></i> Ranking Kelompok
                        </h5>
                    </div>
                    <div class="card-body text-center py-4">
                        <p class="text-muted small mb-4">Lihat peringkat kelompok berdasarkan nilai rata-rata PBL.</p>
                        <a href="{{ route('kelompok.rangking') }}" 
                           class="btn btn-outline-warning fw-semibold w-100 rounded-pill">
                            <i class="bi bi-bar-chart-line me-1"></i> Lihat Ranking
                        </a>
                    </div>
                </div>
            </div>

        @endif

    </div>
</div>

<!-- CSS -->
<style>
body { background-color: #f5f7fa; font-family: 'Segoe UI', Arial, sans-serif; }

.card-dashboard { transition: all 0.3s ease; border-radius: 1.2rem; }
.card-dashboard:hover { transform: translateY(-6px); box-shadow: 0 8px 22px rgba(0, 0, 0, 0.1); }

.bg-soft-blue { background: #e7f1ff; }
.bg-soft-green { background: #e8f7ec; }
.bg-soft-orange { background: #fff3e6; }
.bg-soft-purple { background: #f2e9ff; }

.text-purple { color: #9b59b6; }

.btn { transition: 0.2s ease; }
.btn:hover { transform: scale(1.03); }
</style>
@endsection
