<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // ✅ Tampilkan form register
    public function showRegister()
    {
        return view('login.register');
    }

    // ✅ Tampilkan form login
    public function showLogin()
    {
        return view('login.login');
    }

    // ✅ Proses register
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|min:3',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed', 
            'role'     => 'required|in:admin,dosen,mahasiswa,koordinator_pbl,koordinator_prodi',
        ]);

        // Simpan user baru
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
        ]);

        // Login otomatis setelah register
        Auth::login($user);

        // ✅ Arahkan ke dashboard sesuai role
        return redirect()->route('pbl.dashboard');
    }

    // ✅ Proses login
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // ✅ Arahkan ke dashboard sesuai role
            return redirect()->route('pbl.dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah',
        ]);
    }

    // ✅ Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('user.showLogin');
    }

    // (Opsional) Halaman home lama
    public function home()
    {
        return view('login.home');
    }
}
