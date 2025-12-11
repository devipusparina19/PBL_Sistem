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
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-dark text-center">
                        <tr>
                            <th class="py-3">No</th>
                            <th class="py-3">Nama</th>
                            <th class="py-3">NIM/NIP</th>
                            <th class="py-3">Kelompok</th>
                            <th class="py-3">Email</th>
                            <th class="py-3">Role</th>
                            <th class="py-3">Tanggal Dibuat</th>
                            <th class="py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $index => $user)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td class="fw-bold">{{ $user->name }}</td>
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
                                <td>{{ $user->email }}</td>
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
                                <td class="text-center small">{{ $user->created_at->format('d M Y') }}</td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1">
                                        <a href="{{ url('/akun/edit/'.$user->id) }}" class="btn btn-sm btn-outline-warning">Edit</a>
                                        <form action="{{ url('/akun/delete/'.$user->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus akun ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
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
