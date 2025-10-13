@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3 class="fw-bold mb-4 text-center text-primary">Detail Kelompok PBL</h3>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <table class="table table-bordered">
                <tr>
                    <th class="bg-dark text-white w-25">Nama Kelompok</th>
                    <td>{{ $kelompok->nama_kelompok }}</td>
                </tr>
                <tr>
                    <th class="bg-dark text-white">Judul Proyek</th>
                    <td>{{ $kelompok->judul_proyek }}</td>
                </tr>
                <tr>
                    <th class="bg-dark text-white">Status</th>
                    <td>
                        @if($kelompok->status == 'Selesai')
                            <span class="badge bg-success px-3 py-2">Selesai</span>
                        @elseif($kelompok->status == 'Berjalan')
                            <span class="badge bg-warning text-dark px-3 py-2">Berjalan</span>
                        @else
                            <span class="badge bg-secondary px-3 py-2">Belum Mulai</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th class="bg-dark text-white">Terakhir Diperbarui</th>
                    <td>{{ \Carbon\Carbon::parse($kelompok->updated_at)->format('d M Y') }}</td>
                </tr>
            </table>

            <div class="text-end mt-3">
                <a href="{{ route('koordinator.index') }}" class="btn btn-secondary">Kembali</a>
                <a href="{{ route('koordinator.edit', $kelompok->id_kelompok) }}" class="btn btn-warning">Edit</a>
            </div>
        </div>
    </div>
</div>
@endsection
