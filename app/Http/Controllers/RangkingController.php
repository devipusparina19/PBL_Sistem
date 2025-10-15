<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kelompok;
use App\Models\Nilai;

class RangkingController extends Controller
{
    /**
     * Menampilkan halaman rangking mahasiswa (individu)
     */
    public function mahasiswa()
    {
        try {
            // Ambil semua mahasiswa dengan nilai mereka
            $mahasiswas = \App\Models\Mahasiswa::with(['nilai', 'kelompok'])
                ->get()
                ->map(function ($mahasiswa) {
                    // Ambil nilai mahasiswa
                    $nilai = $mahasiswa->nilai;
                    $nilaiAkhir = $nilai ? $nilai->hasil_akhir : 0;

                    return [
                        'nama' => $mahasiswa->nama ?? '-',
                        'nim' => $mahasiswa->nim ?? '-',
                        'kelas' => $mahasiswa->kelas ?? '-',
                        'kelompok' => $mahasiswa->kelompok->nama_kelompok ?? 'Belum ada kelompok',
                        'nilai' => $nilaiAkhir ? round($nilaiAkhir, 2) : 0,
                    ];
                })
                ->sortByDesc('nilai')
                ->values();

            // Kirim ke view
            return view('mahasiswa.rangking', compact('mahasiswas'));
            
        } catch (\Exception $e) {
            \Log::error('Error di RangkingController@mahasiswa: ' . $e->getMessage());
            $mahasiswas = collect([]);
            return view('mahasiswa.rangking', compact('mahasiswas'))
                ->with('error', 'Terjadi kesalahan saat memuat data ranking mahasiswa.');
        }
    }

    /**
     * Menampilkan halaman rangking kelompok
     */
    public function kelompok()
    {
        try {
            // Ambil data nilai rata-rata per kelompok
            $kelompoks = Kelompok::with('mahasiswas')
                ->get()
                ->map(function ($kelompok) {
                    // Hitung rata-rata nilai dari tabel nilai
                    $rataNilai = Nilai::where('kelompok_id', $kelompok->id_kelompok)->avg('hasil_akhir');

                    // Ambil nama anggota kelompok
                    $anggotaArray = [];
                    if ($kelompok->relationLoaded('mahasiswas') && $kelompok->mahasiswas) {
                        $anggotaArray = $kelompok->mahasiswas->pluck('nama')->toArray();
                    }
                    $anggotaStr = !empty($anggotaArray) ? implode(', ', $anggotaArray) : 'Belum ada anggota';

                    return [
                        'nama' => $kelompok->nama_kelompok ?? 'Kelompok Tanpa Nama',
                        'judul' => $kelompok->judul_proyek ?? '-',
                        'anggota' => $anggotaStr,
                        'nilai' => $rataNilai ? round($rataNilai, 2) : 0,
                    ];
                })
                ->sortByDesc('nilai')
                ->values();

            // Kirim ke view
            return view('kelompok.rangking', compact('kelompoks'));
            
        } catch (\Exception $e) {
            // Jika terjadi error, kirim data kosong dengan pesan error
            \Log::error('Error di RangkingController: ' . $e->getMessage());
            $kelompoks = collect([]);
            return view('kelompok.rangking', compact('kelompoks'))
                ->with('error', 'Terjadi kesalahan saat memuat data ranking.');
        }
    }
}
