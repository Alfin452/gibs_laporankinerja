<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Traits\RadiusCheck;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    use RadiusCheck;

    // --- KONFIGURASI JAM (Bisa dipindah ke AppSetting nanti) ---
    const JAM_MASUK_MAKSIMAL = '07:15';
    const JAM_PULANG_DEFAULT = '16:00:00'; // Untuk Auto Checkout

    // CHECK IN (DATANG)
    public function store(Request $request)
    {
        $request->validate([
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        // 1. Cek Radius
        $radiusCheck = $this->checkRadius($request->latitude, $request->longitude);
        if (!$radiusCheck['allowed']) {
            return back()->with('error', "Gagal! Anda berada di luar radius sekolah. Jarak: {$radiusCheck['distance']} meter.");
        }

        // 2. Cek apakah sudah absen hari ini
        $today = Carbon::today();
        $existing = Attendance::where('user_id', Auth::id())->where('date', $today)->first();

        if ($existing) {
            return back()->with('error', 'Anda sudah melakukan Check In hari ini.');
        }

        // 3. Tentukan Status (Ontime / Late)
        $jamSekarang = Carbon::now()->format('H:i');
        $status = $jamSekarang > self::JAM_MASUK_MAKSIMAL ? 'late' : 'ontime';

        // 4. Simpan Data
        Attendance::create([
            'user_id' => Auth::id(),
            'date' => $today,
            'clock_in' => Carbon::now(),
            'lat_in' => $request->latitude,
            'long_in' => $request->longitude,
            'status' => $status
        ]);

        $pesanStatus = $status == 'ontime' ? 'Tepat Waktu' : 'Terlambat';
        return back()->with('success', "Berhasil Check In! Status: $pesanStatus.");
    }

    // CHECK OUT (PULANG)
    public function update(Request $request)
    {
        $request->validate([
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        // 1. Validasi Jam Pulang (Server Side)
        // Jika jam sekarang KURANG DARI 16:00, tolak request
        if (Carbon::now()->format('H:i') < '16:00') {
            return back()->with('error', 'Belum waktunya pulang! Absen pulang baru dibuka pukul 16:00.');
        }

        // 2. Cek Radius
        $radiusCheck = $this->checkRadius($request->latitude, $request->longitude);
        if (!$radiusCheck['allowed']) {
            return back()->with('error', "Gagal! Anda berada di luar radius sekolah.");
        }

        // 3. Update Data
        $attendance = Attendance::where('user_id', Auth::id())
            ->where('date', Carbon::today())
            ->first();

        if ($attendance) {
            $attendance->update([
                'clock_out' => Carbon::now(),
                'lat_out' => $request->latitude,
                'long_out' => $request->longitude,
            ]);
            return back()->with('success', 'Berhasil Check Out! Hati-hati di jalan.');
        }

        return back()->with('error', 'Anda belum melakukan Check In hari ini.');
    }
}