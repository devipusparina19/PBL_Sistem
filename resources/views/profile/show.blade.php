@extends('layouts.app')

@section('content')
<div class="container-fluid py-5">
    <div class="row justify-content-center">
        <div class="col-xl-11 col-lg-12">

            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-5">

                    <h2 class="fw-bold text-center text-primary mb-5">
                        <i class="bi bi-person-circle me-2"></i> Profil Saya
                    </h2>

                    @if(session('success'))
                        <div class="alert alert-success text-center rounded-3">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="row align-items-center">

                        <!-- FOTO PROFIL -->
                        <div class="col-lg-4 border-end">
                            <div class="d-flex flex-column align-items-center justify-content-center h-100">

                                <div class="position-relative mb-3">
                                    <img id="preview"
                                         src="{{ $user->photo ? asset($user->photo) : asset('default-avatar.png') }}"
                                         class="rounded-circle shadow border border-3 border-light"
                                         width="200" height="200"
                                         style="object-fit: cover;">

                                    <!-- upload -->
                                    <label for="photo"
                                           class="position-absolute bottom-0 end-0 bg-primary text-white p-2 rounded-circle shadow"
                                           style="cursor:pointer;">
                                        <i class="bi bi-camera-fill"></i>
                                    </label>

                                    <!-- hapus -->
                                    @if($user->photo)
                                    <form action="{{ route('profile.photo.delete') }}"
                                          method="POST"
                                          class="position-absolute top-0 end-0">
                                        @csrf
                                        <button type="submit"
                                                onclick="return confirm('Hapus foto profil?')"
                                                class="btn btn-danger btn-sm rounded-circle shadow">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>

                                <p class="fw-semibold text-secondary">
                                    Klik ikon kamera untuk ganti foto
                                </p>
                            </div>
                        </div>

                        <!-- FORM PROFIL -->
                        <div class="col-lg-8 ps-lg-5">
                            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <input type="file" id="photo" name="photo"
                                       class="form-control d-none"
                                       accept="image/*"
                                       onchange="previewImage(event)">

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">Nama Lengkap</label>
                                        <input type="text" name="name"
                                               value="{{ old('name', $user->name) }}"
                                               class="form-control form-control-lg shadow-sm" required>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">Alamat Email</label>
                                        <input type="email" name="email"
                                               value="{{ old('email', $user->email) }}"
                                               class="form-control form-control-lg shadow-sm" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">
                                            Password Baru <span class="text-muted">(opsional)</span>
                                        </label>
                                        <input type="password" name="password"
                                               class="form-control shadow-sm"
                                               placeholder="Password Baru">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">Konfirmasi Password</label>
                                        <input type="password" name="password_confirmation"
                                               class="form-control shadow-sm"
                                               placeholder="Konfirmasi Password">
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Role</label>
                                    <input type="text"
                                           class="form-control form-control-lg shadow-sm bg-light"
                                           value="{{ ucfirst($user->role) }}"
                                           disabled>
                                </div>

                                <div class="text-end">
                                    <button type="submit"
                                            class="btn btn-primary btn-lg px-5 rounded-3 shadow-sm">
                                        <i class="bi bi-save me-1"></i> Simpan Perubahan
                                    </button>
                                </div>

                            </form>
                        </div>

                    </div> <!-- row -->
                </div>
            </div>

        </div>
    </div>
</div>

<script>
function previewImage(event) {
    const input = event.target;
    const preview = document.getElementById('preview');

    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => preview.src = e.target.result;
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection