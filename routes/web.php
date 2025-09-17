<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('mahasiswa', App\Http\Controllers\MahasiswaController::class);
