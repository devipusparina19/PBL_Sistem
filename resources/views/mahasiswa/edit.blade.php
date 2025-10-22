@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Edit Mahasiswa</h1>

    {{-- Tampilkan error validasi --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('mahasiswa.update', $mahasiswa->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nim" class="form-label">NIM</label>
            <input type="text" class="form-control @error('nim') is-invalid @enderror"
                   id="nim" name="nim" value="{{ old('nim', $mahasiswa->nim) }}" required>
            @error('nim')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="nama" class="form-label">Nama</label>
            <input type="text" class="form-control @error('nama') is-invalid @enderror"
                   id="nama" name="nama" value="{{ old('nama', $mahasiswa->nama) }}" required>
            @error('nama')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="kelas" class="form-label">Kelas</label>
            <select class="form-control @error('kelas') is-invalid @enderror" id="kelas" name="kelas" required>
                <option value="">-- Pilih Kelas --</option>
                @foreach(['3A','3B','3C','3D','3E'] as $kelas)
                    <option value="{{ $kelas }}" {{ old('kelas', $mahasiswa->kelas) == $kelas ? 'selected' : '' }}>
                        {{ $kelas }}
                    </option>
                @endforeach
            </select>
            @error('kelas')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="angkatan" class="form-label">Angkatan</label>
            <input type="text" class="form-control @error('angkatan') is-invalid @enderror"
                   id="angkatan" name="angkatan" value="{{ old('angkatan', $mahasiswa->angkatan) }}" required>
            @error('angkatan')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror"
                   id="email" name="email" value="{{ old('email', $mahasiswa->email) }}" required>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Password opsional, kosongkan jika tidak ingin diubah --}}
        <div class="mb-3">
            <label for="password" class="form-label">Password (kosongkan jika tidak diganti)</label>
            <input type="password" class="form-control @error('password') is-invalid @enderror"
                   id="password" name="password">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('mahasiswa.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
