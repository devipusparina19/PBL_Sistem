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
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\RangkingController;
use App\Http\Controllers\ProgresController;
use App\Http\Controllers\PenilaianSejawatController;
use App\Http\Controllers\LaporanController; // ✅ Tambahan untuk laporan penilaian
use App\Http\Controllers\DataAkademikController;

/*
|--------------------------------------------------------------------------
| AUTH & LOGIN / REGISTER
|--------------------------------------------------------------------------
*/

// Dashboard harus login
Route::middleware(['auth'])->group(function () {
    Route::get('/home', [DashboardController::class, 'index'])->name('home');
});

// Halaman utama → Login
Route::get('/', [LoginController::class, 'showLogin'])->name('user.showLogin');

// Register
Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('user.showRegister');
Route::post('/register', [RegisterController::class, 'register'])->name('user.register');

// Login
Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('user.login');

// Logout
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

// Google Login
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('login.google');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('login.google.callback');

// Contact
Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// About
Route::get('/about', fn() => view('about'))->name('about');

/*
|--------------------------------------------------------------------------
| ROUTE YANG BUTUH LOGIN
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Dashboard Per Role
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
    | Perangkingan Kelompok
    |--------------------------------------------------------------------------
    */
    // Perangkingan Kelompok
    Route::get('/perangkingan', [RangkingController::class, 'kelompok'])->name('perangkingan.index');

    // Perangkingan Mahasiswa
    Route::get('/perangkingan/mahasiswa', [RangkingController::class, 'mahasiswa'])->name('perangkingan.mahasiswa');

    /*
    |--------------------------------------------------------------------------
    | CRUD Master Data
    |--------------------------------------------------------------------------
    */
    Route::get('mahasiswa/kelas/{kelas}', [MahasiswaController::class, 'showByKelas'])->name('mahasiswa.kelas');
    Route::resource('mahasiswa', MahasiswaController::class);

    Route::get('data_dosen/kelas/{kelas}', [DosenController::class, 'showByKelas'])->name('data_dosen.kelas');
    Route::resource('data_dosen', DosenController::class);

    Route::get('mata_kuliah/kelas/{kelas}', [MataKuliahController::class, 'showByKelas'])->name('mata_kuliah.kelas');
    Route::get('mata_kuliah/{id}/detail', [MataKuliahController::class, 'showDetail'])->name('mata_kuliah.detail');
    Route::resource('mata_kuliah', MataKuliahController::class);

    Route::get('kelompok/by-kelas/{kelas}', [KelompokController::class, 'showByKelas'])->name('kelompok.byKelas');
    
    // Data Akademik (Unified View)
    Route::get('/data_akademik', [DataAkademikController::class, 'index'])->name('data_akademik.index');
    Route::get('/kelompok/rangking', [RangkingController::class, 'kelompok'])->name('kelompok.rangking');
    Route::resource('kelompok', KelompokController::class);
<<<<<<< HEAD
    // Route khusus manage anggota (fix error route missing)
    Route::get('kelompok/{kelompok}/anggota', [KelompokController::class, 'manageAnggota'])->name('kelompok.anggota.manage');
    Route::put('kelompok/{kelompok}/anggota', [KelompokController::class, 'updateAnggota'])->name('kelompok.anggota.update');
    
=======

>>>>>>> e1442cadc675c50c30c946e60777123fe7d00549
    Route::resource('logbook', LogbookController::class);

    /*
    |--------------------------------------------------------------------------
    | Milestone
    |--------------------------------------------------------------------------
    */
    Route::get('/milestone/view', [MilestoneController::class, 'indexForMember'])->name('milestone.view');
    Route::get('/milestone/input', [MilestoneController::class, 'create'])->name('milestone.create');
    Route::post('/milestone/input', [MilestoneController::class, 'store'])->name('milestone.store');
    Route::get('/milestone/edit/{id}', [MilestoneController::class, 'edit'])->name('milestone.edit');
    Route::post('/milestone/edit/{id}', [MilestoneController::class, 'update'])->name('milestone.update');

    // Validasi Dosen
    Route::middleware('role:dosen')->group(function () {
        Route::get('/milestone/validasi', [MilestoneController::class, 'indexForDosen'])->name('milestone.validasi');
        Route::post('/milestone/validasi/{id}', [MilestoneController::class, 'updateStatus'])->name('milestone.updateStatus');
    });

    /*
    |--------------------------------------------------------------------------
    | Nilai Mahasiswa
    |--------------------------------------------------------------------------
    */
    Route::resource('nilai', NilaiController::class);

    /*
    |--------------------------------------------------------------------------
    | Nilai Kelompok
    |--------------------------------------------------------------------------
    */
    Route::resource('nilai_kelompok', NilaiKelompokController::class);
    /*
    |--------------------------------------------------------------------------
    | Monitoring Progres
    |--------------------------------------------------------------------------
    */
    Route::resource('monitoring', MonitoringController::class);

    /*
    |--------------------------------------------------------------------------
    | Manajemen Akun (Admin)
    |--------------------------------------------------------------------------
    */
    Route::middleware('isAdmin')->group(function () {
        Route::get('/manajemen_akun', [AdminController::class, 'manajemenAkun'])->name('manajemen_akun');
        Route::resource('akun', AkunController::class);
    });

    /*
    |--------------------------------------------------------------------------
    | Profile
    |--------------------------------------------------------------------------
    */
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    /*
    |--------------------------------------------------------------------------
    | Progres Kelompok
    |--------------------------------------------------------------------------
    */
    Route::resource('progres', ProgresController::class);

    /*
    |--------------------------------------------------------------------------
    | Penilaian Sejawat (TIDAK ADA DUPLIKAT)
    |--------------------------------------------------------------------------
    */
    Route::get('/penilaian/sejawat', [PenilaianSejawatController::class, 'index'])->name('penilaian.sejawat.index');
    Route::post('/penilaian/sejawat', [PenilaianSejawatController::class, 'store'])->name('penilaian.sejawat.store');
    Route::get('/penilaian/sejawat/rekap', [PenilaianSejawatController::class, 'rekap'])->name('penilaian.sejawat.rekap');
    Route::get('/penilaian/sejawat/{id}', [PenilaianSejawatController::class, 'show'])->name('penilaian.sejawat.show');
    Route::get('/penilaian/sejawat/{id}/edit', [PenilaianSejawatController::class, 'edit'])->name('penilaian.sejawat.edit');
    Route::put('/penilaian/sejawat/{id}', [PenilaianSejawatController::class, 'update'])->name('penilaian.sejawat.update');

    /*
    |--------------------------------------------------------------------------
    | Laporan Penilaian Akhir
    |--------------------------------------------------------------------------
    */
    Route::get('/laporan/akhir', [LaporanController::class, 'index'])->name('laporan.akhir');
});