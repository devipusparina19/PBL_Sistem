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
    public function getWeights(): array
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
        
        // Create new Spreadsheet
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set document properties
        $spreadsheet->getProperties()
            ->setCreator('Sistem PBL')
            ->setTitle('Ranking Kelompok')
            ->setSubject('Ranking Kelompok PBL')
            ->setDescription('Data Ranking Kelompok');
        
        // Title
        $sheet->setCellValue('A1', 'RANKING KELOMPOK PBL');
        $sheet->mergeCells('A1:K1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        // Date
        $sheet->setCellValue('A2', 'Tanggal Export: ' . date('d/m/Y H:i:s'));
        $sheet->mergeCells('A2:K2');
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        // Headers
        $headers = ['PERINGKAT', 'KELOMPOK', 'JUDUL PROYEK', 'KELAS', 'KETUA', 'ANGGOTA', 'MILESTONE', 'RATA-RATA ANGGOTA', 'KONTRIBUSI', 'PENILAIAN DOSEN', 'SKOR AKHIR'];
        $sheet->fromArray($headers, null, 'A4');
        
        // Style headers
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '6366F1']],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]]
        ];
        $sheet->getStyle('A4:K4')->applyFromArray($headerStyle);
        
        // Data
        $row = 5;
        $rank = 1;
        foreach ($rankings as $ranking) {
            $sheet->setCellValue('A' . $row, $rank++);
            $sheet->setCellValue('B' . $row, $ranking['nama_kelompok']);
            $sheet->setCellValue('C' . $row, $ranking['judul_proyek']);
            $sheet->setCellValue('D' . $row, $ranking['kelas']);
            $sheet->setCellValue('E' . $row, $ranking['ketua']);
            $sheet->setCellValue('F' . $row, $ranking['jumlah_anggota']);
            $sheet->setCellValue('G' . $row, number_format($ranking['milestone'], 2));
            $sheet->setCellValue('H' . $row, number_format($ranking['rata_anggota'], 2));
            $sheet->setCellValue('I' . $row, number_format($ranking['kontribusi'], 2));
            $sheet->setCellValue('J' . $row, number_format($ranking['penilaian_dosen'], 2));
            $sheet->setCellValue('K' . $row, number_format($ranking['saw_score'], 2));
            $row++;
        }
        
        // Style data rows
        $dataStyle = [
            'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
        ];
        $sheet->getStyle('A5:K' . ($row - 1))->applyFromArray($dataStyle);
        
        // Style ranking column (bold)
        $sheet->getStyle('A5:A' . ($row - 1))->getFont()->setBold(true);
        
        // Style score column (bold, green)
        $scoreStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => '059669']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'D1FAE5']]
        ];
        $sheet->getStyle('K5:K' . ($row - 1))->applyFromArray($scoreStyle);
        
        // Auto-size columns
        foreach (range('A', 'K') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Set row height
        $sheet->getRowDimension(1)->setRowHeight(30);
        $sheet->getRowDimension(4)->setRowHeight(25);
        
        // Freeze header row
        $sheet->freezePane('A5');
        
        // Generate file
        $filename = 'ranking_kelompok_' . date('Y-m-d_His') . '.xlsx';
        
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
        exit;
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
        $html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Ranking Kelompok</title>
    <style>
        @page {
            margin: 20mm;
            size: A4 landscape;
        }
        body {
            font-family: "DejaVu Sans", Arial, sans-serif;
            font-size: 10px;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 3px solid #6366f1;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            color: #1e293b;
            font-size: 20px;
            font-weight: bold;
        }
        .header .subtitle {
            color: #64748b;
            font-size: 12px;
            margin-top: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th {
            background-color: #6366f1;
            color: white;
            font-weight: bold;
            padding: 10px 6px;
            text-align: center;
            border: 1px solid #4f46e5;
            font-size: 9px;
        }
        td {
            border: 1px solid #e2e8f0;
            padding: 8px 6px;
            text-align: center;
            font-size: 9px;
        }
        tr:nth-child(even) {
            background-color: #f8fafc;
        }
        tr:hover {
            background-color: #f1f5f9;
        }
        .rank-column {
            font-weight: bold;
            background-color: #fef3c7;
            color: #92400e;
        }
        .score-column {
            font-weight: bold;
            background-color: #d1fae5;
            color: #065f46;
            font-size: 11px;
        }
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #e2e8f0;
            text-align: right;
            font-size: 9px;
            color: #64748b;
        }
        .project-title {
            text-align: left;
            max-width: 200px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🏆 RANKING KELOMPOK PBL</h1>
        <div class="subtitle">Program Studi Teknologi Informasi - Politeknik Negeri Jember</div>
    </div>
    
    <table>
        <thead>
            <tr>
                <th style="width: 40px;">RANK</th>
                <th style="width: 100px;">KELOMPOK</th>
                <th style="width: 180px;">JUDUL PROYEK</th>
                <th style="width: 50px;">KELAS</th>
                <th style="width: 120px;">KETUA</th>
                <th style="width: 50px;">ANGGOTA</th>
                <th style="width: 60px;">MILESTONE</th>
                <th style="width: 60px;">RATA ANGGOTA</th>
                <th style="width: 60px;">KONTRIBUSI</th>
                <th style="width: 60px;">PENILAIAN DOSEN</th>
                <th style="width: 70px;">SKOR AKHIR</th>
            </tr>
        </thead>
        <tbody>';
        
        $rank = 1;
        foreach ($rankings as $row) {
            $html .= '<tr>
                <td class="rank-column">' . $rank++ . '</td>
                <td>' . htmlspecialchars($row['nama_kelompok']) . '</td>
                <td class="project-title">' . htmlspecialchars($row['judul_proyek']) . '</td>
                <td>' . htmlspecialchars($row['kelas']) . '</td>
                <td>' . htmlspecialchars($row['ketua']) . '</td>
                <td>' . $row['jumlah_anggota'] . '</td>
                <td>' . number_format($row['milestone'], 2) . '</td>
                <td>' . number_format($row['rata_anggota'], 2) . '</td>
                <td>' . number_format($row['kontribusi'], 2) . '</td>
                <td>' . number_format($row['penilaian_dosen'], 2) . '</td>
                <td class="score-column">' . number_format($row['saw_score'], 2) . '</td>
            </tr>';
        }
        
        $html .= '
        </tbody>
    </table>
    
    <div class="footer">
        <strong>Keterangan:</strong> Ranking dihitung menggunakan metode SAW (Simple Additive Weighting)<br>
        Dicetak pada: ' . date('d/m/Y H:i:s') . ' | Total Kelompok: ' . count($rankings) . '
    </div>
</body>
</html>';
        
        // Generate PDF using DomPDF
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html);
        $pdf->setPaper('A4', 'landscape');
        
        $filename = 'ranking_kelompok_' . date('Y-m-d_His') . '.pdf';
        
        return $pdf->download($filename);
    }
}
