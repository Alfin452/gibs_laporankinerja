<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\SubstituteTarget;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SubstituteTargetController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);

        $users = User::where('role', 'guru')->orderBy('name')->get();

        $data = [];

        foreach ($users as $user) {
            // Ambil Data Manual dari DB (Terlaksana & Alpha)
            $targetDb = SubstituteTarget::where('user_id', $user->id)
                ->where('month', $month)
                ->where('year', $year)
                ->first();

            $terlaksana = $targetDb ? $targetDb->terlaksana_count : 0;
            $alpha = $targetDb ? $targetDb->alpha_count : 0;

            // Hitung Persentase
            $total_target = $terlaksana + $alpha;
            $persentase = 0;

            if ($total_target > 0) {
                // Rumus: (Terlaksana / Total) * 100
                $persentase = ($terlaksana / $total_target) * 100;
            }

            $data[] = [
                'user' => $user,
                'terlaksana' => $terlaksana,
                'alpha' => $alpha,
                'persentase' => round($persentase, 1) // Pembulatan 1 desimal
            ];
        }

        return view('admin.substitutes.index', compact('data', 'month', 'year'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'month' => 'required',
            'year' => 'required',
            'terlaksanas' => 'array', // Input array terlaksana
            'alphas' => 'array',      // Input array alpha
        ]);

        $userIds = array_keys($request->terlaksanas ?? []);

        foreach ($userIds as $userId) {
            $terlaksana = $request->terlaksanas[$userId] ?? 0;
            $alpha = $request->alphas[$userId] ?? 0;

            SubstituteTarget::updateOrCreate(
                [
                    'user_id' => $userId,
                    'month' => $request->month,
                    'year' => $request->year
                ],
                [
                    'terlaksana_count' => $terlaksana,
                    'alpha_count' => $alpha
                ]
            );
        }

        return back()->with('success', 'Data Kinerja Substitute berhasil disimpan.');
    }
}
