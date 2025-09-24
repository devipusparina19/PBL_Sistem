@extends('login.layout')

@section('content')
<div class="container mt-4">
    <h2>Edit Dosen</h2>
    <form action="{{ route('dosen.update', $dosen->id) }}" method="POST">
        @csrf @method('PUT')
        <div class="mb-3">
            <label>Nama</label>
            <input type="text" name="nama" value="{{ $dosen->nama }}" class="form-control">
        </div>
        <div class="mb-3">
            <label>NIP</label>
            <input type="text" name="nip" value="{{ $dosen->nip }}" class="form-control">
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" value="{{ $dosen->email }}" class="form-control">
        </div>
        <div class="mb-3">
            <label>Mata Kuliah</label>
            <input type="text" name="mata_kuliah" value="{{ $dosen->mata_kuliah }}" class="form-control">
        </div>
        <button type="submit" class="btn btn-success">Update</button>
    </form>
</div>
@endsection
