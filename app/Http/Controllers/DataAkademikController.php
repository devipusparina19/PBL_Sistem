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
            // Filter dosen berdasarkan mata kuliah yang diampu di kelas mahasiswa
            $mataKuliahKelas = MataKuliah::where('kelas', $kelas)->get();
            
            // Ambil semua NIP dosen yang mengampu di kelas ini
            $nipDosens = [];
            foreach ($mataKuliahKelas as $mk) {
                if (!empty($mk->nip_dosen)) {
                    $nips = array_map('trim', explode(',', $mk->nip_dosen));
                    $nipDosens = array_merge($nipDosens, $nips);
                }
            }
            $nipDosens = array_unique($nipDosens);
            
            // Ambil dosen berdasarkan NIP
            if (!empty($nipDosens)) {
                $dosens = Dosen::whereIn('nip', $nipDosens)
                    ->orderBy('nama', 'asc')
                    ->get();
            } else {
                $dosens = collect();
            }
            
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
            // Filter dosen berdasarkan mata kuliah yang diampu di kelas ini
            $mataKuliahKelasDosen = MataKuliah::where('kelas', $kelasItem)->get();
            
            // Ambil semua NIP dosen yang mengampu di kelas ini
            $nipDosensKelas = [];
            foreach ($mataKuliahKelasDosen as $mk) {
                if (!empty($mk->nip_dosen)) {
                    $nips = array_map('trim', explode(',', $mk->nip_dosen));
                    $nipDosensKelas = array_merge($nipDosensKelas, $nips);
                }
            }
            $nipDosensKelas = array_unique($nipDosensKelas);
            
            // Ambil dosen berdasarkan NIP
            if (!empty($nipDosensKelas)) {
                $dosenByKelas[$kelasItem] = Dosen::whereIn('nip', $nipDosensKelas)
                    ->orderBy('nama', 'asc')
                    ->get();
            } else {
                $dosenByKelas[$kelasItem] = collect();
            }
            
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
