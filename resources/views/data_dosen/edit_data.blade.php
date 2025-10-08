@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Edit Dosen</h1>

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

    <form action="{{ route('data_dosen.update', $dosen->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nama" class="form-label">Nama</label>
            <input type="text" id="nama" name="nama" class="form-control" 
                   value="{{ old('nama', $dosen->nama) }}" required>
        </div>

        <div class="mb-3">
            <label for="nip" class="form-label">NIP</label>
            <input type="text" id="nip" name="nip" class="form-control" 
                   value="{{ old('nip', $dosen->nip) }}" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" name="email" class="form-control" 
                   value="{{ old('email', $dosen->email) }}" required>
        </div>

        <div class="mb-3">
            <label for="no_telepon" class="form-label">No. Telepon</label>
            <input type="text" id="no_telepon" name="no_telepon" class="form-control" 
                   value="{{ old('no_telepon', $dosen->no_telepon) }}" required>
        </div>

        <div class="mb-3">
            <label for="kelas" class="form-label">Kelas</label>
            <select id="kelas" name="kelas" class="form-select" required>
                <option value="">-- Pilih Kelas --</option>
                @foreach (['TI 3A', 'TI 3B', 'TI 3C', 'TI 3D', 'TI 3E'] as $kelas)
                    <option value="{{ $kelas }}" {{ old('kelas', $dosen->kelas) == $kelas ? 'selected' : '' }}>
                        {{ $kelas }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="mata_kuliah" class="form-label">Mata Kuliah</label>
            <select id="mata_kuliah" name="mata_kuliah" class="form-select" required>
                <option value="">-- Pilih Mata Kuliah --</option>
                @foreach (['Integrasi Sistem', 'Pemweb Lanjut', 'TPK', 'IT Project'] as $matkul)
                    <option value="{{ $matkul }}" {{ old('mata_kuliah', $dosen->mata_kuliah) == $matkul ? 'selected' : '' }}>
                        {{ $matkul }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="d-flex gap-2 mt-4">
            <a href="{{ route('data_dosen.index') }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-primary">Update</button>
        </div>
    </form>
</div>
@endsection
