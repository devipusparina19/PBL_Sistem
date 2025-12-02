<?php

namespace App\Http\Controllers;

use App\Models\Kelompok;
use Illuminate\Http\Request;

class NilaiKelompokController extends Controller
{
    /**
     * Menampilkan daftar nilai kelompok
     */
    public function index()
    {
        $kelompoks = Kelompok::with('mahasiswas')->orderBy('nama_kelompok', 'asc')->get();
        return view('nilai_kelompok.index', compact('kelompoks'));
    }

    /**
     * Menampilkan form tambah nilai kelompok
     */
    public function create()
    {
        $kelompoks = Kelompok::orderBy('nama_kelompok', 'asc')->get();
        return view('nilai_kelompok.create', compact('kelompoks'));
    }

    /**
     * Menyimpan nilai kelompok baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'kelompok_id' => 'required|exists:kelompoks,id_kelompok',
            'pemrograman_web' => 'required|numeric|min:0|max:100',
            'integrasi_sistem' => 'required|numeric|min:0|max:100',
            'pengambilan_keputusan' => 'required|numeric|min:0|max:100',
            'it_proyek' => 'required|numeric|min:0|max:100',
            'kontribusi_kelompok' => 'required|numeric|min:0|max:100',
            'penilaian_dosen' => 'required|numeric|min:0|max:100',
        ]);

        // Hitung hasil akhir (rata-rata 6 komponen)
        $hasil_akhir = (
            $request->pemrograman_web +
            $request->integrasi_sistem +
            $request->pengambilan_keputusan +
            $request->it_proyek +
            $request->kontribusi_kelompok +
            $request->penilaian_dosen
        ) / 6;

        $kelompok = Kelompok::findOrFail($request->kelompok_id);
        $kelompok->update([
            'pemrograman_web' => $request->pemrograman_web,
            'integrasi_sistem' => $request->integrasi_sistem,
            'pengambilan_keputusan' => $request->pengambilan_keputusan,
            'it_proyek' => $request->it_proyek,
            'kontribusi_kelompok' => $request->kontribusi_kelompok,
            'penilaian_dosen' => $request->penilaian_dosen,
            'hasil_akhir' => round($hasil_akhir, 2),
        ]);

        return redirect()->route('nilai-kelompok.index')->with('success', 'Nilai kelompok berhasil disimpan!');
    }

    /**
     * Menampilkan form edit nilai kelompok
     */
    public function edit($id)
    {
        $kelompok = Kelompok::findOrFail($id);
        return view('nilai_kelompok.edit', compact('kelompok'));
    }

    /**
     * Mengupdate nilai kelompok
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'pemrograman_web' => 'required|numeric|min:0|max:100',
            'integrasi_sistem' => 'required|numeric|min:0|max:100',
            'pengambilan_keputusan' => 'required|numeric|min:0|max:100',
            'it_proyek' => 'required|numeric|min:0|max:100',
            'kontribusi_kelompok' => 'required|numeric|min:0|max:100',
            'penilaian_dosen' => 'required|numeric|min:0|max:100',
        ]);

        // Hitung ulang hasil akhir
        $hasil_akhir = (
            $request->pemrograman_web +
            $request->integrasi_sistem +
            $request->pengambilan_keputusan +
            $request->it_proyek +
            $request->kontribusi_kelompok +
            $request->penilaian_dosen
        ) / 6;

        $kelompok = Kelompok::findOrFail($id);
        $kelompok->update([
            'pemrograman_web' => $request->pemrograman_web,
            'integrasi_sistem' => $request->integrasi_sistem,
            'pengambilan_keputusan' => $request->pengambilan_keputusan,
            'it_proyek' => $request->it_proyek,
            'kontribusi_kelompok' => $request->kontribusi_kelompok,
            'penilaian_dosen' => $request->penilaian_dosen,
            'hasil_akhir' => round($hasil_akhir, 2),
        ]);

        return redirect()->route('nilai-kelompok.index')->with('success', 'Nilai kelompok berhasil diperbarui!');
    }

    /**
     * Menghapus nilai kelompok (set null)
     */
    public function destroy($id)
    {
        $kelompok = Kelompok::findOrFail($id);
        $kelompok->update([
            'pemrograman_web' => null,
            'integrasi_sistem' => null,
            'pengambilan_keputusan' => null,
            'it_proyek' => null,
            'kontribusi_kelompok' => null,
            'penilaian_dosen' => null,
            'hasil_akhir' => null,
        ]);

        return redirect()->route('nilai-kelompok.index')->with('success', 'Nilai kelompok berhasil dihapus!');
    }
}
