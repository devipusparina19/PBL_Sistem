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
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6'
        ]);

        // Simpan user baru
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Login otomatis setelah register
        Auth::login($user);

        // ✅ arahkan ke halaman home di folder login
        return redirect()->route('login.home');
    }

    // ✅ Proses login
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return redirect()->route('login.home');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah',
        ]);
    }

    // ✅ Logout
    public function logout()
    {
        Auth::logout();
        return redirect()->route('user.showLogin');
    }

    // ✅ Halaman home
    public function home()
    {
        return view('login.home');
    }
}
