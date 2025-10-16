<?php

namespace App\Http\Controllers;

use App\Models\MataKuliah;
use Illuminate\Http\Request;

class MataKuliahController extends Controller
{
    public function index(Request $request)
    {
        // Group mata kuliah by kelas
        $kelasList = ['3A', '3B', '3C', '3D', '3E'];
        $mataKuliahByKelas = [];
        
        foreach ($kelasList as $kelas) {
            $mataKuliahByKelas[$kelas] = MataKuliah::where('kelas', $kelas)
                ->orderBy('nama_mk', 'asc')
                ->get();
        }
        
        return view('mata_kuliah.index', compact('mataKuliahByKelas', 'kelasList'));
    }

    // Display mata kuliah by kelas
    public function showByKelas($kelas)
    {
        // Validasi kelas
        if (!in_array($kelas, ['3A', '3B', '3C', '3D', '3E'])) {
            abort(404);
        }

        $mataKuliah = MataKuliah::where('kelas', $kelas)
            ->orderBy('nama_mk', 'asc')
            ->paginate(10);
        
        return view('mata_kuliah.kelas', compact('mataKuliah', 'kelas'));
    }

    public function create(Request $request)
    {
        $kelasDefault = $request->query('kelas', '3A');
        return view('mata_kuliah.create', compact('kelasDefault'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_mk' => 'required|string|max:50',
            'nama_mk' => 'required|string|max:255',
            'kelas' => 'required|in:3A,3B,3C,3D,3E',
            'nip_dosen' => 'required|string|max:30',
        ]);

        $mataKuliah = MataKuliah::create($request->only(['kode_mk', 'nama_mk', 'kelas', 'nip_dosen']));

        // Smart redirect
        if ($request->has('kelas')) {
            return redirect()->route('mata_kuliah.kelas', $request->kelas)
                ->with('success', 'Mata kuliah berhasil ditambahkan.');
        }

        return redirect()->route('mata_kuliah.index')->with('success', 'Mata kuliah berhasil ditambahkan.');
    }

    public function show(MataKuliah $mataKuliah)
    {
        return view('mata_kuliah.show', compact('mataKuliah'));
    }

    public function edit(MataKuliah $mataKuliah)
    {
        return view('mata_kuliah.edit', compact('mataKuliah'));
    }

    public function update(Request $request, MataKuliah $mataKuliah)
    {
        $request->validate([
            'kode_mk' => 'required|string|max:50',
            'nama_mk' => 'required|string|max:255',
            'kelas' => 'required|in:3A,3B,3C,3D,3E',
            'nip_dosen' => 'required|string|max:30',
        ]);

        $mataKuliah->update($request->only(['kode_mk', 'nama_mk', 'kelas', 'nip_dosen']));

        // Smart redirect
        if ($request->has('kelas')) {
            return redirect()->route('mata_kuliah.kelas', $request->kelas)
                ->with('success', 'Data mata kuliah berhasil diperbarui.');
        }

        return redirect()->route('mata_kuliah.index')->with('success', 'Data mata kuliah berhasil diperbarui.');
    }

    public function destroy(MataKuliah $mataKuliah)
    {
        $kelas = $mataKuliah->kelas;
        $mataKuliah->delete();

        // Smart redirect
        if (request()->server('HTTP_REFERER') && str_contains(request()->server('HTTP_REFERER'), 'mata_kuliah/kelas/')) {
            return redirect()->route('mata_kuliah.kelas', $kelas)
                ->with('success', 'Mata kuliah berhasil dihapus.');
        }

        return redirect()->route('mata_kuliah.index')->with('success', 'Mata kuliah berhasil dihapus.');
    }
}
