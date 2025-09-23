@extends('layout')

@section('content')
<div class="card shadow-lg border-0 rounded-4">
    <div class="card-body p-4">
        <h3 class="text-center mb-4">Register</h3>

        {{-- Pesan error --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('user.register') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Nama</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Daftar</button>
        </form>

        <p class="text-center mt-3">
            Sudah punya akun? <a href="{{ route('user.showLogin') }}">Login di sini</a>
        </p>
    </div>
</div>
@endsection
