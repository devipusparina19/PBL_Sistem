@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Milestone / Logbook</h2>
    <a href="{{ route('milestones.create') }}" class="btn btn-primary mb-3">Isi Logbook</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
    <thead>
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
        @foreach($milestones as $milestone)
            <tr>
                <td>{{ $milestone->tanggal }}</td>
                <td>{{ $milestone->judul }}</td>
                <td>{{ $milestone->kelompok }}</td>
                <td>{{ $milestone->rincian }}</td>
                <td>
        @if($milestone->foto)
            <img src="{{ asset('storage/'.$milestone->foto) }}" alt="Foto" width="120">
        @else
        -
        @endif
            </td>
                <td>
                    <form action="{{ route('milestones.destroy', $milestone->id) }}" method="POST" onsubmit="return confirm('Yakin mau hapus data ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
