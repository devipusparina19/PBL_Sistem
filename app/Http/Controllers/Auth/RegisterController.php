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
            'role' => 'required|string',
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

        // Simpan ke database
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'role_kelompok' => $request->role_kelompok,
            'role_di_kelompok' => $request->role_di_kelompok,
        ]);

        // Login otomatis setelah daftar
        auth()->login($user);

        // Arahkan ke dashboard sesuai role
        if ($user->role === 'mahasiswa') {
            return redirect('/dashboard/mahasiswa');
        } elseif ($user->role === 'dosen') {
            return redirect('/dashboard/dosen');
        } elseif ($user->role === 'admin') {
            return redirect('/dashboard/admin');
        } elseif ($user->role === 'koordinator_pbl') {
            return redirect('/dashboard/koordinator-pbl');
        } elseif ($user->role === 'koordinator_prodi') {
            return redirect('/dashboard/koordinator-prodi');
        } else {
            return redirect('/dashboard');
        }
    }
}
