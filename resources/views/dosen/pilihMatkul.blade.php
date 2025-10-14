@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Pilih Mata Kuliah</h3>

    <form id="formPilihMatkul" method="GET">
        <div class="form-group mt-3">
            <label for="mata_kuliah_id">Mata Kuliah</label>
            <select name="mata_kuliah_id" id="mata_kuliah_id" class="form-control" required>
                @foreach ($mataKuliah as $mk)
                    <option value="{{ $mk->id }}">{{ $mk->nama_mk }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Pilih</button>
    </form>
</div>

<script>
    document.getElementById('formPilihMatkul').addEventListener('submit', function(e) {
        e.preventDefault();
        const id = document.getElementById('mata_kuliah_id').value;
        this.action = `/nilai/input/${id}`;
        this.submit();
    });
</script>
@endsection
