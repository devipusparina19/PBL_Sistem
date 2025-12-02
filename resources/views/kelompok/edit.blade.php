@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Edit Kelompok</h1>

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

    @php
        // daftar id anggota lama untuk pre-select di multiple select
        $anggotaLama = old('mahasiswas', $kelompok->mahasiswa->pluck('id')->toArray());
        $ketuaLama = old('ketua_id', optional($kelompok->ketua)->id);
    @endphp

    <form action="{{ route('kelompok.update', $kelompok->id_kelompok) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Kode MK --}}
        <div class="mb-3">
            <label for="kode_mk" class="form-label">Kode MK</label>
            <input type="text"
                   class="form-control @error('kode_mk') is-invalid @enderror"
                   id="kode_mk"
                   name="kode_mk"
                   value="{{ old('kode_mk', $kelompok->kode_mk) }}"
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
                   value="{{ old('nama_kelompok', $kelompok->nama_kelompok) }}"
                   required>
            @error('nama_kelompok')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Kelas --}}
        <div class="mb-3">
            <label for="kelas" class="form-label">Kelas</label>
            <select class="form-select @error('kelas') is-invalid @enderror"
                    id="kelas"
                    name="kelas"
                    required>
                <option value="">-- Pilih Kelas --</option>
                @foreach (['3A','3B','3C','3D','3E'] as $kls)
                    <option value="{{ $kls }}"
                        {{ old('kelas', $kelompok->kelas) == $kls ? 'selected' : '' }}>
                        {{ $kls }}
                    </option>
                @endforeach
            </select>
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
                   value="{{ old('judul_proyek', $kelompok->judul_proyek) }}"
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
                        {{ in_array($mhs->id, $anggotaLama) ? 'selected' : '' }}>
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
                        {{ $ketuaLama == $mhs->id ? 'selected' : '' }}>
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
        <div class="d-flex justify-content-between">
            <a href="{{ route('kelompok.byKelas', $kelompok->kelas) }}" class="btn btn-secondary">
                Kembali
            </a>
            <button type="submit" class="btn btn-primary">
                Update
            </button>
        </div>
    </form>
</div>
@endsection
