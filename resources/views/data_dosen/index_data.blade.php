@extends('login.layout')

@section('content')
<div class="container-fluid mt-5">
    <div class="card shadow-lg border-0 rounded-3">
        <!-- Header -->
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">📚 Data Dosen</h4>
            <a href="{{ route('data_dosen.create') }}" class="btn btn-light btn-sm">
                ➕ Tambah Dosen
            </a>
        </div>

        <!-- Notifikasi -->
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <!-- Pencarian -->
            <form method="GET" action="{{ route('data_dosen.index') }}" class="mb-3 d-flex">
                <input type="text" name="search" class="form-control me-2" 
                       placeholder="🔍 Cari nama / NIP / email..."
                       value="{{ request('search') }}">
                <button type="submit" class="btn btn-outline-primary">Cari</button>
            </form>

            <!-- Tabel -->
            <div class="table-responsive" style="min-height: 500px;">
                <table class="table table-striped table-hover align-middle text-center w-100">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>NIP</th>
                            <th>Email</th>
                            <th>Mata Kuliah</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dosens as $index => $dosen)
                        <tr>
                            <td>{{ $loop->iteration + ($dosens->currentPage()-1) * $dosens->perPage() }}</td>
                            <td>{{ $dosen->nama }}</td>
                            <td>{{ $dosen->nip }}</td>
                            <td>{{ $dosen->email }}</td>
                            <td>{{ $dosen->mata_kuliah }}</td>
                            <td>
                                <a href="{{ route('data_dosen.show', $dosen->id) }}" class="btn btn-info btn-sm">Lihat</a>
                                <a href="{{ route('data_dosen.edit', $dosen->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('data_dosen.destroy', $dosen->id) }}" 
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('Yakin ingin menghapus dosen ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-muted">Tidak ada data dosen</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-end mt-3">
                {{ $dosens->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
