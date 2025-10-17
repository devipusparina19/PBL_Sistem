<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\Kelompok;
use App\Models\Penilaian;

class LaporanController extends Controller
{
    public function index()
    {
        // Misal ambil semua penilaian dari database
        $penilaian = Penilaian::with(['mahasiswa', 'kelompok'])->get();

        return view('koordinator.laporan_penilaian', compact('penilaian'));
    }
}
