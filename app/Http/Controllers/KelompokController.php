<?php

namespace App\Http\Controllers;

use App\Models\Kelompok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KelompokController extends Controller
{
    // ===============================
    // Method untuk sinkronisasi kelompok
    // ===============================
    public function sinkron()
{
    $user = auth()->user();

    // Pastikan user sudah punya kelompok dan kelas
    if (!$user->role_kelompok || !$user->kelas) {
        return redirect()->back()->with('warning', 'Anda belum tergabung dalam kelompok atau belum memiliki kelas.');
    }

    $roleKelompok = $user->role_kelompok;
    $kelas = $user->kelas;

    // Cek apakah kelompok sudah ada
    $exists = DB::table('kelompok')
        ->where('id_kelompok', $roleKelompok)
        ->exists();

    if (!$exists) {
        DB::table('kelompok')->insert([
            'id_kelompok' => $roleKelompok,
            'kode_mk' => 'A0' . $roleKelompok . 'K',
            'nama_kelompok' => 'Kelompok ' . $roleKelompok,
            'judul_proyek' => 'Judul Proyek Default',
            'kelas' => $kelas,
        ]);

        return redirect()->back()->with('success', "Kelompok {$roleKelompok} berhasil disinkron ke kelas {$kelas}.");
    }

    return redirect()->back()->with('info', "Kelompok {$roleKelompok} sudah ada, tidak perlu disinkron lagi.");
}
    // ===============================
    // Menampilkan daftar kelompok grouped by kelas
    // ===============================
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

    public function show(Kelompok $kelompok)
    {
        return view('kelompok.show', compact('kelompok'));
    }

    public function create(Request $request)
    {
        $kelasDefault = $request->query('kelas', '3A');
        return view('kelompok.create', compact('kelasDefault'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_mk' => 'required|string|max:255',
            'nama_kelompok' => 'required|string|max:255',
            'kelas' => 'required|in:3A,3B,3C,3D,3E',
            'judul_proyek' => 'required|string|max:255',
        ]);

        Kelompok::create($request->all());

        return redirect()->route('kelompok.byKelas', $request->kelas)
            ->with('success', 'Data kelompok berhasil ditambahkan!');
    }

    public function edit(Kelompok $kelompok)
    {
        return view('kelompok.edit', compact('kelompok'));
    }

    public function update(Request $request, Kelompok $kelompok)
    {
        $request->validate([
            'kode_mk' => 'required|string|max:255',
            'nama_kelompok' => 'required|string|max:255',
            'kelas' => 'required|in:3A,3B,3C,3D,3E',
            'judul_proyek' => 'required|string|max:255',
        ]);

        $kelompok->update($request->all());

        return redirect()->route('kelompok.byKelas', $request->kelas)
            ->with('success', 'Data kelompok berhasil diperbarui!');
    }

    public function destroy(Kelompok $kelompok)
    {
        $kelas = $kelompok->kelas;
        $kelompok->delete();

        return redirect()->route('kelompok.byKelas', $kelas)
            ->with('success', 'Data kelompok berhasil dihapus!');
    }
}
