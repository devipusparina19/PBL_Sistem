@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="mb-0">
                <i class="bi bi-calculator text-primary"></i> Konfigurasi AHP Kelompok
            </h1>
            <p class="text-muted mb-0">Analytic Hierarchy Process - Penentuan Bobot Kriteria Kelompok</p>
        </div>
        <a href="{{ route('kelompok.ranking') }}" class="btn btn-secondary">
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
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0"><i class="bi bi-pie-chart-fill"></i> Bobot Saat Ini</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                @foreach($criteria as $key => $label)
                <div class="col-md-3">
                    <div class="card text-center h-100">
                        <div class="card-body">
                            <h6 class="card-title">{{ $label }}</h6>
                            <h3 class="text-primary">{{ $currentWeights[$key] ?? 0 }}%</h3>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- AHP Matrix Input -->
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-grid-3x3"></i> Matriks Perbandingan Berpasangan</h5>
        </div>
        <div class="card-body">
            <div class="alert alert-info">
                <h6><i class="bi bi-info-circle"></i> Petunjuk Pengisian:</h6>
                <p class="mb-2">Pilih seberapa penting kriteria <strong>BARIS</strong> dibandingkan <strong>KOLOM</strong>.</p>
                <ul class="mb-0">
                    <li><strong>1</strong> = Sama penting</li>
                    <li><strong>3</strong> = Sedikit lebih penting</li>
                    <li><strong>5</strong> = Lebih penting</li>
                    <li><strong>7</strong> = Sangat lebih penting</li>
                    <li><strong>9</strong> = Mutlak lebih penting</li>
                    <li><strong>1/3, 1/5, 1/7, 1/9</strong> = Kebalikannya (kurang penting)</li>
                </ul>
            </div>

            <form action="{{ route('kelompok.ranking.calculateAhp') }}" method="POST">
                @csrf
                
                <div class="table-responsive">
                    <table class="table table-bordered text-center">
                        <thead class="table-dark">
                            <tr>
                                <th>Kriteria</th>
                                @foreach($criteria as $key => $label)
                                    <th>{{ $label }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @php $criteriaKeys = array_keys($criteria); @endphp
                            @foreach($criteria as $i => $rowLabel)
                                <tr>
                                    <th class="table-secondary">{{ $rowLabel }}</th>
                                    @foreach($criteria as $j => $colLabel)
                                        @php
                                            $iIndex = array_search($i, $criteriaKeys);
                                            $jIndex = array_search($j, $criteriaKeys);
                                        @endphp
                                        <td>
                                            @if($iIndex === $jIndex)
                                                <!-- Diagonal = 1 -->
                                                <span class="badge bg-secondary fs-6">1</span>
                                            @elseif($iIndex < $jIndex)
                                                <!-- Upper triangle - editable -->
                                                <select name="ahp_{{ $iIndex }}_{{ $jIndex }}" class="form-select form-select-sm ahp-input" style="width: 80px; margin: auto;">
                                                    <option value="9">9</option>
                                                    <option value="8">8</option>
                                                    <option value="7">7</option>
                                                    <option value="6">6</option>
                                                    <option value="5">5</option>
                                                    <option value="4">4</option>
                                                    <option value="3">3</option>
                                                    <option value="2">2</option>
                                                    <option value="1" selected>1</option>
                                                    <option value="0.5">1/2</option>
                                                    <option value="0.333">1/3</option>
                                                    <option value="0.25">1/4</option>
                                                    <option value="0.2">1/5</option>
                                                    <option value="0.167">1/6</option>
                                                    <option value="0.143">1/7</option>
                                                    <option value="0.125">1/8</option>
                                                    <option value="0.111">1/9</option>
                                                </select>
                                            @else
                                                <!-- Lower triangle - reciprocal (auto-calculated) -->
                                                <span class="text-muted reciprocal" id="recip_{{ $iIndex }}_{{ $jIndex }}">-</span>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 text-center">
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="bi bi-calculator"></i> Hitung Bobot AHP
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Update reciprocal values when upper triangle changes
    const ahpInputs = document.querySelectorAll('.ahp-input');
    
    ahpInputs.forEach(input => {
        input.addEventListener('change', function() {
            const name = this.name;
            const match = name.match(/ahp_(\d+)_(\d+)/);
            if (match) {
                const i = parseInt(match[1]);
                const j = parseInt(match[2]);
                const value = parseFloat(this.value);
                const reciprocal = (1 / value).toFixed(3);
                
                const recipElem = document.getElementById(`recip_${j}_${i}`);
                if (recipElem) {
                    recipElem.textContent = reciprocal;
                }
            }
        });
        
        // Trigger initial update
        input.dispatchEvent(new Event('change'));
    });
});
</script>

<style>
.form-select-sm { font-size: 0.85rem; }
.table td, .table th { vertical-align: middle; }
</style>
@endsection
