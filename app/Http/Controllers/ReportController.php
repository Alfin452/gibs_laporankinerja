<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ActivityType;
use App\Models\ActivityLog;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    // INDEX REKAP BULANAN (Tampilan Utama Admin)
    public function index(Request $request)
    {
        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);

        $users = User::where('role', 'guru')->orderBy('name')->get();
        $activityTypes = ActivityType::where('is_active', true)->get();

        // Ambil semua log di bulan tersebut
        // Kita eager load untuk performa
        $logs = ActivityLog::whereYear('date', $year)
            ->whereMonth('date', $month)
            ->get();

        // Ambil Data Absensi K1
        $attendances = Attendance::whereYear('date', $year)
            ->whereMonth('date', $month)
            ->get();

        // Mapping Data untuk View (Pivot Manual agar ringan)
        $report = [];
        foreach ($users as $user) {
            $userLogs = $logs->where('user_id', $user->id);
            $userAttendances = $attendances->where('user_id', $user->id);

            // Hitung K1 (Ontime)
            $k1_ontime = $userAttendances->where('status', 'ontime')->count();

            // Hitung K2-K20
            $activities_data = [];
            foreach ($activityTypes as $type) {
                if ($type->input_type == 'numeric') {
                    // Jika Numeric (K2), jumlahkan value-nya (Sum)
                    $activities_data[$type->code] = $userLogs->where('activity_type_id', $type->id)->sum('value');
                } else {
                    // Jika Boolean (K3-dst), hitung hari hadirnya (Count)
                    $activities_data[$type->code] = $userLogs->where('activity_type_id', $type->id)->count();
                }
            }

            $report[] = [
                'user' => $user,
                'k1' => $k1_ontime,
                'activities' => $activities_data
            ];
        }

        return view('admin.reports.index', compact('report', 'activityTypes', 'month', 'year'));
    }

    // DETAIL HARIAN PER GURU (Jika Admin klik nama guru)
    public function daily($userId, Request $request)
    {
        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);

        $user = User::findOrFail($userId);
        $activityTypes = ActivityType::all();

        // Buat list tanggal 1 s/d 31 (atau hari ini)
        $daysInMonth = Carbon::createFromDate($year, $month)->daysInMonth;
        $dates = [];
        for ($i = 1; $i <= $daysInMonth; $i++) {
            $dates[] = Carbon::createFromDate($year, $month, $i)->format('Y-m-d');
        }

        return view('admin.reports.daily', compact('user', 'activityTypes', 'dates', 'month', 'year'));
    }
}
