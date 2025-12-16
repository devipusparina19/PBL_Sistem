@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Validasi Milestone</h2>

    {{-- Pesan sukses --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Jika tidak ada data --}}
    @if($milestones->isEmpty())
        <p class="text-muted">Tidak ada milestone yang menunggu validasi.</p>
    @else
        <table class="table table-bordered align-middle">
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

                    {{-- Menampilkan nama mahasiswa (relasi user) --}}
                    <td>{{ $m->user->name ?? 'Tidak diketahui' }}</td>

                    {{-- Menampilkan nama kelompok (relasi kelompok) --}}
                    <td>{{ $m->kelompok->nama_kelompok ?? 'Belum ada kelompok' }}</td>

                    {{-- Status --}}
                    <td>
                        @if($m->status == 'disetujui')
                            <span class="badge bg-success">Disetujui</span>
                        @elseif($m->status == 'ditolak')
                            <span class="badge bg-danger">Ditolak</span>
                        @else
                            <span class="badge bg-warning text-dark">Menunggu</span>
                        @endif
                    </td>

                    {{-- Catatan dosen --}}
                    <td>{{ $m->catatan_dosen ?? '-' }}</td>

                    {{-- Form validasi --}}
                    <td>
                        <form action="{{ route('milestone.updateStatus', $m->id) }}" method="POST">
                            @csrf
                            <select name="status" class="form-select mb-2" required onchange="toggleNilaiFields(this, {{ $m->id }})">
                                <option value="">-- Pilih Status --</option>
                                <option value="disetujui">Disetujui</option>
                                <option value="ditolak">Ditolak</option>
                            </select>
                            
                            <div id="nilai-fields-{{ $m->id }}" style="display: none;">
                                <div class="mb-2">
                                    <label class="form-label small fw-semibold">Target Minggu</label>
                                    <input type="number" name="target_minggu" class="form-control form-control-sm"
                                           value="{{ $m->minggu_ke }}" min="1" placeholder="Target minggu">
                                    <small class="text-muted">Submit: Minggu {{ $m->minggu_ke }}</small>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label small fw-semibold">Nilai (0-100)</label>
                                    <input type="number" name="nilai" class="form-control form-control-sm"
                                           min="0" max="100" step="0.01" placeholder="Nilai milestone">
                                    <small class="text-muted">Bonus/penalty dihitung otomatis</small>
                                </div>
                            </div>
                            
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

<script>
function toggleNilaiFields(select, id) {
    const nilaiFields = document.getElementById('nilai-fields-' + id);
    if (select.value === 'disetujui') {
        nilaiFields.style.display = 'block';
    } else {
        nilaiFields.style.display = 'none';
    }
}
</script>
@endsection
