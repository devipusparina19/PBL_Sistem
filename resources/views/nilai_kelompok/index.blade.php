@extends('layouts.app')

@section('content')
<div class="container py-5">

    <!-- Header -->
    <div class="text-center mb-5">
        <h2 class="fw-semibold text-primary mb-2">Nilai Kelompok PBL</h2>
        <p class="text-muted">Kelola nilai kelompok berdasarkan performa proyek dan kontribusi kelompok</p>
        <hr class="mx-auto mt-3" style="width: 80px; height: 3px; background-color: #0d6efd; border: none;">
    </div>

    <!-- Alert Success -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Button Tambah / Input Nilai Kelompok (Khusus Dosen) -->
    @if(Auth::user()->role === 'dosen')
        <div class="mb-4 d-flex gap-2">
            <a href="{{ route('nilai_kelompok.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Input Nilai Kelompok
            </a>
            <button class="btn btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#settingsCollapse">
                <i class="bi bi-gear me-1"></i>Pengaturan Bobot
            </button>
        </div>

        <!-- Pengaturan Bobot Collapsible -->
        <div class="collapse mb-4" id="settingsCollapse">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-primary text-white rounded-top-4">
                    <h5 class="mb-0"><i class="bi bi-sliders me-2"></i>Pengaturan Penilaian Kelompok</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('nilai_kelompok.updateSettings') }}" method="POST" id="settingsForm">
                        @csrf
                        <div class="row">
                            <!-- Bobot Milestone & Anggota -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Bobot Nilai Milestone (%)</label>
                                <input type="number" name="bobot_milestone" id="bobot_milestone" class="form-control bobot-input" 
                                       value="{{ $settings['bobot_milestone']->value ?? 50 }}" min="0" max="100">
                                <small class="text-muted">Persentase nilai dari milestone</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Bobot Nilai Rata-rata Anggota (%)</label>
                                <input type="number" name="bobot_nilai_anggota" id="bobot_anggota" class="form-control bobot-input" 
                                       value="{{ $settings['bobot_nilai_anggota']->value ?? 50 }}" min="0" max="100">
                                <small class="text-muted">Persentase nilai dari mahasiswa</small>
                            </div>
                        </div>
                        
                        <!-- Dynamic Warning/Info Alert -->
                        <div id="bobotAlert" class="alert alert-info py-2 mb-3">
                            <i class="bi bi-info-circle me-1"></i>
                            <span id="bobotAlertText">Total bobot harus = 100% (Milestone + Nilai Anggota)</span>
                            <span id="bobotTotal" class="fw-bold ms-2">100%</span>
                        </div>
                        
                        <!-- Minimum Milestone -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Minimum Milestone Disetujui</label>
                            <input type="number" name="minimum_milestone" class="form-control" 
                                   value="{{ $settings['minimum_milestone']->value ?? 1 }}" min="1">
                            <small class="text-muted">Jumlah minimum milestone yang harus disetujui sebelum nilai bisa dihitung</small>
                        </div>
                        
                        <!-- Bonus/Penalty -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold"><i class="bi bi-plus-circle text-success me-1"></i>Bonus per Minggu Lebih Cepat</label>
                                <div class="input-group">
                                    <input type="number" name="bonus_per_minggu" class="form-control" 
                                           value="{{ $settings['bonus_per_minggu']->value ?? 5 }}" min="0" max="20">
                                    <span class="input-group-text">poin</span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold"><i class="bi bi-dash-circle text-danger me-1"></i>Penalty per Minggu Terlambat</label>
                                <div class="input-group">
                                    <input type="number" name="penalty_per_minggu" class="form-control" 
                                           value="{{ $settings['penalty_per_minggu']->value ?? 5 }}" min="0" max="20">
                                    <span class="input-group-text">poin</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary" id="btnSaveSettings">
                                <i class="bi bi-check-circle me-1"></i>Simpan Pengaturan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const bobotMilestone = document.getElementById('bobot_milestone');
            const bobotAnggota = document.getElementById('bobot_anggota');
            const alertDiv = document.getElementById('bobotAlert');
            const alertText = document.getElementById('bobotAlertText');
            const totalSpan = document.getElementById('bobotTotal');
            const btnSave = document.getElementById('btnSaveSettings');
            
            function validateBobot() {
                const milestone = parseFloat(bobotMilestone.value) || 0;
                const anggota = parseFloat(bobotAnggota.value) || 0;
                const total = milestone + anggota;
                
                totalSpan.textContent = total + '%';
                
                if (total > 100) {
                    alertDiv.className = 'alert alert-danger py-2 mb-3';
                    alertText.innerHTML = '<i class="bi bi-exclamation-triangle-fill me-1"></i> Total bobot melebihi 100%! ';
                    btnSave.disabled = true;
                } else if (total < 100) {
                    alertDiv.className = 'alert alert-warning py-2 mb-3';
                    alertText.innerHTML = '<i class="bi bi-exclamation-triangle me-1"></i> Total bobot kurang dari 100%! ';
                    btnSave.disabled = false;
                } else {
                    alertDiv.className = 'alert alert-success py-2 mb-3';
                    alertText.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Total bobot sudah tepat ';
                    btnSave.disabled = false;
                }
            }
            
            bobotMilestone.addEventListener('input', validateBobot);
            bobotAnggota.addEventListener('input', validateBobot);
            validateBobot(); // Initial check
        });
        </script>
    @endif

    <!-- Tabel Nilai Kelompok -->
    <div class="card shadow border-0 rounded-4">
        <div class="card-body px-5 py-4">
            <h5 class="fw-bold text-dark mb-4">Daftar Nilai Kelompok</h5>
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle table-sm">
                    <thead class="table-light text-center align-middle">
                        <tr>
                            <th width="3%">No</th>
                            <th>Nama Kelompok</th>
                            <th>Judul Proyek</th>
                            <th width="6%">Anggota</th>
                            <th width="6%">Milestone</th>
                            <th width="7%">Nilai Milestone</th>
                            <th width="7%">Nilai Anggota</th>
                            <th width="8%">Hasil Akhir</th>
                            @if(Auth::user()->role === 'dosen')
                                <th width="10%">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kelompoks as $index => $kelompok)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td class="fw-semibold">{{ $kelompok->nama_kelompok }}</td>
                                <td class="small">{{ $kelompok->judul_proyek ?? '-' }}</td>
                                <td class="text-center small">{{ $kelompok->mahasiswa->count() }} orang</td>
                                <td class="text-center">
                                    <span class="badge bg-info">{{ $kelompok->milestone_approved_count ?? 0 }}</span>
                                </td>
                                <td class="text-center">
                                    {{ $kelompok->nilai_milestone_avg !== null ? number_format($kelompok->nilai_milestone_avg, 1) : '-' }}
                                </td>
                                <td class="text-center">
                                    {{ $kelompok->nilai_rata_anggota !== null ? number_format($kelompok->nilai_rata_anggota, 1) : '-' }}
                                </td>
                                <td class="text-center">
                                    @if($kelompok->hasil_akhir !== null)
                                        <span class="badge 
                                            @if($kelompok->hasil_akhir >= 85) bg-success
                                            @elseif($kelompok->hasil_akhir >= 75) bg-primary
                                            @elseif($kelompok->hasil_akhir >= 65) bg-warning
                                            @else bg-danger
                                            @endif">
                                            {{ number_format($kelompok->hasil_akhir, 2) }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">Belum</span>
                                    @endif
                                </td>
                                @if(Auth::user()->role === 'dosen')
                                    <td class="text-center">
                                        @if($kelompok->hasil_akhir !== null)
                                            <div class="d-flex gap-1 justify-content-center">
                                                <a href="{{ route('nilai_kelompok.edit', $kelompok->id_kelompok) }}" 
                                                   class="btn btn-warning btn-sm" title="Edit">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                                <form action="{{ route('nilai_kelompok.destroy', $kelompok->id_kelompok) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-danger btn-sm" 
                                                            onclick="return confirm('Yakin ingin menghapus nilai kelompok ini?')" title="Hapus">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <a href="{{ route('nilai_kelompok.create') }}?kelompok_id={{ $kelompok->id_kelompok }}" 
                                               class="btn btn-primary btn-sm btn-block w-100">
                                                <i class="bi bi-plus-circle me-1"></i> Input
                                            </a>
                                        @endif
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ Auth::user()->role === 'dosen' ? 9 : 8 }}" class="text-center text-muted">
                                    Belum ada data kelompok.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<style>
.table th {
    font-weight: 600;
    font-size: 0.9rem;
}

.table td {
    font-size: 0.9rem;
}

.badge {
    font-size: 0.95rem;
    padding: 0.4rem 0.7rem;
}

.btn-sm {
    padding: 0.35rem 0.7rem;
    font-size: 0.85rem;
}
</style>
@endsection
