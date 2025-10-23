<?php

namespace App\Http\Controllers;

use App\Models\Kelompok;
use Illuminate\Http\Request;

class KelompokController extends Controller
{
    // ===============================
    // Sinkronisasi kelompok otomatis
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

    // ✅ Cek apakah kelompok sudah ada untuk kelas yang sama
    $exists = Kelompok::where('nama_kelompok', 'Kelompok ' . $roleKelompok)
        ->where('kelas', $kelas)
        ->exists();

    if (!$exists) {
        Kelompok::create([
            // ✅ Satu kolom kode_mk langsung berisi beberapa kode
            'kode_mk' => 'A01K,A02K,A03K,A04K',
            'nama_kelompok' => 'Kelompok ' . $roleKelompok,
            'judul_proyek' => 'Judul Proyek Default',
            'kelas' => $kelas,
        ]);

        return redirect()->back()->with('success', "Kelompok {$roleKelompok} berhasil disinkron ke kelas {$kelas} dengan kode MK A01K–A04K.");
    }

    return redirect()->back()->with('info', "Kelompok {$roleKelompok} di kelas {$kelas} sudah ada, tidak perlu disinkron lagi.");
}


    // ===============================
    // Daftar kelompok per kelas
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
    // Tampilkan kelompok berdasarkan kelas
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
    // Detail kelompok
    // ===============================
    public function show(Kelompok $kelompok)
    {
        return view('kelompok.show', compact('kelompok'));
    }

    // ===============================
    // Form tambah kelompok
    // ===============================
    public function create(Request $request)
    {
        $kelasDefault = $request->query('kelas', '3A');
        return view('kelompok.create', compact('kelasDefault'));
    }

    // ===============================
    // Simpan kelompok baru
    // ===============================
    public function store(Request $request)
    {
        $request->validate([
            'kode_mk' => 'required|string|max:255',
            'nama_kelompok' => 'required|string|max:255',
            'kelas' => 'required|in:3A,3B,3C,3D,3E',
            'judul_proyek' => 'required|string|max:255',
        ]);

        // ✅ Pastikan tidak ada nama kelompok sama di kelas yang sama
        $exists = Kelompok::where('nama_kelompok', $request->nama_kelompok)
            ->where('kelas', $request->kelas)
            ->exists();

        if ($exists) {
            return redirect()->back()->with('warning', 'Kelompok dengan nama yang sama sudah ada di kelas ini.');
        }

        Kelompok::create($request->only(['kode_mk', 'nama_kelompok', 'kelas', 'judul_proyek']));

        return redirect()->route('kelompok.byKelas', $request->kelas)
            ->with('success', 'Data kelompok berhasil ditambahkan!');
    }

    // ===============================
    // Form edit kelompok
    // ===============================
    public function edit(Kelompok $kelompok)
    {
        return view('kelompok.edit', compact('kelompok'));
    }

    // ===============================
    // Update data kelompok
    // ===============================
    public function update(Request $request, Kelompok $kelompok)
    {
        $request->validate([
            'kode_mk' => 'required|string|max:255',
            'nama_kelompok' => 'required|string|max:255',
            'kelas' => 'required|in:3A,3B,3C,3D,3E',
            'judul_proyek' => 'required|string|max:255',
        ]);

        // ✅ Gunakan id_kelompok, bukan id
        $exists = Kelompok::where('nama_kelompok', $request->nama_kelompok)
            ->where('kelas', $request->kelas)
            ->where('id_kelompok', '!=', $kelompok->id_kelompok)
            ->exists();

        if ($exists) {
            return redirect()->back()->with('warning', 'Nama kelompok tersebut sudah digunakan di kelas ini.');
        }

        $kelompok->update($request->only(['kode_mk', 'nama_kelompok', 'kelas', 'judul_proyek']));

        return redirect()->route('kelompok.byKelas', $request->kelas)
            ->with('success', 'Data kelompok berhasil diperbarui!');
    }

    // ===============================
    // Hapus kelompok
    // ===============================
    public function destroy(Kelompok $kelompok)
    {
        $kelas = $kelompok->kelas;
        $kelompok->delete();

        return redirect()->route('kelompok.byKelas', $kelas)
            ->with('success', 'Data kelompok berhasil dihapus!');
    }
}
