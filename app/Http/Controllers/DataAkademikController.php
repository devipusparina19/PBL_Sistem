<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\MataKuliah;
use App\Models\User;
use Illuminate\Http\Request;

class DataAkademikController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $kelasList = ['3A', '3B', '3C', '3D', '3E'];

        // Jika user adalah mahasiswa, filter berdasarkan kelas mereka
        if ($user->role === 'mahasiswa') {
            $kelas = $user->kelas;
            
            // Ambil data untuk kelas mahasiswa
            $dosens = Dosen::where('kelas', $kelas)
                ->orderBy('nama', 'asc')
                ->get();
            
            $mataKuliah = MataKuliah::where('kelas', $kelas)
                ->orderBy('nama_mk', 'asc')
                ->get();
            
            $mahasiswas = User::where('role', 'mahasiswa')
                ->where('kelas', $kelas)
                ->orderBy('name', 'asc')
                ->get();
            
            return view('data_akademik.index', compact(
                'dosens',
                'mataKuliah',
                'mahasiswas',
                'kelas',
                'kelasList'
            ));
        }

        // Jika user adalah dosen atau role lain, tampilkan semua data atau per kelas
        $dosenByKelas = [];
        $mataKuliahByKelas = [];
        $mahasiswaByKelas = [];

        foreach ($kelasList as $kelasItem) {
            $dosenByKelas[$kelasItem] = Dosen::where('kelas', $kelasItem)
                ->orderBy('nama', 'asc')
                ->get();
            
            $mataKuliahByKelas[$kelasItem] = MataKuliah::where('kelas', $kelasItem)
                ->orderBy('nama_mk', 'asc')
                ->get();
            
            $mahasiswaByKelas[$kelasItem] = User::where('role', 'mahasiswa')
                ->where('kelas', $kelasItem)
                ->orderBy('name', 'asc')
                ->get();
        }

        return view('data_akademik.index', compact(
            'dosenByKelas',
            'mataKuliahByKelas',
            'mahasiswaByKelas',
            'kelasList'
        ));
    }
}
