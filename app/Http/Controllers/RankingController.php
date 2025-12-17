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
        
        // ✅ Get Kelompok Rankings
        $kelompokController = new \App\Http\Controllers\KelompokRankingController();
        $kelompokWeights = $kelompokController->getWeights();
        $kelompokCriteria = \App\Http\Controllers\KelompokRankingController::getKelompokCriteria();
        
        $kelompoks = \App\Models\Kelompok::with(['mahasiswa', 'ketua'])->get();
        $kelompokData = $kelompoks->map(function ($kelompok) {
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
        
        if ($method === 'saw' && count($kelompokData) > 0) {
            $kelompokRankings = AhpSawCalculator::calculateSaw($kelompokData, $kelompokWeights, array_keys($kelompokCriteria));
        } else {
            $kelompokRankings = [];
        }
        
        return view('ranking.index', [
            'rankings' => $rankings,
            'weights' => $weights,
            'method' => $method,
            'criteria' => AhpSawCalculator::getDefaultCriteria(),
            'kelompokRankings' => $kelompokRankings,
            'kelompokWeights' => $kelompokWeights,
            'kelompokCriteria' => $kelompokCriteria,
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
     * Export ranking data to Excel (XLSX format)
     */
    public function exportExcel()
    {
        $mahasiswas = Mahasiswa::with('kelompok')->get();
        $weights = $this->getWeights();
        
        $data = $mahasiswas->map(fn($mhs) => $this->getMahasiswaScores($mhs))->filter(fn($item) => $item['nama'] !== null);
        
        $rankings = AhpSawCalculator::calculateSaw($data->toArray(), $weights);
        
        // Create spreadsheet
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Ranking Mahasiswa');
        
        // Header styling
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '4CAF50']],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]],
        ];
        
        // Set headers
        $headers = ['Peringkat', 'NIM', 'Nama', 'Kelas', 'Kelompok', 'PWL', 'Integrasi', 'TPK', 'IT Projek', 'Kontribusi', 'Sejawat', 'Proyek', 'Skor Akhir'];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $col++;
        }
        $sheet->getStyle('A1:M1')->applyFromArray($headerStyle);
        
        // Add data
        $row = 2;
        $rank = 1;
        foreach ($rankings as $item) {
            $sheet->setCellValue('A' . $row, $rank++);
            $sheet->setCellValue('B' . $row, $item['nim']);
            $sheet->setCellValue('C' . $row, $item['nama']);
            $sheet->setCellValue('D' . $row, $item['kelas']);
            $sheet->setCellValue('E' . $row, $item['kelompok']);
            $sheet->setCellValue('F' . $row, $item['pwl']);
            $sheet->setCellValue('G' . $row, $item['integrasi']);
            $sheet->setCellValue('H' . $row, $item['tpk']);
            $sheet->setCellValue('I' . $row, $item['it_project']);
            $sheet->setCellValue('J' . $row, $item['kontribusi']);
            $sheet->setCellValue('K' . $row, $item['sejawat']);
            $sheet->setCellValue('L' . $row, $item['proyek']);
            $sheet->setCellValue('M' . $row, number_format($item['saw_score'], 2));
            $row++;
        }
        
        // Auto-size columns
        foreach (range('A', 'M') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Add borders to data
        $lastRow = $row - 1;
        $sheet->getStyle('A2:M' . $lastRow)->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]],
        ]);
        
        // Alternate row colors
        for ($i = 2; $i <= $lastRow; $i++) {
            if ($i % 2 == 0) {
                $sheet->getStyle('A' . $i . ':M' . $i)->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('F2F2F2');
            }
        }
        
        $filename = 'ranking_mahasiswa_' . date('Y-m-d_His') . '.xlsx';
        
        // Output
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    /**
     * Export ranking data to PDF
     */
    public function exportPdf()
    {
        $mahasiswas = Mahasiswa::with('kelompok')->get();
        $weights = $this->getWeights();
        
        $data = $mahasiswas->map(fn($mhs) => $this->getMahasiswaScores($mhs))->filter(fn($item) => $item['nama'] !== null);
        
        $rankings = AhpSawCalculator::calculateSaw($data->toArray(), $weights);
        
        // Generate HTML for PDF
        $html = '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Ranking Mahasiswa</title>
        <style>
            body { font-family: Arial, sans-serif; font-size: 10px; margin: 20px; }
            h1 { text-align: center; color: #333; font-size: 18px; margin-bottom: 5px; }
            .subtitle { text-align: center; color: #666; margin-bottom: 15px; }
            table { width: 100%; border-collapse: collapse; margin-top: 10px; }
            th, td { border: 1px solid #ddd; padding: 6px; text-align: center; }
            th { background-color: #4CAF50; color: white; font-size: 9px; }
            td { font-size: 9px; }
            tr:nth-child(even) { background-color: #f2f2f2; }
            .date { text-align: right; color: #666; margin-bottom: 10px; font-size: 10px; }
            .footer { text-align: center; margin-top: 20px; font-size: 9px; color: #666; }
        </style>
        </head><body>
        <h1>Ranking Mahasiswa</h1>
        <p class="subtitle">Sistem Penilaian Kinerja Mahasiswa dan Kelompok PBL</p>
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
                <td style="text-align:left;">' . $row['nama'] . '</td>
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
        
        $html .= '</tbody></table>
        <p class="footer">Total Mahasiswa: ' . count($rankings) . '</p>
        </body></html>';
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html);
        $pdf->setPaper('A4', 'landscape');
        
        return $pdf->download('ranking_mahasiswa_' . date('Y-m-d') . '.pdf');
    }
}
