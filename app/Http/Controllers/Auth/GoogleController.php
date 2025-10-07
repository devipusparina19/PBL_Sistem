<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
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

            // Cek apakah email dari domain Politala
            if (
                !str_ends_with($googleUser->getEmail(), '@politala.ac.id') &&
                !str_ends_with($googleUser->getEmail(), '@mhs.politala.ac.id')
            ) {
                return redirect('/login')->with('error', 'Gunakan email Politala untuk login.');
            }

            // Simpan atau update user
            $user = User::updateOrCreate(
                ['email' => $googleUser->getEmail()],
                [
                    'name' => $googleUser->getName(),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'password' => $user->password ?? null,
                ]
            );

            // Login-kan user
            Auth::login($user);

            return redirect('/dashboard');
        } catch (Exception $e) {
            \Log::error('Google Login Error: ' . $e->getMessage());
            return redirect('/login')->with('error', 'Gagal login menggunakan Google.');
        }
    }
}
