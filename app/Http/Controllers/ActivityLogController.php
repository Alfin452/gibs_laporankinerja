<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\ActivityType;
use Illuminate\Http\Request;
use App\Traits\RadiusCheck;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ActivityLogController extends Controller
{
    use RadiusCheck;

    public function index()
    {
        // Menampilkan form input untuk Guru
        $activities = ActivityType::where('is_active', true)->get();
        $todayLogs = ActivityLog::where('user_id', Auth::id())
            ->where('date', Carbon::today())
            ->get()
            ->keyBy('activity_type_id'); // Biar gampang dicek di view

        return view('guru.activities', compact('activities', 'todayLogs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'activity_type_id' => 'required|exists:activity_types,id',
            'value' => 'required', // Bisa 1 (hadir) atau angka jam
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        // 1. Cek Radius
        $radiusCheck = $this->checkRadius($request->latitude, $request->longitude);
        if (!$radiusCheck['allowed']) {
            return response()->json(['message' => 'Diluar jangkauan radius sekolah!'], 403);
        }

        // 2. Simpan Kegiatan
        ActivityLog::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'activity_type_id' => $request->activity_type_id,
                'date' => Carbon::today(),
            ],
            [
                'time_recorded' => Carbon::now(),
                'value' => $request->value,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
            ]
        );

        return response()->json(['message' => 'Kegiatan berhasil dilaporkan!']);
    }
}
