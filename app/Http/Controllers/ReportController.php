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
    // INDEX REKAP BULANAN (Tabel Matriks K1-K20)
    public function index(Request $request)
    {
        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);

        $users = User::where('role', 'guru')->orderBy('name')->get();
        // Urutkan ActivityType agar K1, K2, K3 berurutan
        $activityTypes = ActivityType::where('is_active', true)->orderBy('id')->get();

        // Eager load data bulan ini
        $logs = ActivityLog::whereYear('date', $year)
            ->whereMonth('date', $month)
            ->get();

        $attendances = Attendance::whereYear('date', $year)
            ->whereMonth('date', $month)
            ->get();

        // Mapping Data untuk View
        $report = [];
        foreach ($users as $user) {
            $userLogs = $logs->where('user_id', $user->id);
            $userAttendances = $attendances->where('user_id', $user->id);

            // Hitung K1 (Kehadiran)
            $k1_count = $userAttendances->count();

            // Hitung K2-K20
            $activities_data = [];
            foreach ($activityTypes as $type) {
                if ($type->input_type == 'numeric') {
                    // Jika Numeric (misal K2), jumlahkan value-nya
                    $activities_data[$type->code] = $userLogs->where('activity_type_id', $type->id)->sum('value');
                } else {
                    // Jika Ceklis, hitung jumlah hari
                    $activities_data[$type->code] = $userLogs->where('activity_type_id', $type->id)->count();
                }
            }

            $report[] = [
                'user' => $user,
                'k1' => $k1_count,
                'activities' => $activities_data
            ];
        }

        return view('admin.reports.index', compact('report', 'activityTypes', 'month', 'year'));
    }

    // DETAIL HARIAN PER GURU
    public function daily($userId, Request $request)
    {
        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);

        $user = User::findOrFail($userId);

        // Buat range tanggal dalam 1 bulan
        $startDate = Carbon::createFromDate($year, $month, 1);
        $endDate = $startDate->copy()->endOfMonth();

        $dates = [];
        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            // Skip hari minggu jika mau
            if ($date->dayOfWeek === Carbon::SUNDAY) continue;

            $currentDate = $date->format('Y-m-d');

            // Ambil data spesifik tanggal ini
            $attendance = Attendance::where('user_id', $user->id)->where('date', $currentDate)->first();
            $logs = ActivityLog::with('activityType')
                ->where('user_id', $user->id)
                ->where('date', $currentDate)
                ->get();

            $dates[] = [
                'display_date' => $date->translatedFormat('l, d F Y'),
                'attendance' => $attendance,
                'logs' => $logs
            ];
        }

        return view('admin.reports.daily', compact('user', 'dates', 'month', 'year'));
    }
}
