@extends('login.layout')

@section('content')
<div class="container mt-4">
    <h2>Detail Dosen</h2>
    <ul class="list-group">
        <li class="list-group-item"><strong>Nama:</strong> {{ $dosen->nama }}</li>
        <li class="list-group-item"><strong>NIP:</strong> {{ $dosen->nip }}</li>
        <li class="list-group-item"><strong>Email:</strong> {{ $dosen->email }}</li>
        <li class="list-group-item"><strong>Mata Kuliah:</strong> {{ $dosen->mata_kuliah }}</li>
    </ul>
    <a href="{{ route('data_dosen.index') }}" class="btn btn-secondary mt-3">Kembali</a>
</div>
@endsection
