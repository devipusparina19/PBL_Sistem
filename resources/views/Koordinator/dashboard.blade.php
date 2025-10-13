@extends('layouts.app')

@section('title', 'Dashboard Kelompok PBL')

@section('content')
<div class="container mt-4">
    <h3 class="text-center mb-4 fw-bold text-primary">Dashboard Progres Kelompok PBL</h3>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped align-middle text-center mb-0">
                    <thead class="bg-dark text-white">
                        <tr>
                            <th>No</th>
                            <th>Nama Kelompok</th>
                            <th>Mahasiswa</th>
                            <th>Progres</th>
                            <th>Status</th>
                            <th>Terakhir Diperbarui</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($kelompoks as $index => $kelompok)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td class="fw-semibold">{{ $kelompok->nama_kelompok }}</td>
                            <td class="text-start">
                                @if($kelompok->mahasiswa && $kelompok->mahasiswa->count() > 0)
                                    <ul class="list-unstyled mb-0">
                                        @foreach($kelompok->mahasiswa as $mhs)
                                            <li>{{ $mhs->nama }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <span class="text-muted fst-italic">Belum ada anggota</span>
                                @endif
                            </td>
                            <td>
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar 
                                        @if($kelompok->progres >= 80) bg-success
                                        @elseif($kelompok->progres >= 50) bg-warning text-dark
                                        @else bg-danger
                                        @endif"
                                        role="progressbar"
                                        style="width: {{ $kelompok->progres }}%;">
                                        {{ $kelompok->progres }}%
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($kelompok->status == 'Selesai')
                                    <span class="badge bg-success px-3 py-2">Selesai</span>
                                @elseif($kelompok->status == 'Berjalan')
                                    <span class="badge bg-warning text-dark px-3 py-2">Berjalan</span>
                                @else
                                    <span class="badge bg-secondary px-3 py-2">Belum Mulai</span>
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($kelompok->updated_at)->format('d M Y') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-muted fst-italic py-3">Belum ada data progres kelompok.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
