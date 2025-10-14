@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Daftar Progres</h3>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (Auth::user()->role == 'admin')
        <a href="{{ url('/progres/create') }}" class="btn btn-primary mb-3">Tambah Progres</a>
    @endif

    <table class="table table-bordered">
        <tr>
            <th>No</th>
            <th>Judul</th>
            <th>Deskripsi</th>
            @if (Auth::user()->role == 'admin')
                <th>Aksi</th>
            @endif
        </tr>
        @foreach ($progres as $p)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $p->judul }}</td>
                <td>{{ $p->deskripsi }}</td>
                @if (Auth::user()->role == 'admin')
                    <td>
                        <a href="{{ url('/progres/'.$p->id.'/edit') }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ url('/progres/'.$p->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('Hapus data ini?')">Hapus</button>
                        </form>
                    </td>
                @endif
            </tr>
        @endforeach
    </table>
</div>
@endsection
