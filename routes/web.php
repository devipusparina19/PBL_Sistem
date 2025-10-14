<?php

use Illuminate\Support\Facades\Route;

// Controllers
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
use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\RangkingController;
use App\Http\Controllers\PenilaianController;

/*
|--------------------------------------------------------------------------
| AUTH & LOGIN / REGISTER
|--------------------------------------------------------------------------
*/

// Home / Dashboard
Route::middleware(['auth'])->group(function () {
    Route::get('/home', [DashboardController::class, 'index'])->name('home');
});

// Halaman utama langsung ke login
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

// Contact Publik
Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// About Page
Route::get('/about', function () {
    return view('about');
})->name('about');

/*
|--------------------------------------------------------------------------
| ROUTE YANG BUTUH LOGIN
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD PER ROLE
    |--------------------------------------------------------------------------
    */
    Route::get('/dashboard/mahasiswa', fn() => view('dashboard.mahasiswa'))->name('mahasiswa.dashboard');
    Route::get('/dashboard/dosen', fn() => view('dashboard.dosen'))->name('dosen.dashboard');
    Route::get('/dashboard/admin', fn() => view('dashboard.admin'))->name('admin.dashboard');
    Route::get('/dashboard/koordinator_pbl', fn() => view('dashboard.koordinator_pbl'))->name('koordinator_pbl.dashboard');
    Route::get('/dashboard/koordinator_prodi', fn() => view('dashboard.koordinator_prodi'))->name('koordinator_prodi.dashboard');
    Route::get('/dashboard/kelompok', fn() => view('dashboard.kelompok'))->name('dashboard.kelompok');

    /*
    |--------------------------------------------------------------------------
    | CRUD DATA
    |--------------------------------------------------------------------------
    */
    Route::resource('mahasiswa', MahasiswaController::class);
    Route::resource('data_dosen', DosenController::class);
    Route::resource('mata_kuliah', MataKuliahController::class);
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

    // Validasi Dosen
    Route::middleware('role:dosen')->group(function () {
        Route::get('/milestone/validasi', [MilestoneController::class, 'indexForDosen'])->name('milestone.validasi');
        Route::post('/milestone/validasi/{id}', [MilestoneController::class, 'updateStatus'])->name('milestone.updateStatus');
    });

    /*
    |--------------------------------------------------------------------------
    | NILAI MAHASISWA
    |--------------------------------------------------------------------------
    */
    Route::middleware(['auth', 'role:dosen'])->group(function () {
        Route::get('/nilai', [NilaiController::class, 'index'])->name('nilai.index');
        Route::get('/nilai/pilih-matkul', [NilaiController::class, 'pilihMatkul'])
            ->middleware(['auth', 'role:dosen'])
            ->name('nilai.pilihMatkul');
        Route::get('/nilai/input/{matkul}', [NilaiController::class, 'create'])->name('nilai.create');
        Route::post('/nilai/store/{matkul}', [NilaiController::class, 'store'])->name('nilai.store');
        Route::get('/nilai/{id}/edit', [NilaiController::class, 'edit'])->name('nilai.edit');
        Route::put('/nilai/{id}', [NilaiController::class, 'update'])->name('nilai.update');
        Route::delete('/nilai/{id}', [NilaiController::class, 'destroy'])->name('nilai.destroy');
        Route::get('/nilai/input/{id}', [NilaiController::class, 'inputNilai'])->name('nilai.input');
    });

    /*
    |--------------------------------------------------------------------------
    | MONITORING PROGRES KELOMPOK (KOORDINATOR)
    |--------------------------------------------------------------------------
    */
    Route::get('/monitoring', [MonitoringController::class, 'index'])->name('monitoring.index');
    Route::get('/monitoring/{id}', [MonitoringController::class, 'show'])->name('monitoring.show');

    /*
    |--------------------------------------------------------------------------
    | RANGKING KELOMPOK BERDASARKAN NILAI
    |--------------------------------------------------------------------------
    */
    Route::get('/kelompok/rangking', [RangkingController::class, 'index'])->name('kelompok.rangking');

    /*
    |--------------------------------------------------------------------------
    | MANAJEMEN AKUN (ADMIN)
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
});

/*
|--------------------------------------------------------------------------
| ROUTE TAMBAHAN (AUTENTIKASI DASAR)
|--------------------------------------------------------------------------
*/
Route::resource('mata_kuliah', MataKuliahController::class)->middleware('auth');
Route::resource('user', UserController::class)->middleware('auth');
Route::resource('dosen', DosenController::class)->middleware('auth');
Route::resource('mahasiswa', MahasiswaController::class)->middleware('auth');
Route::resource('kelompok', KelompokController::class)->middleware('auth');
Route::resource('logbook', LogbookController::class)->middleware('auth');

/*
|--------------------------------------------------------------------------
| PENILAIAN DOSEN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:dosen'])->group(function () {
    Route::get('/dosen/matkul', [PenilaianController::class, 'daftarMatkul'])->name('dosen.matkul');
    Route::get('/dosen/matkul/{id}/nilai', [PenilaianController::class, 'formNilai'])->name('dosen.input.nilai');
    Route::post('/dosen/matkul/{id}/nilai', [PenilaianController::class, 'store'])->name('dosen.nilai.store');
});
