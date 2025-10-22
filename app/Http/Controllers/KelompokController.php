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
        // Ambil semua users yang belum ada di tabel kelompok
        $users = DB::table('users')
            ->leftJoin('kelompok', 'kelompok.id_kelompok', '=', 'users.role_kelompok')
            ->whereNotNull('users.role_kelompok')
            ->whereNull('kelompok.id_kelompok')
            ->select('users.role_kelompok')
            ->get();

        $kelasList = ['3A', '3B', '3C', '3D', '3E'];

        foreach ($users as $u) {
            // Pilih kelas berdasarkan role_kelompok (contoh: modulo untuk merata)
            $kelas = $kelasList[$u->role_kelompok % count($kelasList)];

            DB::table('kelompok')->insert([
                'id_kelompok' => $u->role_kelompok,
                'kode_mk' => 'A0'.$u->role_kelompok.'K',
                'nama_kelompok' => 'Kelompok '.$u->role_kelompok,
                'judul_proyek' => 'Judul Proyek Default',
                'kelas' => $kelas,
            ]);
        }

        return redirect()->back()->with('success', 'Sinkronisasi kelompok berhasil!');
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

    // ===============================
    // Menampilkan kelompok berdasarkan kelas
    // ===============================
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

    // ===============================
    // Menampilkan detail kelompok
    // ===============================
    public function show(Kelompok $kelompok)
    {
        return view('kelompok.show', compact('kelompok'));
    }

    // ===============================
    // Menampilkan form tambah kelompok
    // ===============================
    public function create(Request $request)
    {
        $kelasDefault = $request->query('kelas', '3A');
        return view('kelompok.create', compact('kelasDefault'));
    }

    // ===============================
    // Menyimpan data kelompok baru
    // ===============================
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

    // ===============================
    // Menampilkan form edit kelompok
    // ===============================
    public function edit(Kelompok $kelompok)
    {
        return view('kelompok.edit', compact('kelompok'));
    }

    // ===============================
    // Memperbarui data kelompok
    // ===============================
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

    // ===============================
    // Menghapus data kelompok
    // ===============================
    public function destroy(Kelompok $kelompok)
    {
        $kelas = $kelompok->kelas;
        $kelompok->delete();

        return redirect()->route('kelompok.byKelas', $kelas)
            ->with('success', 'Data kelompok berhasil dihapus!');
    }
}
