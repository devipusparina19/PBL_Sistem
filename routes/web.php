<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
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

// Logout (GET sesuai modul praktikum)
Route::get('/logout', function () {
    Auth::logout();
    return redirect()->route('user.showLogin');
})->name('user.logout');

// Home (hanya untuk user yang sudah login)
Route::get('/home', function () {
    return view('login.home'); // diarahkan ke resources/views/login/home.blade.php
})->name('home')->middleware('auth');
