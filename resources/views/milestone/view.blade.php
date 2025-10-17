@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4 text-center">Daftar Milestone Kelompok</h1>

    {{-- Pesan sukses / warning --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(isset($warning))
        <div class="alert alert-warning">{{ $warning }}</div>
    @endif

    {{-- Tombol tambah milestone (hanya ketua) --}}
    @if($user->role_di_kelompok === 'ketua')
        <div class="text-center mb-4">
            <a href="{{ route('milestone.create') }}" class="btn btn-primary">
                Tambah Milestone
            </a>
        </div>
    @endif

    {{-- Grid Kartu Minggu --}}
    <div class="row g-4">
        @for($i = 1; $i <= 16; $i++)
            <div class="col-md-3 col-sm-6">
                <div class="card shadow-sm h-100 border-0 rounded-4 card-week">
                    <div class="card-header bg-soft-blue text-center fw-semibold">
                        Minggu {{ $i }}
                    </div>
                    <div class="card-body">
                        @php
                            $milestoneMinggu = $milestones->where('minggu_ke', $i);
                        @endphp

                        @if($milestoneMinggu->isEmpty())
                            <p class="text-muted small text-center">Belum ada milestone.</p>
                        @else
                            @foreach($milestoneMinggu as $m)
                                <div class="milestone-item mb-2 p-2 rounded-3 shadow-sm bg-white">
                                    <h6 class="fw-semibold">{{ $m->judul }}</h6>
                                    <p class="text-muted small mb-1">{{ Str::limit($m->deskripsi, 50) }}</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="badge 
                                            @if($m->status == 'disetujui') bg-success
                                            @elseif($m->status == 'menunggu') bg-warning text-dark
                                            @elseif($m->status == 'ditolak') bg-danger
                                            @else bg-secondary
                                            @endif">
                                            {{ ucfirst($m->status ?? 'Belum Divalidasi') }}
                                        </span>
                                        @if(auth()->id() == $m->user_id && $m->status != 'disetujui')
                                            <a href="{{ route('milestone.edit', $m->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                        @elseif(Auth::user()->role === 'dosen')
                                            <a href="{{ route('milestone.edit', $m->id) }}" class="btn btn-sm btn-success">Validasi</a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        @endfor
    </div>
</div>

{{-- CSS --}}
<style>
.card-week {
    transition: all 0.3s ease;
}
.card-week:hover {
    transform: translateY(-4px);
    box-shadow: 0 6px 18px rgba(0,0,0,0.1);
}

.bg-soft-blue { background: #71a4edff; }
.milestone-item:hover { 
    transform: scale(1.02);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    transition: 0.2s;
}
</style>
@endsection
