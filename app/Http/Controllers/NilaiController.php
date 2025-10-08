<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\Nilai;
use Illuminate\Http\Request;

class NilaiController extends Controller
{
    /**
     * Menampilkan halaman input nilai & daftar nilai.
     */
    public function index()
    {
        // Ambil semua data mahasiswa untuk pilihan select
        $mahasiswa = Mahasiswa::orderBy('nama', 'asc')->get();

        // Ambil semua data nilai beserta relasi mahasiswa
        $nilai = Nilai::with('mahasiswa')->orderBy('created_at', 'desc')->get();

        return view('nilai.index', compact('mahasiswa', 'nilai'));
    }

    /**
     * Simpan data nilai ke database.
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'mahasiswa_id' => 'required|exists:mahasiswa,id',
            'laporan' => 'required|numeric|min:0|max:100',
            'presentasi' => 'required|numeric|min:0|max:100',
            'kontribusi' => 'required|numeric|min:0|max:100',
            'catatan' => 'nullable|string|max:500',
        ]);

        // Simpan data
        Nilai::create([
            'mahasiswa_id' => $request->mahasiswa_id,
            'laporan' => $request->laporan,
            'presentasi' => $request->presentasi,
            'kontribusi' => $request->kontribusi,
            'catatan' => $request->catatan,
        ]);

        return redirect()->route('nilai.index')->with('success', 'Nilai mahasiswa berhasil disimpan!');
    }

    /**
     * Hapus nilai tertentu.
     */
    public function destroy($id)
    {
        $nilai = Nilai::findOrFail($id);
        $nilai->delete();

        return redirect()->route('nilai.index')->with('success', 'Data nilai berhasil dihapus!');
    }

    /**
     * Tampilkan form edit nilai.
     */
    public function edit($id)
    {
        $nilai = Nilai::findOrFail($id);
        $mahasiswa = Mahasiswa::orderBy('nama', 'asc')->get();

        return view('nilai.edit', compact('nilai', 'mahasiswa'));
    }

    /**
     * Update nilai mahasiswa.
     */
    public function update(Request $request, $id)
    {
        $nilai = Nilai::findOrFail($id);

        $request->validate([
            'mahasiswa_id' => 'required|exists:mahasiswa,id',
            'laporan' => 'required|numeric|min:0|max:100',
            'presentasi' => 'required|numeric|min:0|max:100',
            'kontribusi' => 'required|numeric|min:0|max:100',
            'catatan' => 'nullable|string|max:500',
        ]);

        $nilai->update($request->all());

        return redirect()->route('nilai.index')->with('success', 'Nilai berhasil diperbarui!');
    }
}
