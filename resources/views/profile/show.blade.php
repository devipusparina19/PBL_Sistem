@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="fw-bold text-primary mb-4">Profil Saya</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        <div class="col-md-4 text-center">
            <img src="{{ $user->photo ? asset($user->photo) : asset('default-avatar.png') }}" 
                 class="rounded-circle mb-3" width="150" height="150" alt="Foto Profil">
        </div>
        <div class="col-md-8">
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label>Nama</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control">
                </div>
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control">
                </div>
                <div class="mb-3">
                    <label>Password Baru (opsional)</label>
                    <input type="password" name="password" class="form-control" placeholder="Password Baru">
                    <input type="password" name="password_confirmation" class="form-control mt-2" placeholder="Konfirmasi Password">
                </div>
                <div class="mb-3">
                    <label>Foto Profil</label>
                    <input type="file" name="photo" class="form-control">
                </div>
                <div class="mb-3">
                    <label>Role</label>
                    <input type="text" class="form-control" value="{{ $user->role }}" disabled>
                </div>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </form>
        </div>
    </div>
</div>
@endsection
