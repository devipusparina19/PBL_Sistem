@extends('layout')

@section('content')
<div class="card shadow-lg border-0 rounded-4">
    <div class="card-body text-center p-4">
        <h2 class="mb-3">Halo, {{ Auth::user()->name }} 👋</h2>
        <p class="text-muted">Email: {{ Auth::user()->email }}</p>
        
        <a href="{{ route('user.logout') }}" class="btn btn-danger mt-3">Logout</a>
    </div>
</div>
@endsection
