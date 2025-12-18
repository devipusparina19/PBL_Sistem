@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="mb-0">
                <i class="bi bi-calculator text-primary"></i> Konfigurasi AHP
            </h1>
            <p class="text-muted mb-0">Analytic Hierarchy Process - Bobot Kriteria Penilaian</p>
        </div>
        <a href="{{ route('ranking.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali ke Ranking
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-circle-fill"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Current Weights -->
    <div class="card shadow-sm">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0"><i class="bi bi-pie-chart-fill"></i> Bobot Saat Ini</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                @foreach($criteria as $key => $label)
                <div class="col-md-4 col-lg-2">
                    <div class="card text-center h-100 border-primary">
                        <div class="card-body">
                            <h6 class="card-title text-muted">{{ $label }}</h6>
                            <h3 class="text-primary mb-0">{{ $currentWeights[$key] ?? 0 }}%</h3>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <div class="alert alert-info mt-4 mb-0">
                <i class="bi bi-info-circle-fill"></i> <strong>Info:</strong> 
                Bobot kriteria ini digunakan untuk menghitung ranking mahasiswa. 
                Untuk mengubah bobot, silakan hubungi administrator sistem.
            </div>
        </div>
    </div>

</div>
@endsection
