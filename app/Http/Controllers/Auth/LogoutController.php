<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    /**
     * Proses logout pengguna dari sistem (manual maupun Google).
     */
    public function logout(Request $request)
    {
        // Logout user
        Auth::logout();

        // Hapus semua session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Arahkan ke halaman login dengan notifikasi sukses
        return redirect()->route('login')->with('success', 'Anda telah berhasil logout.');
    }
}
