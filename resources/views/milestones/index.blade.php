@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="fw-bold text-primary mb-4">Milestone / Logbook</h2>

    <a href="{{ route('milestones.create') }}" class="btn btn-primary mb-3">Isi Logbook</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered align-middle">
        <thead class="table-light">
            <tr>
                <th>Tanggal</th>
                <th>Judul</th>
                <th>Kelompok</th>
                <th>Rincian</th>
                <th>Foto</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($milestones as $milestone)
                <tr>
                    <td>{{ $milestone->tanggal }}</td>
                    <td>{{ $milestone->judul }}</td>
                    <td>{{ $milestone->kelompok }}</td>
                    <td>{{ $milestone->rincian }}</td>
                    <td>
                        @if($milestone->foto)
                            <img src="{{ asset('storage/' . $milestone->foto) }}" alt="Foto" width="120" class="rounded">
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        <form action="{{ route('milestones.destroy', $milestone->id) }}" method="POST" onsubmit="return confirm('Yakin mau hapus data ini?')" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">Belum ada data logbook</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
