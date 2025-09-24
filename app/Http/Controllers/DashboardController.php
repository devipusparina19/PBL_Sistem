<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // Halaman utama dashboard sesuai role
    public function index()
    {
        $role = auth()->user()->role;

        switch ($role) {
    case 'mahasiswa':
        return view('dashboard.mahasiswa');
    case 'dosen':
        return view('dashboard.dosen');
    case 'koordinator_pbl':
        return view('dashboard.koordinator_pbl');
    case 'koordinator_prodi':
        return view('dashboard.koordinator_prodi');
    case 'admin':
        return view('dashboard.admin');
    default:
        abort(403, 'Role tidak dikenali: ' . $role);
}

    }

    // Menu Akun Mahasiswa
    public function mahasiswa()
    {
        return view('mahasiswa.index');
    }

    // Menu Dosen
    public function dosen()
    {
        return view('dosen.index');
    }

    // Menu Milestone
    public function milestone()
    {
        return view('milestone.index');
    }

    // Menu Koordinator PBL
    public function koor()
    {
        return view('koor.index');
    }

    // Menu Ranking
    public function ranking()
    {
        return view('ranking.index');
    }
}
