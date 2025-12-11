@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4">
            <h4 class="fw-bold text-primary mb-3">Edit Akun Pengguna</h4>
            <hr class="header-line mb-4">

            <form action="{{ route('akun.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Nama</label>
                    <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">NIM/NIP</label>
                    <input type="text" name="nim_nip" class="form-control" value="{{ $user->nim_nip }}" placeholder="Masukkan NIM atau NIP">
                    <small class="text-muted">Opsional - ID mahasiswa atau pegawai</small>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password (kosongkan jika tidak diubah)</label>
                    <input type="password" name="password" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Role</label>
                    <select name="role" id="roleSelect" class="form-select" required>
                        <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="dosen" {{ $user->role == 'dosen' ? 'selected' : '' }}>Dosen</option>
                        <option value="koordinator_pbl" {{ $user->role == 'koordinator_pbl' ? 'selected' : '' }}>Koordinator PBL</option>
                        <option value="koordinator_prodi" {{ $user->role == 'koordinator_prodi' ? 'selected' : '' }}>Koordinator Prodi</option>
                        <option value="mahasiswa" {{ $user->role == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                    </select>
                </div>

                <div class="mb-3" id="kelasField" style="display: {{ $user->role == 'mahasiswa' ? 'block' : 'none' }};">
                    <label class="form-label">Kelas</label>
                    <select name="kelas" class="form-select">
                        <option value="">-- Pilih Kelas --</option>
                        <option value="3A" {{ $user->kelas == '3A' ? 'selected' : '' }}>3A</option>
                        <option value="3B" {{ $user->kelas == '3B' ? 'selected' : '' }}>3B</option>
                        <option value="3C" {{ $user->kelas == '3C' ? 'selected' : '' }}>3C</option>
                        <option value="3D" {{ $user->kelas == '3D' ? 'selected' : '' }}>3D</option>
                        <option value="3E" {{ $user->kelas == '3E' ? 'selected' : '' }}>3E</option>
                    </select>
                    <small class="text-muted">Hanya untuk mahasiswa</small>
                </div>

                <div class="mb-3" id="kelompokField" style="display: {{ $user->role == 'mahasiswa' ? 'block' : 'none' }};">
                    <label class="form-label">Kelompok</label>
                    <select name="role_kelompok" class="form-select">
                        <option value="">-- Pilih Kelompok --</option>
                        <option value="1" {{ $user->role_kelompok == '1' ? 'selected' : '' }}>Kelompok 1</option>
                        <option value="2" {{ $user->role_kelompok == '2' ? 'selected' : '' }}>Kelompok 2</option>
                        <option value="3" {{ $user->role_kelompok == '3' ? 'selected' : '' }}>Kelompok 3</option>
                        <option value="4" {{ $user->role_kelompok == '4' ? 'selected' : '' }}>Kelompok 4</option>
                        <option value="5" {{ $user->role_kelompok == '5' ? 'selected' : '' }}>Kelompok 5</option>
                        <option value="6" {{ $user->role_kelompok == '6' ? 'selected' : '' }}>Kelompok 6</option>
                    </select>
                    <small class="text-muted">Opsional - kelompok PBL</small>
                </div>

                <div class="mb-3" id="roleKelompokField" style="display: {{ $user->role == 'mahasiswa' ? 'block' : 'none' }};">
                    <label class="form-label">Role di Kelompok</label>
                    <select name="role_di_kelompok" class="form-select">
                        <option value="">-- Pilih Role --</option>
                        <option value="ketua" {{ $user->role_di_kelompok == 'ketua' ? 'selected' : '' }}>Ketua</option>
                        <option value="anggota" {{ $user->role_di_kelompok == 'anggota' ? 'selected' : '' }}>Anggota</option>
                    </select>
                    <small class="text-muted">Opsional - posisi dalam kelompok</small>
                </div>

                <div class="text-end">
                    <a href="{{ route('akun.index') }}" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-primary">Perbarui</button>
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
