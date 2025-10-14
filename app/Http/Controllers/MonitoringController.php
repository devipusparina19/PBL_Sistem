<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\Kelompok; // pastikan model ini ada

class MonitoringController extends Controller
{
    public function index()
    {
        // Ambil semua data mahasiswa beserta kelompok dan milestone
        $mahasiswa = Mahasiswa::with(['kelompok', 'kelompok.milestones'])->get();

        return view('koordinator.monitoring', compact('mahasiswa'));
    }

    public function show($id)
    {
        // Tampilkan detail progres kelompok tertentu
        $kelompok = Kelompok::with(['mahasiswas', 'milestones'])
            ->findOrFail($id);

        return view('koordinator.monitoring_detail', compact('kelompok'));
    }
}
