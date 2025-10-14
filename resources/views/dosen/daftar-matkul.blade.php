@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h3 class="fw-semibold text-primary mb-4 text-center">Daftar Mata Kuliah</h3>

    <div class="row justify-content-center">
        @foreach($matkul as $mk)
        <div class="col-md-5 col-lg-4 mb-4">
            <div class="card shadow border-0 rounded-4 text-center p-4">
                <h5 class="fw-semibold text-dark mb-2">{{ $mk->nama }}</h5>
                <p class="text-muted mb-3">{{ $mk->kode }}</p>
                <a href="{{ route('dosen.input.nilai', $mk->id) }}" class="btn btn-primary px-4">
                    Input Nilai
                </a>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
