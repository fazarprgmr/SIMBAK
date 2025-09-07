<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RkaController;
use App\Http\Controllers\KodeRekeningController;
use App\Http\Controllers\SatuanController;

// 🔹 Home Route: cek login
Route::get('/', function () {
    return Auth::check() 
        ? redirect()->route('dashboard') 
        : redirect()->route('login');
});

// 🔹 Semua route butuh login
Route::middleware('auth')->group(function () {
    // Profil User
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Dashboard (biar menu Dashboard di sidebar bisa dipakai)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // 🔹 RKA
    Route::prefix('rka')->name('rka.')->group(function () {
        Route::get('/', [RkaController::class, 'index'])->name('index');
        Route::get('/create', [RkaController::class, 'create'])->name('create');
        Route::post('/', [RkaController::class, 'store'])->name('store');
        Route::get('/{rka}/edit', [RkaController::class, 'edit'])->name('edit');
        Route::put('/{rka}', [RkaController::class, 'update'])->name('update');
        Route::delete('/{rka}', [RkaController::class, 'destroy'])->name('destroy');

        Route::view('/laporan', 'laporan.index')->name('laporan');
        Route::get('/export/bulanan', [RkaController::class, 'exportPdfBulanan'])->name('export.bulanan');
        Route::get('/export/tahunan', [RkaController::class, 'exportPdfTahunan'])->name('export.tahunan');
    });

    // 🔹 Master Data
    Route::resource('kode-rekenings', KodeRekeningController::class);
    Route::resource('satuans', SatuanController::class);
});

// 🔹 Auth Routes Breeze
require __DIR__ . '/auth.php';
