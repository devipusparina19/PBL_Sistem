@extends('layouts.app')

@section('content')
<h1>Detail Kelompok</h1>

<p>ID: {{ $kelompok->id }}</p>
<p>Nama: {{ $kelompok->nama }}</p>

<a href="{{ route('kelompok.index') }}">Kembali</a>
@endsection
