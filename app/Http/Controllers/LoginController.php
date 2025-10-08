<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Mahasiswa;

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

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Arahkan langsung ke dashboard sesuai role
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
                    return redirect()->route('user.showLogin')->withErrors([
                        'email' => 'Role pengguna tidak dikenali.',
                    ]);
            }
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }

    // ===== PROSES LOGOUT =====
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('user.showLogin');
    }

    // ===== FORM REGISTER =====
    public function showRegister()
    {
        return view('login.register');
    }

    // ===== PROSES REGISTER =====
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nim' => 'required|string|unique:mahasiswas,nim',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Buat akun user (default mahasiswa)
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'mahasiswa',
        ]);

        // Tambahkan ke tabel mahasiswa
        Mahasiswa::create([
            'user_id' => $user->id,
            'nim' => $request->nim,
            'nama' => $request->name,
            'angkatan' => date('Y'),
            'email' => $request->email,
        ]);

        // Login otomatis
        Auth::login($user);

        return redirect()->route('mahasiswa.dashboard');
    }
}
