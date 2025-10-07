@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="fw-bold text-primary mb-4">Logbook</h2>

    <a href="{{ route('logbook.create') }}" class="btn btn-primary mb-3">Isi Logbook</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered align-middle">
        <thead class="table-light">
            <tr>
                <th>Tanggal</th>
                <th>Minggu ke</th>
                <th>Judul</th>
                <th>Kelompok</th>
                <th>Rincian</th>
                <th>Foto</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logbooks as $logbook)
                <tr>
                    <td>{{ $logbook->tanggal }}</td>
                    <td>{{ $logbook->minggu_ke }}</td>
                    <td>{{ $logbook->judul }}</td>
                    <td>{{ $logbook->kelompok }}</td>
                    <td>{{ $logbook->rincian }}</td>
                    <td class="text-center">
                        @if($logbook->foto)
                            <img src="{{ asset('storage/' . $logbook->foto) }}" alt="Foto" width="120">
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        <form action="{{ route('logbook.destroy', $logbook->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin untuk menghapus data ini?')" class="d-inline">
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
