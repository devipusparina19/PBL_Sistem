@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Edit Dosen</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('data_dosen.update', $dosen->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nama" class="form-label">Nama Lengkap</label>
            <input type="text" class="form-control @error('nama') is-invalid @enderror"
                   id="nama" name="nama" value="{{ old('nama', $dosen->nama) }}" required>
            @error('nama')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="nip" class="form-label">NIP</label>
            <input type="text" class="form-control @error('nip') is-invalid @enderror"
                   id="nip" name="nip" value="{{ old('nip', $dosen->nip) }}" required>
            @error('nip')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror"
                   id="email" name="email" value="{{ old('email', $dosen->email) }}" required>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="no_telp" class="form-label">No. Telepon</label>
            <input type="text" class="form-control @error('no_telp') is-invalid @enderror"
                   id="no_telp" name="no_telp" value="{{ old('no_telp', $dosen->no_telp) }}">
            @error('no_telp')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="kelas" class="form-label">Kelas</label>
            <select class="form-control @error('kelas') is-invalid @enderror"
                    id="kelas" name="kelas" required>
                <option value="">-- Pilih Kelas --</option>
                <option value="3A" {{ old('kelas', $dosen->kelas) == '3A' ? 'selected' : '' }}>3A</option>
                <option value="3B" {{ old('kelas', $dosen->kelas) == '3B' ? 'selected' : '' }}>3B</option>
                <option value="3C" {{ old('kelas', $dosen->kelas) == '3C' ? 'selected' : '' }}>3C</option>
                <option value="3D" {{ old('kelas', $dosen->kelas) == '3D' ? 'selected' : '' }}>3D</option>
                <option value="3E" {{ old('kelas', $dosen->kelas) == '3E' ? 'selected' : '' }}>3E</option>
            </select>
            @error('kelas')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Bagian Mata Kuliah Dinamis --}}
        <div class="mb-3">
            <label class="form-label">Mata Kuliah</label>
            <div id="mataKuliahContainer">
                @php
                    $mataKuliahs = old('mata_kuliah', explode(',', $dosen->mata_kuliah ?? ''));
                @endphp
                @foreach ($mataKuliahs as $index => $mk)
                    <input type="text" name="mata_kuliah[]" class="form-control mb-2" 
                           placeholder="Masukkan nama mata kuliah"
                           value="{{ trim($mk) }}">
                @endforeach
            </div>
            <button type="button" class="btn btn-sm btn-success" id="addMataKuliah">+ Tambah Mata Kuliah</button>
            @error('mata_kuliah')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Perbarui</button>
        <a href="{{ route('data_dosen.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>

{{-- Script untuk tambah input dinamis --}}
<script>
    document.getElementById('addMataKuliah').addEventListener('click', function () {
        const container = document.getElementById('mataKuliahContainer');
        const input = document.createElement('input');
        input.type = 'text';
        input.name = 'mata_kuliah[]';
        input.className = 'form-control mb-2';
        input.placeholder = 'Masukkan nama mata kuliah';
        container.appendChild(input);
    });
</script>
@endsection
