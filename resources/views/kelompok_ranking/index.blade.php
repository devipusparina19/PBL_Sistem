@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="mb-0">
                <i class="bi bi-trophy-fill text-warning"></i> Ranking Kelompok
            </h1>
        </div>
        @if(in_array(auth()->user()->role, ['admin', 'dosen']))
        <div>
            <a href="{{ route('kelompok.ranking.ahp') }}" class="btn btn-outline-primary">
                <i class="bi bi-sliders"></i> Konfigurasi AHP
            </a>
            @if(auth()->user()->role === 'admin')
            <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#weightSettings">
                <i class="bi bi-gear"></i> Pengaturan Bobot
            </button>
            @endif
        </div>
        @endif
        
        <!-- Export Buttons - Available for all roles -->
        <div class="ms-2">
            <div class="btn-group">
                <a href="{{ route('kelompok.ranking.exportExcel') }}" class="btn btn-success">
                    <i class="bi bi-file-earmark-excel"></i> Excel
                </a>
                <a href="{{ route('kelompok.ranking.exportPdf') }}" class="btn btn-danger">
                    <i class="bi bi-file-earmark-pdf"></i> PDF
                </a>
            </div>
        </div>
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

    @if(auth()->user()->role === 'admin')
    <!-- Weight Settings Panel -->
    <div class="collapse mb-4" id="weightSettings">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-sliders"></i> Pengaturan Bobot SAW Kelompok (Total = 100%)</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('kelompok.ranking.updateWeights') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Nilai Milestone</label>
                            <div class="input-group">
                                <input type="number" name="milestone" class="form-control text-center weight-input" 
                                       value="{{ $weights['milestone'] ?? 0 }}" min="0" max="100" required>
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Nilai Rata-rata Anggota</label>
                            <div class="input-group">
                                <input type="number" name="rata_anggota" class="form-control text-center weight-input" 
                                       value="{{ $weights['rata_anggota'] ?? 0 }}" min="0" max="100" required>
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Kontribusi Kelompok</label>
                            <div class="input-group">
                                <input type="number" name="kontribusi" class="form-control text-center weight-input" 
                                       value="{{ $weights['kontribusi'] ?? 0 }}" min="0" max="100" required>
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Penilaian Dosen</label>
                            <div class="input-group">
                                <input type="number" name="penilaian_dosen" class="form-control text-center weight-input" 
                                       value="{{ $weights['penilaian_dosen'] ?? 0 }}" min="0" max="100" required>
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div id="weight-total" class="alert alert-info">
                            Total: <strong><span id="weight-sum">100</span>%</strong>
                        </div>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-save"></i> Simpan Bobot
                        </button>
                        <a href="{{ route('kelompok.ranking.ahp') }}" class="btn btn-outline-primary">
                            <i class="bi bi-calculator"></i> Hitung dengan AHP
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Method Toggle -->
    <div class="mb-3">
        <div class="btn-group" role="group">
            <a href="{{ route('kelompok.ranking', ['method' => 'saw']) }}" 
               class="btn {{ $method === 'saw' ? 'btn-primary' : 'btn-outline-primary' }}">
                <i class="bi bi-calculator"></i> SAW
            </a>
            <a href="{{ route('kelompok.ranking', ['method' => 'average']) }}" 
               class="btn {{ $method === 'average' ? 'btn-primary' : 'btn-outline-primary' }}">
                <i class="bi bi-bar-chart"></i> Rata-rata
            </a>
        </div>
    </div>

    @if(in_array(auth()->user()->role, ['admin', 'dosen']))
    <!-- SAW Normalized Table -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0"><i class="bi bi-table"></i> Tabel Perhitungan SAW (Nilai Ternormalisasi)</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover mb-0">
                    <thead class="table-secondary">
                        <tr>
                            <th class="text-center" rowspan="2" style="vertical-align: middle;">Kelompok</th>
                            <th class="text-center" colspan="4">Kriteria (Nilai Ternormalisasi)</th>
                            <th class="text-center bg-success text-white" rowspan="2" style="vertical-align: middle;">Skor SAW</th>
                        </tr>
                        <tr>
                            <th class="text-center">Milestone<br><small>({{ $weights['milestone'] }}%)</small></th>
                            <th class="text-center">Rata-rata Anggota<br><small>({{ $weights['rata_anggota'] }}%)</small></th>
                            <th class="text-center">Kontribusi<br><small>({{ $weights['kontribusi'] }}%)</small></th>
                            <th class="text-center">Penilaian Dosen<br><small>({{ $weights['penilaian_dosen'] }}%)</small></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rankings as $kelompok)
                        <tr>
                            <td>
                                <strong>{{ $kelompok['nama_kelompok'] }}</strong>
                                <br><small class="text-muted">{{ $kelompok['kelas'] }}</small>
                            </td>
                            <td class="text-center">{{ number_format($kelompok['normalized']['milestone'] ?? 0, 2) }}</td>
                            <td class="text-center">{{ number_format($kelompok['normalized']['rata_anggota'] ?? 0, 2) }}</td>
                            <td class="text-center">{{ number_format($kelompok['normalized']['kontribusi'] ?? 0, 2) }}</td>
                            <td class="text-center">{{ number_format($kelompok['normalized']['penilaian_dosen'] ?? 0, 2) }}</td>
                            <td class="text-center bg-light">
                                <strong>{{ number_format($kelompok['saw_score'] / 100, 2) }}</strong>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                                <p class="text-muted mt-2">Belum ada data kelompok</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- Final Ranking Table - Premium Design -->
    <div class="card shadow-lg border-0 ranking-card mt-4">
        <div class="card-header ranking-header text-white py-3">
            <div class="d-flex align-items-center justify-content-between">
                <h5 class="mb-0">
                    <i class="bi bi-trophy-fill me-2"></i> Ranking Kelompok
                </h5>
                <span class="badge bg-light text-dark">
                    <i class="bi bi-people-fill"></i> {{ count($rankings) }} Kelompok
                </span>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 ranking-table">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 80px;">Peringkat</th>
                            <th>Kelompok</th>
                            <th>Judul Proyek</th>
                            <th class="text-center">Kelas</th>
                            <th>Ketua</th>
                            <th class="text-center">Anggota</th>
                            @if(auth()->user()->role !== 'mahasiswa')
                            <th class="text-center">Milestone</th>
                            <th class="text-center">Rata-rata</th>
                            <th class="text-center">Kontribusi</th>
                            <th class="text-center">Dosen</th>
                            @endif
                            <th class="text-center score-header">Skor Akhir</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rankings as $kelompok)
                        <tr class="ranking-row {{ $kelompok['rank'] <= 3 ? 'top-rank rank-' . $kelompok['rank'] : '' }}">
                            <td class="text-center">
                                @if($kelompok['rank'] == 1)
                                    <div class="rank-badge rank-gold">
                                        <i class="bi bi-trophy-fill"></i>
                                        <span>1</span>
                                    </div>
                                @elseif($kelompok['rank'] == 2)
                                    <div class="rank-badge rank-silver">
                                        <i class="bi bi-award-fill"></i>
                                        <span>2</span>
                                    </div>
                                @elseif($kelompok['rank'] == 3)
                                    <div class="rank-badge rank-bronze">
                                        <i class="bi bi-award"></i>
                                        <span>3</span>
                                    </div>
                                @else
                                    <div class="rank-badge rank-normal">
                                        <span>{{ $kelompok['rank'] }}</span>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle me-3">
                                        {{ strtoupper(substr($kelompok['nama_kelompok'], 0, 1)) }}
                                    </div>
                                    <strong>{{ $kelompok['nama_kelompok'] }}</strong>
                                </div>
                            </td>
                            <td>
                                <small class="text-muted">{{ Str::limit($kelompok['judul_proyek'], 40) }}</small>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-primary-subtle text-primary px-3 py-2">{{ $kelompok['kelas'] }}</span>
                            </td>
                            <td>{{ $kelompok['ketua'] }}</td>
                            <td class="text-center">
                                <span class="badge bg-secondary">{{ $kelompok['jumlah_anggota'] }}</span>
                            </td>
                            @if(auth()->user()->role !== 'mahasiswa')
                            <td class="text-center"><span class="score-cell">{{ $kelompok['milestone'] }}</span></td>
                            <td class="text-center"><span class="score-cell">{{ $kelompok['rata_anggota'] }}</span></td>
                            <td class="text-center"><span class="score-cell">{{ $kelompok['kontribusi'] }}</span></td>
                            <td class="text-center"><span class="score-cell">{{ $kelompok['penilaian_dosen'] }}</span></td>
                            @endif
                            <td class="text-center">
                                <div class="final-score {{ $kelompok['rank'] <= 3 ? 'top-score' : '' }}">
                                    {{ number_format($kelompok['saw_score'], 2) }}
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ auth()->user()->role !== 'mahasiswa' ? '11' : '7' }}" class="text-center py-5">
                                <div class="empty-state">
                                    <i class="bi bi-inbox"></i>
                                    <p>Belum ada data kelompok</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const weightInputs = document.querySelectorAll('.weight-input');
    const weightSum = document.getElementById('weight-sum');
    const weightTotal = document.getElementById('weight-total');
    
    function updateSum() {
        let total = 0;
        weightInputs.forEach(input => {
            total += parseInt(input.value) || 0;
        });
        if (weightSum) weightSum.textContent = total;
        
        if (weightTotal) {
            if (total === 100) {
                weightTotal.className = 'alert alert-success';
            } else {
                weightTotal.className = 'alert alert-danger';
            }
        }
    }
    
    weightInputs.forEach(input => {
        input.addEventListener('input', updateSum);
    });
    
    updateSum();
});
</script>

<style>
/* Ranking Card */
.ranking-card { border-radius: 16px; overflow: hidden; }
.ranking-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }

/* Ranking Table */
.ranking-table { border-collapse: separate; border-spacing: 0; }
.ranking-table thead th {
    background: linear-gradient(180deg, #f8f9fa 0%, #e9ecef 100%);
    border-bottom: 2px solid #dee2e6;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.5px;
    color: #495057;
    padding: 16px 12px;
}
.ranking-table tbody td { padding: 16px 12px; vertical-align: middle; border-bottom: 1px solid #f1f3f4; }
.ranking-row { transition: all 0.2s ease; }
.ranking-row:hover { background-color: #f8f9ff !important; transform: scale(1.005); box-shadow: 0 4px 12px rgba(0,0,0,0.08); }

/* Top 3 Ranks */
.rank-1 { background: linear-gradient(90deg, rgba(255,215,0,0.08) 0%, transparent 100%); }
.rank-2 { background: linear-gradient(90deg, rgba(192,192,192,0.08) 0%, transparent 100%); }
.rank-3 { background: linear-gradient(90deg, rgba(205,127,50,0.08) 0%, transparent 100%); }

/* Rank Badges */
.rank-badge { width: 48px; height: 48px; border-radius: 12px; display: inline-flex; flex-direction: column; align-items: center; justify-content: center; font-weight: 700; margin: 0 auto; }
.rank-badge i { font-size: 1rem; }
.rank-badge span { font-size: 0.85rem; margin-top: 2px; }
.rank-gold { background: linear-gradient(135deg, #ffd700 0%, #ffb347 100%); color: #5d4e00; box-shadow: 0 4px 12px rgba(255,215,0,0.4); }
.rank-silver { background: linear-gradient(135deg, #c0c0c0 0%, #a8a8a8 100%); color: #4a4a4a; box-shadow: 0 4px 12px rgba(192,192,192,0.4); }
.rank-bronze { background: linear-gradient(135deg, #cd7f32 0%, #b8860b 100%); color: white; box-shadow: 0 4px 12px rgba(205,127,50,0.4); }
.rank-normal { background: #f1f3f4; color: #5f6368; }

/* Avatar Circle */
.avatar-circle { width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 1rem; }

/* Score */
.score-cell { display: inline-block; padding: 4px 8px; background: #f8f9fa; border-radius: 6px; font-weight: 500; font-size: 0.85rem; }
.score-header { background: linear-gradient(135deg, #28a745 0%, #20c997 100%) !important; color: white !important; }
.final-score { display: inline-block; padding: 8px 16px; background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white; border-radius: 8px; font-weight: 700; font-size: 1rem; box-shadow: 0 2px 8px rgba(40,167,69,0.3); }
.top-score { animation: pulse 2s infinite; }
@keyframes pulse { 0%, 100% { transform: scale(1); } 50% { transform: scale(1.05); } }

/* Empty State */
.empty-state { color: #adb5bd; }
.empty-state i { font-size: 4rem; display: block; margin-bottom: 1rem; }
.empty-state p { font-size: 1.1rem; margin: 0; }
.bg-primary-subtle { background-color: rgba(102, 126, 234, 0.15) !important; }
</style>
@endsection
