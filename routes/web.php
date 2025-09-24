<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use Illuminate\Http\Request;

// Halaman utama langsung ke register
Route::get('/', [UserController::class, 'showRegister'])->name('user.showRegister');

// Mahasiswa CRUD
Route::resource('mahasiswa', MahasiswaController::class);

// Register
Route::get('/register', [UserController::class, 'showRegister'])->name('user.showRegister');
Route::post('/register', [UserController::class, 'register'])->name('user.register');

// Login
Route::get('/login', [UserController::class, 'showLogin'])->name('user.showLogin');
Route::post('/login', [UserController::class, 'login'])->name('user.login');

// Logout (POST → best practice)
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('user.showLogin');
})->name('user.logout');

// Home (hanya untuk user yang sudah login)
Route::get('/home', [UserController::class, 'home'])
    ->name('login.home')
    ->middleware('auth');

// ===============================
// 🚀 Tambahan untuk Dashboard PBL
// ===============================
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('pbl.dashboard');

    Route::get('/mahasiswa', [DashboardController::class, 'mahasiswa'])->name('pbl.mahasiswa');
    Route::get('/dosen', [DashboardController::class, 'dosen'])->name('pbl.dosen');
    Route::get('/milestone', [DashboardController::class, 'milestone'])->name('pbl.milestone');
    Route::get('/koor', [DashboardController::class, 'koor'])->name('pbl.koor');
    Route::get('/ranking', [DashboardController::class, 'ranking'])->name('pbl.ranking');
});
