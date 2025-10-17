@extends('layouts.app')

@section('title', 'Penilaian Teman Sejawat')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-indigo-50 via-blue-50 to-purple-100 py-12 px-4 sm:px-8">
    <div class="max-w-5xl mx-auto">
        {{-- Header --}}
        <div class="text-center mb-14">
            <h1 class="text-5xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-700 via-indigo-600 to-purple-600 mb-3 flex justify-center items-center gap-3">
                👥 Penilaian Teman Sejawat
            </h1>
            <p class="text-gray-600 text-lg">Beri penilaian jujur untuk setiap anggota kelompokmu ✨</p>
        </div>

        {{-- Notifikasi --}}
        @if (session('success'))
            <div class="mb-8 p-4 bg-green-100 border border-green-300 text-green-800 rounded-xl text-center font-semibold shadow">
                ✅ {{ session('success') }}
            </div>
        @endif

        {{-- Jika tidak ada teman --}}
        @if ($temanKelompok->isEmpty())
            <div class="p-8 text-center bg-yellow-50 text-yellow-800 rounded-2xl shadow-md border border-yellow-200">
                Kamu belum memiliki teman dalam kelompok ini.
            </div>
        @else
            <form action="{{ route('penilaian.sejawat.store') }}" method="POST" class="space-y-10">
                @csrf

                @foreach ($temanKelompok as $index => $teman)
                {{-- CARD PER ANGGOTA --}}
                <div class="relative bg-white/90 backdrop-blur-md border border-gray-200 rounded-3xl shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-300">
                    {{-- Header Gradient --}}
                    <div class="h-32 bg-gradient-to-r from-indigo-500 via-blue-500 to-purple-500"></div>

                    {{-- Avatar --}}
                    <div class="absolute -top-12 left-1/2 transform -translate-x-1/2">
                        <div class="w-24 h-24 rounded-full border-4 border-white shadow-xl bg-gradient-to-br from-indigo-400 to-sky-400 flex items-center justify-center text-white text-2xl font-bold">
                            {{ strtoupper(substr($teman->name, 0, 2)) }}
                        </div>
                    </div>

                    {{-- Body --}}
                    <div class="pt-16 pb-8 px-6 sm:px-10 text-center">
                        <h2 class="text-2xl font-bold text-gray-800 mb-1">{{ $teman->name }}</h2>
                        <p class="text-sm text-gray-500 mb-4">Kelas: {{ $teman->kelas }} • Kelompok: {{ $teman->role_kelompok }}</p>

                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-5 shadow-inner">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">💯 Nilai (1–100)</label>
                            <input
                                type="number"
                                name="penilaian[{{ $teman->id }}]"
                                min="1"
                                max="100"
                                required
                                class="w-full py-3 text-center text-gray-800 font-semibold rounded-xl border border-blue-200 focus:ring-4 focus:ring-blue-300 focus:outline-none placeholder-gray-400 transition"
                                placeholder="Masukkan nilai..."
                            >
                        </div>

                        <div class="mt-6 flex justify-center">
                            <button
                                type="submit"
                                class="relative overflow-hidden px-8 py-3 bg-gradient-to-r from-indigo-600 via-blue-600 to-purple-600 text-white font-semibold rounded-full shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 group">
                                <span class="absolute inset-0 bg-gradient-to-r from-purple-400 via-indigo-400 to-blue-400 opacity-0 group-hover:opacity-100 blur-xl transition"></span>
                                <span class="relative flex items-center gap-2">💾 Simpan Penilaian</span>
                            </button>
                        </div>
                    </div>

                    {{-- Nomor urut --}}
                    <span class="absolute top-3 left-4 text-sm text-white bg-indigo-500 rounded-full px-3 py-1 shadow">
                        Anggota #{{ $index + 1 }}
                    </span>
                </div>
                @endforeach
            </form>
        @endif
    </div>
</div>
@endsection
