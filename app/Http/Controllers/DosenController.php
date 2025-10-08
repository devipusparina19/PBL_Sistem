<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Nilai;
use Illuminate\Http\Request;

class DosenController extends Controller
{
    /* ========================================================
     |  BAGIAN 1 — MANAJEMEN DATA DOSEN (SUDAH ADA)
     ======================================================== */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $dosens = Dosen::when($search, function ($query, $search) {
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
            'nip' => 'required|string|max:20|unique:dosens,nip,' . $dosen->id,
            'email' => 'required|email|unique:dosens,email,' . $dosen->id,
            'no_telepon' => 'nullable|string|max:15',
            'mata_kuliah' => 'nullable|array',
            'mata_kuliah.*' => 'exists:mata_kuliah,id'
        ]);

        $dosen->update($request->except('mata_kuliah'));

        if ($request->has('mata_kuliah')) {
            $dosen->mataKuliah()->sync($request->mata_kuliah);
        } else {
            $dosen->mataKuliah()->detach();
        }

        return redirect()->route('dosen.index')->with('success', 'Data dosen berhasil diperbarui');
    }

    public function destroy($id)
    {
        $dosen = Dosen::findOrFail($id);
        $dosen->delete();
        return redirect()->route('data_dosen.index')->with('success', 'Data dosen berhasil dihapus!');
    }

    /* ========================================================
     |  BAGIAN 2 — FITUR TAMBAHAN: INPUT NILAI MAHASISWA
     ======================================================== */
    public function inputNilai()
    {
        $mahasiswa = Mahasiswa::orderBy('nama', 'asc')->get();
        $nilai = Nilai::with('mahasiswa')->latest()->get();
        return view('dosen.input_nilai', compact('mahasiswa', 'nilai'));
    }

    public function storeNilai(Request $request)
    {
        $request->validate([
            'mahasiswa_id' => 'required|exists:mahasiswas,id',
            'laporan' => 'required|numeric|min:0|max:100',
            'presentasi' => 'required|numeric|min:0|max:100',
            'kontribusi' => 'required|numeric|min:0|max:100',
            'tanggal_penilaian' => 'required|date',
        ]);

        Nilai::create([
            'mahasiswa_id' => $request->mahasiswa_id,
            'laporan' => $request->laporan,
            'presentasi' => $request->presentasi,
            'kontribusi' => $request->kontribusi,
            'tanggal_penilaian' => $request->tanggal_penilaian,
        ]);

        return back()->with('success', 'Nilai mahasiswa berhasil disimpan.');
    }
}
