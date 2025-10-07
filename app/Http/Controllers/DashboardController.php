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
    $mahasiswa = Mahasiswa::all(); // atau filter sesuai kebutuhan
    return view('Mahasiswa.home', compact('Mahasiswa'));
    }

    // Menu Dosen
    public function dosen()
    {
        $dosen = Dosen::all(); // atau filter sesuai kebutuhan
        return view('dosen.home', compact('dosen'));
    }
    
    // Menu Logbook
    public function logbook()
    {
        return view('logbook.index');
    }

    // Menu Milestone
    public function milestones()
    {
        $milestones = Milestone::all(); // atau filter sesuai kebutuhan
        return view('milestones.index', compact('milestones'));
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
