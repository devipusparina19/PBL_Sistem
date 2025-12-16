<?php

namespace App\Helpers;

/**
 * AHP (Analytic Hierarchy Process) & SAW (Simple Additive Weighting) Calculator
 * 
 * AHP: Menentukan bobot kriteria dari matriks perbandingan berpasangan
 * SAW: Menghitung skor akhir dengan normalisasi dan pembobotan
 */
class AhpSawCalculator
{
    /**
     * Skala AHP Saaty
     */
    const SCALE = [
        1 => 'Sama penting',
        2 => 'Mendekati sedikit lebih penting',
        3 => 'Sedikit lebih penting',
        4 => 'Mendekati lebih penting',
        5 => 'Lebih penting',
        6 => 'Mendekati sangat lebih penting',
        7 => 'Sangat lebih penting',
        8 => 'Mendekati mutlak lebih penting',
        9 => 'Mutlak lebih penting',
    ];

    /**
     * Random Index untuk Consistency Ratio
     */
    const RI = [
        1 => 0.00,
        2 => 0.00,
        3 => 0.58,
        4 => 0.90,
        5 => 1.12,
        6 => 1.24,
        7 => 1.32,
        8 => 1.41,
        9 => 1.45,
        10 => 1.49,
    ];

    /**
     * Kriteria default untuk ranking
     */
    public static function getDefaultCriteria(): array
    {
        return [
            'pwl' => 'Pemrograman Web Lanjut',
            'integrasi' => 'Integrasi Sistem',
            'tpk' => 'Teknik Pengambilan Keputusan',
            'it_project' => 'IT Projek',
            'kontribusi' => 'Kontribusi Kelompok',
            'sejawat' => 'Penilaian Teman Sejawat',
            'proyek' => 'Hasil Akhir Proyek',
        ];
    }

    /**
     * Hitung bobot dari matriks perbandingan berpasangan (AHP)
     * 
     * @param array $matrix Matriks perbandingan berpasangan
     * @return array ['weights' => [...], 'cr' => float, 'consistent' => bool]
     */
    public static function calculateAhpWeights(array $matrix): array
    {
        $n = count($matrix);
        
        if ($n < 2) {
            return ['weights' => [], 'cr' => 0, 'consistent' => true];
        }

        // 1. Normalisasi kolom
        $columnSums = [];
        for ($j = 0; $j < $n; $j++) {
            $sum = 0;
            for ($i = 0; $i < $n; $i++) {
                $sum += $matrix[$i][$j];
            }
            $columnSums[$j] = $sum;
        }

        $normalizedMatrix = [];
        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $normalizedMatrix[$i][$j] = $columnSums[$j] > 0 
                    ? $matrix[$i][$j] / $columnSums[$j] 
                    : 0;
            }
        }

        // 2. Hitung bobot (rata-rata baris)
        $weights = [];
        for ($i = 0; $i < $n; $i++) {
            $rowSum = array_sum($normalizedMatrix[$i]);
            $weights[$i] = $rowSum / $n;
        }

        // 3. Hitung Consistency Ratio (CR)
        // CR = CI / RI
        // CI = (λmax - n) / (n - 1)
        
        // Hitung λmax
        $lambdaMax = 0;
        for ($j = 0; $j < $n; $j++) {
            $weightedSum = 0;
            for ($i = 0; $i < $n; $i++) {
                $weightedSum += $matrix[$i][$j] * $weights[$i];
            }
            if ($weights[$j] > 0) {
                $lambdaMax += $weightedSum / $weights[$j];
            }
        }
        $lambdaMax = $lambdaMax / $n;

        // Hitung CI dan CR
        $ci = ($lambdaMax - $n) / ($n - 1);
        $ri = self::RI[$n] ?? 1.49;
        $cr = $ri > 0 ? $ci / $ri : 0;

        return [
            'weights' => $weights,
            'lambda_max' => round($lambdaMax, 4),
            'ci' => round($ci, 4),
            'cr' => round($cr, 4),
            'consistent' => $cr <= 0.1, // CR <= 10% dianggap konsisten
        ];
    }

    /**
     * Hitung skor SAW (Simple Additive Weighting)
     * 
     * @param array $data Data mahasiswa dengan nilai per kriteria
     * @param array $weights Bobot per kriteria (dalam persen, total 100)
     * @return array Data dengan skor SAW ditambahkan
     */
    public static function calculateSaw(array $data, array $weights): array
    {
        if (empty($data)) {
            return $data;
        }

        $criteria = array_keys($weights);
        
        // 1. Cari nilai maksimum per kriteria (untuk normalisasi benefit)
        $maxValues = [];
        foreach ($criteria as $criterion) {
            $maxValues[$criterion] = 0;
            foreach ($data as $item) {
                $value = $item[$criterion] ?? 0;
                if ($value > $maxValues[$criterion]) {
                    $maxValues[$criterion] = $value;
                }
            }
        }

        // 2. Normalisasi dan hitung skor SAW
        foreach ($data as $key => $item) {
            $sawScore = 0;
            $normalizedValues = [];
            
            foreach ($criteria as $criterion) {
                $value = $item[$criterion] ?? 0;
                $max = $maxValues[$criterion];
                
                // Normalisasi (benefit type): r = value / max
                $normalized = $max > 0 ? $value / $max : 0;
                $normalizedValues[$criterion] = round($normalized, 4);
                
                // Weighted score: w * r
                $weight = $weights[$criterion] / 100; // Convert to decimal
                $sawScore += $weight * $normalized;
            }
            
            $data[$key]['normalized'] = $normalizedValues;
            $data[$key]['saw_score'] = round($sawScore * 100, 2); // Scale to 0-100
        }

        // 3. Sort by SAW score descending
        usort($data, function($a, $b) {
            return $b['saw_score'] <=> $a['saw_score'];
        });

        // 4. Add rank
        foreach ($data as $key => $item) {
            $data[$key]['rank'] = $key + 1;
        }

        return $data;
    }

    /**
     * Validasi apakah matriks perbandingan valid (reciprocal)
     */
    public static function validateMatrix(array $matrix): bool
    {
        $n = count($matrix);
        
        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                // Diagonal harus 1
                if ($i === $j && $matrix[$i][$j] != 1) {
                    return false;
                }
                // Reciprocal: a[i][j] = 1/a[j][i]
                if ($i !== $j) {
                    $expected = 1 / $matrix[$j][$i];
                    if (abs($matrix[$i][$j] - $expected) > 0.001) {
                        return false;
                    }
                }
            }
        }
        
        return true;
    }

    /**
     * Buat matriks reciprocal dari input upper triangle
     */
    public static function buildReciprocalMatrix(array $upperTriangle, int $n): array
    {
        $matrix = [];
        
        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                if ($i === $j) {
                    $matrix[$i][$j] = 1;
                } elseif ($i < $j) {
                    $matrix[$i][$j] = $upperTriangle[$i][$j] ?? 1;
                } else {
                    $matrix[$i][$j] = 1 / ($upperTriangle[$j][$i] ?? 1);
                }
            }
        }
        
        return $matrix;
    }
}
