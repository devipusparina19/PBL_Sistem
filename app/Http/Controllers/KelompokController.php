<?php

namespace App\Http\Controllers;

use App\Models\Kelompok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KelompokController extends Controller
{
    /**
     * Sinkronisasi kelompok berdasarkan data user.
     * User menentukan kelas & role_kelompok di tabel users.
     */
    public function sinkron()
    {
        // Ambil semua user yang sudah punya kelas dan role_kelompok
        $users = DB::table('users')
            ->whereNotNull('kelas')
            ->whereNotNull('role_kelompok')
            ->select('kelas', 'role_kelompok')
            ->groupBy('kelas', 'role_kelompok')
            ->get();

        $inserted = 0;

        foreach ($users as $u) {
            // Cek apakah kelompok ini sudah ada
            $exists = Kelompok::where('nama_kelompok', 'Kelompok ' . $u->role_kelompok)
                ->where('kelas', $u->kelas)
                ->exists();

            if (!$exists) {
                Kelompok::create([
                    'kode_mk' => 'A0' . str_pad($u->role_kelompok, 2, '0', STR_PAD_LEFT) . 'K',
                    'nama_kelompok' => 'Kelompok ' . $u->role_kelompok,
                    'judul_proyek' => 'Judul Proyek Default',
                    'kelas' => $u->kelas,
                ]);
                $inserted++;
            }
        }

        return redirect()->back()->with('success', "Sinkronisasi selesai! $inserted kelompok baru berhasil ditambahkan.");
    }

    /**
     * Menampilkan daftar kelompok grouped by kelas
     */
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

    /**
     * Menampilkan kelompok per kelas
     */
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
            'deskripsi' => 'nullable|string',
        ]);

        Kelompok::create($request->only(['kode_mk', 'nama_kelompok', 'kelas', 'judul_proyek']));

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
            'nip' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $kelompok->update($request->only(['kode_mk', 'nama_kelompok', 'kelas', 'judul_proyek', 'nip', 'deskripsi']));

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
