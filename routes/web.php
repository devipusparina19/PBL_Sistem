<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;

// Halaman utama langsung ke register
Route::get('/', [UserController::class, 'showRegister'])->name('user.showRegister');

// Mahasiswa CRUD
Route::resource('mahasiswa', MahasiswaController::class);

// Register
Route::get('/register', [UserController::class, 'showRegister'])->name('user.showRegister');
Route::post('/register', [UserController::class, 'register'])->name('user.register');

// Login
Route::get('/login', [LoginController::class, 'showLogin'])->name('user.showLogin');
Route::post('/login', [LoginController::class, 'login'])->name('user.login');

// Logout (pastikan method logout ada di controller yg sesuai)
Route::post('/logout', [LoginController::class, 'logout'])->name('user.logout');

// Home (hanya untuk user yang sudah login)
Route::get('/home', function () {
    return view('home');
})->name('home')->middleware('auth');
