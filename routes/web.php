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

// Logout
Route::post('/logout', [UserController::class, 'logout'])->name('user.logout');

/*
|--------------------------------------------------------------------------
| Protected Routes (Butuh Login)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Dashboard umum
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('pbl.dashboard');

    // Dashboard per role
    Route::get('/dashboard/mahasiswa', fn() => view('dashboard.mahasiswa'))->name('mahasiswa.dashboard');
    Route::get('/dashboard/dosen', fn() => view('dashboard.dosen'))->name('dosen.dashboard');
    Route::get('/dashboard/admin', fn() => view('dashboard.admin'))->name('admin.dashboard');
    Route::get('/dashboard/koordinatorpbl', fn() => view('dashboard.koordinatorpbl'))->name('koordinatorpbl.dashboard');
    Route::get('/dashboard/koordinatorprodi', fn() => view('dashboard.koordinatorprodi'))->name('koordinatorprodi.dashboard');

    /*
    |--------------------------------------------------------------------------
    | Resource CRUD Routes
    |--------------------------------------------------------------------------
    */
    Route::resource('mahasiswa', MahasiswaController::class);
    Route::resource('data_dosen', DosenController::class);
    Route::resource('kelompok', KelompokController::class);
    Route::resource('milestones', MilestoneController::class);
});
