<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Exception;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // ✅ Validasi domain email Politala
            if (
                !str_ends_with($googleUser->getEmail(), '@politala.ac.id') &&
                !str_ends_with($googleUser->getEmail(), '@mhs.politala.ac.id')
            ) {
                return redirect('/login')->with('error', 'Gunakan email Politala untuk login.');
            }

            $email = strtolower($googleUser->getEmail()); // pakai lowercase biar konsisten
            $existingUser = User::where('email', $email)->first();

            // ✅ Tentukan role berdasarkan domain email
            $role = str_ends_with($email, '@mhs.politala.ac.id') ? 'mahasiswa' : 'dosen';

            if ($existingUser) {
                // ✅ Kalau user sudah register manual — jangan ubah password-nya
                $existingUser->update([
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'role' => $existingUser->role ?? $role,
                ]);

                $user = $existingUser;
            } else {
                // ✅ Kalau belum pernah register manual — buat akun baru pakai password random
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $email,
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'password' => Hash::make(Str::random(16)), // random hanya untuk akun baru
                    'role' => $role,
                ]);
            }

            // ✅ Login user
            Auth::login($user);

            // ✅ Arahkan sesuai role
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
                    return redirect('/login')->with('error', 'Role tidak dikenali.');
            }

        } catch (Exception $e) {
            \Log::error('Google Login Error: ' . $e->getMessage());
            return redirect('/login')->with('error', 'Gagal login menggunakan Google.');
        }
    }
}
