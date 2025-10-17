@extends('layouts.app')

@section('title', 'Detail Penilaian Teman')

@section('content')
<div class="p-6 max-w-3xl mx-auto">
    <h1 class="text-2xl font-bold text-blue-700 mb-6">📋 Detail Penilaian</h1>

    <div class="bg-white rounded-2xl p-6 shadow-lg">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">
            {{ $teman->name }}
        </h2>
        <p class="text-gray-600 mb-4">Kelas: {{ $teman->kelas }} | Kelompok: {{ $teman->role_kelompok }}</p>

        <div class="border-t pt-4">
            <p class="text-gray-700">Nilai kamu: 
                <span class="font-bold text-blue-600 text-lg">
                    {{ $nilai ? $nilai->nilai : 'Belum ada' }}
                </span>
            </p>
        </div>
    </div>

    <a href="{{ route('penilaian.sejawat.index') }}" 
       class="inline-block mt-6 bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 transition">
       ⬅️ Kembali
    </a>
</div>
@endsection
