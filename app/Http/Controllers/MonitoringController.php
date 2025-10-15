<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kelompok;

class MonitoringController extends Controller
{
    public function index(Request $request)
    {
        // Ambil semua data kelompok beserta mahasiswa & milestone
        $query = Kelompok::with(['mahasiswa', 'milestone']);

        // Jika ada pencarian (nama mahasiswa / kelompok)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;

            $query->where('nama', 'like', "%$search%")
                ->orWhereHas('mahasiswa', function ($q) use ($search) {
                    $q->where('nama', 'like', "%$search%");
                });
        }

        $kelompok = $query->get();

        return view('koordinator.monitoring', compact('kelompok'));
    }

    public function show($id)
    {
<<<<<<< HEAD
        // Tampilkan detail progres kelompok tertentu
        $kelompok = Kelompok::with(['mahasiswas', 'milestones'])
            ->findOrFail($id);
=======
        // Detail progres kelompok
        $kelompok = Kelompok::with(['mahasiswa', 'milestone'])->findOrFail($id);
>>>>>>> 8fdb431 (coba progres)

        return view('koordinator.monitoring_detail', compact('kelompok'));
    }
}
