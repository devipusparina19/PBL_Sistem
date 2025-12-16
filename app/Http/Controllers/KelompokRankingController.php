<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kelompok;
use App\Models\Setting;
use App\Helpers\AhpSawCalculator;

class KelompokRankingController extends Controller
{
    /**
     * Get default criteria for kelompok ranking
     */
    public static function getKelompokCriteria(): array
    {
        return [
            'milestone' => 'Nilai Milestone',
            'rata_anggota' => 'Nilai Rata-rata Anggota',
            'kontribusi' => 'Kontribusi Kelompok',
            'penilaian_dosen' => 'Penilaian Dosen',
        ];
    }

    /**
     * Display kelompok ranking with SAW
     */
    public function index(Request $request)
    {
        $method = $request->get('method', Setting::get('kelompok_ranking_method', 'saw'));
        $weights = $this->getWeights();
        $criteria = self::getKelompokCriteria();

        // Get all kelompok
        $kelompoks = Kelompok::with(['mahasiswa', 'ketua'])->get();

        // Prepare data for SAW
        $data = $kelompoks->map(function ($kelompok) {
            return [
                'id' => $kelompok->id_kelompok,
                'nama_kelompok' => $kelompok->nama_kelompok,
                'judul_proyek' => $kelompok->judul_proyek ?? '-',
                'kelas' => $kelompok->kelas ?? '-',
                'ketua' => $kelompok->ketua ? $kelompok->ketua->nama : '-',
                'jumlah_anggota' => $kelompok->mahasiswa->count(),
                'milestone' => $kelompok->nilai_milestone_avg ?? 0,
                'rata_anggota' => $kelompok->nilai_rata_anggota ?? 0,
                'kontribusi' => $kelompok->kontribusi_kelompok ?? 0,
                'penilaian_dosen' => $kelompok->penilaian_dosen ?? 0,
            ];
        })->toArray();

        // Calculate SAW
        if ($method === 'saw' && count($data) > 0) {
            $rankings = AhpSawCalculator::calculateSaw($data, $weights, array_keys($criteria));
        } else {
            // Simple average method
            $rankings = $this->calculateSimpleAverage($data);
        }

        return view('kelompok_ranking.index', [
            'rankings' => $rankings,
            'weights' => $weights,
            'method' => $method,
            'criteria' => $criteria,
        ]);
    }

    /**
     * Calculate simple average
     */
    private function calculateSimpleAverage(array $data): array
    {
        $criteria = ['milestone', 'rata_anggota', 'kontribusi', 'penilaian_dosen'];
        
        foreach ($data as &$item) {
            $sum = 0;
            $count = 0;
            foreach ($criteria as $key) {
                $sum += $item[$key] ?? 0;
                $count++;
            }
            $item['saw_score'] = $count > 0 ? $sum / $count : 0;
            $item['normalized'] = [];
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
     * Get kelompok weights from settings
     */
    private function getWeights(): array
    {
        return [
            'milestone' => (int) Setting::get('kelompok_weight_milestone', 30),
            'rata_anggota' => (int) Setting::get('kelompok_weight_rata_anggota', 25),
            'kontribusi' => (int) Setting::get('kelompok_weight_kontribusi', 25),
            'penilaian_dosen' => (int) Setting::get('kelompok_weight_penilaian_dosen', 20),
        ];
    }

    /**
     * Update kelompok SAW weights
     */
    public function updateWeights(Request $request)
    {
        $request->validate([
            'milestone' => 'required|numeric|min:0|max:100',
            'rata_anggota' => 'required|numeric|min:0|max:100',
            'kontribusi' => 'required|numeric|min:0|max:100',
            'penilaian_dosen' => 'required|numeric|min:0|max:100',
        ]);

        // Validate total = 100
        $total = $request->milestone + $request->rata_anggota + 
                 $request->kontribusi + $request->penilaian_dosen;
        
        if ($total != 100) {
            return back()->with('error', 'Total bobot harus = 100%. Saat ini: ' . $total . '%');
        }

        // Save weights
        Setting::set('kelompok_weight_milestone', $request->milestone);
        Setting::set('kelompok_weight_rata_anggota', $request->rata_anggota);
        Setting::set('kelompok_weight_kontribusi', $request->kontribusi);
        Setting::set('kelompok_weight_penilaian_dosen', $request->penilaian_dosen);

        return back()->with('success', 'Bobot SAW Kelompok berhasil diperbarui!');
    }

    /**
     * Show AHP configuration for kelompok
     */
    public function ahpConfig()
    {
        $criteria = self::getKelompokCriteria();
        $currentWeights = $this->getWeights();
        
        return view('kelompok_ranking.ahp_config', [
            'criteria' => $criteria,
            'currentWeights' => $currentWeights,
            'scale' => AhpSawCalculator::SCALE,
        ]);
    }

    /**
     * Calculate AHP weights for kelompok
     */
    public function calculateAhp(Request $request)
    {
        $criteria = self::getKelompokCriteria();
        $n = count($criteria);
        
        // Build matrix from form input
        $matrix = [];
        for ($i = 0; $i < $n; $i++) {
            $matrix[$i] = [];
            for ($j = 0; $j < $n; $j++) {
                if ($i === $j) {
                    $matrix[$i][$j] = 1;
                } elseif ($i < $j) {
                    $value = $request->input("ahp_{$i}_{$j}", 1);
                    $matrix[$i][$j] = floatval($value);
                } else {
                    // Reciprocal
                    $matrix[$i][$j] = 1 / $matrix[$j][$i];
                }
            }
        }

        // Calculate weights using AHP
        $result = AhpSawCalculator::calculateAhpWeights($matrix);
        
        if (!$result['consistent']) {
            return back()->with('error', 
                'Matriks tidak konsisten! CR = ' . number_format($result['cr'] * 100, 2) . 
                '% (harus ≤ 10%). Silakan perbaiki perbandingan.');
        }

        // Convert weights to percentages and save
        $criteriaKeys = array_keys($criteria);
        foreach ($result['weights'] as $index => $weight) {
            $key = $criteriaKeys[$index];
            $percentage = round($weight * 100);
            Setting::set("kelompok_weight_{$key}", $percentage);
        }

        return redirect()->route('kelompok.ranking')
            ->with('success', 'Bobot AHP Kelompok berhasil dihitung! CR = ' . 
                number_format($result['cr'] * 100, 2) . '%');
    }

    /**
     * Export kelompok ranking to Excel (CSV format)
     */
    public function exportExcel()
    {
        $weights = $this->getWeights();
        $criteria = self::getKelompokCriteria();
        $kelompoks = Kelompok::with(['mahasiswa', 'ketua'])->get();

        $data = $kelompoks->map(function ($kelompok) {
            return [
                'id' => $kelompok->id_kelompok,
                'nama_kelompok' => $kelompok->nama_kelompok,
                'judul_proyek' => $kelompok->judul_proyek ?? '-',
                'kelas' => $kelompok->kelas ?? '-',
                'ketua' => $kelompok->ketua ? $kelompok->ketua->nama : '-',
                'jumlah_anggota' => $kelompok->mahasiswa->count(),
                'milestone' => $kelompok->nilai_milestone_avg ?? 0,
                'rata_anggota' => $kelompok->nilai_rata_anggota ?? 0,
                'kontribusi' => $kelompok->kontribusi_kelompok ?? 0,
                'penilaian_dosen' => $kelompok->penilaian_dosen ?? 0,
            ];
        })->toArray();

        $rankings = AhpSawCalculator::calculateSaw($data, $weights, array_keys($criteria));
        
        $filename = 'ranking_kelompok_' . date('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($rankings) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM
            
            // Header
            fputcsv($file, ['Peringkat', 'Nama Kelompok', 'Judul Proyek', 'Kelas', 'Ketua', 'Anggota', 'Milestone', 'Rata-rata Anggota', 'Kontribusi', 'Penilaian Dosen', 'Skor Akhir']);
            
            // Data
            $rank = 1;
            foreach ($rankings as $row) {
                fputcsv($file, [
                    $rank++,
                    $row['nama_kelompok'],
                    $row['judul_proyek'],
                    $row['kelas'],
                    $row['ketua'],
                    $row['jumlah_anggota'],
                    $row['milestone'],
                    $row['rata_anggota'],
                    $row['kontribusi'],
                    $row['penilaian_dosen'],
                    number_format($row['saw_score'], 2)
                ]);
            }
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export kelompok ranking to PDF
     */
    public function exportPdf()
    {
        $weights = $this->getWeights();
        $criteria = self::getKelompokCriteria();
        $kelompoks = Kelompok::with(['mahasiswa', 'ketua'])->get();

        $data = $kelompoks->map(function ($kelompok) {
            return [
                'id' => $kelompok->id_kelompok,
                'nama_kelompok' => $kelompok->nama_kelompok,
                'judul_proyek' => $kelompok->judul_proyek ?? '-',
                'kelas' => $kelompok->kelas ?? '-',
                'ketua' => $kelompok->ketua ? $kelompok->ketua->nama : '-',
                'jumlah_anggota' => $kelompok->mahasiswa->count(),
                'milestone' => $kelompok->nilai_milestone_avg ?? 0,
                'rata_anggota' => $kelompok->nilai_rata_anggota ?? 0,
                'kontribusi' => $kelompok->kontribusi_kelompok ?? 0,
                'penilaian_dosen' => $kelompok->penilaian_dosen ?? 0,
            ];
        })->toArray();

        $rankings = AhpSawCalculator::calculateSaw($data, $weights, array_keys($criteria));
        
        // Generate HTML for PDF
        $html = '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Ranking Kelompok</title>
        <style>
            body { font-family: Arial, sans-serif; font-size: 12px; }
            h1 { text-align: center; color: #333; }
            table { width: 100%; border-collapse: collapse; margin-top: 20px; }
            th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
            th { background-color: #6366f1; color: white; }
            tr:nth-child(even) { background-color: #f2f2f2; }
            .date { text-align: right; color: #666; margin-bottom: 10px; }
        </style>
        </head><body>
        <h1>Ranking Kelompok</h1>
        <p class="date">Dicetak: ' . date('d/m/Y H:i:s') . '</p>
        <table>
            <thead>
                <tr>
                    <th>#</th><th>Kelompok</th><th>Judul Proyek</th><th>Kelas</th><th>Ketua</th>
                    <th>Milestone</th><th>Rata-rata</th><th>Kontribusi</th><th>Dosen</th><th>Skor</th>
                </tr>
            </thead>
            <tbody>';
        
        $rank = 1;
        foreach ($rankings as $row) {
            $html .= '<tr>
                <td>' . $rank++ . '</td>
                <td>' . $row['nama_kelompok'] . '</td>
                <td>' . $row['judul_proyek'] . '</td>
                <td>' . $row['kelas'] . '</td>
                <td>' . $row['ketua'] . '</td>
                <td>' . $row['milestone'] . '</td>
                <td>' . $row['rata_anggota'] . '</td>
                <td>' . $row['kontribusi'] . '</td>
                <td>' . $row['penilaian_dosen'] . '</td>
                <td><strong>' . number_format($row['saw_score'], 2) . '</strong></td>
            </tr>';
        }
        
        $html .= '</tbody></table></body></html>';
        
        return response($html)
            ->header('Content-Type', 'text/html')
            ->header('Content-Disposition', 'attachment; filename="ranking_kelompok_' . date('Y-m-d') . '.html"');
    }
}
