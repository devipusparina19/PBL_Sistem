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

        {{-- Field NIP Dosen --}}
        <div class="mb-3">
            <label for="nip" class="form-label">NIP Dosen</label>
            <input type="text"
                   id="nip"
                   name="nip"
                   class="form-control @error('nip') is-invalid @enderror"
                   value="{{ old('nip', $mataKuliah->nip) }}"
                   placeholder="Masukkan NIP dosen pengampu"
                   required>
            @error('nip')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Dropdown Semester --}}
        <div class="mb-3">
            <label for="semester" class="form-label">Semester</label>
            <select id="semester"
                    name="semester"
                    class="form-control @error('semester') is-invalid @enderror"
                    required>
                <option value="">-- Pilih Semester --</option>
                @for ($i = 1; $i <= 6; $i++)
                    <option value="{{ $i }}" {{ old('semester', $mataKuliah->semester) == $i ? 'selected' : '' }}>
                        Semester {{ $i }}
                    </option>
                @endfor
            </select>
            @error('semester')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Perbarui</button>
        <a href="{{ route('mata_kuliah.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
