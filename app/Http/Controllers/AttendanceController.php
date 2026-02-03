<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Traits\RadiusCheck;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    use RadiusCheck; // Panggil rumus jarak tadi

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

        // 3. Simpan Data
        Attendance::create([
            'user_id' => Auth::id(),
            'date' => $today,
            'clock_in' => Carbon::now(),
            'lat_in' => $request->latitude,
            'long_in' => $request->longitude,
            'status' => Carbon::now()->format('H:i') > '07:15' ? 'late' : 'ontime' // Logika telat sederhana
        ]);

        return back()->with('success', 'Berhasil Check In!');
    }

    // CHECK OUT (PULANG)
    public function update(Request $request)
    {
        $request->validate([
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        // 1. Cek Radius (Tetap harus di sekolah saat pulang)
        $radiusCheck = $this->checkRadius($request->latitude, $request->longitude);
        if (!$radiusCheck['allowed']) {
            return back()->with('error', "Gagal! Anda berada di luar radius sekolah.");
        }

        // 2. Update Data
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

        return back()->with('error', 'Data absen tidak ditemukan.');
    }
}
