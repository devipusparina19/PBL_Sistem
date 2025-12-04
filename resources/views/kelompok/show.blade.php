@extends('layouts.app')

@section('content')
<div class="container mt-5">

    {{-- Judul Halaman --}}
    <h1 class="mb-4 fw-bold">Dashboard Kelompok PBL</h1>
    <p class="text-muted mb-4">
        Selamat datang, <span class="fw-semibold">{{ strtoupper(auth()->user()->name) }}</span>
    </p>

    {{-- ============= ROW KARTU ATAS ============= --}}
    <div class="row g-4 mb-5">

        {{-- Kartu 1: Detail Kelompok --}}
        <div class="col-md-4">
            <div class="card feature-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-wrapper bg-primary-subtle me-3">
                            <i class="bi bi-people-fill text-primary"></i>
                        </div>
                        <div>
                            <h5 class="card-title mb-0 fw-semibold">Detail Kelompok</h5>
                            <small class="text-muted">Informasi kelompok & anggota.</small>
                        </div>
                    </div>

                    <ul class="list-unstyled mb-4 small text-muted">
                        <li><strong>Nama:</strong> {{ $kelompok->nama_kelompok }}</li>
                        <li><strong>Kelas:</strong> {{ $kelompok->kelas ?? '-' }}</li>
                        <li><strong>Judul Proyek:</strong> {{ $kelompok->judul_proyek }}</li>
                    </ul>

                    <a href="#detail-kelompok" class="btn btn-outline-primary w-100">
                        <i class="bi bi-eye"></i> Lihat Detail
                    </a>
                </div>
            </div>
        </div>

        {{-- Kartu 2: Input Milestone (semua anggota kelompok bisa lihat) --}}
        <div class="col-md-4">
            <div class="card feature-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-wrapper bg-info-subtle me-3">
                            <i class="bi bi-flag-fill text-info"></i>
                        </div>
                        <div>
                            <h5 class="card-title mb-0 fw-semibold">Input Milestone</h5>
                            <small class="text-muted">Lihat dan kelola progres mingguan.</small>
                        </div>
                    </div>

                    <p class="small text-muted mb-4">
                        Ketua kelompok dapat menambahkan milestone, anggota dapat melihat perkembangan proyek.
                    </p>

                    <a href="{{ route('milestone.view') }}" class="btn btn-outline-info w-100">
                        <i class="bi bi-journal-text"></i> Lihat Milestone
                    </a>
                </div>
            </div>
        </div>

        {{-- Kartu 3: Sinkron Kelompok (khusus mahasiswa) --}}
        <div class="col-md-4">
            <div class="card feature-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-wrapper bg-success-subtle me-3">
                            <i class="bi bi-arrow-repeat text-success"></i>
                        </div>
                        <div>
                            <h5 class="card-title mb-0 fw-semibold">Sinkron Kelompok</h5>
                            <small class="text-muted">Samakan data akun & tabel mahasiswa.</small>
                        </div>
                    </div>

                    <p class="small text-muted mb-4">
                        Gunakan tombol ini jika data kelompok Anda belum sesuai dengan kelas atau peran di sistem.
                    </p>

                    @if(auth()->user()->role === 'mahasiswa')
                        <a href="{{ route('kelompok.sinkron') }}" class="btn btn-success w-100">
                            <i class="bi bi-arrow-clockwise"></i> Sinkron Kelompok
                        </a>
                    @else
                        <button class="btn btn-secondary w-100" disabled>
                            <i class="bi bi-lock-fill"></i> Hanya untuk mahasiswa
                        </button>
                    @endif
                </div>
            </div>
        </div>

    </div>
    {{-- ============= END ROW KARTU ATAS ============= --}}

    {{-- ================== DETAIL LENGKAP (SEPERTI SEBELUMNYA) ================== --}}
    <div id="detail-kelompok"></div>

    {{-- Info Umum --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-body">

            <h5 class="fw-bold mb-3">Informasi Kelompok</h5>

            <div class="mb-3">
                <label class="form-label fw-semibold">ID Kelompok</label>
                <input type="text" class="form-control" value="{{ $kelompok->id_kelompok }}" readonly>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Kode MK</label>
                <input type="text" class="form-control" value="{{ $kelompok->kode_mk }}" readonly>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Nama Kelompok</label>
                <input type="text" class="form-control" value="{{ $kelompok->nama_kelompok }}" readonly>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Kelas</label>
                <input type="text" class="form-control" value="{{ $kelompok->kelas ?? '-' }}" readonly>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Judul Proyek</label>
                <input type="text" class="form-control" value="{{ $kelompok->judul_proyek }}" readonly>
            </div>

        </div>
    </div>

    {{-- Ketua Kelompok --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-body">

            <h5 class="mb-3 fw-bold"><i class="bi bi-person-badge"></i> Ketua Kelompok</h5>

            @if($kelompok->ketua)
                <div class="alert alert-primary mb-0">
                    <strong>{{ $kelompok->ketua->nim }}</strong> — {{ $kelompok->ketua->nama }}
                </div>
            @else
                <p class="text-muted mb-0">Belum dipilih.</p>
            @endif

        </div>
    </div>

    {{-- Anggota Kelompok --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">

            <h5 class="mb-3 fw-bold"><i class="bi bi-people-fill"></i> Anggota Kelompok</h5>

            @if($kelompok->mahasiswa->count() > 0)

                {{-- Warning jika anggota kurang dari 4 --}}
                @if($kelompok->mahasiswa->count() < 4)
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        Anggota kelompok kurang dari <strong>4 orang</strong>. Harap lengkapi anggota.
                    </div>
                @endif

                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr class="text-center">
                            <th width="50">#</th>
                            <th>NIM</th>
                            <th>Nama</th>
                            <th width="150">Peran</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($kelompok->mahasiswa as $index => $mhs)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>{{ $mhs->nim }}</td>
                                <td>{{ $mhs->nama }}</td>
                                <td class="text-center">
                                    @if($kelompok->ketua && $kelompok->ketua->id == $mhs->id)
                                        <span class="badge bg-primary px-3 py-2">Ketua</span>
                                    @else
                                        <span class="badge bg-secondary px-3 py-2">Anggota</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <p class="text-muted mt-2 mb-0">
                    Total anggota: <strong>{{ $kelompok->mahasiswa->count() }}</strong>
                </p>

            @else
                <p class="text-muted mb-0">Belum ada anggota dalam kelompok ini.</p>
            @endif

        </div>
    </div>

    @php
        // Role yang tidak boleh kelola anggota
        $restrictedRoles = ['mahasiswa','dosen','koordinator_prodi','koordinator_pbl'];
        $isRestricted = in_array(auth()->user()->role, $restrictedRoles);
    @endphp

    {{-- Tombol bawah --}}
    <div class="d-flex gap-2 mb-5">

        {{-- Tombol kembali --}}
        <a href="{{ route('kelompok.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>

        {{-- Tombol Kelola Anggota (admin/role khusus) --}}
        @unless($isRestricted)
            <a href="{{ route('kelompok.anggota.manage', $kelompok->id_kelompok) }}"
               class="btn btn-primary">
                <i class="bi bi-people-fill"></i> Kelola Anggota
            </a>
        @endunless

        {{-- Tombol Sinkron di bawah (opsional, duplikat dari kartu atas) --}}
        @if(auth()->user()->role === 'mahasiswa')
            <a href="{{ route('kelompok.sinkron') }}" class="btn btn-success">
                <i class="bi bi-arrow-clockwise"></i> Sinkron Kelompok
            </a>
        @endif

    </div>

</div>

{{-- CSS Khusus kartu --}}
<style>
    .feature-card {
        border-radius: 16px;
        transition: all 0.2s ease;
    }
    .feature-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    }
    .icon-wrapper {
        width: 46px;
        height: 46px;
        border-radius: 999px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
    }
</style>
@endsection
