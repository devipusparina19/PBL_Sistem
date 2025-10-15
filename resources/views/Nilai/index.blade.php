@extends('layouts.app')

@section('content')
<div class="container py-5">

    <!-- Header -->
    <div class="text-center mb-5">
        <h2 class="fw-semibold text-primary mb-2">Nilai Mahasiswa PBL</h2>
        <p class="text-muted fs-5">Berikut rekap penilaian Anda berdasarkan hasil proyek, kontribusi, dan penilaian sejawat</p>
        <hr class="mx-auto mt-3" style="width: 80px; height: 3px; background-color: #0d6efd; border: none;">
    </div>

    <!-- Alert Success -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Button Tambah Nilai (Khusus Dosen) -->
    @if(Auth::user()->role === 'dosen')
        <div class="mb-4">
            <a href="{{ route('nilai.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Tambah Nilai Mahasiswa
            </a>
        </div>
    @endif

    <!-- Kartu Identitas -->
    <div class="card shadow border-0 rounded-4 mb-4">
        <div class="card-body px-5 py-4">
            <h5 class="fw-bold text-dark mb-3">Identitas Mahasiswa</h5>
            <div class="row">
                <div class="col-md-6 mb-2">
                    <p class="mb-1"><strong>Nama:</strong> {{ Auth::user()->nama }}</p>
                    <p class="mb-1"><strong>NIM:</strong> {{ Auth::user()->nim }}</p>
                </div>
                <div class="col-md-6 mb-2">
                    <p class="mb-1"><strong>Kelas:</strong> {{ Auth::user()->kelas ?? '-' }}</p>
                    <p class="mb-1"><strong>Prodi:</strong> Teknologi Informasi</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Detail Nilai -->
    <div class="card shadow border-0 rounded-4 mb-4">
        <div class="card-body px-5 py-4">
            <h5 class="fw-bold text-dark mb-4">Detail Penilaian</h5>
            <div class="table-responsive">
                <table class="table table-bordered align-middle text-center">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Mahasiswa</th>
                            <th>Pemrograman Web</th>
                            <th>Integrasi Sistem</th>
                            <th>Pengambilan Keputusan</th>
                            <th>IT Proyek</th>
                            <th>Kontribusi Kelompok</th>
                            <th>Penilaian Sejawat</th>
                            <th>Hasil Akhir</th>
                            @if(Auth::user()->role === 'dosen')
                                <th>Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($nilai as $index => $n)
                            @if(Auth::user()->role === 'dosen' || Auth::user()->id === $n->mahasiswa->user_id)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $n->mahasiswa->nama ?? '-' }}</td>
                                    <td>{{ $n->pemrograman_web }}</td>
                                    <td>{{ $n->integrasi_sistem }}</td>
                                    <td>{{ $n->pengambilan_keputusan }}</td>
                                    <td>{{ $n->it_proyek }}</td>
                                    <td>{{ $n->kontribusi_kelompok }}</td>
                                    <td>{{ $n->penilaian_sejawat }}</td>
                                    <td>{{ $n->hasil_akhir }}</td>
                                    @if(Auth::user()->role === 'dosen')
                                        <td class="text-center">
                                            <a href="{{ route('nilai.edit', $n->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                            <form action="{{ route('nilai.destroy', $n->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin hapus nilai ini?')">Hapus</button>
                                            </form>
                                        </td>
                                    @endif
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="{{ Auth::user()->role === 'dosen' ? 10 : 9 }}" class="text-center">Belum ada data nilai.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection
