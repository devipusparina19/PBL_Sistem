<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    // Tampilkan halaman register
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // Proses pendaftaran user baru
   public function register(Request $request)
{
    // Validasi input
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:6|confirmed',
        'role' => 'nullable|string', // ubah ke nullable karena nanti otomatis
        'role_kelompok' => 'nullable|string',
        'role_di_kelompok' => 'nullable|string',
    ]);

    // Validasi domain email Politala
    if (
        !str_ends_with($request->email, '@politala.ac.id') &&
        !str_ends_with($request->email, '@mhs.politala.ac.id')
    ) {
        return back()->withErrors(['email' => 'Hanya email Politala yang diperbolehkan mendaftar'])->withInput();
    }

    // Tentukan role otomatis berdasarkan domain
    $role = $request->role; // default dari input (untuk admin/dosen jika ada)

    if (str_ends_with($request->email, '@mhs.politala.ac.id')) {
        $role = 'mahasiswa';
    } elseif (str_ends_with($request->email, '@politala.ac.id')) {
        // Jika tidak diisi manual, set default dosen
        $role = $role ?? 'dosen';
    }

    // Simpan user ke database
    $user = \App\Models\User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => $role,
        'role_kelompok' => $request->role_kelompok,
        'role_di_kelompok' => $request->role_di_kelompok,
    ]);

    // Login langsung setelah register
    Auth::login($user);

    // Redirect sesuai role
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
