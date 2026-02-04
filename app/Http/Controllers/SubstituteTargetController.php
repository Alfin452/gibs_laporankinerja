<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\SubstituteLog;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SubstituteTargetController extends Controller
{
    // 1. REKAP BULANAN (View Only)
    public function index(Request $request)
    {
        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);

        $users = User::where('role', 'guru')->orderBy('name')->get();

        $data = [];

        foreach ($users as $user) {
            // Ambil Sum dari Log Harian berdasarkan Bulan & Tahun
            $terlaksana = SubstituteLog::where('user_id', $user->id)
                ->whereMonth('date', $month)
                ->whereYear('date', $year)
                ->sum('terlaksana');

            $alpha = SubstituteLog::where('user_id', $user->id)
                ->whereMonth('date', $month)
                ->whereYear('date', $year)
                ->sum('alpha');

            $total_target = $terlaksana + $alpha;
            $persentase = $total_target > 0 ? ($terlaksana / $total_target) * 100 : 0;

            $data[] = [
                'user' => $user,
                'terlaksana' => $terlaksana,
                'alpha' => $alpha,
                'persentase' => round($persentase, 1)
            ];
        }

        return view('admin.substitutes.index', compact('data', 'month', 'year'));
    }

    // 2. HALAMAN INPUT HARIAN
    public function daily(Request $request)
    {
        // Default ke hari ini jika tidak ada input tanggal
        $date = $request->input('date', Carbon::today()->format('Y-m-d'));

        $users = User::where('role', 'guru')->orderBy('name')->get();

        // Ambil data yang sudah tersimpan di tanggal tersebut (agar form terisi value yg sudah ada)
        $logs = SubstituteLog::whereDate('date', $date)->get()->keyBy('user_id');

        return view('admin.substitutes.daily', compact('users', 'date', 'logs'));
    }

    // 3. SIMPAN DATA HARIAN
    public function storeDaily(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'data' => 'array', // Array format: [user_id => ['terlaksana' => x, 'alpha' => y]]
        ]);

        foreach ($request->data as $userId => $values) {
            $terlaksana = $values['terlaksana'] ?? 0;
            $alpha = $values['alpha'] ?? 0;

            // Hanya simpan jika ada isinya (supaya database tidak penuh sampah 0)
            if ($terlaksana > 0 || $alpha > 0) {
                SubstituteLog::updateOrCreate(
                    [
                        'user_id' => $userId,
                        'date' => $request->date
                    ],
                    [
                        'terlaksana' => $terlaksana,
                        'alpha' => $alpha
                    ]
                );
            } else {
                // Jika diedit jadi 0 semua, hapus row-nya agar bersih
                SubstituteLog::where('user_id', $userId)
                    ->whereDate('date', $request->date)
                    ->delete();
            }
        }

        return redirect()->route('admin.substitutes.daily', ['date' => $request->date])
            ->with('success', 'Data Harian Substitute berhasil disimpan.');
    }
}
