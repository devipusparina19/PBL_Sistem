<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\MataKuliahController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KelompokController;
use App\Http\Controllers\LogbookController;
use App\Http\Controllers\MilestoneController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AkunController;
use App\Http\Controllers\NilaiController;
use App\Http\Controllers\LoginController;

/*
|--------------------------------------------------------------------------
| AUTH & LOGIN / REGISTER
|--------------------------------------------------------------------------
*/

// ================================
// HOME / DASHBOARD
// ================================
Route::middleware(['auth'])->group(function () {
    Route::get('/home', [DashboardController::class, 'index'])->name('home');
});

// Halaman utama langsung ke halaman login
Route::get('/', [LoginController::class, 'showLogin'])->name('user.showLogin');

// Register
Route::get('/register', [LoginController::class, 'showRegister'])->name('user.showRegister');
Route::post('/register', [LoginController::class, 'register'])->name('user.register');

// Login
Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('user.login');

// Logout
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

// Login via Google
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('login.google');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('login.google.callback');

// Halaman Contact publik
Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

/*
|--------------------------------------------------------------------------
| ROUTE YANG BUTUH LOGIN
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // ======= Dashboard Per Role =======
    Route::get('/dashboard/mahasiswa', fn() => view('dashboard.mahasiswa'))->name('mahasiswa.dashboard');
    Route::get('/dashboard/dosen', fn() => view('dashboard.dosen'))->name('dosen.dashboard');
    Route::get('/dashboard/admin', fn() => view('dashboard.admin'))->name('admin.dashboard');
    Route::get('/dashboard/koordinator_pbl', fn() => view('dashboard.koordinator_pbl'))->name('koordinator_pbl.dashboard');
    Route::get('/dashboard/koordinator_prodi', fn() => view('dashboard.koordinator_prodi'))->name('koordinator_prodi.dashboard');

    // ======= CRUD Data =======
    Route::resource('mahasiswa', MahasiswaController::class);
    Route::resource('dosen', DosenController::class);
    Route::resource('kelompok', KelompokController::class);
    Route::resource('logbook', LogbookController::class);

    /*
    |--------------------------------------------------------------------------
    | MILESTONE ROUTES
    |--------------------------------------------------------------------------
    */
    Route::get('/milestone/view', [MilestoneController::class, 'indexForMember'])->name('milestone.view');
    Route::get('/milestone/input/{id}', [MilestoneController::class, 'create'])->name('milestone.create');
    Route::post('/milestone/input/{id}', [MilestoneController::class, 'store'])->name('milestone.store');
    Route::get('/milestone/edit/{id}', [MilestoneController::class, 'edit'])->name('milestone.edit');
    Route::post('/milestone/edit/{id}', [MilestoneController::class, 'update'])->name('milestone.update');

    // ======= Validasi Dosen =======
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
    | PROFIL PENGGUNA
    |--------------------------------------------------------------------------
    */
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    // Dashboard Kelompok
    Route::get('/dashboard/kelompok', fn() => view('dashboard.kelompok'))->name('dashboard.kelompok');
});

/*
|--------------------------------------------------------------------------
| ROUTE LAINNYA (AUTENTIKASI)
|--------------------------------------------------------------------------
*/

// Mata Kuliah
Route::resource('mata_kuliah', MataKuliahController::class)->middleware('auth');

// User
Route::resource('user', UserController::class)->middleware('auth');
Route::get('/user', [UserController::class, 'index'])->name('user.index')->middleware('auth');
Route::get('/user/create', [UserController::class, 'create'])->name('user.create')->middleware('auth');
Route::post('/user', [UserController::class, 'store'])->name('user.store')->middleware('auth');
Route::get('/user/{id}', [UserController::class, 'show'])->name('user.show')->middleware('auth');
Route::get('/user/{id}/edit', [UserController::class, 'edit'])->name('user.edit')->middleware('auth');
Route::put('/user/{id}', [UserController::class, 'update'])->name('user.update')->middleware('auth');
Route::delete('/user/{id}', [UserController::class, 'destroy'])->name('user.destroy')->middleware('auth');

// Dosen
Route::get('/dosen', [DosenController::class, 'index'])->name('dosen.index')->middleware('auth');
Route::get('/dosen/create', [DosenController::class, 'create'])->name('dosen.create')->middleware('auth');
Route::post('/dosen', [DosenController::class, 'store'])->name('dosen.store')->middleware('auth');
Route::get('/dosen/{id}', [DosenController::class, 'show'])->name('dosen.show')->middleware('auth');
Route::get('/dosen/{id}/edit', [DosenController::class, 'edit'])->name('dosen.edit')->middleware('auth');
Route::put('/dosen/{id}', [DosenController::class, 'update'])->name('dosen.update')->middleware('auth');
Route::delete('/dosen/{id}', [DosenController::class, 'destroy'])->name('dosen.destroy')->middleware('auth');

// Mahasiswa
Route::get('/mahasiswa', [MahasiswaController::class, 'index'])->name('mahasiswa.index')->middleware('auth');
Route::get('/mahasiswa/create', [MahasiswaController::class, 'create'])->name('mahasiswa.create')->middleware('auth');
Route::post('/mahasiswa', [MahasiswaController::class, 'store'])->name('mahasiswa.store')->middleware('auth');
Route::get('/mahasiswa/{id}', [MahasiswaController::class, 'show'])->name('mahasiswa.show')->middleware('auth');
Route::get('/mahasiswa/{id}/edit', [MahasiswaController::class, 'edit'])->name('mahasiswa.edit')->middleware('auth');
Route::put('/mahasiswa/{id}', [MahasiswaController::class, 'update'])->name('mahasiswa.update')->middleware('auth');
Route::delete('/mahasiswa/{id}', [MahasiswaController::class, 'destroy'])->name('mahasiswa.destroy')->middleware('auth');

// Kelompok
Route::get('/kelompok', [KelompokController::class, 'index'])->name('kelompok.index')->middleware('auth');
Route::get('/kelompok/create', [KelompokController::class, 'create'])->name('kelompok.create')->middleware('auth');
Route::post('/kelompok', [KelompokController::class, 'store'])->name('kelompok.store')->middleware('auth');
Route::get('/kelompok/{id}', [KelompokController::class, 'show'])->name('kelompok.show')->middleware('auth');
Route::get('/kelompok/{id}/edit', [KelompokController::class, 'edit'])->name('kelompok.edit')->middleware('auth');
Route::put('/kelompok/{id}', [KelompokController::class, 'update'])->name('kelompok.update')->middleware('auth');
Route::delete('/kelompok/{id}', [KelompokController::class, 'destroy'])->name('kelompok.destroy')->middleware('auth');

// Logbook
Route::get('/logbook', [LogbookController::class, 'index'])->name('logbook.index')->middleware('auth');
Route::get('/logbook/create', [LogbookController::class, 'create'])->name('logbook.create')->middleware('auth');
Route::post('/logbook', [LogbookController::class, 'store'])->name('logbook.store')->middleware('auth');
Route::get('/logbook/{id}', [LogbookController::class, 'show'])->name('logbook.show')->middleware('auth');
Route::get('/logbook/{id}/edit', [LogbookController::class, 'edit'])->name('logbook.edit')->middleware('auth');
Route::put('/logbook/{id}', [LogbookController::class, 'update'])->name('logbook.update')->middleware('auth');
Route::delete('/logbook/{id}', [LogbookController::class, 'destroy'])->name('logbook.destroy')->middleware('auth');