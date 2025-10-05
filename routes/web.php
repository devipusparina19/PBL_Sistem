<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\KelompokController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;

// ================================
// HOME / DASHBOARD
// ================================
Route::middleware(['auth'])->group(function () {
    Route::get('/home', [DashboardController::class, 'index'])->name('home');

    // Profil
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// ================================
// DOSEN
// ================================
Route::prefix('dosen')->middleware(['auth'])->group(function () {
    Route::get('/', [DosenController::class, 'index'])->name('dosen.index');
    Route::get('/create', [DosenController::class, 'create'])->name('dosen.create');
    Route::post('/store', [DosenController::class, 'store'])->name('dosen.store');
    Route::get('/{dosen}', [DosenController::class, 'show'])->name('dosen.show');
    Route::get('/{dosen}/edit', [DosenController::class, 'edit'])->name('dosen.edit');
    Route::put('/{dosen}', [DosenController::class, 'update'])->name('dosen.update');
    Route::delete('/{dosen}', [DosenController::class, 'destroy'])->name('dosen.destroy');
});

// ================================
// MAHASISWA
// ================================
Route::prefix('mahasiswa')->middleware(['auth'])->group(function () {
    Route::get('/', [MahasiswaController::class, 'index'])->name('mahasiswa.index');
    Route::get('/create', [MahasiswaController::class, 'create'])->name('mahasiswa.create');
    Route::post('/store', [MahasiswaController::class, 'store'])->name('mahasiswa.store');
    Route::get('/{mahasiswa}', [MahasiswaController::class, 'show'])->name('mahasiswa.show');
    Route::get('/{mahasiswa}/edit', [MahasiswaController::class, 'edit'])->name('mahasiswa.edit');
    Route::put('/{mahasiswa}', [MahasiswaController::class, 'update'])->name('mahasiswa.update');
    Route::delete('/{mahasiswa}', [MahasiswaController::class, 'destroy'])->name('mahasiswa.destroy');
});

// ================================
// KELOMPOK PBL
// ================================
Route::prefix('kelompok')->middleware(['auth'])->group(function () {
    Route::get('/', [KelompokController::class, 'index'])->name('kelompok.index');
    Route::get('/create', [KelompokController::class, 'create'])->name('kelompok.create');
    Route::post('/store', [KelompokController::class, 'store'])->name('kelompok.store');
    Route::get('/{kelompok}', [KelompokController::class, 'show'])->name('kelompok.show');
    Route::get('/{kelompok}/edit', [KelompokController::class, 'edit'])->name('kelompok.edit');
    Route::put('/{kelompok}', [KelompokController::class, 'update'])->name('kelompok.update');
    Route::delete('/{kelompok}', [KelompokController::class, 'destroy'])->name('kelompok.destroy');
});

// ================================
// LOGOUT
// ================================
Route::post('/logout', function () {
    auth()->logout();
    return redirect()->route('home');
})->name('logout');
