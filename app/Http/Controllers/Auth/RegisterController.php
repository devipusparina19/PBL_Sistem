<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Kelompok;
use App\Models\Mahasiswa;

class RegisterController extends Controller
{
    // ✅ Tampilkan halaman register
    // ✅ Tampilkan halaman register
    public function showRegisterForm()
    {
        // Data Kelas hardcoded sesuai MahasiswaController
        $kelasList = ['3A', '3B', '3C', '3D', '3E'];
        
        // Ambil data kelompok dari database
        $kelompokList = Kelompok::all();

        return view('auth.register', compact('kelasList', 'kelompokList'));
    }

    // ✅ Proses pendaftaran user baru
    public function register(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'nim_nip' => 'required|string|max:50|unique:users',
            'kelas' => 'required|string', // Wajib bagi mahasiswa
            'role_kelompok' => 'nullable|string',
            'role_di_kelompok' => 'nullable|string|in:Ketua,Anggota',
        ]);

        // Validasi domain email Politala
        if (
            !str_ends_with($request->email, '@politala.ac.id') &&
            !str_ends_with($request->email, '@mhs.politala.ac.id')
        ) {
            return back()
                ->withErrors(['email' => 'Hanya email Politala yang diperbolehkan mendaftar'])
                ->withInput();
        }

        // ✅ Default role mahasiswa, sisanya null (diisi admin)
        $role = 'mahasiswa';

        // ✅ Simpan user ke database
        $user = User::create([
            'name' => $request->name,
            'email' => strtolower($request->email),
            'password' => Hash::make($request->password),
            'role' => $role,
            'nim_nip' => $request->nim_nip,
            'kelas' => $request->kelas,
            'role_kelompok' => $request->role_kelompok, // Save ID or Name to user? User table role_kelompok is string. Let's save ID or Name? The view now sends ID. Let's save ID to be consistent if possible, OR if User table expects name we might need to find name. BUT, the main request is to save to Data Kelompok.
            // User table 'role_kelompok' seems to be a legacy/redundant string field based on previous conversations/code.
            // We will save the ID here for reference or nullable.
            'role_di_kelompok' => $request->role_di_kelompok,
        ]);

        // ✅ Login langsung setelah register
        Auth::login($user);

        // ✅ Create Mahasiswa Record
        // ✅ Create Mahasiswa Record
        if ($role === 'mahasiswa') {
            $kelompokId = null;
            if ($request->role_kelompok) {
                // Construct specific name: Kelompok 1 (3A)
                $namaKelompok = 'Kelompok ' . $request->role_kelompok . ' (' . $request->kelas . ')';
                
                // Auto-create group if not exists
                $kelompok = Kelompok::firstOrCreate(
                    ['nama_kelompok' => $namaKelompok, 'kelas' => $request->kelas],
                    [
                        'kode_mk' => 'PBL-' . $request->kelas, // Default code
                        'judul_proyek' => 'Belum ada judul',
                    ]
                );
                
                $kelompokId = $kelompok->id_kelompok;
            }

            $mahasiswa = Mahasiswa::updateOrCreate(
                ['nim' => $request->nim_nip],
                [
                    'nama' => $request->name,
                    'kelas' => $request->kelas,
                    'angkatan' => '2023',
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'kelompok_id' => $kelompokId,
                ]
            );

            // ✅ Update Ketua Kelompok if selected
            if ($kelompokId && $request->role_di_kelompok === 'Ketua') {
                $kelompok = Kelompok::find($kelompokId);
                if ($kelompok) {
                    $kelompok->update(['ketua_id' => $mahasiswa->id]);
                }
            }
        }

        // ✅ Redirect sesuai role
        switch ($role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'koordinator_pbl':
                return redirect()->route('koordinator_pbl.dashboard');
            case 'koordinator_prodi':
                return redirect()->route('koordinator_prodi.dashboard');
            case 'dosen':
                return redirect()->route('dosen.dashboard');
            case 'mahasiswa':
                return redirect()->route('mahasiswa.dashboard');
            default:
                return redirect()->route('home');
        }
    }
}
