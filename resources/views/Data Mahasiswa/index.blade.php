@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="fw-bold text-primary mb-4">Data Mahasiswa</h2>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>NIM</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Angkatan</th>
                <th>Kelas</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($mahasiswas as $index => $mhs)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $mhs->nim }}</td>
                    <td>{{ $mhs->nama }}</td>
                    <td>{{ $mhs->email }}</td>
                    <td>{{ $mhs->angkatan }}</td>
                    <td>{{ $mhs->kelas ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Belum ada data mahasiswa.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
