@extends('layouts.app')

@section('title', 'Rekap Penilaian Sejawat')

@section('content')
<div class="p-6">
    <h1 class="text-3xl font-bold text-blue-700 mb-6 text-center">📊 Rekap Penilaian Kelompok</h1>

    @if ($rekap->isEmpty())
        <div class="bg-yellow-50 text-yellow-700 p-6 text-center rounded-lg shadow">
            Belum ada penilaian yang masuk.
        </div>
    @else
        <div class="overflow-x-auto bg-white rounded-xl shadow-lg">
            <table class="min-w-full text-sm text-gray-700 border-collapse">
                <thead class="bg-blue-100">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold">Nama</th>
                        <th class="px-4 py-3 text-left font-semibold">Kelas</th>
                        <th class="px-4 py-3 text-center font-semibold">Kelompok</th>
                        <th class="px-4 py-3 text-center font-semibold">Rata-rata Nilai</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rekap as $r)
                        <tr class="border-b hover:bg-blue-50">
                            <td class="px-4 py-3">{{ $r->name }}</td>
                            <td class="px-4 py-3">{{ $r->kelas }}</td>
                            <td class="px-4 py-3 text-center">{{ $r->role_kelompok }}</td>
                            <td class="px-4 py-3 text-center font-bold text-blue-600">
                                {{ number_format($r->rata_nilai, 2) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
