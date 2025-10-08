<?php

use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\UserController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KelompokController;
use App\Http\Controllers\LogbookController;
use App\Http\Controllers\MataKuliahController;
use App\Http\Controllers\MilestoneController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AkunController;
use App\Http\Controllers\NilaiController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES (TANPA LOGIN)
|--------------------------------------------------------------------------
*/

// Halaman utama diarahkan ke register
Route::get('/', [UserController::class, 'showRegister'])->name('user.showRegister');

// Register
Route::get('/register', [UserController::class, 'showRegister'])->name('register.form');
Route::post('/register', [RegisterController::class, 'register'])->name('register');

// Login
Route::get('/login', [UserController::class, 'showLogin'])->name('login.form');
Route::post('/login', [UserController::class, 'login'])->name('login');

// Login via Google
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('login.google');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('login.google.callback');

// Logout
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

// Kontak publik
Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');


/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES (BUTUH LOGIN)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Dashboard umum
    Route::get('/home', fn() => view('home.home'))->name('home');
    Route::get('/about', fn() => view('about'))->name('about');
    Route::get('/contact/dashboard', fn() => view('contact'))->name('contact.dashboard');

    // Dashboard utama
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Dashboard per role
    Route::view('/dashboard/mahasiswa', 'dashboard.mahasiswa')->name('mahasiswa.dashboard');
    Route::view('/dashboard/dosen', 'dashboard.dosen')->name('dosen.dashboard');
    Route::view('/dashboard/admin', 'dashboard.admin')->name('admin.dashboard');
    Route::view('/dashboard/koordinator_pbl', 'dashboard.koordinator_pbl')->name('koordinator_pbl.dashboard');
    Route::view('/dashboard/koordinator_prodi', 'dashboard.koordinator_prodi')->name('koordinator_prodi.dashboard');
    Route::view('/dashboard/kelompok', 'dashboard.kelompok')->name('kelompok.dashboard');

    // CRUD data
    Route::resource('mahasiswa', MahasiswaController::class);
    Route::resource('data_dosen', DosenController::class);
    Route::resource('mata_kuliah', MataKuliahController::class);
    Route::resource('kelompok', KelompokController::class);
    Route::resource('logbook', LogbookController::class);

    // =====================
    // Milestone Routes
    // =====================
    
    // Member Routes
    Route::get('/milestone/view', [MilestoneController::class, 'indexForMember'])->name('milestone.view');
    Route::get('/milestone/input/{id}', [MilestoneController::class, 'create'])->name('milestone.create');
    Route::post('/milestone/input/{id}', [MilestoneController::class, 'store'])->name('milestone.store');
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

    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
});
