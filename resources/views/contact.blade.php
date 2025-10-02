@extends('layouts.app')

@section('content')
<div class="container mt-4">

    <!-- Judul Halaman -->
    <div class="text-center mb-4">
        <h2 class="fw-bold text-primary">Kontak Kami</h2>
        <p class="text-muted">
            Silakan hubungi kami melalui informasi di bawah ini
        </p>
    </div>

    <!-- Konten utama -->
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4">

                    <h5 class="fw-semibold text-secondary mb-3">Alamat</h5>
                    <p>
                        Jl. Pendidikan No. 123, Banjarbaru, Kalimantan Selatan
                    </p>

                    <h5 class="fw-semibold text-secondary mt-4 mb-3">Email</h5>
                    <p>
                        <a href="mailto:support@example.com" class="fw-semibold text-decoration-none text-primary">
                            support@example.com
                        </a>
                    </p>

                    <h5 class="fw-semibold text-secondary mt-4 mb-3">Telepon</h5>
                    <p>
                        +62 812-3456-7890
                    </p>

                </div>
            </div>
        </div>
    </div>

</div>
@endsection