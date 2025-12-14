<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\Nilai;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RankingController extends Controller
{
    public function index()
    {
        $mahasiswas = Mahasiswa::with('kelompok')->get();

        $rankings = $mahasiswas->map(function ($mhs) {
            // 1. Nilai Akademik
            // Ambil nilai dari mata kuliah tertentu
            $grades = Nilai::where('mahasiswa_id', $mhs->id)
                ->with('mataKuliah')
                ->get();

            $academicSum = 0;
            $academicCount = 0;
            $subjects = ['it project', 'it proyek', 'pengambilan keputusan', 'integrasi sistem', 'pwl', 'pemrograman web'];

            foreach ($grades as $grade) {
                if ($grade->mataKuliah) {
                    $mkName = strtolower($grade->mataKuliah->nama_mk);
                    foreach ($subjects as $subject) {
                        if (strpos($mkName, $subject) !== false) {
                            $academicSum += $grade->nilai_akhir; // Menggunakan accessor nilai_akhir
                            $academicCount++;
                            break; // Count subject once
                        }
                    }
                }
            }

            $scoreAcademic = $academicCount > 0 ? ($academicSum / $academicCount) : 0;

            // 2. Nilai Proyek
            // Ambil dari Nilai Kelompok
            $scoreProject = $mhs->kelompok ? $mhs->kelompok->hasil_akhir : 0;

            // 3. Nilai Sejawat (Peer Assessment)
            // Cari User berdasarkan NIM
            $user = User::where('nim_nip', $mhs->nim)->first();
            $scorePeer = 0;
            if ($user) {
                $scorePeer = DB::table('penilaian_sejawat')
                    ->where('dinilai_id', $user->id)
                    ->avg('nilai');
            }
            $scorePeer = $scorePeer ? round($scorePeer, 2) : 0;

            // Total Score (Rata-rata dari 3 komponen)
            // Jika salah satu komponen 0, apakah tetap dibagi 3? 
            // Asumsi: Tetap dibagi 3 untuk fairness, atau sesuai bobot.
            // Formula: (Akademik + Proyek + Sejawat) / 3
            
            $totalScore = ($scoreAcademic + $scoreProject + $scorePeer) / 3;

            return [
                'nim' => $mhs->nim,
                'nama' => $mhs->nama,
                'kelas' => $mhs->kelas,
                'kelompok' => $mhs->kelompok ? $mhs->kelompok->nama_kelompok : '-',
                'score_academic' => round($scoreAcademic, 2),
                'score_project' => round($scoreProject, 2),
                'score_peer' => round($scorePeer, 2),
                'total_score' => round($totalScore, 2),
            ];
        })->sortByDesc('total_score')->values();

        return view('ranking.index', compact('rankings'));
    }
}
