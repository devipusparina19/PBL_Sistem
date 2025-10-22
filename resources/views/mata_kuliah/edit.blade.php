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

        {{-- NIP Dosen Pengampu (bisa 2 dosen) --}}
        <div class="mb-3">
            <label class="form-label">NIP Dosen Pengampu</label>
            <div id="nipDosenContainer">
                @php
                    $nipList = old('nip_dosen', explode(',', $mataKuliah->nip_dosen));
                @endphp

                @foreach ($nipList as $index => $nip)
                    <div class="input-group mb-2">
                        <input type="text"
                               name="nip_dosen[]"
                               class="form-control @error('nip_dosen.' . $index) is-invalid @enderror"
                               value="{{ $nip }}"
                               placeholder="Masukkan NIP Dosen {{ $index + 1 }}"
                               required>
                        @error('nip_dosen.' . $index)
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        @if ($index > 0)
                            <button type="button" class="btn btn-danger btn-remove-nip">Hapus</button>
                        @endif
                    </div>
                @endforeach
            </div>

            <button type="button" class="btn btn-sm btn-outline-success mt-2" id="btnTambahNip">
                <i class="bi bi-plus-circle"></i> Tambah Dosen
            </button>
        </div>

        <button type="submit" class="btn btn-primary">Perbarui</button>
        <a href="{{ route('mata_kuliah.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>

{{-- Script dinamis tambah/hapus dosen --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btnTambah = document.getElementById('btnTambahNip');
        const container = document.getElementById('nipDosenContainer');

        btnTambah.addEventListener('click', function() {
            if (container.querySelectorAll('input').length >= 2) {
                alert('Maksimal 2 dosen pengampu.');
                return;
            }

            const div = document.createElement('div');
            div.classList.add('input-group', 'mb-2');
            div.innerHTML = `
                <input type="text" name="nip_dosen[]" class="form-control" placeholder="Masukkan NIP Dosen 2" required>
                <button type="button" class="btn btn-danger btn-remove-nip">Hapus</button>
            `;
            container.appendChild(div);
        });

        container.addEventListener('click', function(e) {
            if (e.target.classList.contains('btn-remove-nip')) {
                e.target.parentElement.remove();
            }
        });
    });
</script>
@endsection
