<?php

namespace App\Http\Controllers;

use App\Models\Kelompok;
use Illuminate\Http\Request;

class KelompokController extends Controller
{
    // Menampilkan daftar kelompok grouped by kelas
    public function index()
    {
        $kelasList = ['3A', '3B', '3C', '3D', '3E'];
        $kelompokByKelas = [];

        foreach ($kelasList as $kelas) {
            $kelompokByKelas[$kelas] = Kelompok::where('kelas', $kelas)
                ->orderBy('nama_kelompok', 'asc')
                ->get();
        }

        return view('kelompok.index', compact('kelasList', 'kelompokByKelas'));
    }

    // Menampilkan kelompok berdasarkan kelas
    public function showByKelas($kelas)
    {
        if (!in_array($kelas, ['3A', '3B', '3C', '3D', '3E'])) {
            abort(404);
        }

        $kelompok = Kelompok::where('kelas', $kelas)
            ->orderBy('nama_kelompok', 'asc')
            ->paginate(10);

        return view('kelompok.byKelas', compact('kelompok', 'kelas'));
    }

    // Menampilkan detail kelompok
    public function show(Kelompok $kelompok)
    {
        return view('kelompok.show_data', compact('kelompok'));
    }

    // Menampilkan form tambah kelompok
    public function create(Request $request)
    {
        $kelasDefault = $request->query('kelas', '3A');
        return view('kelompok.create', compact('kelasDefault'));
    }

    // Menyimpan data kelompok baru
    public function store(Request $request)
    {
        $request->validate([
            'kode_mk' => 'required|string|max:255',
            'nama_kelompok' => 'required|string|max:255',
            'kelas' => 'required|in:3A,3B,3C,3D,3E',
            'judul_proyek' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $kelompok = Kelompok::create($request->all());

        return redirect()->route('kelompok.byKelas', $request->kelas)
            ->with('success', 'Data kelompok berhasil ditambahkan!');
    }

    // Menampilkan form edit kelompok
    public function edit(Kelompok $kelompok)
    {
        return view('kelompok.edit', compact('kelompok'));
    }

    // Memperbarui data kelompok
    public function update(Request $request, Kelompok $kelompok)
    {
        $request->validate([
            'kode_mk' => 'required|string|max:255',
            'nama_kelompok' => 'required|string|max:255',
            'kelas' => 'required|in:3A,3B,3C,3D,3E',
            'judul_proyek' => 'required|string|max:255',
            'nip' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $kelompok->update($request->all());

        return redirect()->route('kelompok.byKelas', $request->kelas)
            ->with('success', 'Data kelompok berhasil diperbarui!');
    }

    // Menghapus data kelompok
    public function destroy(Kelompok $kelompok)
    {
        $kelas = $kelompok->kelas;
        $kelompok->delete();

        return redirect()->route('kelompok.byKelas', $kelas)
            ->with('success', 'Data kelompok berhasil dihapus!');
    }
}
