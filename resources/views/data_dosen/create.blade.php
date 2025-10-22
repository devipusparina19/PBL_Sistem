@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Tambah Dosen</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('data_dosen.store') }}" method="POST">
        @csrf

        {{-- Nama --}}
        <div class="mb-3">
            <label for="nama" class="form-label">Nama Lengkap</label>
            <input type="text" class="form-control @error('nama') is-invalid @enderror"
                   id="nama" name="nama" value="{{ old('nama') }}" required>
            @error('nama')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- NIP --}}
        <div class="mb-3">
            <label for="nip" class="form-label">NIP</label>
            <input type="text" class="form-control @error('nip') is-invalid @enderror"
                   id="nip" name="nip" value="{{ old('nip') }}" required>
            @error('nip')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Email --}}
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror"
                   id="email" name="email" value="{{ old('email') }}" required>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- No Telepon --}}
        <div class="mb-3">
            <label for="no_telp" class="form-label">No. Telepon</label>
            <input type="text" class="form-control @error('no_telp') is-invalid @enderror"
                   id="no_telp" name="no_telp" value="{{ old('no_telp') }}">
            @error('no_telp')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Kelas --}}
        <div class="mb-3">
            <label for="kelas" class="form-label">Kelas</label>
            <select class="form-control @error('kelas') is-invalid @enderror"
                    id="kelas" name="kelas" required>
                <option value="">-- Pilih Kelas --</option>
                <option value="3A" {{ old('kelas', $kelasDefault ?? '3A') == '3A' ? 'selected' : '' }}>3A</option>
                <option value="3B" {{ old('kelas', $kelasDefault ?? '3A') == '3B' ? 'selected' : '' }}>3B</option>
                <option value="3C" {{ old('kelas', $kelasDefault ?? '3A') == '3C' ? 'selected' : '' }}>3C</option>
                <option value="3D" {{ old('kelas', $kelasDefault ?? '3A') == '3D' ? 'selected' : '' }}>3D</option>
                <option value="3E" {{ old('kelas', $kelasDefault ?? '3A') == '3E' ? 'selected' : '' }}>3E</option>
            </select>
            @error('kelas')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Mata Kuliah (Dynamic Input) --}}
        <div class="mb-3">
            <label class="form-label">Mata Kuliah</label>
            <div id="mataKuliahWrapper">
                <div class="input-group mb-2">
                    <input type="text" name="mata_kuliah[]" class="form-control" placeholder="Masukkan nama mata kuliah pertama" required>
                </div>
            </div>
            <button type="button" class="btn btn-sm btn-outline-primary" id="addMataKuliah">
                + Tambah Mata Kuliah
            </button>
            @error('mata_kuliah')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('data_dosen.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>

{{-- Script Dinamis Tambah/Hapus Mata Kuliah --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const wrapper = document.getElementById('mataKuliahWrapper');
    const addBtn = document.getElementById('addMataKuliah');

    addBtn.addEventListener('click', () => {
        const inputGroup = document.createElement('div');
        inputGroup.classList.add('input-group', 'mb-2');
        inputGroup.innerHTML = `
            <input type="text" name="mata_kuliah[]" class="form-control" placeholder="Masukkan nama mata kuliah" required>
            <button type="button" class="btn btn-danger remove-field">Hapus</button>
        `;
        wrapper.appendChild(inputGroup);
    });

    wrapper.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-field')) {
            e.target.closest('.input-group').remove();
        }
    });
});
</script>
@endsection