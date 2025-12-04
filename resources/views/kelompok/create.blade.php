@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Tambah Kelompok</h1>

    {{-- Error Validation --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Terjadi kesalahan:</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('kelompok.store') }}" method="POST">
        @csrf

        {{-- Kode MK --}}
        <div class="mb-3">
            <label for="kode_mk" class="form-label">Kode MK</label>
            <input type="text"
                   class="form-control @error('kode_mk') is-invalid @enderror"
                   id="kode_mk"
                   name="kode_mk"
                   value="{{ old('kode_mk') }}"
                   required>
            @error('kode_mk')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Nama Kelompok --}}
        <div class="mb-3">
            <label for="nama_kelompok" class="form-label">Nama Kelompok</label>
            <input type="text"
                   class="form-control @error('nama_kelompok') is-invalid @enderror"
                   id="nama_kelompok"
                   name="nama_kelompok"
                   value="{{ old('nama_kelompok') }}"
                   required>
            @error('nama_kelompok')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Kelas --}}
        <div class="mb-3">
            <label for="kelas" class="form-label">Kelas</label>
            <input type="text"
                   class="form-control @error('kelas') is-invalid @enderror"
                   id="kelas"
                   name="kelas"
                   value="{{ old('kelas', $kelasDefault) }}"
                   required>
            @error('kelas')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Judul Proyek --}}
        <div class="mb-3">
            <label for="judul_proyek" class="form-label">Judul Proyek</label>
            <input type="text"
                   class="form-control @error('judul_proyek') is-invalid @enderror"
                   id="judul_proyek"
                   name="judul_proyek"
                   value="{{ old('judul_proyek') }}"
                   required>
            @error('judul_proyek')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Anggota Kelompok --}}
        <div class="mb-3">
            <label class="form-label">Anggota Kelompok (Minimal 4)</label>

            <select name="mahasiswas[]"
                    class="form-select @error('mahasiswas') is-invalid @enderror"
                    multiple
                    required
                    size="6">
                @foreach($mahasiswas as $mhs)
                    <option value="{{ $mhs->id }}"
                        {{ in_array($mhs->id, old('mahasiswas', [])) ? 'selected' : '' }}>
                        {{ $mhs->nim }} - {{ $mhs->nama }}
                    </option>
                @endforeach
            </select>

            <small class="text-muted">Tahan CTRL (Windows) atau CMD (macOS) untuk memilih banyak.</small>

            @error('mahasiswas')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Ketua Kelompok --}}
        <div class="mb-4">
            <label class="form-label">Ketua Kelompok</label>
            <select name="ketua_id"
                    class="form-select @error('ketua_id') is-invalid @enderror"
                    required>
                <option value="">-- Pilih Ketua --</option>
                @foreach($mahasiswas as $mhs)
                    <option value="{{ $mhs->id }}"
                        {{ old('ketua_id') == $mhs->id ? 'selected' : '' }}>
                        {{ $mhs->nim }} - {{ $mhs->nama }}
                    </option>
                @endforeach
            </select>

            @error('ketua_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror

            <small class="text-muted">Ketua harus salah satu dari anggota kelompok.</small>
        </div>

        {{-- Tombol --}}
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('kelompok.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
