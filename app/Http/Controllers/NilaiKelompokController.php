<?php

namespace App\Http\Controllers;

use App\Models\Kelompok;
use App\Models\NilaiKelompok;
use Illuminate\Http\Request;

class NilaiKelompokController extends Controller
{
    /**
     * Menampilkan daftar nilai kelompok
     */
    public function index()
    {
        // Data lama (nilai berada di tabel kelompok)
        $kelompoks = Kelompok::with('mahasiswas')->orderBy('nama_kelompok', 'asc')->get();

        // Data baru (nilai ada di tabel nilai_kelompoks)
        $data = NilaiKelompok::with('kelompok')->get();

        return view('nilai_kelompok.index', compact('kelompoks', 'data'));
    }

    /**
     * Menampilkan form input nilai kelompok
     */
    public function create()
    {
        $kelompoks = Kelompok::with('mahasiswas')->orderBy('nama_kelompok', 'asc')->get();
        return view('nilai_kelompok.create', compact('kelompoks'));
    }

    /**
     * Menyimpan nilai kelompok (tabel kelompok + tabel nilai_kelompoks)
     */
    public function store(Request $request)
    {
        // FIX: exists diarahkan ke tabel 'kelompok', bukan 'kelompoks'
        $request->validate([
            'kelompok_id' => 'required|exists:kelompok,id_kelompok',

            // nilai lama (tabel kelompok)
            'pemrograman_web' => 'required|numeric|min:0|max:100',
            'integrasi_sistem' => 'required|numeric|min:0|max:100',
            'pengambilan_keputusan' => 'required|numeric|min:0|max:100',
            'it_proyek' => 'required|numeric|min:0|max:100',
            'kontribusi_kelompok' => 'required|numeric|min:0|max:100',
            'penilaian_dosen' => 'required|numeric|min:0|max:100',

            // nilai baru (tabel nilai_kelompoks)
            'presentasi' => 'required|numeric|min:0|max:100',
            'laporan' => 'required|numeric|min:0|max:100',
            'kerjasama' => 'required|numeric|min:0|max:100',
        ]);

        /*
        |--------------------------------------------------------------------------
        | 1. Simpan nilai ke tabel kelompok
        |--------------------------------------------------------------------------
        */
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

        /*
        |--------------------------------------------------------------------------
        | 2. Simpan nilai ke tabel nilai_kelompoks
        |--------------------------------------------------------------------------
        */
        $nilai_akhir = ($request->presentasi + $request->laporan + $request->kerjasama) / 3;

        NilaiKelompok::create([
            'kelompok_id' => $request->kelompok_id,
            'presentasi' => $request->presentasi,
            'laporan' => $request->laporan,
            'kerjasama' => $request->kerjasama,
            'nilai_akhir' => round($nilai_akhir, 2),
        ]);

        return redirect()->route('nilai_kelompok.index')
            ->with('success', 'Nilai kelompok berhasil disimpan!');
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
     * Mengupdate nilai kelompok (tabel kelompok)
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

        return redirect()->route('nilai_kelompok.index')
            ->with('success', 'Nilai kelompok berhasil diperbarui!');
    }

    /**
     * Menghapus nilai di tabel kelompok
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

        return redirect()->route('nilai_kelompok.index')
            ->with('success', 'Nilai kelompok berhasil dihapus!');
    }
}
