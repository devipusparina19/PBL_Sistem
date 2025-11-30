<?php

namespace App\Http\Controllers;

use App\Models\Pemeringkatan;
use App\Models\Kelompok;
use Illuminate\Http\Request;

class PemeringkatanController extends Controller
{
    public function index()
    {
        $data = Pemeringkatan::with('kelompok')
            ->orderBy('peringkat', 'asc')
            ->get();

        return view('Peringkat.Index', compact('data'));
    }

    public function generate()
    {
        Pemeringkatan::truncate(); // kosongkan tabel

        // Ambil kelompok berdasarkan nilai_total TERTINGGI
        $kelompoks = Kelompok::whereNotNull('nilai_total')
            ->orderBy('nilai_total', 'desc')
            ->get();

        $rank = 1;

        foreach ($kelompoks as $k) {
            Pemeringkatan::create([
                'kelompok_id' => $k->id_kelompok,
                'nilai_total' => $k->nilai_total,
                'peringkat' => $rank,
            ]);

            $rank++;
        }

        return redirect()->back()->with('success', 'Perangkingan berhasil diperbarui');
    }
}
