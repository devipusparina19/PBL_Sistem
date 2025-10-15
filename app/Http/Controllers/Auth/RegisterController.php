<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class RegisterController extends Controller
{
    // ✅ Tampilkan halaman register
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // ✅ Proses pendaftaran user baru
    public function register(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|string',
            'nim_nip' => 'required|string|max:50',
            'kelas' => 'nullable|string|max:50',
            'role_kelompok' => 'nullable|string|max:50',
            'role_di_kelompok' => 'nullable|string|max:50',
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

        // ✅ Tentukan role otomatis berdasarkan domain
        $role = $request->role;

        if (str_ends_with($request->email, '@mhs.politala.ac.id')) {
            $role = 'mahasiswa';
        } elseif (str_ends_with($request->email, '@politala.ac.id')) {
            $role = $role ?? 'dosen';
        }

        // ✅ Simpan user ke database
        $user = User::create([
            'name' => $request->name,
            'email' => strtolower($request->email),
            'password' => Hash::make($request->password),
            'role' => $role,
            'nim_nip' => $request->nim_nip,
            'kelas' => $request->kelas,
            'role_kelompok' => $request->role_kelompok,
            'role_di_kelompok' => $request->role_di_kelompok,
        ]);

        // ✅ Login langsung setelah register
        Auth::login($user);

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
