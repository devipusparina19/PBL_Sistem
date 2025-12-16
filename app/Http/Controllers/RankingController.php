<?php

namespace App\Http\Controllers;

use App\Helpers\AhpSawCalculator;
use App\Models\Mahasiswa;
use App\Models\Nilai;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RankingController extends Controller
{
    /**
     * Display ranking with AHP/SAW calculation
     */
    public function index(Request $request)
    {
        // Get ranking method preference
        $method = $request->get('method', Setting::get('ranking_method', 'saw'));
        
        // Get SAW weights from settings
        $weights = $this->getWeights();
        
        // Get mahasiswa data with individual scores
        $mahasiswas = Mahasiswa::with('kelompok')->get();
        
        $data = $mahasiswas->map(function ($mhs) {
            return $this->getMahasiswaScores($mhs);
        })->toArray();
        
        // Apply SAW or simple average calculation
        if ($method === 'saw') {
            $rankings = AhpSawCalculator::calculateSaw($data, $weights);
        } else {
            $rankings = $this->calculateSimpleAverage($data);
        }
        
        return view('ranking.index', [
            'rankings' => $rankings,
            'weights' => $weights,
            'method' => $method,
            'criteria' => AhpSawCalculator::getDefaultCriteria(),
        ]);
    }

    /**
     * Get individual scores for a mahasiswa
     */
    private function getMahasiswaScores(Mahasiswa $mhs): array
    {
        $grades = Nilai::where('mahasiswa_id', $mhs->id)
            ->with('mataKuliah')
            ->get();

        // Initialize scores - 7 kriteria
        $scores = [
            'nim' => $mhs->nim,
            'nama' => $mhs->nama,
            'kelas' => $mhs->kelas,
            'kelompok' => $mhs->kelompok ? $mhs->kelompok->nama_kelompok : '-',
            'pwl' => 0,              // Kriteria 1 - Dinamis
            'integrasi' => 0,        // Kriteria 2 - Dinamis
            'tpk' => 0,              // Kriteria 3 - Dinamis
            'it_project' => 0,       // Kriteria 4 - Dinamis
            'kontribusi' => 0,       // Kriteria 5 - Tetap (Kontribusi Kelompok)
            'sejawat' => 0,          // Kriteria 6 - Tetap (Penilaian Teman Sejawat)
            'proyek' => 0,           // Kriteria 7 - Tetap (Hasil Akhir Proyek)
        ];

        // Map grades to criteria (4 mata kuliah dinamis)
        foreach ($grades as $grade) {
            if (!$grade->mataKuliah) continue;
            
            $mkName = strtolower($grade->mataKuliah->nama_mk);
            $nilai = $grade->nilai_akhir ?? 0;
            
            if (strpos($mkName, 'pwl') !== false || strpos($mkName, 'pemrograman web') !== false) {
                $scores['pwl'] = $nilai;
            } elseif (strpos($mkName, 'integrasi') !== false) {
                $scores['integrasi'] = $nilai;
            } elseif (strpos($mkName, 'pengambilan keputusan') !== false || strpos($mkName, 'tpk') !== false) {
                $scores['tpk'] = $nilai;
            } elseif (strpos($mkName, 'it project') !== false || strpos($mkName, 'it proyek') !== false) {
                $scores['it_project'] = $nilai;
            }
        }

        // Kriteria 5 - Kontribusi Kelompok (dari tabel kelompok)
        $scores['kontribusi'] = $mhs->kelompok ? ($mhs->kelompok->kontribusi_kelompok ?? 0) : 0;

        // Kriteria 6 - Penilaian Teman Sejawat
        $user = User::where('nim_nip', $mhs->nim)->first();
        if ($user) {
            $peerScore = DB::table('penilaian_sejawat')
                ->where('dinilai_id', $user->id)
                ->avg('nilai');
            $scores['sejawat'] = $peerScore ? round($peerScore, 2) : 0;
        }

        // Kriteria 7 - Hasil Akhir Proyek (dari tabel kelompok)
        $scores['proyek'] = $mhs->kelompok ? ($mhs->kelompok->hasil_akhir ?? 0) : 0;

        return $scores;
    }

    /**
     * Calculate simple average (old method)
     */
    private function calculateSimpleAverage(array $data): array
    {
        foreach ($data as $key => $item) {
            $academicSum = $item['it_project'] + $item['pwl'] + $item['integrasi'] + $item['tpk'];
            $academicCount = 0;
            if ($item['it_project'] > 0) $academicCount++;
            if ($item['pwl'] > 0) $academicCount++;
            if ($item['integrasi'] > 0) $academicCount++;
            if ($item['tpk'] > 0) $academicCount++;
            
            $academicAvg = $academicCount > 0 ? $academicSum / $academicCount : 0;
            
            $data[$key]['saw_score'] = round(
                ($academicAvg + $item['proyek'] + $item['sejawat']) / 3, 
                2
            );
        }

        // Sort by score
        usort($data, fn($a, $b) => $b['saw_score'] <=> $a['saw_score']);
        
        // Add rank
        foreach ($data as $key => $item) {
            $data[$key]['rank'] = $key + 1;
        }

        return $data;
    }

    /**
     * Get weights from settings
     */
    private function getWeights(): array
    {
        return [
            'pwl' => (int) Setting::get('saw_weight_pwl', 15),
            'integrasi' => (int) Setting::get('saw_weight_integrasi', 15),
            'tpk' => (int) Setting::get('saw_weight_tpk', 15),
            'it_project' => (int) Setting::get('saw_weight_it_project', 15),
            'kontribusi' => (int) Setting::get('saw_weight_kontribusi', 15),
            'sejawat' => (int) Setting::get('saw_weight_sejawat', 10),
            'proyek' => (int) Setting::get('saw_weight_proyek', 15),
        ];
    }

    /**
     * Update SAW weights
     */
    public function updateWeights(Request $request)
    {
        $request->validate([
            'pwl' => 'required|numeric|min:0|max:100',
            'integrasi' => 'required|numeric|min:0|max:100',
            'tpk' => 'required|numeric|min:0|max:100',
            'it_project' => 'required|numeric|min:0|max:100',
            'kontribusi' => 'required|numeric|min:0|max:100',
            'sejawat' => 'required|numeric|min:0|max:100',
            'proyek' => 'required|numeric|min:0|max:100',
        ]);

        // Validate total = 100
        $total = $request->pwl + $request->integrasi + $request->tpk + 
                 $request->it_project + $request->kontribusi + 
                 $request->sejawat + $request->proyek;
        
        if ($total != 100) {
            return back()->with('error', 'Total bobot harus = 100%. Saat ini: ' . $total . '%');
        }

        // Save weights (7 kriteria)
        Setting::set('saw_weight_pwl', $request->pwl);
        Setting::set('saw_weight_integrasi', $request->integrasi);
        Setting::set('saw_weight_tpk', $request->tpk);
        Setting::set('saw_weight_it_project', $request->it_project);
        Setting::set('saw_weight_kontribusi', $request->kontribusi);
        Setting::set('saw_weight_sejawat', $request->sejawat);
        Setting::set('saw_weight_proyek', $request->proyek);

        return back()->with('success', 'Bobot SAW berhasil diperbarui!');
    }

    /**
     * Show AHP matrix configuration page
     */
    public function ahpConfig()
    {
        $criteria = AhpSawCalculator::getDefaultCriteria();
        $currentWeights = $this->getWeights();
        
        return view('ranking.ahp_config', [
            'criteria' => $criteria,
            'currentWeights' => $currentWeights,
            'scale' => AhpSawCalculator::SCALE,
        ]);
    }

    /**
     * Calculate weights from AHP matrix
     */
    public function calculateAhp(Request $request)
    {
        $criteria = array_keys(AhpSawCalculator::getDefaultCriteria());
        $n = count($criteria);
        
        // Build matrix from form input
        $upperTriangle = [];
        for ($i = 0; $i < $n; $i++) {
            for ($j = $i + 1; $j < $n; $j++) {
                $key = "ahp_{$i}_{$j}";
                $upperTriangle[$i][$j] = (float) $request->get($key, 1);
            }
        }
        
        // Build full reciprocal matrix
        $matrix = AhpSawCalculator::buildReciprocalMatrix($upperTriangle, $n);
        
        // Calculate AHP weights
        $result = AhpSawCalculator::calculateAhpWeights($matrix);
        
        if (!$result['consistent']) {
            return back()->with('error', 
                'Matriks tidak konsisten! CR = ' . ($result['cr'] * 100) . '% (harus ≤ 10%). ' .
                'Silakan periksa kembali perbandingan Anda.'
            );
        }
        
        // Convert weights to percentage (total = 100)
        $weights = $result['weights'];
        $percentWeights = [];
        foreach ($criteria as $i => $key) {
            $percentWeights[$key] = round($weights[$i] * 100, 2);
        }
        
        // Save to settings
        Setting::set('saw_weight_it_project', $percentWeights['it_project']);
        Setting::set('saw_weight_pwl', $percentWeights['pwl']);
        Setting::set('saw_weight_integrasi', $percentWeights['integrasi']);
        Setting::set('saw_weight_tpk', $percentWeights['tpk']);
        Setting::set('saw_weight_proyek', $percentWeights['proyek']);
        Setting::set('saw_weight_sejawat', $percentWeights['sejawat']);
        
        return back()->with('success', 
            'Bobot AHP berhasil dihitung dan disimpan! CR = ' . ($result['cr'] * 100) . '% (Konsisten)'
        )->with('ahp_result', $result);
    }

    /**
     * Export ranking data to Excel (CSV format)
     */
    public function exportExcel()
    {
        $mahasiswas = Mahasiswa::with('kelompok')->get();
        $weights = $this->getWeights();
        
        $data = $mahasiswas->map(fn($mhs) => $this->getMahasiswaScores($mhs))->filter(fn($item) => $item['nama'] !== null);
        
        $rankings = AhpSawCalculator::calculateSawScores($data->toArray(), $weights);
        usort($rankings, fn($a, $b) => $b['saw_score'] <=> $a['saw_score']);
        
        $filename = 'ranking_mahasiswa_' . date('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($rankings) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM
            
            // Header
            fputcsv($file, ['Peringkat', 'NIM', 'Nama', 'Kelas', 'Kelompok', 'PWL', 'Integrasi', 'TPK', 'IT Projek', 'Kontribusi', 'Sejawat', 'Proyek', 'Skor Akhir']);
            
            // Data
            $rank = 1;
            foreach ($rankings as $row) {
                fputcsv($file, [
                    $rank++,
                    $row['nim'],
                    $row['nama'],
                    $row['kelas'],
                    $row['kelompok'],
                    $row['pwl'],
                    $row['integrasi'],
                    $row['tpk'],
                    $row['it_project'],
                    $row['kontribusi'],
                    $row['sejawat'],
                    $row['proyek'],
                    number_format($row['saw_score'], 2)
                ]);
            }
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export ranking data to PDF
     */
    public function exportPdf()
    {
        $mahasiswas = Mahasiswa::with('kelompok')->get();
        $weights = $this->getWeights();
        
        $data = $mahasiswas->map(fn($mhs) => $this->getMahasiswaScores($mhs))->filter(fn($item) => $item['nama'] !== null);
        
        $rankings = AhpSawCalculator::calculateSawScores($data->toArray(), $weights);
        usort($rankings, fn($a, $b) => $b['saw_score'] <=> $a['saw_score']);
        
        // Generate HTML for PDF
        $html = '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Ranking Mahasiswa</title>
        <style>
            body { font-family: Arial, sans-serif; font-size: 12px; }
            h1 { text-align: center; color: #333; }
            table { width: 100%; border-collapse: collapse; margin-top: 20px; }
            th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
            th { background-color: #4CAF50; color: white; }
            tr:nth-child(even) { background-color: #f2f2f2; }
            .date { text-align: right; color: #666; margin-bottom: 10px; }
        </style>
        </head><body>
        <h1>Ranking Mahasiswa</h1>
        <p class="date">Dicetak: ' . date('d/m/Y H:i:s') . '</p>
        <table>
            <thead>
                <tr>
                    <th>#</th><th>NIM</th><th>Nama</th><th>Kelas</th><th>Kelompok</th>
                    <th>PWL</th><th>Integrasi</th><th>TPK</th><th>IT Projek</th>
                    <th>Kontribusi</th><th>Sejawat</th><th>Proyek</th><th>Skor</th>
                </tr>
            </thead>
            <tbody>';
        
        $rank = 1;
        foreach ($rankings as $row) {
            $html .= '<tr>
                <td>' . $rank++ . '</td>
                <td>' . $row['nim'] . '</td>
                <td>' . $row['nama'] . '</td>
                <td>' . $row['kelas'] . '</td>
                <td>' . $row['kelompok'] . '</td>
                <td>' . $row['pwl'] . '</td>
                <td>' . $row['integrasi'] . '</td>
                <td>' . $row['tpk'] . '</td>
                <td>' . $row['it_project'] . '</td>
                <td>' . $row['kontribusi'] . '</td>
                <td>' . $row['sejawat'] . '</td>
                <td>' . $row['proyek'] . '</td>
                <td><strong>' . number_format($row['saw_score'], 2) . '</strong></td>
            </tr>';
        }
        
        $html .= '</tbody></table></body></html>';
        
        return response($html)
            ->header('Content-Type', 'text/html')
            ->header('Content-Disposition', 'attachment; filename="ranking_mahasiswa_' . date('Y-m-d') . '.html"');
    }
}
