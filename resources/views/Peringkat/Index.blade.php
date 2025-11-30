@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Data Perangkingan Kelompok</h1>

    <a href="{{ route('perangkingan.generate') }}" class="btn btn-primary mb-3">Generate / Update Perangkingan</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Peringkat</th>
                <th>Nama Kelompok</th>
                <th>Nilai Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $p)
            <tr>
                <td>{{ $p->peringkat }}</td>
                <td>{{ $p->kelompok->nama_kelompok ?? 'Tidak Ada' }}</td>
                <td>{{ $p->nilai_total }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="text-center">Belum ada data perangkingan</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
