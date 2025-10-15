<?php

namespace App\Http\Controllers;

use App\Models\Kelompok;
use Illuminate\Http\Request;

class KelompokController extends Controller
{
    // menampilkan daftar kelompok (grouped by kelas)
    public function index()
    {
        // Group kelompok by kelas
        $kelasList = ['3A', '3B', '3C', '3D', '3E'];
        $kelompokByKelas = [];
        
        foreach ($kelasList as $kelas) {
            $kelompokByKelas[$kelas] = Kelompok::where('kelas', $kelas)
                ->orderBy('nama_kelompok', 'asc')
                ->get();
        }
        
        return view('kelompok.index', compact('kelompokByKelas', 'kelasList'));
    }

    // menampilkan kelompok berdasarkan kelas
    public function showByKelas($kelas)
    {
        // Validasi kelas
        if (!in_array($kelas, ['3A', '3B', '3C', '3D', '3E'])) {
            abort(404);
        }

        $kelompok = Kelompok::where('kelas', $kelas)
            ->orderBy('nama_kelompok', 'asc')
            ->paginate(10);
        
        return view('kelompok.kelas', compact('kelompok', 'kelas'));
    }

    // menampilkan detail kelompok
    public function show(Kelompok $kelompok)
    {
        return view('kelompok.show', compact('kelompok'));
    }

    // menampilkan form tambah kelompok
    public function create(Request $request)
    {
        $kelasDefault = $request->query('kelas', '3A'); // Default 3A jika tidak ada
        return view('kelompok.create', compact('kelasDefault'));
    }

    // menyimpan data kelompok baru
    public function store(Request $request)
    {
        $request->validate([
            'kode_mk' => 'required|string|max:255',
            'nama_kelompok' => 'required|string|max:255',
            'kelas' => 'required|in:3A,3B,3C,3D,3E',
            'judul_proyek' => 'required|string|max:255',
            'kelas' => 'required|string|max:255',
        ]);

        $kelompok = Kelompok::create($request->all());

        // Redirect ke halaman kelas jika ada
        if ($request->has('kelas')) {
            return redirect()->route('kelompok.kelas', $request->kelas)->with('success', 'Data kelompok berhasil ditambahkan!');
        }

        return redirect()->route('kelompok.index')->with('success', 'Data kelompok berhasil ditambahkan!');
    }

    // menampilkan form edit kelompok
    public function edit(Kelompok $kelompok)
    {
        return view('kelompok.edit', compact('kelompok'));
    }

    // memperbarui data kelompok
    public function update(Request $request, Kelompok $kelompok)
    {
        $request->validate([
            'kode_mk' => 'required|string|max:255',
            'nama_kelompok' => 'required|string|max:255',
            'kelas' => 'required|in:3A,3B,3C,3D,3E',
            'judul_proyek' => 'required|string|max:255',
            'kelas' => 'required|string|max:255',
        ]);

        $kelompok->update($request->all());

        // Redirect ke halaman kelas jika ada
        if ($request->has('kelas')) {
            return redirect()->route('kelompok.kelas', $request->kelas)->with('success', 'Data kelompok berhasil diperbarui!');
        }

        return redirect()->route('kelompok.index')->with('success', 'Data kelompok berhasil diperbarui!');
    }

    // menghapus data kelompok
    public function destroy(Kelompok $kelompok)
    {
        $kelas = $kelompok->kelas; // Simpan kelas sebelum dihapus
        $kelompok->delete();

        // Cek apakah request dari halaman detail kelas
        if (request()->server('HTTP_REFERER') && str_contains(request()->server('HTTP_REFERER'), 'kelompok/kelas/')) {
            return redirect()->route('kelompok.kelas', $kelas)->with('success', 'Data kelompok berhasil dihapus!');
        }

        return redirect()->route('kelompok.index')->with('success', 'Data kelompok berhasil dihapus!');
    }
}
