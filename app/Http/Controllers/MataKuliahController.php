<?php

namespace App\Http\Controllers;

use App\Models\MataKuliah;
use Illuminate\Http\Request;

class MataKuliahController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $mataKuliah = MataKuliah::when($search, function ($query, $search) {
            return $query->where('kode_mk', 'like', "%{$search}%")
                         ->orWhere('nama_mk', 'like', "%{$search}%");
        })->paginate(10);

        return view('mata_kuliah.index', compact('mataKuliah'));
    }

    public function create()
    {
        return view('mata_kuliah.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_mk' => 'required|unique:mata_kuliah,kode_mk',
            'nama_mk' => 'required|string|max:255',
        ]);

        MataKuliah::create($request->all());
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
            'kode_mk' => 'required|string|max:50|unique:mata_kuliah,kode_mk,' . $mataKuliah->id,
            'nama_mk' => 'required|string|max:255',
        ]);

        $mataKuliah->update($request->all());
        return redirect()->route('mata_kuliah.index')->with('success', 'Data mata kuliah berhasil diperbarui.');
    }

    public function destroy(MataKuliah $mataKuliah)
    {
        $mataKuliah->delete();
        return redirect()->route('mata_kuliah.index')->with('success', 'Mata kuliah berhasil dihapus.');
    }
}
