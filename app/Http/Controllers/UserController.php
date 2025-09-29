<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserController extends Controller
{
    /**
     * ✅ Tampilkan form register
     */
    public function showRegister()
    {
        return view('login.register'); 
        // resources/views/login/register.blade.php
    }

    /**
     * ✅ Tampilkan form login
     */
    public function showLogin()
    {
        return view('login.login'); 
        // resources/views/login/login.blade.php
    }

    /**
     * ✅ Proses register user baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role'     => 'required|string|in:mahasiswa,dosen,admin,koordinator_pbl,koordinator_prodi',
        ]);

        // Simpan user ke database
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
        ]);

        // Login otomatis setelah register
        Auth::login($user);

        // Redirect sesuai role
        return $this->redirectToDashboard($user);
    }

    /**
     * ✅ Proses login user
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return $this->redirectToDashboard(Auth::user());
        }

        return back()->withErrors([
            'email' => 'Email atau password salah!',
        ])->withInput();
    }

    /**
     * ✅ Proses logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('user.showLogin');
    }

    /**
     * ✅ Redirect sesuai role user
     */
    private function redirectToDashboard(User $user)
    {
        return match ($user->role) {
            'admin'             => redirect()->route('admin.dashboard'),
            'dosen'             => redirect()->route('dosen.dashboard'),
            'koordinator_pbl'   => redirect()->route('koordinatorpbl.dashboard'),
            'koordinator_prodi' => redirect()->route('koordinatorprodi.dashboard'),
            default             => redirect()->route('mahasiswa.dashboard'),
        };
    }

    /**
     * (Opsional) halaman home lama
     */
    public function home()
    {
        return view('login.home');
    }
}
