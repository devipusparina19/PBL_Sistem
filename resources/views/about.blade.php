@extends('layouts.app')

@section('content')
<div class="container mt-4">

    <!-- Judul Halaman -->
    <div class="text-center mb-4">
        <h2 class="fw-bold text-primary">Tentang Kami</h2>
        <p class="text-muted">
            Sistem informasi untuk mendukung kegiatan PBL secara efektif
        </p>
    </div>

    <!-- Konten utama -->
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4">

                    <h4 class="fw-semibold text-secondary mb-3">Profil Singkat</h4>
                    <p>
                        Website ini adalah <span class="fw-semibold">Sistem Informasi PBL</span> 
                        yang bertujuan untuk mempermudah manajemen data mahasiswa, dosen, kelompok, 
                        serta milestones secara terstruktur dan efisien.
                    </p>

                    <h4 class="fw-semibold text-secondary mt-4 mb-3">Visi & Misi</h4>
                    <p>
                        <strong>Visi:</strong> Menjadi platform pembelajaran digital yang modern, 
                        efisien, dan mendukung kolaborasi akademik.  
                    </p>
                    <p>
                        <strong>Misi:</strong>
                    </p>
                    <ul>
                        <li>Menyediakan sistem informasi yang mudah diakses.</li>
                        <li>Mendukung komunikasi antar mahasiswa dan dosen.</li>
                        <li>Meningkatkan efektivitas kegiatan PBL.</li>
                    </ul>

                </div>
            </div>
        </div>
    </div>

</div>
@endsection
