<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kelompok;
use App\Models\Mahasiswa;
use App\Models\Nilai;

class RangkingController extends Controller
{
    /**
     * Menampilkan halaman rangking kelompok berdasarkan nilai tertinggi
     */
    public function index()
    {
        // Ambil semua kelompok beserta rata-rata nilainya
        $kelompoks = Kelompok::all()->map(function ($kelompok) {
            // hitung rata-rata nilai dari tabel nilais berdasarkan id_kelompok
            $rataNilai = Nilai::where('kelompok_id', $kelompok->id_kelompok)->avg('nilai_akhir');

            // ambil anggota dari tabel mahasiswas yang punya role_kelompok sama
            $anggota = Mahasiswa::where('role_kelompok', $kelompok->id_kelompok)
                                ->pluck('nama')
                                ->implode(', ');

            return [
                'nama' => $kelompok->nama_kelompok ?? 'Kelompok Tanpa Nama',
                'judul' => $kelompok->judul_proyek ?? '-',
                'anggota' => $anggota ?: '-',
                'nilai' => round($rataNilai, 2) ?? 0,
            ];
        })
        ->sortByDesc('nilai')
        ->values(); // urutkan dari nilai tertinggi

        return view('kelompok.rangking', compact('kelompoks'));
    }
}
