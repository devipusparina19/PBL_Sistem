<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kelompok;
use App\Models\Mahasiswa;
use App\Models\Milestone;

class ProgresController extends Controller
{
    // Halaman utama progres seluruh kelompok
    public function index(Request $request)
    {
        $query = Kelompok::with(['mahasiswa', 'milestone']);

        // Fitur pencarian (berdasarkan nama kelompok atau mahasiswa)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;

            $query->where('nama', 'like', "%$search%")
                ->orWhereHas('mahasiswa', function ($q) use ($search) {
                    $q->where('nama', 'like', "%$search%");
                });
        }

        $kelompok = $query->get();

        return view('koordinator.progres', compact('kelompok'));
    }

    // Detail progres untuk satu kelompok
    public function show($id)
    {
        $kelompok = Kelompok::with(['mahasiswa', 'milestone'])->findOrFail($id);
        return view('koordinator.progres_detail', compact('kelompok'));
    }
}
