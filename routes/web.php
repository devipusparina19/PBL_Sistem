<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

// Controllers
use App\Http\Controllers\UserController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KelompokController;
use App\Http\Controllers\MilestoneController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Auth\GoogleController; // ✅ Tambahan controller Google
use App\Http\Controllers\ProfileController; // ✅ Tambahkan biar route profile dikenali

/*
|--------------------------------------------------------------------------
| Public Routes (Tanpa Login)
|--------------------------------------------------------------------------
*/

// Halaman utama langsung ke register
Route::get('/', [UserController::class, 'showRegister'])->name('user.showRegister');

// Register
Route::get('/register', [UserController::class, 'showRegister'])->name('user.showRegister');
Route::post('/register', [UserController::class, 'store'])->name('user.store');

// Login
Route::get('/login', [UserController::class, 'showLogin'])->name('user.showLogin');
Route::post('/login', [UserController::class, 'login'])->name('user.login');

// ✅ Tambahan: Login via Google
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('login.google');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('login.google.callback');

// Logout
Route::post('/logout', [UserController::class, 'logout'])->name('logout');

// Contact (Publik)
Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

/*
|--------------------------------------------------------------------------
| Protected Routes (Butuh Login)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/home', function () {
        return view('home.home'); 
    })->name('home');

    // ✅ About Page
    Route::get('/about', function () {
        return view('about');
    })->name('about');

    // ✅ Contact Page (versi dalam login)
    Route::get('/contact', function () {
        return view('contact');
    })->name('contact');

    // Dashboard umum
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('pbl.dashboard');

    // Dashboard per role
    Route::get('/dashboard/mahasiswa', fn() => view('dashboard.mahasiswa'))->name('mahasiswa.dashboard');
    Route::get('/dashboard/dosen', fn() => view('dashboard.dosen'))->name('dosen.dashboard');
    Route::get('/dashboard/admin', fn() => view('dashboard.admin'))->name('admin.dashboard');
    Route::get('/dashboard/koordinator_pbl', fn() => view('dashboard.koordinator_pbl'))->name('koordinator_pbl.dashboard');
    Route::get('/dashboard/koordinator_prodi', fn() => view('dashboard.koordinator_prodi'))->name('koordinator_prodi.dashboard');
    
    /*
    |--------------------------------------------------------------------------
    | Resource CRUD Routes
    |--------------------------------------------------------------------------
    */
    
    Route::resource('mahasiswa', MahasiswaController::class);
    Route::resource('data_dosen', DosenController::class);
    Route::resource('kelompok', KelompokController::class);
    Route::resource('milestones', MilestoneController::class);

    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    // Dosen Resource tambahan (boleh tetap)
    Route::resource('dosen', DosenController::class);
});
