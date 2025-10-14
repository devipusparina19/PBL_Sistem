@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4 text-primary fw-bold">Monitoring Keseluruhan</h2>
    <p class="text-muted">Pantau progres mahasiswa dan kelompok PBL secara real-time.</p>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead class="table-primary text-center">
                    <tr>
                        <th>No</th>
                        <th>Nama Mahasiswa</th>
                        <th>Kelompok</th>
                        <th>Status Progres</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mahasiswa as $index => $mhs)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ $mhs->nama }}</td>
                            <td>{{ $mhs->kelompok->nama ?? '-' }}</td>
                            <td class="text-center">
                                <span class="badge bg-info text-dark">
                                    {{ $mhs->status_progres ?? 'Belum ada data' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">Belum ada data mahasiswa</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
