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

    <!-- Filter & Button (Khusus Dosen) -->
    @if(Auth::user()->role === 'dosen')
        <div class="card shadow border-0 rounded-4 mb-4">
            <div class="card-body px-5 py-4">
                <div class="row align-items-end">
                    <div class="col-md-8">
                        <label for="mahasiswa_id" class="form-label fw-semibold">Pilih Mahasiswa untuk Melihat Detail Nilai</label>
                        <form method="GET" action="{{ route('nilai.index') }}" id="filterForm">
                            <select name="mahasiswa_id" id="mahasiswa_id" class="form-select" onchange="this.form.submit()">
                                <option value="">-- Pilih Mahasiswa --</option>
                                @foreach($mahasiswas as $mhs)
                                    <option value="{{ $mhs->id }}" {{ request('mahasiswa_id') == $mhs->id ? 'selected' : '' }}>
                                        {{ $mhs->nama }} ({{ $mhs->nim }})
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                    <div class="col-md-4 text-end">
                        <a href="{{ route('nilai.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i>Tambah Nilai Mahasiswa
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Kartu Identitas Mahasiswa (Muncul setelah pilih mahasiswa) -->
    @if($selectedMahasiswa)
        <div class="card shadow border-0 rounded-4 mb-4">
            <div class="card-body px-5 py-4">
                <h5 class="fw-bold text-dark mb-3">Identitas Mahasiswa</h5>
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <p class="mb-1"><strong>Nama:</strong> {{ $selectedMahasiswa->nama }}</p>
                        <p class="mb-1"><strong>NIM:</strong> {{ $selectedMahasiswa->nim }}</p>
                    </div>
                    <div class="col-md-6 mb-2">
                        <p class="mb-1"><strong>Kelas:</strong> {{ $selectedMahasiswa->kelas ?? '-' }}</p>
                        <p class="mb-1"><strong>Prodi:</strong> Teknologi Informasi</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Tabel Detail Nilai (Hanya muncul jika ada mahasiswa dipilih) -->
    @if($selectedMahasiswa)
        <div class="card shadow border-0 rounded-4 mb-4">
            <div class="card-body px-5 py-4">
                <h5 class="fw-bold text-dark mb-4">Detail Penilaian</h5>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle text-center">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Mata Kuliah</th>
                                <th>Nilai</th>
                                <th>Dosen</th>
                                @if(Auth::user()->role === 'dosen')
                                    <th>Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($nilai as $index => $n)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        {{ $n->mataKuliah->nama_mk ?? '-' }}
                                        @if($n->mataKuliah && stripos($n->mataKuliah->nama_mk, 'pengambilan keputusan') !== false)
                                            <br>
                                            <small class="text-muted">
                                                <i class="bi bi-info-circle"></i> 
                                                Keaktifan: {{ $n->presentasi }} | Kerja: {{ $n->kontribusi }} | 
                                                Penyajian: {{ $n->laporan }} | Proyek: {{ $n->hasil_proyek }}
                                            </small>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $nilaiAkhir = $n->laporan; // default untuk mata kuliah standar
                                            
                                            // Jika Pengambilan Keputusan, hitung nilai akhir
                                            if($n->mataKuliah && stripos($n->mataKuliah->nama_mk, 'pengambilan keputusan') !== false) {
                                                $nilaiAkhir = ($n->presentasi * 0.2) + ($n->kontribusi * 0.3) + 
                                                             ($n->laporan * 0.2) + ($n->hasil_proyek * 0.3);
                                            }
                                        @endphp
                                        <span class="badge 
                                            @if($nilaiAkhir >= 85) bg-success
                                            @elseif($nilaiAkhir >= 75) bg-primary
                                            @elseif($nilaiAkhir >= 65) bg-warning
                                            @else bg-danger
                                            @endif">
                                            {{ number_format($nilaiAkhir, 2) }}
                                        </span>
                                    </td>
                                    <td>{{ $n->dosen->nama ?? '-' }}</td>
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
                            @empty
                                <tr>
                                    <td colspan="{{ Auth::user()->role === 'dosen' ? 5 : 4 }}" class="text-center">Belum ada data nilai untuk mahasiswa ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @elseif(Auth::user()->role === 'dosen')
        <div class="alert alert-info text-center" role="alert">
            <i class="bi bi-info-circle-fill me-2"></i>
            Silakan pilih mahasiswa dari dropdown di atas untuk melihat detail penilaian.
        </div>
    @endif

</div>
@endsection
