@extends('login.layout')

@section('content')
<div class="card shadow-lg border-0 rounded-4 mx-auto" style="max-width: 500px;">
    <div class="card-body text-center p-4">
        <h2 class="mb-3">Halo, {{ Auth::user()->name }} 👋</h2>
        <p class="text-muted">Email: {{ Auth::user()->email }}</p>
        
        <form action="{{ route('user.logout') }}" method="POST" class="mt-3">
            @csrf
            <button type="submit" class="btn btn-danger w-100">Logout</button>
        </form>
    </div>
</div>
@endsection
