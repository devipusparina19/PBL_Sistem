@extends('login.layout')

@section('content')
<div class="card shadow-lg border-0 rounded-4">
    <div class="card-body p-4">
        <h3 class="text-center mb-4">Login</h3>

        {{-- Pesan error --}}
        @if ($errors->any())
            <div class="alert alert-danger rounded-3">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('user.login') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold">Email</label>
                <input type="email" name="email" class="form-control rounded-3" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Password</label>
                <input type="password" name="password" class="form-control rounded-3" required>
            </div>

            <button type="submit" class="btn btn-success w-100 rounded-3">Login</button>
        </form>

        <p class="text-center mt-3 mb-0">
            Belum punya akun? <a href="{{ route('user.showRegister') }}">Daftar sekarang</a>
        </p>
    </div>
</div>
@endsection
