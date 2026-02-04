<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\ReportController;
use App\Models\Attendance; 
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminSettingsController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\ActivityTypeController;
use App\Http\Controllers\SubstituteTargetController; 


Route::get('/', function () {
    return view('welcome');
});


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

    // 2. Setting Lokasi & Aplikasi
    Route::get('/settings', [AdminSettingsController::class, 'index'])->name('settings');
    Route::post('/settings', [AdminSettingsController::class, 'update'])->name('settings.update');

    // 2. Laporan Kinerja
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/user/{id}', [ReportController::class, 'daily'])->name('reports.daily');

    // 3. Manajemen Guru (Placeholder sementara)
    Route::resource('users', AdminUserController::class);

    Route::prefix('activities')->name('activities.')->group(function () {
        Route::get('/', [ActivityTypeController::class, 'index'])->name('index');
        Route::post('/store', [ActivityTypeController::class, 'store'])->name('store');
        Route::post('/{id}/toggle', [ActivityTypeController::class, 'toggleStatus'])->name('toggle');
    });

    Route::get('/substitutes', [SubstituteTargetController::class, 'index'])->name('substitutes.index');
    Route::post('/substitutes', [SubstituteTargetController::class, 'store'])->name('substitutes.store');

});

// ROUTE KHUSUS GURU
Route::middleware(['auth', 'role:guru'])->prefix('guru')->name('guru.')->group(function () {
    // 1. Dashboard (Absensi K1)
    Route::get('/dashboard', function () {
        $today = Carbon::today();
        $attendance = Attendance::where('user_id', Auth::id())
            ->where('date', $today)
            ->first();

        $settings = App\Models\AppSetting::first();

        // Jaga-jaga jika database kosong, kita buat data dummy di memory
        if (!$settings) {
            $settings = new App\Models\AppSetting();
            $settings->school_latitude = -3.319363; // Contoh Lat
            $settings->school_longitude = 114.589803; // Contoh Long
            $settings->radius_meters = 100;
        }

        return view('guru.dashboard', compact('attendance', 'settings'));
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
