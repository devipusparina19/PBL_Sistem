<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MataKuliah;
use App\Models\Mahasiswa;
use App\Models\Nilai;

class PenilaianController extends Controller
{
    public function daftarMatkul()
    {
        // Ambil daftar mata kuliah yang diampu dosen login
        $mataKuliah = MataKuliah::all();
        return view('dosen.daftar-matkul', compact('mataKuliah'));
    }

    public function formNilai($id)
    {
        $matkul = MataKuliah::findOrFail($id);
        $mahasiswa = Mahasiswa::all();
        $nilai = Nilai::where('mata_kuliah_id', $id)->with('mahasiswa')->get();

        return view('dosen.input-nilai', compact('matkul', 'mahasiswa', 'nilai'));
    }

    public function store(Request $request, $id)
    {
        $request->validate([
            'mahasiswa_id' => 'required',
            'laporan' => 'required|numeric|min:0|max:100',
            'presentasi' => 'required|numeric|min:0|max:100',
            'kontribusi' => 'required|numeric|min:0|max:100',
            'catatan' => 'nullable|string',
        ]);

        Nilai::create([
            'mata_kuliah_id' => $id,
            'mahasiswa_id' => $request->mahasiswa_id,
            'laporan' => $request->laporan,
            'presentasi' => $request->presentasi,
            'kontribusi' => $request->kontribusi,
            'catatan' => $request->catatan,
        ]);

        return redirect()->route('dosen.input.nilai', $id)->with('success', 'Nilai berhasil disimpan!');
    }
}
