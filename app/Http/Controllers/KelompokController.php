<?php

namespace App\Http\Controllers;

use App\Models\Kelompok;
use Illuminate\Http\Request;

class KelompokController extends Controller
{
    // menampilkan daftar kelompok
    public function index()
    {
        $kelompok = Kelompok::paginate(5);
        return view('kelompok.index', compact('kelompok'));
    }

    // menampilkan form tambah kelompok
    public function create()
    {
        return view('kelompok.create');
    }

    // menyimpan data kelompok baru
    public function store(Request $request)
    {
        $request->validate([
            'kode_mk' => 'required|string|max:255',
            'nama_kelompok' => 'required|string|max:255',
            'judul_proyek' => 'required|string|max:255',
            'nip' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        Kelompok::create($request->all());

        return redirect()->route('kelompok.index')->with('success', 'Data kelompok berhasil ditambahkan!');
    }

    // menampilkan form edit kelompok
    public function edit(Kelompok $kelompok)
    {
        return view('kelompok.edit', compact('kelompok'));
    }

    // memperbarui data kelompok
    public function update(Request $request, Kelompok $kelompok)
    {
        $request->validate([
            'kode_mk' => 'required|string|max:255',
            'nama_kelompok' => 'required|string|max:255',
            'judul_proyek' => 'required|string|max:255',
            'nip' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $kelompok->update($request->all());

        return redirect()->route('kelompok.index')->with('success', 'Data kelompok berhasil diperbarui!');
    }

    // menghapus data kelompok
    public function destroy(Kelompok $kelompok)
    {
        $kelompok->delete();

        return redirect()->route('kelompok.index')->with('success', 'Data kelompok berhasil dihapus!');
    }
}
