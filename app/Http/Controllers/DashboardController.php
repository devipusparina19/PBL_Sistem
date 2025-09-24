<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // Halaman utama dashboard
    public function index()
    {
        return view('dashboard');
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
