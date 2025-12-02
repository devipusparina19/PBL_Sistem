<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kelompok;

class MonitoringController extends Controller
{
    public function index(Request $request)
    {
        $query = Kelompok::with(['mahasiswas', 'milestones']);

if ($request->has('search') && $request->search != '') {
    $search = $request->search;

    $query->where('nama_kelompok', 'like', "%$search%")
        ->orWhereHas('mahasiswas', function ($q) use ($search) {
            $q->where('nama', 'like', "%$search%");
        });
}

$kelompok = $query->get();

        return view('koordinator.monitoring', compact('kelompok'));
    }

    public function show($id)
    {
        $kelompok = Kelompok::with(['mahasiswas', 'milestones'])->findOrFail($id);

        return view('koordinator.monitoring_detail', compact('kelompok'));
    }
}
