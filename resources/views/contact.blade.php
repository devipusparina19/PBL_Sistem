@extends('layouts.app')

@section('content')
<div class="container mt-4">

    <!-- Judul Halaman -->
    <div class="text-center mb-4">
        <h2 class="fw-bold text-primary">Hubungi Kami</h2>
        <p class="text-muted">
            Sistem informasi untuk mendukung kegiatan PBL secara efektif
        </p>
    </div>

    <!-- Konten utama -->
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4">

                    <h4 class="fw-semibold text-secondary mb-3">Informasi Kontak</h4>
                    <p>Jika kamu memiliki pertanyaan, kritik, atau saran, kamu bisa menghubungi kami melalui informasi berikut:</p>

                    <ul class="list-unstyled text-secondary mt-3">
                        <li><strong>📍 Alamat:</strong> Jl. Pendidikan No. 45, Surabaya, Jawa Timur</li>
                        <li><strong>📧 Email:</strong> 
                            <a href="mailto:pblsupport@gmail.com" class="text-primary text-decoration-none">pblsupport@gmail.com</a>
                        </li>
                        <li><strong>📞 Telepon:</strong> 
                            <a href="tel:+6281234567890" class="text-primary text-decoration-none">+62 812-3456-7890</a>
                        </li>
                        <li><strong>💬 WhatsApp:</strong> 
                            <a href="https://wa.me/6281234567890" target="_blank" class="text-primary text-decoration-none">
                                Chat via WhatsApp
                            </a>
                        </li>
                    </ul>

                    <div class="text-center mt-4">
                        <a href="/" class="btn btn-primary px-4 py-2 rounded-3">
                            Kembali ke Beranda
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>
@endsection
