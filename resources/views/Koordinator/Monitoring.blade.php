@extends('layouts.app')

@section('title', 'Monitoring Kelompok PBL')

@section('content')
<div class="container mt-4">
    <h3 class="text-center mb-4 fw-bold text-primary">Monitoring Progres Kelompok PBL</h3>

    <div class="card p-4 shadow-sm border-0">
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
                    @foreach($kelompok as $index => $k)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td class="fw-semibold">{{ $k->nama_kelompok }}</td>
                        <td class="text-start">
                            <ul class="mb-0">
                                @foreach($k->mahasiswa as $m)
                                    <li>{{ $m->nama }}</li>
                                @endforeach
                            </ul>
                        </td>
                        <td>
                            @if($k->milestone && count($k->milestone) > 0)
                                {{ round(($k->milestone->where('status', 'selesai')->count() / $k->milestone->count()) * 100, 1) }}%
                            @else
                                <span class="text-muted">Belum ada progres</span>
                            @endif
                        </td>
                        <td>
                            @if($k->milestone && $k->milestone->where('status', 'selesai')->count() == $k->milestone->count())
                                <span class="badge bg-success px-3 py-2">Selesai</span>
                            @else
                                <span class="badge bg-warning text-dark px-3 py-2">Berjalan</span>
                            @endif
                        </td>
                        <td>{{ $k->updated_at->format('d M Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
