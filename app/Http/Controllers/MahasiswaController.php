<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    /**
     * Display a listing of the resource (grouped by kelas)
     */
    public function index()
    {
        // Group mahasiswa by kelas
        $kelasList = ['3A', '3B', '3C', '3D', '3E'];
        $mahasiswaByKelas = [];
        
        foreach ($kelasList as $kelas) {
            $mahasiswaByKelas[$kelas] = Mahasiswa::where('kelas', $kelas)
                ->orderBy('nama', 'asc')
                ->get();
        }
        
        return view('mahasiswa.index', compact('mahasiswaByKelas', 'kelasList'));
    }

    /**
     * Display mahasiswa by kelas
     */
    public function showByKelas($kelas)
    {
        // Validasi kelas
        if (!in_array($kelas, ['3A', '3B', '3C', '3D', '3E'])) {
            abort(404);
        }

        $mahasiswa = Mahasiswa::where('kelas', $kelas)
            ->orderBy('nama', 'asc')
            ->paginate(15);
        
        return view('mahasiswa.kelas', compact('mahasiswa', 'kelas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $kelasDefault = $request->query('kelas', '3A');
        return view('mahasiswa.create', compact('kelasDefault'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $request->validate([
        'nim' => 'required|unique:mahasiswas',
        'nama' => 'required',
        'kelas' => 'required', 
        'angkatan' => 'required',
        'email' => 'required|email|unique:mahasiswas',
    ]);

    Mahasiswa::create([
        'nim' => $request->nim,
        'nama' => $request->nama,
        'kelas' => $request->kelas, 
        'angkatan' => $request->angkatan,
        'email' => $request->email,
    ]);

    return redirect()->route('mahasiswa.kelas', $request->kelas)
        ->with('success', 'Data mahasiswa berhasil ditambahkan.');
}

    /**
     * Display the specified resource.
     */
    public function show(Mahasiswa $mahasiswa)
    {
        return view('mahasiswa.show', compact('mahasiswa'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Mahasiswa $mahasiswa)
    {
        return view('mahasiswa.edit', compact('mahasiswa'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Mahasiswa $mahasiswa)
{
    $request->validate([
        'nama' => 'required',
        'kelas' => 'required', 
        'angkatan' => 'required',
        'email' => 'required|email|unique:mahasiswas,email,' . $mahasiswa->id,
    ]);

    $data = [
        'nama' => $request->nama,
        'kelas' => $request->kelas, 
        'angkatan' => $request->angkatan,
        'email' => $request->email,
    ];

    $mahasiswa->update($data);

    return redirect()->route('mahasiswa.kelas', $request->kelas)
        ->with('success', 'Data mahasiswa berhasil diperbarui.');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Mahasiswa $mahasiswa)
    {
        $kelas = $mahasiswa->kelas; // Simpan kelas sebelum dihapus
        $mahasiswa->delete();

        // Smart redirect ke halaman kelas jika dari halaman detail kelas
        if (request()->server('HTTP_REFERER') && str_contains(request()->server('HTTP_REFERER'), 'mahasiswa/kelas/')) {
            return redirect()->route('mahasiswa.kelas', $kelas)
                ->with('success', 'Data mahasiswa berhasil dihapus.');
        }

        return redirect()->route('mahasiswa.index')
            ->with('success', 'Data mahasiswa berhasil dihapus.');
    }
}
