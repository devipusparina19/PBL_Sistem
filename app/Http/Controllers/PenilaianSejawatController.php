<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\PenilaianSejawat;

class PenilaianSejawatController extends Controller
{
    /**
     * Halaman utama isi penilaian.
     */
    public function index()
    {
        $user = Auth::user();

        $temanKelompok = User::where('kelas', $user->kelas)
            ->where('role_kelompok', $user->role_kelompok)
            ->where('id', '!=', $user->id)
            ->get();

        return view('penilaian_sejawat.index', compact('temanKelompok'));
    }

    /**
     * Simpan hasil penilaian sejawat.
     */
    public function store(Request $request)
    {
        $request->validate([
            'penilaian' => 'required|array',
        ]);

        foreach ($request->penilaian as $target_id => $nilai) {
            PenilaianSejawat::updateOrCreate(
                [
                    'penilai_id' => Auth::id(),
                    'dinilai_id' => $target_id,
                ],
                [
                    'nilai' => $nilai,
                ]
            );
        }

        return redirect()->route('penilaian.sejawat.index')->with('success', 'Penilaian berhasil disimpan!');
    }

    /**
     * Detail satu teman.
     */
    public function show($id)
    {
        $teman = User::findOrFail($id);
        $nilai = PenilaianSejawat::where('penilai_id', Auth::id())
            ->where('dinilai_id', $id)
            ->first();

        return view('penilaian_sejawat.show', compact('teman', 'nilai'));
    }

    /**
     * Rekap nilai rata-rata.
     */
    public function rekap()
    {
        $user = Auth::user();

        $rekap = User::where('kelas', $user->kelas)
            ->where('role_kelompok', $user->role_kelompok)
            ->select('id', 'name', 'kelas', 'role_kelompok')
            ->get()
            ->map(function ($u) {
                $u->rata_nilai = PenilaianSejawat::where('dinilai_id', $u->id)->avg('nilai') ?? 0;
                return $u;
            });

        return view('penilaian_sejawat.rekap', compact('rekap'));
    }

    /**
     * Edit nilai.
     */
    public function edit($id)
    {
        $teman = User::findOrFail($id);
        $nilai = PenilaianSejawat::where('penilai_id', Auth::id())
            ->where('dinilai_id', $id)
            ->first();

        return view('penilaian_sejawat.edit', compact('teman', 'nilai'));
    }

    /**
     * Update nilai.
     */
    public function update(Request $request, $id)
    {
        $request->validate(['nilai' => 'required|integer|min:1|max:100']);

        PenilaianSejawat::updateOrCreate(
            [
                'penilai_id' => Auth::id(),
                'dinilai_id' => $id,
            ],
            ['nilai' => $request->nilai]
        );

        return redirect()->route('penilaian.sejawat.index')->with('success', 'Nilai berhasil diperbarui!');
    }
}
