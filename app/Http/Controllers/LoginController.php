<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Mahasiswa;

class LoginController extends Controller
{
    // Tampilkan form login
    public function showLogin()
    {
        return view('login.login'); // resources/views/login/login.blade.php
    }

    // Proses login
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate(); // buat sesi baru setelah login
            return redirect()->route('dashboard'); // arahkan ke dashboard
        }

        return back()->withErrors(['email' => 'Email atau password salah.']);
    }

    // Proses logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();      
        $request->session()->regenerateToken();
        return redirect()->route('user.showLogin');
    }

    // Tampilkan form register mahasiswa
    public function showRegister()
    {
        return view('login.register'); // buat resources/views/login/register.blade.php
    }

    // Proses register mahasiswa
    public function register(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'nim' => 'required|string|unique:mahasiswas,nim',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Buat user untuk login
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'mahasiswa',
        ]);

        // Buat data mahasiswa di tabel mahasiswa
        Mahasiswa::create([
            'user_id' => $user->id,
            'nim' => $request->nim,
            'nama' => $request->name,
            'angkatan' => date('Y'),
            'email' => $request->email,
        ]);

        // Login otomatis
        Auth::login($user);

        return redirect()->route('dashboard');
    }
}
