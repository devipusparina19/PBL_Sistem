<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\Nilai;
use Illuminate\Http\Request;

class NilaiController extends Controller
{
    /**
     * Menampilkan daftar nilai (khusus dosen atau admin)
     */
    public function index()
    {
        $mahasiswa = Mahasiswa::orderBy('nama', 'asc')->get();
        $nilai = Nilai::with('mahasiswa')->orderBy('created_at', 'desc')->get();

        return view('nilai.index', compact('mahasiswa', 'nilai'));
    }

    /**
     * Menampilkan form input nilai baru
     */
    public function create()
    {
        $mahasiswa = Mahasiswa::orderBy('nama', 'asc')->get();
        return view('nilai.create', compact('mahasiswa'));
    }

    /**
     * Menyimpan data nilai ke database
     */
    public function store(Request $request)
    {
        $request->validate([
            'mahasiswa_id' => 'required|exists:mahasiswas,id',
            'pemrograman_web' => 'required|numeric|min:0|max:100',
            'integrasi_sistem' => 'required|numeric|min:0|max:100',
            'pengambilan_keputusan' => 'required|numeric|min:0|max:100',
            'it_proyek' => 'required|numeric|min:0|max:100',
            'kontribusi_kelompok' => 'required|numeric|min:0|max:100',
            'penilaian_sejawat' => 'required|numeric|min:0|max:100',
        ]);

        // Hitung hasil akhir (rata-rata)
        $hasil_akhir = (
            $request->pemrograman_web +
            $request->integrasi_sistem +
            $request->pengambilan_keputusan +
            $request->it_proyek +
            $request->kontribusi_kelompok +
            $request->penilaian_sejawat
        ) / 6;

        Nilai::create([
            'mahasiswa_id' => $request->mahasiswa_id,
            'pemrograman_web' => $request->pemrograman_web,
            'integrasi_sistem' => $request->integrasi_sistem,
            'pengambilan_keputusan' => $request->pengambilan_keputusan,
            'it_proyek' => $request->it_proyek,
            'kontribusi_kelompok' => $request->kontribusi_kelompok,
            'penilaian_sejawat' => $request->penilaian_sejawat,
            'hasil_akhir' => $hasil_akhir,
        ]);

        return redirect()->route('nilai.index')->with('success', 'Nilai mahasiswa berhasil disimpan!');
    }

    /**
     * Menampilkan form edit nilai
     */
    public function edit($id)
    {
        $nilai = Nilai::findOrFail($id);
        $mahasiswa = Mahasiswa::orderBy('nama', 'asc')->get();

        return view('nilai.edit', compact('nilai', 'mahasiswa'));
    }

    /**
     * Mengupdate data nilai
     */
    public function update(Request $request, $id)
    {
        $nilai = Nilai::findOrFail($id);

        $request->validate([
            'mahasiswa_id' => 'required|exists:mahasiswas,id',
            'pemrograman_web' => 'required|numeric|min:0|max:100',
            'integrasi_sistem' => 'required|numeric|min:0|max:100',
            'pengambilan_keputusan' => 'required|numeric|min:0|max:100',
            'it_proyek' => 'required|numeric|min:0|max:100',
            'kontribusi_kelompok' => 'required|numeric|min:0|max:100',
            'penilaian_sejawat' => 'required|numeric|min:0|max:100',
        ]);

        $hasil_akhir = (
            $request->pemrograman_web +
            $request->integrasi_sistem +
            $request->pengambilan_keputusan +
            $request->it_proyek +
            $request->kontribusi_kelompok +
            $request->penilaian_sejawat
        ) / 6;

        $nilai->update([
            'mahasiswa_id' => $request->mahasiswa_id,
            'pemrograman_web' => $request->pemrograman_web,
            'integrasi_sistem' => $request->integrasi_sistem,
            'pengambilan_keputusan' => $request->pengambilan_keputusan,
            'it_proyek' => $request->it_proyek,
            'kontribusi_kelompok' => $request->kontribusi_kelompok,
            'penilaian_sejawat' => $request->penilaian_sejawat,
            'hasil_akhir' => $hasil_akhir,
        ]);

        return redirect()->route('nilai.index')->with('success', 'Nilai berhasil diperbarui!');
    }

    /**
     * Menghapus data nilai
     */
    public function destroy($id)
    {
        $nilai = Nilai::findOrFail($id);
        $nilai->delete();

        return redirect()->route('nilai.index')->with('success', 'Data nilai berhasil dihapus!');
    }
}
