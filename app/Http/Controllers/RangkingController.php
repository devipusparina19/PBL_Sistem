<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kelompok;
use App\Models\Nilai;

class RangkingController extends Controller
{
    /**
     * Menampilkan halaman rangking kelompok
     */
    public function index()
    {
        // Ambil data nilai rata-rata per kelompok (contoh logika)
        // Pastikan tabel nilais memiliki kolom: kelompok_id, nilai_total atau nilai_akhir
        $kelompoks = Kelompok::with('mahasiswas') // ambil relasi anggota kelompok
            ->get()
            ->map(function ($kelompok) {
                // Hitung rata-rata nilai dari tabel nilais
                $rataNilai = Nilai::where('kelompok_id', $kelompok->id)->avg('nilai_akhir');

                return [
                    'nama' => $kelompok->nama_kelompok ?? 'Kelompok Tanpa Nama',
                    'judul' => $kelompok->judul_proyek ?? '-',
                    'anggota' => $kelompok->mahasiswas->pluck('nama')->implode(', '),
                    'nilai' => round($rataNilai, 2) ?? 0,
                ];
            })
            ->sortByDesc('nilai')
            ->values();

        // Kirim ke view
        return view('kelompok.rangking', compact('kelompoks'));
    }
}
