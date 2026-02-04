<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ActivityType;
use App\Models\ActivityLog;
use App\Models\SubstituteTarget;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SubstituteTargetController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);

        // Ambil ID Kegiatan untuk K2 (Substitute)
        // Pastikan di Master Kegiatan kodenya 'K2'
        $k2Type = ActivityType::where('code', 'K2')->first();

        $users = User::where('role', 'guru')->orderBy('name')->get();

        $data = [];

        foreach ($users as $user) {
            // 1. HITUNG TERLAKSANA (Otomatis dari Inputan Guru)
            $terlaksana = 0;
            if ($k2Type) {
                // Kita hitung FREKUENSI (berapa kali input), bukan total jam
                // Jika ingin total jam, ganti count() dengan sum('value')
                $terlaksana = ActivityLog::where('user_id', $user->id)
                    ->where('activity_type_id', $k2Type->id)
                    ->whereMonth('date', $month)
                    ->whereYear('date', $year)
                    ->count();
            }

            // 2. AMBIL TIDAK TERLAKSANA (Manual dari DB)
            $targetDb = SubstituteTarget::where('user_id', $user->id)
                ->where('month', $month)
                ->where('year', $year)
                ->first();

            $alpha = $targetDb ? $targetDb->alpha_count : 0;

            $data[] = [
                'user' => $user,
                'terlaksana' => $terlaksana,
                'alpha' => $alpha
            ];
        }

        return view('admin.substitutes.index', compact('data', 'month', 'year'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'month' => 'required',
            'year' => 'required',
            'alphas' => 'array', // Array inputan dari form
        ]);

        foreach ($request->alphas as $userId => $alphaCount) {
            // Update atau Create data
            SubstituteTarget::updateOrCreate(
                [
                    'user_id' => $userId,
                    'month' => $request->month,
                    'year' => $request->year
                ],
                [
                    'alpha_count' => $alphaCount ?? 0
                ]
            );
        }

        return back()->with('success', 'Data Alpha Substitute berhasil disimpan.');
    }
}
