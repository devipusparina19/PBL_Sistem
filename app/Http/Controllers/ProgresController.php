<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Progres;

class ProgresController extends Controller
{
    public function index()
    {
        $progres = Progres::all();
        return view('progres.index', compact('progres'));
    }

    public function show($id)
    {
        $item = Progres::findOrFail($id);
        return view('progres.show', compact('item'));
    }

    public function create()
    {
        return view('progres.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required',
            'deskripsi' => 'required',
        ]);

        Progres::create($request->all());
        return redirect('/progres')->with('success', 'Data progres berhasil ditambah');
    }

    public function edit($id)
    {
        $item = Progres::findOrFail($id);
        return view('progres.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = Progres::findOrFail($id);
        $item->update($request->all());
        return redirect('/progres')->with('success', 'Data progres berhasil diperbarui');
    }

    public function destroy($id)
    {
        Progres::destroy($id);
        return redirect('/progres')->with('success', 'Data progres berhasil dihapus');
    }
}
