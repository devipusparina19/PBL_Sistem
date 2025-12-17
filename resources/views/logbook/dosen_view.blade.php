@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('dosen.dashboard') }}" class="btn btn-outline-secondary me-3">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
        <h2 class="fw-bold text-primary mb-0">Progres Logbook Mahasiswa</h2>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 50px;">No</th>
                            <th>Tanggal</th>
                            <th>Minggu ke</th>
                            <th>Judul</th>
                            <th>Kelompok</th>
                            <th>Rincian</th>
                            <th class="text-center">Foto</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logbooks as $index => $logbook)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>{{ $logbook->tanggal }}</td>
                                <td>{{ $logbook->minggu_ke }}</td>
                                <td>{{ $logbook->judul }}</td>
                                <td>{{ $logbook->kelompok }}</td>
                                <td>{{ $logbook->rincian }}</td>
                                <td class="text-center">
                                    @if($logbook->foto)
                                        <img src="{{ asset('storage/' . $logbook->foto) }}" alt="Foto" width="120" class="rounded">
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="bi bi-journal-x" style="font-size: 2rem;"></i>
                                    <p class="mb-0 mt-2">Belum ada data logbook</p>
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
    .table thead th {
        background-color: #f8f9fa;
        font-weight: 600;
        color: #495057;
    }
    
    .table tbody tr:hover {
        background-color: #f8f9fa;
    }
    
    .card {
        transition: all 0.3s ease;
    }
</style>
@endsection
