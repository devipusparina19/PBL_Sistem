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
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Tanggal Dibuat</th>
                            <th width="180">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $index => $user)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span class="badge 
                                        @if($user->role == 'admin') bg-danger 
                                        @elseif($user->role == 'dosen') bg-success 
                                        @elseif($user->role == 'koordinator') bg-warning text-dark 
                                        @else bg-primary @endif">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td>{{ $user->created_at->format('d M Y') }}</td>
                                <td>
                                    <a href="{{ url('/akun/edit/'.$user->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ url('/akun/delete/'.$user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus akun ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">Belum ada akun yang terdaftar.</td>
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
