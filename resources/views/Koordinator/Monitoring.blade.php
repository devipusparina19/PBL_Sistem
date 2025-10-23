@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Monitoring Progres</h1>

    {{-- Pesan sukses --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Form Pencarian --}}
    <div class="row mb-3">
        <div class="col-md-8">
            <form action="{{ route('monitoring.index') }}" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control me-2"
                       placeholder="Cari nama kelompok atau mahasiswa..."
                       value="{{ request('search') }}">
                <button type="submit" class="btn btn-secondary">Cari</button>
                @if(request('search'))
                    <a href="{{ route('monitoring.index') }}" class="btn btn-outline-secondary ms-2">Reset</a>
                @endif
            </form>
        </div>
    </div>

    {{-- Tabel Monitoring --}}
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>No</th>
                            <th>Nama Kelompok</th>
                            <th>Anggota</th>
                            <th>Status Progres</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kelompok as $index => $k)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>{{ $k->nama }}</td>
                                <td>
                                    @foreach($k->mahasiswa as $m)
                                        {{ $m->nama }} <br>
                                    @endforeach
                                </td>
                                <td class="text-center">
                                    @if($k->milestones->isNotEmpty())
                                        @php
                                            $last = $k->milestones->last();
                                        @endphp
                                        @if($last->status == 'Selesai')
                                            <span class="badge bg-success">Selesai</span>
                                        @elseif($last->status == 'Proses')
                                            <span class="badge bg-warning text-dark">Proses</span>
                                        @else
                                            <span class="badge bg-secondary">Belum Ada Data</span>
                                        @endif
                                    @else
                                        <span class="badge bg-secondary">Belum Ada Data</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">Tidak ada data kelompok</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
