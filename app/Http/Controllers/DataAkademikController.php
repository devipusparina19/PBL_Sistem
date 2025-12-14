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
            
            // Filter Mata Kuliah
            $queryMataKuliah = MataKuliah::where('kelas', $kelasItem);
            
            // Jika dosen, hanya tampilkan mata kuliah yang diajar (NIP ada di kolom nip_dosen)
            if ($user->role === 'dosen') {
                $nip = $user->nim_nip;
                // Menggunakan get() lalu filter collection karena nip_dosen menyimpan string CSV
                // Atau bisa pakai whereRaw/LIKE tapi collection filter lebih aman untuk format yang tidak konsisten
                // Namun untuk performa dan konsistensi dengan query builder di atas:
                // Kita ambil dulu semua untuk kelas ini, lalu filter di PHP side untuk 'dosen' khusus ini
                // Tapi variabel ini ($queryMataKuliah) adalah builder.
                // Mari kita ambil datanya dulu.
            }
            
            $mataKuliahByKelas[$kelasItem] = $queryMataKuliah->orderBy('nama_mk', 'asc')->get();

            // Jika dosen, filter collection hasil query
            if ($user->role === 'dosen') {
                $nip = $user->nim_nip;
                $mataKuliahByKelas[$kelasItem] = $mataKuliahByKelas[$kelasItem]->filter(function($mk) use ($nip) {
                    return in_array($nip, $mk->nip_dosen_array);
                });
            }
            
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
