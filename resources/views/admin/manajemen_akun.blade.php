@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <!-- Header -->
    <div class="text-center mb-5">
        <h2 class="fw-bold text-primary mb-2">Manajemen Akun Pengguna</h2>
        <p class="text-muted fs-5">Kelola data akun mahasiswa, dosen, dan koordinator</p>
        <hr class="header-line mx-auto">
    </div>

    {{-- Tombol Tambah Akun --}}
    <div class="d-flex justify-content-end mb-3">
        <a href="{{ url('/akun/create') }}" class="btn btn-primary">
            ➕ Tambah Akun
        </a>
    </div>

    {{-- Tabel Akun --}}
    <div class="card shadow border-0 rounded-4">
        <div class="card-body px-5 py-4">
            <h5 class="fw-bold text-dark mb-4">Daftar Akun Pengguna</h5>
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle table-sm">
                    <thead class="table-light text-center align-middle">
                        <tr>
                            <th width="5%">No</th>
                            <th class="text-start">Nama</th>
                            <th width="15%">NIM/NIP</th>
                            <th width="12%">Kelompok</th>
                            <th class="text-start">Email</th>
                            <th width="10%">Role</th>
                            <th width="12%">Tanggal Dibuat</th>
                            <th width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $index => $user)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td class="fw-semibold text-start">{{ ucwords(strtolower($user->name)) }}</td>
                                <td class="text-center">{{ $user->nim_nip ?? '-' }}</td>
                                <td class="text-center">
                                    @if($user->role == 'mahasiswa' && $user->mahasiswa && $user->mahasiswa->kelompok)
                                        <span class="badge bg-info text-dark">
                                            {{ $user->mahasiswa->kelompok->nama_kelompok }}
                                        </span>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                                <td class="text-start text-muted small">{{ $user->email }}</td>
                                <td class="text-center">
                                    <span class="badge rounded-pill
                                        @if($user->role == 'admin') bg-danger 
                                        @elseif($user->role == 'dosen') bg-success 
                                        @elseif($user->role == 'koordinator_pbl') bg-warning text-dark 
                                        @elseif($user->role == 'mahasiswa') bg-primary 
                                        @else bg-secondary @endif">
                                        {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                                    </span>
                                </td>
                                <td class="text-center small text-muted">{{ $user->created_at->format('d M Y') }}</td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1">
                                        <a href="{{ route('akun.edit', $user->id) }}" class="btn btn-warning btn-sm" title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form action="{{ route('akun.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus akun ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">Belum ada akun yang terdaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Style (identik dengan halaman Dosen) --}}
<style>
body {
    background-color: #f8f9fa;
    font-family: "Poppins", sans-serif;
}

.header-line {
    width: 80px;
    height: 3px;
    background-color: #0d6efd;
    border-radius: 2px;
    margin-top: 10px;
}

.table th {
    font-weight: 600;
    text-align: center;
}

.table td {
    vertical-align: middle;
    text-align: center;
}

.table-hover tbody tr:hover {
    background-color: #e9f1ff;
    transition: 0.2s;
}

.btn {
    font-size: 0.9rem;
    transition: all 0.2s ease;
}

.btn-warning {
    background-color: #ffc107;
    border: none;
}

.btn-warning:hover {
    background-color: #e0a800;
}

.btn-danger:hover {
    background-color: #c82333;
}

.card {
    border: none;
    border-radius: 8px;
}
</style>
@endsection
