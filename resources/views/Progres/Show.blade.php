@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Detail Progres</h3>
    <p><strong>Judul:</strong> {{ $item->judul }}</p>
    <p><strong>Deskripsi:</strong> {{ $item->deskripsi }}</p>
</div>
@endsection
