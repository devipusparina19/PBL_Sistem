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
use App\Http\Controllers\NilaiKelompokController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\RangkingController;
use App\Http\Controllers\ProgresController; // ✅ Tambahan

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

// ✅ About Page (TAMBAHAN)
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
    | RANGKING MAHASISWA & KELOMPOK
    | ⚠️ HARUS SEBELUM RESOURCE KELOMPOK agar tidak conflict
    |--------------------------------------------------------------------------
    */
    Route::get('/mahasiswa/rangking', [RangkingController::class, 'mahasiswa'])->name('mahasiswa.rangking');
    Route::get('/kelompok/rangking', [RangkingController::class, 'kelompok'])->name('kelompok.rangking');

    /*
    |--------------------------------------------------------------------------
    | CRUD DATA
    |--------------------------------------------------------------------------
    */
    Route::resource('mahasiswa', MahasiswaController::class);
    Route::resource('data_dosen', DosenController::class);
    Route::resource('mata_kuliah', MataKuliahController::class);
    Route::get('kelompok/kelas/{kelas}', [KelompokController::class, 'showByKelas'])->name('kelompok.kelas');
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
    Route::get('/nilai', [NilaiController::class, 'index'])->name('nilai.index');
    Route::get('/nilai/create', [NilaiController::class, 'create'])->name('nilai.create');
    Route::post('/nilai', [NilaiController::class, 'store'])->name('nilai.store');
    Route::get('/nilai/{id}/edit', [NilaiController::class, 'edit'])->name('nilai.edit');
    Route::put('/nilai/{id}', [NilaiController::class, 'update'])->name('nilai.update');
    Route::delete('/nilai/{id}', [NilaiController::class, 'destroy'])->name('nilai.destroy');

    /*
    |--------------------------------------------------------------------------
    | NILAI KELOMPOK - DOSEN
    |--------------------------------------------------------------------------
    */
    Route::get('/nilai-kelompok', [NilaiKelompokController::class, 'index'])->name('nilai-kelompok.index');
    Route::get('/nilai-kelompok/create', [NilaiKelompokController::class, 'create'])->name('nilai-kelompok.create');
    Route::post('/nilai-kelompok', [NilaiKelompokController::class, 'store'])->name('nilai-kelompok.store');
    Route::get('/nilai-kelompok/{id}/edit', [NilaiKelompokController::class, 'edit'])->name('nilai-kelompok.edit');
    Route::put('/nilai-kelompok/{id}', [NilaiKelompokController::class, 'update'])->name('nilai-kelompok.update');
    Route::delete('/nilai-kelompok/{id}', [NilaiKelompokController::class, 'destroy'])->name('nilai-kelompok.destroy');

    /*
    |--------------------------------------------------------------------------
    | MONITORING PROGRES KELOMPOK (KOORDINATOR)
    |--------------------------------------------------------------------------
    */
    Route::get('/monitoring', [MonitoringController::class, 'index'])->name('monitoring.index');
    Route::get('/monitoring/{id}', [MonitoringController::class, 'show'])->name('monitoring.show');

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

    /*
    |--------------------------------------------------------------------------
    | PROGRES KELOMPOK (KOORDINATOR & ADMIN)
    |--------------------------------------------------------------------------
    */
    // ✅ Tambahan baru untuk fitur Progres
    Route::middleware(['auth'])->group(function () {
        Route::get('/progres', [ProgresController::class, 'index'])->name('progres.index');
        Route::get('/progres/{id}', [ProgresController::class, 'show'])->name('progres.show'); // ✅ Tambahan: detail progres
        Route::get('/progres/create', [ProgresController::class, 'create'])->name('progres.create');
        Route::post('/progres', [ProgresController::class, 'store'])->name('progres.store');
        Route::get('/progres/{id}/edit', [ProgresController::class, 'edit'])->name('progres.edit');
        Route::put('/progres/{id}', [ProgresController::class, 'update'])->name('progres.update');
        Route::delete('/progres/{id}', [ProgresController::class, 'destroy'])->name('progres.destroy');
    });
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
