<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use Illuminate\Http\Request;

class DosenController extends Controller
{
    public function index()
    {
        $dosens = Dosen::paginate(10);
        return view('data_dosen.index_data', compact('dosens'));
    }

    public function create()
    {
        return view('data_dosen.create_data');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => 'required|string|max:50|unique:dosens,nip',
            'email' => 'required|email|unique:dosens,email',
            'no_telepon' => 'nullable|string|max:20',
            'kelas' => 'nullable|string|max:50',
            'mata_kuliah' => 'nullable|string|max:100',
        ]);

        Dosen::create($request->all());
        return redirect()->route('data_dosen.index')->with('success', 'Data dosen berhasil ditambahkan!');
    }

    public function show($id)
    {
        $dosen = Dosen::findOrFail($id);
        return view('data_dosen.show_data', compact('dosen'));
    }

    public function edit($id)
    {
        $dosen = Dosen::findOrFail($id);
        return view('data_dosen.edit_data', compact('dosen'));
    }

    public function update(Request $request, $id)
    {
        $dosen = Dosen::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => 'required|string|max:50|unique:dosens,nip,' . $dosen->id,
            'email' => 'required|email|unique:dosens,email,' . $dosen->id,
            'no_telepon' => 'nullable|string|max:20',
            'kelas' => 'nullable|string|max:50',
            'mata_kuliah' => 'nullable|string|max:100',
        ]);

        $dosen->update($request->all());
        return redirect()->route('data_dosen.index')->with('success', 'Data dosen berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $dosen = Dosen::findOrFail($id);
        $dosen->delete();
        return redirect()->route('data_dosen.index')->with('success', 'Data dosen berhasil dihapus!');
    }
}
