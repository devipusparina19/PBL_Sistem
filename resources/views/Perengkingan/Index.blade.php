@extends('layouts.app')

@section('title', 'Perangkingan')

@section('content')
<div class="container mt-4">

    <h2 class="mb-3">📊 Ranking Mahasiswa</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Peringkat</th>
                <th>Nama</th>
                <th>NIM</th>
                <th>Kelas</th>
                <th>Kelompok</th>
                <th>Nilai</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($mahasiswas as $i => $mhs)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $mhs['nama'] }}</td>
                    <td>{{ $mhs['nim'] }}</td>
                    <td>{{ $mhs['kelas'] }}</td>
                    <td>{{ $mhs['kelompok'] }}</td>
                    <td>{{ $mhs['nilai'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <hr>

    <h2 class="mb-3">🏆 Ranking Kelompok</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Peringkat</th>
                <th>Nama Kelompok</th>
                <th>Judul Proyek</th>
                <th>Anggota</th>
                <th>Rata-rata Nilai</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($kelompoks as $i => $kel)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $kel['nama'] }}</td>
                    <td>{{ $kel['judul'] }}</td>
                    <td>{{ $kel['anggota'] }}</td>
                    <td>{{ $kel['nilai'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</div>
@endsection
