@extends('layouts.app')

@section('content')

<div class="container py-5">

```
<!-- Header -->
<div class="text-center mb-5">
    <h2 class="fw-semibold text-primary mb-2">Input Nilai Mahasiswa</h2>
    <p class="text-muted fs-5">Form penilaian laporan, presentasi, dan kontribusi mahasiswa dalam proyek PBL</p>
    <hr class="mx-auto mt-3" style="width: 80px; height: 3px; background-color: #0d6efd; border: none;">
</div>

<!-- Form Input Nilai (hanya untuk dosen) -->
@if(Auth::user()->role === 'dosen')
<div class="card shadow border-0 rounded-4 mb-5">
    <div class="card-body px-5 py-4">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('nilai.store') }}" method="POST">
            @csrf

            <!-- Pilih Mahasiswa -->
            <div class="mb-4">
                <label class="form-label fw-semibold text-dark">Pilih Mahasiswa</label>
                <select name="mahasiswa_id" class="form-select form-select-lg rounded-3 shadow-sm" required>
                    <option value="" selected disabled>-- Pilih Mahasiswa --</option>
                    @foreach($mahasiswa as $m)
                        <option value="{{ $m->id }}">{{ $m->nama }} ({{ $m->nim }})</option>
                    @endforeach
                </select>
                @error('mahasiswa_id')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <!-- Komponen Nilai -->
            <div class="row g-4">
                <div class="col-md-4">
                    <label class="form-label fw-semibold text-dark">Laporan Akhir (30%)</label>
                    <input type="number" name="laporan" class="form-control form-control-lg rounded-3 shadow-sm" placeholder="0 - 100" min="0" max="100" required>
                    @error('laporan')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold text-dark">Presentasi (30%)</label>
                    <input type="number" name="presentasi" class="form-control form-control-lg rounded-3 shadow-sm" placeholder="0 - 100" min="0" max="100" required>
                    @error('presentasi')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold text-dark">Kontribusi (40%)</label>
                    <input type="number" name="kontribusi" class="form-control form-control-lg rounded-3 shadow-sm" placeholder="0 - 100" min="0" max="100" required>
                    @error('kontribusi')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <!-- Tombol Submit -->
            <div class="mt-5 text-center">
                <button type="submit" class="btn btn-primary btn-lg px-5 rounded-pill shadow-sm">
                    Simpan Nilai
                </button>
            </div>
        </form>
    </div>
</div>
@endif

<!-- Tabel Nilai -->
<div class="card shadow border-0 rounded-4">
    <div class="card-body px-5 py-4">
        <h4 class="fw-semibold mb-4 text-primary">Daftar Nilai</h4>

        <div class="table-responsive">
            <table class="table table-bordered align-middle text-center">
                <thead class="table-primary">
                    <tr>
                        <th>No</th>
                        <th>Nama Mahasiswa</th>
                        <th>Mata Kuliah</th>
                        <th>Nilai Akhir</th>
                        @if(Auth::user()->role === 'dosen')
                            <th>Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($nilai as $n)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $n->mahasiswa->nama }}</td>
                            <td>{{ $n->mata_kuliah }}</td>
                            <td>{{ $n->nilai }}</td>

                            @if(Auth::user()->role === 'dosen')
                            <td>
                                <a href="{{ route('nilai.edit', $n->id) }}" class="btn btn-warning btn-sm me-2">Edit</a>
                                <form action="{{ route('nilai.destroy', $n->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Yakin ingin menghapus nilai ini?')">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ Auth::user()->role === 'dosen' ? 5 : 4 }}" class="text-muted">Belum ada data nilai.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
```

</div>
@endsection
