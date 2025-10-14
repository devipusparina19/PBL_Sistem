<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginController extends Controller
{
    // ===== TAMPILKAN FORM LOGIN =====
    public function showLogin()
    {
        return view('login.login');
    }

    // ===== PROSES LOGIN =====
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        // Validasi domain email Politala
        if (
            !str_ends_with($request->email, '@politala.ac.id') &&
            !str_ends_with($request->email, '@mhs.politala.ac.id')
        ) {
            return back()->withErrors([
                'email' => 'Hanya email Politala yang diperbolehkan login.',
            ]);
        }

        // Proses autentikasi manual
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            // Arahkan sesuai role
            return $this->redirectByRole($user);
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }

    // ===== LOGOUT =====
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    // ===== TAMPILKAN FORM REGISTER =====
    public function showRegister()
    {
        return view('login.register');
    }

    // ===== PROSES REGISTER =====
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|string|in:mahasiswa,dosen,admin,koordinator_pbl,koordinator_prodi',
        ]);

        // Validasi domain Politala
        if (
            !str_ends_with($request->email, '@politala.ac.id') &&
            !str_ends_with($request->email, '@mhs.politala.ac.id')
        ) {
            return back()->withErrors([
                'email' => 'Gunakan email Politala yang valid.',
            ]);
        }

        // Buat akun baru
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        Auth::login($user);
        return $this->redirectByRole($user);
    }

    // ===== ARAHKAN BERDASARKAN ROLE =====
    private function redirectByRole($user)
    {
        switch ($user->role) {
            case 'mahasiswa':
                return redirect()->route('mahasiswa.dashboard');
            case 'dosen':
                return redirect()->route('dosen.dashboard');
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'koordinator_pbl':
                return redirect()->route('koordinator_pbl.dashboard');
            case 'koordinator_prodi':
                return redirect()->route('koordinator_prodi.dashboard');
            default:
                Auth::logout();
                return redirect()->route('login')->withErrors([
                    'email' => 'Role pengguna tidak dikenali.',
                ]);
        }
    }
}
