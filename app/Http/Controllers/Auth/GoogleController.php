<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Exception;

class GoogleController extends Controller
{
    // Redirect ke halaman Google
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    // Callback dari Google
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Debug: log data user dari Google
            Log::info('Google User Data:', (array) $googleUser);

            // Cek apakah email dari domain Politala
            if (
                !str_ends_with($googleUser->getEmail(), '@politala.ac.id') &&
                !str_ends_with($googleUser->getEmail(), '@mhs.politala.ac.id')
            ) {
                return redirect('/login')->with('error', 'Gunakan email Politala untuk login.');
            }

            // Simpan atau update user di database
            $user = User::updateOrCreate(
                ['email' => $googleUser->getEmail()],
                [
                    'name'       => $googleUser->getName(),
                    'google_id'  => $googleUser->getId(),
                    'avatar'     => $googleUser->getAvatar(),
                    'password'   => bcrypt(Str::random(16)), // password random
                ]
            );

            // Login user
            Auth::login($user);

            // Redirect ke dashboard
            return redirect()->intended('/dashboard');

        } catch (Exception $e) {
            Log::error('Google Login Error: ' . $e->getMessage());
            return redirect('/login')->with('error', 'Gagal login menggunakan Google.');
        }
    }
}
