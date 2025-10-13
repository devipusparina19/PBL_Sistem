<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kelompok; // misal model kelompok kamu sudah ada
use App\Models\Mahasiswa; // jika butuh data mahasiswa

class MonitoringController extends Controller
{
    public function index()
    {
        // ambil semua data progres dari tabel kelompok / logbook / milestone (sesuaikan)
        $kelompok = Kelompok::with(['mahasiswa', 'milestone'])->get();

        return view('koordinator.monitoring', compact('kelompok'));
    }
}
