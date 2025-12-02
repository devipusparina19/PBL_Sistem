@extends('layouts.app')

@section('content')
<div class="container mt-5">
    @php
        $user = auth()->user();
        $isMahasiswa = $user->role === 'mahasiswa';
        $isDosen     = $user->role === 'dosen';
        $isKetua     = strtolower($user->role_di_kelompok ?? '') === 'ketua';
    @endphp

    {{-- Header --}}
    <div class="text-center mb-4">
        <h2 class="fw-bold text-primary display-6 border-bottom pb-2" style="display:inline-block;">
            Dashboard Kelompok PBL
        </h2>
        <p class="text-muted fs-5 mt-2 mb-1">
            Selamat datang, <strong>{{ $user->name ?? 'Mahasiswa' }}</strong>
        </p>
    </div>

    {{-- Sinkronisasi Kelompok --}}
    @if($isMahasiswa)
        <div class="text-center mb-4">
            <a href="{{ route('kelompok.sinkron') }}" class="text-decoration-none">
                <i class="bi bi-arrow-repeat me-1"></i>
                <span class="fw-semibold">Sinkronisasi Kelompok</span>
            </a>
        </div>
    @endif

    <div class="row justify-content-center g-4">

        {{-- Jika dosen: hanya 1 kartu --}}
        @if($isDosen)
            <div class="col-md-6 col-lg-4 mx-auto">
                <div class="card shadow-sm border-0 rounded-4 card-dashboard">
                    <div class="card-header bg-soft-purple rounded-top-4 px-4 py-3">
                        <h5 class="mb-0 fw-semibold text-dark">
                            <i class="bi bi-check-circle text-purple me-2"></i> Validasi Milestone
                        </h5>
                    </div>
                    <div class="card-body text-center py-4">
                        <p class="text-muted small mb-4">Validasi milestone kelompok bimbingan Anda.</p>
                        <a href="{{ route('milestone.validasi') }}" 
                           class="btn btn-outline-secondary w-100 rounded-pill fw-semibold">
                            Validasi Milestone
                        </a>
                    </div>
                </div>
            </div>

        @else
            {{-- ================= MAHASISWA ================= --}}
            {{-- Lihat Milestone --}}
            <div class="col-md-5 col-lg-4">
                <div class="card shadow-sm border-0 rounded-4 card-dashboard">
                    <div class="card-header bg-soft-purple rounded-top-4 px-4 py-3">
                        <h5 class="mb-0 fw-semibold text-dark">
                            <i class="bi bi-eye-fill text-purple me-2"></i> Lihat Milestone
                        </h5>
                    </div>
                    <div class="card-body text-center py-4">
                        <p class="text-muted small mb-4">Pantau milestone kelompok Anda.</p>
                        <a href="{{ route('milestone.view') }}" 
                           class="btn btn-outline-secondary w-100 rounded-pill fw-semibold">
                            Lihat Milestone
                        </a>
                    </div>
                </div>
            </div>

            {{-- Penilaian Teman Sejawat --}}
            <div class="col-md-5 col-lg-4">
                <div class="card shadow-sm border-0 rounded-4 card-dashboard">
                    <div class="card-header bg-soft-green rounded-top-4 px-4 py-3">
                        <h5 class="mb-0 fw-semibold text-dark">
                            <i class="bi bi-people-fill text-success me-2"></i> Penilaian Teman Sejawat
                        </h5>
                    </div>
                    <div class="card-body text-center py-4">
                        <p class="text-muted small mb-4">Nilai kinerja teman kelompok Anda.</p>
                        <a href="{{ route('penilaian.sejawat.index') }}" 
                           class="btn btn-outline-success w-100 rounded-pill fw-semibold">
                            Beri Penilaian
                        </a>
                    </div>
                </div>
            </div>

            {{-- Ranking Kelompok --}}
            <div class="col-md-5 col-lg-4">
                <div class="card shadow-sm border-0 rounded-4 card-dashboard">
                    <div class="card-header bg-soft-orange rounded-top-4 px-4 py-3">
                        <h5 class="mb-0 fw-semibold text-dark">
                            <i class="bi bi-trophy-fill text-warning me-2"></i> Ranking Kelompok
                        </h5>
                    </div>
                    <div class="card-body text-center py-4">
                        <p class="text-muted small mb-4">Lihat peringkat kelompok PBL.</p>
                        <a href="{{ route('kelompok.rangking') }}" 
                           class="btn btn-outline-warning w-100 rounded-pill fw-semibold">
                            Lihat Ranking
                        </a>
                    </div>
                </div>
            </div>

        @endif

    </div>
</div>

{{-- CSS --}}
<style>
    body { background-color: #f5f7fa; font-family: 'Segoe UI', Arial, sans-serif; }

    .card-dashboard { transition: .3s ease; border-radius: 1.2rem; }
    .card-dashboard:hover { transform: translateY(-6px); box-shadow: 0 8px 22px rgba(0,0,0,0.1); }

    .bg-soft-blue   { background: #e7f1ff; }
    .bg-soft-green  { background: #e8f7ec; }
    .bg-soft-orange { background: #fff3e6; }
    .bg-soft-purple { background: #f2e9ff; }

    .text-purple { color: #9b59b6; }
</style>
@endsection
