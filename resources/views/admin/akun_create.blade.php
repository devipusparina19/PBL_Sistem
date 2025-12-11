@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4">
            <h4 class="fw-bold text-primary mb-3">Tambah Akun Pengguna</h4>
            <hr class="header-line mb-4">

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('akun.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">NIM/NIP</label>
                        <input type="text" name="nim_nip" class="form-control @error('nim_nip') is-invalid @enderror" value="{{ old('nim_nip') }}" placeholder="Masukkan NIM atau NIP">
                        <small class="text-muted">Opsional - ID mahasiswa atau pegawai</small>
                        @error('nim_nip')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select name="role" id="roleSelect" class="form-select @error('role') is-invalid @enderror" required>
                            <option value="">-- Pilih Role --</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="dosen" {{ old('role') == 'dosen' ? 'selected' : '' }}>Dosen</option>
                            <option value="koordinator_pbl" {{ old('role') == 'koordinator_pbl' ? 'selected' : '' }}>Koordinator PBL</option>
                            <option value="koordinator_prodi" {{ old('role') == 'koordinator_prodi' ? 'selected' : '' }}>Koordinator Prodi</option>
                            <option value="mahasiswa" {{ old('role') == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                <div class="mb-3" id="kelasField" style="display: none;">
                    <label class="form-label">Kelas</label>
                    <select name="kelas" class="form-select">
                        <option value="">-- Pilih Kelas --</option>
                        <option value="3A">3A</option>
                        <option value="3B">3B</option>
                        <option value="3C">3C</option>
                        <option value="3D">3D</option>
                        <option value="3E">3E</option>
                    </select>
                    <small class="text-muted">Hanya untuk mahasiswa</small>
                </div>

                <div class="mb-3" id="kelompokField" style="display: none;">
                    <label class="form-label">Kelompok</label>
                    <select name="role_kelompok" class="form-select">
                        <option value="">-- Pilih Kelompok --</option>
                        <option value="1">Kelompok 1</option>
                        <option value="2">Kelompok 2</option>
                        <option value="3">Kelompok 3</option>
                        <option value="4">Kelompok 4</option>
                        <option value="5">Kelompok 5</option>
                        <option value="6">Kelompok 6</option>
                    </select>
                    <small class="text-muted">Opsional - kelompok PBL</small>
                </div>

                <div class="mb-3" id="roleKelompokField" style="display: none;">
                    <label class="form-label">Role di Kelompok</label>
                    <select name="role_di_kelompok" class="form-select">
                        <option value="">-- Pilih Role --</option>
                        <option value="ketua">Ketua</option>
                        <option value="anggota">Anggota</option>
                    </select>
                    <small class="text-muted">Opsional - posisi dalam kelompok</small>
                </div>

                <div class="text-end">
                    <a href="{{ route('akun.index') }}" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.header-line {
    width: 80px;
    height: 3px;
    background-color: #0d6efd;
    border-radius: 2px;
}
</style>

<script>
document.getElementById('roleSelect').addEventListener('change', function() {
    const kelasField = document.getElementById('kelasField');
    const kelompokField = document.getElementById('kelompokField');
    const roleKelompokField = document.getElementById('roleKelompokField');
    
    if (this.value === 'mahasiswa') {
        kelasField.style.display = 'block';
        kelompokField.style.display = 'block';
        roleKelompokField.style.display = 'block';
        kelasField.querySelector('select').required = true;
        // Kelompok and role_di_kelompok are optional
    } else {
        kelasField.style.display = 'none';
        kelompokField.style.display = 'none';
        roleKelompokField.style.display = 'none';
        kelasField.querySelector('select').required = false;
    }
});
</script>
@endsection
