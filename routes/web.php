<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\ReportController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // 1. Dashboard Admin (Nanti kita buat)
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // 2. Laporan Kinerja
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/user/{id}', [ReportController::class, 'daily'])->name('reports.daily');

    // 3. Manajemen Guru (Placeholder sementara)
    Route::get('/users', function () {
        return "Halaman Manajemen Guru";
    })->name('users.index');

    // 4. Master Kegiatan (Placeholder sementara)
    Route::get('/activities', function () {
        return "Halaman Master Kegiatan";
    })->name('activities.index');

    // 5. Settings (Placeholder sementara)
    Route::get('/settings', function () {
        return "Halaman Setting Lokasi";
    })->name('settings');
});

// ROUTE KHUSUS GURU
Route::middleware(['auth', 'role:guru'])->prefix('guru')->name('guru.')->group(function () {
    // 1. Dashboard (Absensi K1)
    Route::get('/dashboard', function () {
        return view('guru.dashboard');
    })->name('dashboard');

    // 2. Input Kegiatan K2-K20
    Route::get('/activities', [ActivityLogController::class, 'index'])->name('activities.index');
    Route::post('/activities', [ActivityLogController::class, 'store'])->name('activities.store');

    // 3. Riwayat Saya (Placeholder sementara)
    Route::get('/history', function () {
        return "Halaman Riwayat";
    })->name('history');

    // Route Proses Absen K1
    Route::post('/attendance/in', [AttendanceController::class, 'store'])->name('attendance.in');
    Route::post('/attendance/out', [AttendanceController::class, 'update'])->name('attendance.out');
});

require __DIR__.'/auth.php';
