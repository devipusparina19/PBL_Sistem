@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Daftar Kelompok</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('kelompok.create') }}" class="btn btn-primary mb-3">+ Tambah Kelompok</a>

    <table class="table table-bordered">
        <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>Deskripsi</th>
            <th>Aksi</th>
        </tr>

        @foreach($kelompok as $item)
        <tr>
            <td>{{ $item->id }}</td>
            <td>{{ $item->nama }}</td>
            <td>{{ $item->deskripsi }}</td>
            <td>
                <a href="{{ route('kelompok.edit', $item->id) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('kelompok.destroy', $item->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>

    {{-- Pagination --}}
    {{ $kelompok->links() }}
</div>
@endsection
