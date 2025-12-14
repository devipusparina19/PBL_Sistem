<?php

namespace App\Http\Controllers;

use App\Models\Kelompok;
use App\Models\Nilai;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class NilaiKelompokController extends Controller
{
    /**
     * Menampilkan daftar nilai kelompok
     */
    public function index()
    {
        $kelompoks = Kelompok::with('mahasiswa')->orderBy('nama_kelompok', 'asc')->get();
        return view('nilai_kelompok.index', compact('kelompoks'));
    }

    /**
     * Menampilkan form tambah nilai kelompok
     */
    public function create()
    {
        $kelompoks = Kelompok::with('mahasiswa')->orderBy('nama_kelompok', 'asc')->get();
        return view('nilai_kelompok.create', compact('kelompoks'));
    }

    /**
     * Helper to calculate average grade for a subject for a group
     */
    private function getAverageGradeForSubject($kelompokId, $subjectKeywords)
    {
        // Get all student IDs in the group
        $studentIds = Mahasiswa::where('kelompok_id', $kelompokId)->pluck('id');

        if ($studentIds->isEmpty()) {
            return 0;
        }

        // Get Nilai for these students and the specific subject
        $grades = Nilai::whereIn('mahasiswa_id', $studentIds)
            ->whereHas('mataKuliah', function ($query) use ($subjectKeywords) {
                $query->where(function ($q) use ($subjectKeywords) {
                    foreach ((array)$subjectKeywords as $keyword) {
                        $q->orWhere('nama_mk', 'like', '%' . $keyword . '%');
                    }
                });
            })
            ->with('mataKuliah') // Eager load for accessor
            ->get();

        if ($grades->isEmpty()) {
            return 0;
        }

        // Calculate average using the accessor
        $total = $grades->sum('nilai_akhir'); // Use the accessor
        return $total / $grades->count();
    }

    /**
     * Menyimpan nilai kelompok baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'kelompok_id' => 'required|exists:kelompoks,id_kelompok',
            'penilaian_dosen' => 'required|numeric|min:0|max:100',
        ]);

        $kelompokId = $request->kelompok_id;

        // 1. Pemrograman Web (Average of 'PWL' or 'Pemrograman Web')
        $pemrogramanWeb = $this->getAverageGradeForSubject($kelompokId, ['pwl', 'pemrograman web']);

        // 2. Integrasi Sistem
        $integrasiSistem = $this->getAverageGradeForSubject($kelompokId, ['integrasi sistem']);

        // 3. Pengambilan Keputusan
        $pengambilanKeputusan = $this->getAverageGradeForSubject($kelompokId, ['pengambilan keputusan']);

        // 4. IT Proyek
        $itProyek = $this->getAverageGradeForSubject($kelompokId, ['it project', 'it proyek']);

        // 5. Kontribusi Kelompok (Average of 'Kontribusi' from IT Project grades ideally, or similar)
        // For now, let's fetch the average of 'kontribusi' column from IT Project grades
        // Reuse logic but specifically pick 'kontribusi' column.
         $studentIds = Mahasiswa::where('kelompok_id', $kelompokId)->pluck('id');
         $kontribusiGrades = Nilai::whereIn('mahasiswa_id', $studentIds)
            ->whereHas('mataKuliah', function ($q) {
                $q->where('nama_mk', 'like', '%it project%')
                  ->orWhere('nama_mk', 'like', '%it proyek%');
            })->pluck('kontribusi');
        
        $kontribusiKelompok = $kontribusiGrades->isEmpty() ? 0 : $kontribusiGrades->avg();


        // Hitung hasil akhir (rata-rata 6 komponen)
        $hasil_akhir = (
            $pemrogramanWeb +
            $integrasiSistem +
            $pengambilanKeputusan +
            $itProyek +
            $kontribusiKelompok +
            $request->penilaian_dosen
        ) / 6;

        $kelompok = Kelompok::findOrFail($kelompokId);
        $kelompok->update([
            'pemrograman_web' => round($pemrogramanWeb, 2),
            'integrasi_sistem' => round($integrasiSistem, 2),
            'pengambilan_keputusan' => round($pengambilanKeputusan, 2),
            'it_proyek' => round($itProyek, 2),
            'kontribusi_kelompok' => round($kontribusiKelompok, 2),
            'penilaian_dosen' => $request->penilaian_dosen,
            'hasil_akhir' => round($hasil_akhir, 2),
        ]);

        return redirect()->route('nilai_kelompok.index')->with('success', 'Nilai kelompok berhasil disimpan! Data diambil otomatis dari penilaian mata kuliah.');
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
            'penilaian_dosen' => 'required|numeric|min:0|max:100',
        ]);
        
        $kelompokId = $id; // ID here is kelompok ID based on routes usually? Wait, route resource.
        // Route::resource('nilai_kelompok', ...);
        // ID passed is likely the Kelompok ID since we are editing a Kelompok's grades.
        
        // Recalculate everything to ensure up-to-date data
        $pemrogramanWeb = $this->getAverageGradeForSubject($kelompokId, ['pwl', 'pemrograman web']);
        $integrasiSistem = $this->getAverageGradeForSubject($kelompokId, ['integrasi sistem']);
        $pengambilanKeputusan = $this->getAverageGradeForSubject($kelompokId, ['pengambilan keputusan']);
        $itProyek = $this->getAverageGradeForSubject($kelompokId, ['it project', 'it proyek']);
        
        $studentIds = Mahasiswa::where('kelompok_id', $kelompokId)->pluck('id');
        $kontribusiGrades = Nilai::whereIn('mahasiswa_id', $studentIds)
            ->whereHas('mataKuliah', function ($q) {
                $q->where('nama_mk', 'like', '%it project%')
                  ->orWhere('nama_mk', 'like', '%it proyek%');
            })->pluck('kontribusi');
        $kontribusiKelompok = $kontribusiGrades->isEmpty() ? 0 : $kontribusiGrades->avg();

        // Hitung ulang hasil akhir
        $hasil_akhir = (
            $pemrogramanWeb +
            $integrasiSistem +
            $pengambilanKeputusan +
            $itProyek +
            $kontribusiKelompok +
            $request->penilaian_dosen
        ) / 6;

        $kelompok = Kelompok::findOrFail($id);
        $kelompok->update([
            'pemrograman_web' => round($pemrogramanWeb, 2),
            'integrasi_sistem' => round($integrasiSistem, 2),
            'pengambilan_keputusan' => round($pengambilanKeputusan, 2),
            'it_proyek' => round($itProyek, 2),
            'kontribusi_kelompok' => round($kontribusiKelompok, 2),
            'penilaian_dosen' => $request->penilaian_dosen,
            'hasil_akhir' => round($hasil_akhir, 2),
        ]);

        return redirect()->route('nilai_kelompok.index')->with('success', 'Nilai kelompok berhasil diperbarui!');
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

        return redirect()->route('nilai_kelompok.index')->with('success', 'Nilai kelompok berhasil dihapus!');
    }
}
