@extends('layouts.app')

@section('title', 'Edit Penilaian Sejawat')

@section('content')
<div class="p-6 max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold text-blue-700 mb-6">✏️ Edit Penilaian untuk {{ $teman->name }}</h1>

    <form action="{{ route('penilaian.sejawat.update', $teman->id) }}" method="POST" class="space-y-6 bg-white p-6 rounded-2xl shadow">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Nilai (1–100)</label>
            <input type="number" name="nilai" min="1" max="100" value="{{ old('nilai', $nilai->nilai ?? '') }}" required
                class="w-full p-3 border border-gray-300 rounded-lg focus:ring focus:ring-blue-200">
        </div>

        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
            💾 Simpan Perubahan
        </button>

        <a href="{{ route('penilaian.sejawat.index') }}" class="ml-3 text-gray-600 hover:underline">
            Batal
        </a>
    </form>
</div>
@endsection
