@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <!-- Card Container -->
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-5">

                    <!-- Header -->
                    <div class="text-center mb-4">
                        <h2 class="fw-bold text-primary">Input Milestone</h2>
                        <p class="text-muted mb-0">Kelompok <span class="fw-semibold">{{ $kelompok_id }}</span></p>
                        <hr class="mt-3 mb-0">
                    </div>

                    <!-- Alert -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Form -->
                    <form action="{{ route('milestone.store', $kelompok_id) }}" method="POST" class="mt-4">
                        @csrf

                        <!-- Judul -->
                        <div class="mb-4">
                            <label for="judul" class="form-label fw-semibold">Judul Milestone</label>
                            <input 
                                type="text" 
                                name="judul" 
                                id="judul" 
                                class="form-control form-control-lg rounded-3 shadow-sm" 
                                placeholder="Masukkan judul milestone" 
                                required>
                        </div>

                        <!-- Deskripsi -->
                        <div class="mb-4">
                            <label for="deskripsi" class="form-label fw-semibold">Deskripsi</label>
                            <textarea 
                                name="deskripsi" 
                                id="deskripsi" 
                                rows="4" 
                                class="form-control rounded-3 shadow-sm" 
                                placeholder="Jelaskan milestone secara singkat dan jelas" 
                                required></textarea>
                        </div>

                        <!-- Minggu Ke -->
                        <div class="mb-4">
                            <label for="minggu_ke" class="form-label fw-semibold">Minggu ke-</label>
                            <select 
                                name="minggu_ke" 
                                id="minggu_ke" 
                                class="form-select form-select-lg rounded-3 shadow-sm" 
                                required>
                                <option value="">-- Pilih Minggu --</option>
                                @for ($i = 1; $i <= 16; $i++)
                                    <option value="{{ $i }}">Minggu {{ $i }}</option>
                                @endfor
                            </select>
                        </div>

                        <!-- Submit -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg rounded-3 shadow-sm">
                                <i class="bi bi-send-fill me-2"></i> Kirim Milestone
                            </button>
                        </div>
                    </form>

                </div>
            </div>

            <!-- Back Button -->
            <div class="text-center mt-4">
                <a href="{{ url()->previous() }}" class="text-decoration-none text-secondary">
                    <i class="bi bi-arrow-left-circle me-1"></i> Kembali ke daftar
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
