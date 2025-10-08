<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\Nilai;
use Illuminate\Http\Request;

class NilaiController extends Controller
{
    public function index()
    {
        $mahasiswa = Mahasiswa::orderBy('nama', 'asc')->get(); // ambil dari CRUD Mahasiswa
        $nilai = Nilai::with('mahasiswa')->get(); // tampilkan semua nilai + relasi mahasiswa

        return view('nilai.index', compact('mahasiswa', 'nilai'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'mahasiswa_id' => 'required|exists:mahasiswa,id',
            'laporan' => 'required|numeric|min:0|max:100',
            'presentasi' => 'required|numeric|min:0|max:100',
            'kontribusi' => 'required|numeric|min:0|max:100',
            'catatan' => 'nullable|string|max:500',
        ]);

        Nilai::create($request->all());
        return redirect()->route('nilai.index')->with('success', 'Nilai berhasil disimpan!');
    }
}
