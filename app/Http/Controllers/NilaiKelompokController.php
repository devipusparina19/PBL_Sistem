<?php

namespace App\Http\Controllers;

use App\Models\Kelompok;
use App\Models\Nilai;
use App\Models\Mahasiswa;
use App\Models\Setting;
use Illuminate\Http\Request;

class NilaiKelompokController extends Controller
{
    /**
     * Menampilkan daftar nilai kelompok
     */
    public function index()
    {
        $kelompoks = Kelompok::with('mahasiswa')->orderBy('nama_kelompok', 'asc')->get();
        $settings = Setting::all()->keyBy('key');
        return view('nilai_kelompok.index', compact('kelompoks', 'settings'));
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
     * Helper: Hitung nilai kelompok berdasarkan milestone
     */
    private function calculateMilestoneGrade($kelompok)
    {
        // Ambil settings
        $bobotMilestone = (int) Setting::get('bobot_milestone', 50);
        $bobotAnggota = (int) Setting::get('bobot_nilai_anggota', 50);
        $minMilestone = (int) Setting::get('minimum_milestone', 1);
        
        // Hitung nilai milestone (rata-rata nilai_akhir dari milestone yang disetujui)
        $milestoneApproved = $kelompok->milestones()
            ->where('status', 'disetujui')
            ->whereNotNull('nilai_akhir')
            ->get();
        
        if ($milestoneApproved->count() < $minMilestone) {
            return [
                'error' => "Minimum {$minMilestone} milestone harus disetujui.",
                'milestone_count' => $milestoneApproved->count(),
            ];
        }
        
        $nilaiMilestone = $milestoneApproved->avg('nilai_akhir');
        
        // Hitung rata-rata nilai anggota kelompok
        $studentIds = Mahasiswa::where('kelompok_id', $kelompok->id_kelompok)->pluck('id');
        $nilaiAnggota = Nilai::whereIn('mahasiswa_id', $studentIds)->get();
        $rataRataAnggota = $nilaiAnggota->isEmpty() ? 0 : $nilaiAnggota->avg('hasil_proyek');
        
        // Hitung hasil akhir dengan bobot dinamis
        $hasilAkhir = ($nilaiMilestone * $bobotMilestone / 100) + ($rataRataAnggota * $bobotAnggota / 100);
        
        return [
            'nilai_milestone_avg' => round($nilaiMilestone, 2),
            'milestone_approved_count' => $milestoneApproved->count(),
            'nilai_rata_anggota' => round($rataRataAnggota, 2),
            'hasil_akhir' => round($hasilAkhir, 2),
        ];
    }

    /**
 * Menyimpan nilai kelompok baru (berbasis milestone)
 */
public function store(Request $request)
{
    $request->validate([
        'kelompok_id' => 'required|exists:kelompok,id_kelompok',
        'penilaian_dosen' => 'required|numeric|min:0|max:100',
        'kontribusi_kelompok' => 'required|numeric|min:0|max:100',
        'hasil_akhir' => 'required|numeric|min:0|max:100',
    ]);

    $kelompok = Kelompok::findOrFail($request->kelompok_id);
    $kelompokId = $kelompok->id_kelompok;
    
    // Calculate average grades from mata kuliah
    $pemrogramanWeb = $this->getAverageGradeForSubject($kelompokId, ['pwl', 'pemrograman web']);
    $integrasiSistem = $this->getAverageGradeForSubject($kelompokId, ['integrasi sistem']);
    $pengambilanKeputusan = $this->getAverageGradeForSubject($kelompokId, ['pengambilan keputusan']);
    $itProyek = $this->getAverageGradeForSubject($kelompokId, ['it project', 'it proyek']);

    // Calculate nilai rata-rata anggota (for kelompok ranking)
    $studentIds = Mahasiswa::where('kelompok_id', $kelompokId)->pluck('id');
    $nilaiRataAnggota = 0;
    if ($studentIds->count() > 0) {
        $allGrades = Nilai::whereIn('mahasiswa_id', $studentIds)->pluck('nilai_akhir');
        $nilaiRataAnggota = $allGrades->isEmpty() ? 0 : $allGrades->avg();
    }
    
    // Update nilai kelompok
    $kelompok->update([
        'pemrograman_web' => round($pemrogramanWeb, 2),
        'integrasi_sistem' => round($integrasiSistem, 2),
        'pengambilan_keputusan' => round($pengambilanKeputusan, 2),
        'it_proyek' => round($itProyek, 2),
        'kontribusi_kelompok' => $request->kontribusi_kelompok,
        'penilaian_dosen' => $request->penilaian_dosen,
        'hasil_akhir' => $request->hasil_akhir,
        'nilai_rata_anggota' => round($nilaiRataAnggota, 2),
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
 * Mengupdate nilai kelompok
 */
public function update(Request $request, $id)
{
    $request->validate([
        'penilaian_dosen' => 'required|numeric|min:0|max:100',
        'kontribusi_kelompok' => 'required|numeric|min:0|max:100',
        'hasil_akhir' => 'required|numeric|min:0|max:100',
    ]);
    
    $kelompokId = $id;
    
    // Calculate average grades from mata kuliah
    $pemrogramanWeb = $this->getAverageGradeForSubject($kelompokId, ['pwl', 'pemrograman web']);
    $integrasiSistem = $this->getAverageGradeForSubject($kelompokId, ['integrasi sistem']);
    $pengambilanKeputusan = $this->getAverageGradeForSubject($kelompokId, ['pengambilan keputusan']);
    $itProyek = $this->getAverageGradeForSubject($kelompokId, ['it project', 'it proyek']);

    // Calculate nilai rata-rata anggota (for kelompok ranking)
    $studentIds = Mahasiswa::where('kelompok_id', $kelompokId)->pluck('id');
    $nilaiRataAnggota = 0;
    if ($studentIds->count() > 0) {
        $allGrades = Nilai::whereIn('mahasiswa_id', $studentIds)->pluck('nilai_akhir');
        $nilaiRataAnggota = $allGrades->isEmpty() ? 0 : $allGrades->avg();
    }

    $kelompok = Kelompok::findOrFail($id);
    $kelompok->update([
        'pemrograman_web' => round($pemrogramanWeb, 2),
        'integrasi_sistem' => round($integrasiSistem, 2),
        'pengambilan_keputusan' => round($pengambilanKeputusan, 2),
        'it_proyek' => round($itProyek, 2),
        'kontribusi_kelompok' => $request->kontribusi_kelompok,
        'penilaian_dosen' => $request->penilaian_dosen,
        'hasil_akhir' => $request->hasil_akhir,
        'nilai_rata_anggota' => round($nilaiRataAnggota, 2),
    ]);

    return redirect()->route('nilai_kelompok.index')->with('success', 'Nilai kelompok berhasil diperbarui!');
}    

    /**
     * Update pengaturan bobot penilaian kelompok
     */
    public function updateSettings(Request $request)
    {
        $request->validate([
            'bobot_milestone' => 'required|numeric|min:0|max:100',
            'bobot_nilai_anggota' => 'required|numeric|min:0|max:100',
            'minimum_milestone' => 'required|integer|min:1',
            'bonus_per_minggu' => 'required|numeric|min:0|max:20',
            'penalty_per_minggu' => 'required|numeric|min:0|max:20',
        ]);
        
        Setting::set('bobot_milestone', $request->bobot_milestone);
        Setting::set('bobot_nilai_anggota', $request->bobot_nilai_anggota);
        Setting::set('minimum_milestone', $request->minimum_milestone);
        Setting::set('bonus_per_minggu', $request->bonus_per_minggu);
        Setting::set('penalty_per_minggu', $request->penalty_per_minggu);
        
        return redirect()->route('nilai_kelompok.index')
            ->with('success', 'Pengaturan bobot berhasil disimpan!');
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
