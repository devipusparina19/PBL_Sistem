<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use Illuminate\Http\Request;

class DosenController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $dosens = Dosen::when($search, function($query, $search) {
            return $query->where('nama', 'like', "%{$search}%")
                         ->orWhere('nip', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
        })->paginate(10);

        return view('data_dosen.index_data', compact('dosens'));
    }

    public function create()
    {
        return view('data_dosen.create_data');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'nip' => 'required|unique:dosens',
            'email' => 'required|email|unique:dosens',
            'mata_kuliah' => 'required',
        ]);

        Dosen::create($request->all());
        return redirect()->route('dosen.index')->with('success', 'Dosen berhasil ditambahkan.');
    }

    public function show(Dosen $dosen)
    {
        return view('data_dosen.show_data', compact('dosen'));
    }

    public function edit(Dosen $dosen)
    {
        return view('data_dosen.edit_data', compact('dosen'));
    }

    public function update(Request $request, Dosen $dosen)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => 'required|string|max:20|unique:dosens,nip,'.$dosen->id,
            'email' => 'required|email|unique:dosens,email,'.$dosen->id,
            'no_telepon' => 'nullable|string|max:15',
            'mata_kuliah' => 'nullable|array',
            'mata_kuliah.*' => 'exists:mata_kuliah,id'
        ]);

        $dosen->update($request->except('mata_kuliah'));

        // Sync mata kuliah
        if ($request->has('mata_kuliah')) {
            $dosen->mataKuliah()->sync($request->mata_kuliah);
        } else {
            $dosen->mataKuliah()->detach();
        }

        return redirect()->route('dosen.index')->with('success', 'Data dosen berhasil diperbarui');
    }

    public function destroy(Dosen $dosen)
    {
        $dosen->delete();
        return redirect()->route('dosen.index')->with('success', 'Dosen berhasil dihapus.');
    }
}
