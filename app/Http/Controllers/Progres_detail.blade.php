@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-3">Detail Progres - {{ $kelompok->nama }}</h2>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5>Anggota Kelompok:</h5>
            <ul>
                @foreach($kelompok->mahasiswa as $m)
                    <li>{{ $m->nama }}</li>
                @endforeach
            </ul>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <h5>Daftar Milestone / Tahapan:</h5>
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Minggu ke-</th>
                        <th>Judul</th>
                        <th>Deskripsi</th>
                        <th>Status</th>
                        <th>Catatan Dosen</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kelompok->milestone as $m)
                        <tr>
                            <td>{{ $m->minggu_ke }}</td>
                            <td>{{ $m->judul }}</td>
                            <td>{{ $m->deskripsi }}</td>
                            <td>
                                @if($m->status == 'disetujui')
                                    <span class="badge bg-success">Disetujui</span>
                                @elseif($m->status == 'menunggu')
                                    <span class="badge bg-warning text-dark">Menunggu</span>
                                @else
                                    <span class="badge bg-danger">Ditolak</span>
                                @endif
                            </td>
                            <td>{{ $m->catatan_dosen ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Belum ada data progres</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
