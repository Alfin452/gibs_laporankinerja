<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\ActivityType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->input('date', Carbon::today()->format('Y-m-d'));

        // Cek jika ada permintaan edit
        $editLog = null;
        if ($request->has('edit_id')) {
            $editLog = ActivityLog::where('user_id', Auth::id())->find($request->edit_id);
        }

        $activityTypes = ActivityType::where('is_active', true)->where('code', '!=', 'K1')->get();
        $logs = ActivityLog::with('activityType')->where('user_id', Auth::id())->whereDate('date', $date)->get();

        return view('guru.activities.index', compact('activityTypes', 'logs', 'date', 'editLog'));
    }

    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'date' => 'required|date',
            'activity_type_id' => 'required|exists:activity_types,id',
            'value' => 'nullable|numeric|min:0',
            'description' => 'nullable|string|max:255',
            'file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048', // Max 2MB
        ]);

        // 2. Proses Upload File (Jika ada)
        $filePath = null;
        if ($request->hasFile('file')) {
            // Simpan di storage/app/public/evidence/USER_ID/TAHUN/BULAN
            // Contoh: evidence/6/2026/02/unik_name.jpg
            $path = "evidence/" . Auth::id() . "/" . date('Y/m');
            $filePath = $request->file('file')->store($path, 'public');
        }

        // 3. Ambil Tipe Kegiatan untuk Cek Logic Value
        $activityType = ActivityType::findOrFail($request->activity_type_id);

        // Jika tipe inputnya 'check' (ceklis), paksa nilainya jadi 1
        // Jika numeric, gunakan inputan user. Jika kosong, default 0.
        $value = $request->value;
        if ($activityType->input_type == 'check') {
            $value = 1;
        }

        // 4. Simpan ke Database
        ActivityLog::create([
            'user_id' => Auth::id(),
            'activity_type_id' => $request->activity_type_id,
            'date' => $request->date,
            'time_recorded' => Carbon::now()->format('H:i:s'), // Fix error "Field time_recorded doesn't have default value"
            'value' => $value ?? 0,
            'description' => $request->description,
            'file_path' => $filePath,
        ]);

        // 5. Redirect Kembali
        return redirect()->route('guru.activities.index', ['date' => $request->date])
            ->with('success', 'Kegiatan berhasil disimpan!');
    }

    public function destroy($id)
    {
        $log = ActivityLog::where('user_id', Auth::id())->findOrFail($id);

        // Hapus file fisik jika ada
        if ($log->file_path && Storage::disk('public')->exists($log->file_path)) {
            Storage::disk('public')->delete($log->file_path);
        }

        $date = $log->date; // Simpan tanggal untuk redirect
        $log->delete();

        return redirect()->route('guru.activities.index', ['date' => $date])
            ->with('success', 'Kegiatan berhasil dihapus.');
    }

    // ... method index, store, destroy yang sudah ada ...

    public function history(Request $request)
    {
        // Mengambil filter dari request, atau default ke bulan/tahun saat ini
        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);
        $search = $request->input('search');

        $query = ActivityLog::with('activityType')
            ->where('user_id', Auth::id())
            ->whereMonth('date', $month)
            ->whereYear('date', $year);

        // Tambahkan pencarian jika ada input search
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                    ->orWhereHas('activityType', function ($typeQ) use ($search) {
                        $typeQ->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Clone query untuk keperluan rekapitulasi statistik
        $rekapQuery = clone $query;

        // Gunakan paginate agar data yang banyak tidak menumpuk
        $logs = $query->orderByDesc('date')->paginate(10)->withQueryString();

        // Hitung rekapitulasi dari hasil query yang sudah difilter
        $rekap = [
            'total_kegiatan' => $query->count(),
            'total_volume' => $query->sum('value'),
            'dengan_bukti' => $query->whereNotNull('file_path')->count(),
        ];

        return view('guru.history.index', compact('logs', 'month', 'year', 'search', 'rekap'));
    }
}
