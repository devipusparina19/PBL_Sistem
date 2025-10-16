@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Edit Mata Kuliah</h1>

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

    <form action="{{ route('mata_kuliah.update', $mataKuliah->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Kode Mata Kuliah --}}
        <div class="mb-3">
            <label for="kode_mk" class="form-label">Kode MK</label>
            <input type="text"
                   id="kode_mk"
                   name="kode_mk"
                   class="form-control @error('kode_mk') is-invalid @enderror"
                   value="{{ old('kode_mk', $mataKuliah->kode_mk) }}"
                   required>
            @error('kode_mk')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Nama Mata Kuliah --}}
        <div class="mb-3">
            <label for="nama_mk" class="form-label">Nama Mata Kuliah</label>
            <input type="text"
                   id="nama_mk"
                   name="nama_mk"
                   class="form-control @error('nama_mk') is-invalid @enderror"
                   value="{{ old('nama_mk', $mataKuliah->nama_mk) }}"
                   required>
            @error('nama_mk')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Kelas --}}
        <div class="mb-3">
            <label for="kelas" class="form-label">Kelas</label>
            <select class="form-control @error('kelas') is-invalid @enderror"
                    id="kelas" name="kelas" required>
                <option value="">-- Pilih Kelas --</option>
                <option value="3A" {{ old('kelas', $mataKuliah->kelas) == '3A' ? 'selected' : '' }}>3A</option>
                <option value="3B" {{ old('kelas', $mataKuliah->kelas) == '3B' ? 'selected' : '' }}>3B</option>
                <option value="3C" {{ old('kelas', $mataKuliah->kelas) == '3C' ? 'selected' : '' }}>3C</option>
                <option value="3D" {{ old('kelas', $mataKuliah->kelas) == '3D' ? 'selected' : '' }}>3D</option>
                <option value="3E" {{ old('kelas', $mataKuliah->kelas) == '3E' ? 'selected' : '' }}>3E</option>
            </select>
            @error('kelas')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- NIP Dosen Pengampu --}}
        <div class="mb-3">
            <label for="nip_dosen" class="form-label">NIP Dosen Pengampu</label>
            <input type="text"
                   id="nip_dosen"
                   name="nip_dosen"
                   class="form-control @error('nip_dosen') is-invalid @enderror"
                   value="{{ old('nip_dosen', $mataKuliah->nip_dosen) }}"
                   placeholder="Masukkan NIP dosen pengampu"
                   required>
            @error('nip_dosen')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Perbarui</button>
        <a href="{{ route('mata_kuliah.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
