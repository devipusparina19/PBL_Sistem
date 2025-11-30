<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Nilai;
use Illuminate\Http\Request;

class DosenController extends Controller
{
    /* ========================================================
     |  BAGIAN 1 — MANAJEMEN DATA DOSEN (KODE KAMU)
     ======================================================== */

    public function index()
    {
        $kelasList = ['3A', '3B', '3C', '3D', '3E'];
        $dosenByKelas = [];

        foreach ($kelasList as $kelas) {
            $dosenByKelas[$kelas] = Dosen::where('kelas', $kelas)
                ->orderBy('nama', 'asc')
                ->get();
        }

        return view('data_dosen.index', compact('dosenByKelas', 'kelasList'));
    }

    public function showByKelas($kelas)
    {
        if (!in_array($kelas, ['3A', '3B', '3C', '3D', '3E'])) {
            abort(404);
        }

        $dosens = Dosen::where('kelas', $kelas)
            ->orderBy('nama', 'asc')
            ->paginate(15);

        return view('data_dosen.kelas', compact('dosens', 'kelas'));
    }

    public function create(Request $request)
    {
        $kelasDefault = $request->query('kelas', '3A');
        return view('data_dosen.create', compact('kelasDefault'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'          => 'required|string|max:255',
            'nip'           => 'required|string|max:50',
            'email'         => 'required|email|max:255',
            'no_telp'       => 'nullable|string|max:20',
            'kelas'         => 'nullable|string|max:50',
            'mata_kuliah'   => 'required|array',
            'mata_kuliah.*' => 'string|max:100',
        ]);

        $mataKuliahGabung = implode(', ', $request->mata_kuliah);

        Dosen::create([
            'nama'        => $request->nama,
            'nip'         => $request->nip,
            'email'       => $request->email,
            'no_telp'     => $request->no_telp,
            'kelas'       => $request->kelas,
            'mata_kuliah' => $mataKuliahGabung,
        ]);

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
        return view('data_dosen.edit', compact('dosen'));
    }

    public function update(Request $request, $id)
    {
        $dosen = Dosen::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => 'required|string|max:50',
            'email' => 'required|email|max:255',
            'no_telp' => 'nullable|string|max:20',
            'kelas' => 'nullable|string|max:50',
            'mata_kuliah' => 'required|array',
            'mata_kuliah.*' => 'string|max:100',
        ]);

        $mataKuliahGabung = implode(', ', $request->mata_kuliah);

        $dosen->update([
            'nama'        => $request->nama,
            'nip'         => $request->nip,
            'email'       => $request->email,
            'no_telp'     => $request->no_telp,
            'kelas'       => $request->kelas,
            'mata_kuliah' => $mataKuliahGabung,
        ]);

        return redirect()->route('data_dosen.index')->with('success', 'Data dosen berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $dosen = Dosen::findOrFail($id);
        $dosen->delete();

        return redirect()->route('data_dosen.index')->with('success', 'Data dosen berhasil dihapus!');
    }


    /* ========================================================
     |  BAGIAN 2 — INPUT NILAI MAHASISWA (KODE KAMU)
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
            'mahasiswa_id'      => 'required|exists:mahasiswas,id',
            'laporan'           => 'required|numeric|min:0|max:100',
            'presentasi'        => 'required|numeric|min:0|max:100',
            'kontribusi'        => 'required|numeric|min:0|max:100',
            'tanggal_penilaian' => 'required|date',
        ]);

        Nilai::create([
            'mahasiswa_id'      => $request->mahasiswa_id,
            'laporan'           => $request->laporan,
            'presentasi'        => $request->presentasi,
            'kontribusi'        => $request->kontribusi,
            'tanggal_penilaian' => $request->tanggal_penilaian,
        ]);

        return back()->with('success', 'Nilai mahasiswa berhasil disimpan.');
    }


    /* ========================================================
     |  BAGIAN 3 — CRUD SEDERHANA (TAMBAHAN PERMINTAAN KAMU)
     ======================================================== */

    public function crudIndex()
    {
        $data = Dosen::all();
        return view('dosen.index', compact('data'));
    }

    public function crudCreate()
    {
        return view('dosen.create');
    }

    public function crudStore(Request $request)
    {
        Dosen::create($request->all());
        return redirect()->back()->with('success', 'Dosen berhasil ditambahkan');
    }

    public function crudEdit($id)
    {
        $dosen = Dosen::findOrFail($id);
        return view('dosen.edit', compact('dosen'));
    }

    public function crudUpdate(Request $request, $id)
    {
        Dosen::findOrFail($id)->update($request->all());
        return redirect()->back()->with('success', 'Dosen berhasil diupdate');
    }

    public function crudDestroy($id)
    {
        Dosen::destroy($id);
        return redirect()->back()->with('success', 'Dosen berhasil dihapus');
    }
}
