<?php

namespace App\Http\Controllers;

use App\Models\Kelompok;
use Illuminate\Http\Request;

class KelompokController extends Controller
{
    // Menampilkan daftar kelompok
    public function index()
    {
        $kelompoks = Kelompok::all();
        return view('kelompok.index', compact('kelompoks'));
    }

    // Menampilkan form tambah kelompok
    public function create()
    {
        return view('kelompok.create');
    }

    // Menyimpan data kelompok baru
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        Kelompok::create([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('kelompok.index')
                         ->with('success', 'Data kelompok berhasil ditambahkan.');
    }

    // Menampilkan form edit kelompok
    public function edit(Kelompok $kelompok)
    {
        return view('kelompok.edit', compact('kelompok'));
    }

    // Mengupdate data kelompok
    public function update(Request $request, Kelompok $kelompok)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $kelompok->update([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('kelompok.index')
                         ->with('success', 'Data kelompok berhasil diupdate.');
    }

    // Menghapus kelompok
    public function destroy(Kelompok $kelompok)
    {
        $kelompok->delete();

        return redirect()->route('kelompok.index')
                         ->with('success', 'Data kelompok berhasil dihapus.');
    }
}
