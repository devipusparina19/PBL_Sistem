<?php

use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\UserController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KelompokController;
use App\Http\Controllers\LogbookController;
use App\Http\Controllers\MilestoneController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LogoutController;

/*
|--------------------------------------------------------------------------
| Public Routes (Tanpa Login)
|--------------------------------------------------------------------------
*/

// Halaman utama langsung ke register
Route::get('/', [UserController::class, 'showRegister'])->name('user.showRegister');
Route::get('/register', [UserController::class, 'showRegister'])->name('user.showRegister');
Route::post('/register', [RegisterController::class, 'register'])->name('register');

// Login
Route::get('/login', [UserController::class, 'showLogin'])->name('login.form');
Route::post('/login', [UserController::class, 'login'])->name('login');

// Register
Route::get('/register', [UserController::class, 'showRegister'])->name('register.form');
Route::post('/register', [RegisterController::class, 'register'])->name('register');

// Login via Google
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('login.google');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('login.google.callback');

// Contact publik
Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

/*
|--------------------------------------------------------------------------
| Protected Routes (Butuh Login)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Dashboard umum
    Route::get('/home', fn() => view('home.home'))->name('home');
    Route::get('/about', fn() => view('about'))->name('about');
    Route::get('/contact/dashboard', fn() => view('contact'))->name('contact.dashboard'); // ubah nama route untuk dashboard

    // Dashboard utama
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('pbl.dashboard');

    // Dashboard per role (akses langsung tanpa cek role)
    Route::get('/dashboard/mahasiswa', fn() => view('dashboard.mahasiswa'))->name('mahasiswa.dashboard');
    Route::get('/dashboard/dosen', fn() => view('dashboard.dosen'))->name('dosen.dashboard');
    Route::get('/dashboard/admin', fn() => view('dashboard.admin'))->name('admin.dashboard');
    Route::get('/dashboard/koordinator_pbl', fn() => view('dashboard.koordinator_pbl'))->name('koordinator_pbl.dashboard');
    Route::get('/dashboard/koordinator_prodi', fn() => view('dashboard.koordinator_prodi'))->name('koordinator_prodi.dashboard');

    // CRUD data
    Route::resource('mahasiswa', MahasiswaController::class);
    Route::resource('dosen', DosenController::class);
    Route::resource('kelompok', KelompokController::class);
    Route::resource('logbook', LogbookController::class);

    // =====================
    // Milestone Routes
    // =====================
    
    // Member Routes
    Route::get('/milestone/view', [MilestoneController::class, 'indexForMember'])->name('milestone.view');
    Route::get('/milestone/input/{id}', [MilestoneController::class, 'create'])->name('milestone.create');
    Route::post('/milestone/input/{id}', [MilestoneController::class, 'store'])->name('milestone.store');

    Route::get('/milestone/view', [MilestoneController::class, 'indexForMember'])->name('milestone.view');
    Route::get('/milestone/validasi', [MilestoneController::class, 'indexForDosen'])->name('milestone.validasi');
    Route::post('/milestone/validasi/{id}', [MilestoneController::class, 'validasi'])->name('milestone.validasi.submit');
    // Edit milestone
    Route::get('/milestone/edit/{id}', [MilestoneController::class, 'edit'])->name('milestone.edit');
    Route::post('/milestone/edit/{id}', [MilestoneController::class, 'update'])->name('milestone.update');

    // Dosen / Validasi Routes
    Route::middleware('role:dosen')->group(function () {
        Route::get('/milestone/validasi', [MilestoneController::class, 'indexForDosen'])->name('milestone.validasi');
        Route::post('/milestone/validasi/{id}', [MilestoneController::class, 'updateStatus'])->name('milestone.updateStatus');
    });

    /*
    |--------------------------------------------------------------------------
    | NILAI MAHASISWA (Dosen)
    |--------------------------------------------------------------------------
    */
    Route::get('/nilai', [NilaiController::class, 'index'])->name('nilai.index');
    Route::post('/nilai', [NilaiController::class, 'store'])->name('nilai.store');
    Route::get('/nilai/{id}/edit', [NilaiController::class, 'edit'])->name('nilai.edit');
    Route::put('/nilai/{id}', [NilaiController::class, 'update'])->name('nilai.update');
    Route::delete('/nilai/{id}', [NilaiController::class, 'destroy'])->name('nilai.destroy');

    /*
    |--------------------------------------------------------------------------
    | MANAJEMEN AKUN (Admin)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['isAdmin'])->group(function () {
        Route::get('/manajemen_akun', [AdminController::class, 'manajemenAkun'])->name('manajemen_akun');

        Route::get('/akun', [AkunController::class, 'index'])->name('akun.index');
        Route::get('/akun/create', [AkunController::class, 'create'])->name('akun.create');
        Route::post('/akun', [AkunController::class, 'store'])->name('akun.store');
        Route::get('/akun/edit/{id}', [AkunController::class, 'edit'])->name('akun.edit');
        Route::put('/akun/update/{id}', [AkunController::class, 'update'])->name('akun.update');
        Route::delete('/akun/delete/{id}', [AkunController::class, 'destroy'])->name('akun.destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | PROFIL
    |--------------------------------------------------------------------------
    */
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    // Dashboard Kelompok (langsung tanpa middleware role)
    Route::get('/dashboard/kelompok', fn() => view('dashboard.kelompok'))->name('dashboard.kelompok');
});
