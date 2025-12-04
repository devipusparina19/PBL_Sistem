@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4">
    <!-- Header -->
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('mata_kuliah.index') }}">Data Mata Kuliah</a></li>
                <li class="breadcrumb-item active">{{ $mataKuliah->nama_mk }}</li>
            </ol>
        </nav>
        <h1 class="mb-0">{{ $mataKuliah->nama_mk }}</h1>
        <p class="text-muted mt-2">Detail mata kuliah dan daftar mahasiswa</p>
    </div>

    <!-- Course Info Card -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-info-circle"></i> Informasi Mata Kuliah</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-2"><strong><i class="bi bi-hash"></i> Kode MK:</strong> {{ $mataKuliah->kode_mk }}</p>
                    <p class="mb-2"><strong><i class="bi bi-book"></i> Nama MK:</strong> {{ $mataKuliah->nama_mk }}</p>
                </div>
                <div class="col-md-6">
                    <p class="mb-2"><strong><i class="bi bi-door-open"></i> Kelas:</strong> <span class="badge bg-primary">{{ $mataKuliah->kelas }}</span></p>
                    @if($mataKuliah->nip_dosen)
                        <div class="mb-2">
                            <strong><i class="bi bi-person-badge"></i> Dosen Pengampu:</strong><br>
                            @foreach($mataKuliah->dosens as $dosen)
                                <div class="d-flex align-items-center mt-1">
                                    <span class="badge bg-info text-white me-2">{{ $dosen->nip }}</span>
                                    <span>{{ $dosen->nama }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Student List Card -->
    <div class="card shadow-sm">
        <div class="card-header bg-success text-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-people-fill"></i> Daftar Mahasiswa</h5>
                <span class="badge bg-light text-success">{{ $students->count() }} Mahasiswa</span>
            </div>
        </div>
        <div class="card-body">
            @if($students->count() > 0)
                <div class="row g-3">
                    @foreach($students as $student)
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="card h-100 student-card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle me-3">
                                            @if($student->photo)
                                                <img src="{{ asset('storage/' . $student->photo) }}" alt="{{ $student->name }}" class="rounded-circle" width="50" height="50">
                                            @else
                                                <div class="avatar-placeholder">
                                                    {{ strtoupper(substr($student->name, 0, 1)) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 fw-bold">{{ $student->name }}</h6>
                                            <p class="mb-0 text-muted small">
                                                <i class="bi bi-person-badge"></i> {{ $student->nim_nip ?? '-' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center text-muted py-5">
                    <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                    <p class="mt-3 mb-0">Belum ada mahasiswa terdaftar di kelas {{ $mataKuliah->kelas }}</p>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .student-card {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .student-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        border-color: #28a745;
    }

    .avatar-placeholder {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        font-weight: bold;
    }

    .breadcrumb {
        background: transparent;
        padding: 0;
    }

    .breadcrumb-item + .breadcrumb-item::before {
        content: "›";
    }
</style>
@endsection
