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

// ✅ REGISTER (tidak butuh login)
Route::get('/register', [UserController::class, 'showRegister'])->name('user.showRegister');
Route::post('/register', [RegisterController::class, 'register'])->name('register');

// ✅ LOGIN (tidak butuh login)
Route::get('/login', [UserController::class, 'showLogin'])->name('login');
Route::post('/login', [UserController::class, 'login'])->name('user.login');

// ✅ GOOGLE LOGIN
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('login.google');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('login.google.callback');

// ✅ LOGOUT
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

// ✅ CONTACT (publik)
Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

/*
|--------------------------------------------------------------------------
| Protected Routes (Butuh Login)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    Route::get('/home', fn() => view('home.home'))->name('home');
    Route::get('/about', fn() => view('about'))->name('about');
    Route::get('/contact', fn() => view('contact'))->name('contact');

    // ✅ Dashboard utama
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('pbl.dashboard');

    // ✅ Dashboard per role
    Route::get('/dashboard/mahasiswa', fn() => view('dashboard.mahasiswa'))->name('mahasiswa.dashboard');
    Route::get('/dashboard/dosen', fn() => view('dashboard.dosen'))->name('dosen.dashboard');
    Route::get('/dashboard/admin', fn() => view('dashboard.admin'))->name('admin.dashboard');
    Route::get('/dashboard/koordinator_pbl', fn() => view('dashboard.koordinator_pbl'))->name('koordinator_pbl.dashboard');
    Route::get('/dashboard/koordinator_prodi', fn() => view('dashboard.koordinator_prodi'))->name('koordinator_prodi.dashboard');

    // ✅ CRUD
    Route::resource('mahasiswa', MahasiswaController::class);
    Route::resource('data_dosen', DosenController::class);
    Route::resource('kelompok', KelompokController::class);
    Route::resource('logbook', LogbookController::class);

    // ✅ Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    // ✅ Dosen Resource tambahan
    Route::resource('dosen', DosenController::class);

    //Dashboard Kelompok
    Route::get('/dashboard/kelompok', function () {
    return view('dashboard.kelompok');
});

});
