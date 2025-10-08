@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Validasi Milestone</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($milestones->isEmpty())
        <p class="text-muted">Tidak ada milestone yang menunggu validasi.</p>
    @else
        <table class="table table-bordered">
            <thead class="table-primary">
                <tr>
                    <th>Minggu Ke</th>
                    <th>Judul</th>
                    <th>Deskripsi</th>
                    <th>Mahasiswa</th>
                    <th>Kelompok</th>
                    <th>Status</th>
                    <th>Catatan Dosen</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($milestones as $m)
                <tr>
                    <td>{{ $m->minggu_ke }}</td>
                    <td>{{ $m->judul }}</td>
                    <td>{{ $m->deskripsi }}</td>
                    <td>{{ $m->user->name ?? '-' }}</td>
                    <td>{{ $m->kelompok->nama ?? '-' }}</td>
                    <td>{{ ucfirst($m->status) }}</td>
                    <td>{{ $m->catatan_dosen ?? '-' }}</td>
                    <td>
                        <form action="{{ route('milestone.updateStatus', $m->id) }}" method="POST">
                            @csrf
                            <select name="status" class="form-select mb-2">
                                <option value="disetujui">Disetujui</option>
                                <option value="ditolak">Ditolak</option>
                            </select>
                            <textarea name="catatan_dosen" class="form-control mb-2" placeholder="Catatan (opsional)"></textarea>
                            <button type="submit" class="btn btn-success btn-sm w-100">Update Status</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
