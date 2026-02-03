<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminSettingsController extends Controller
{
    public function index()
    {
        $settings = AppSetting::firstOrNew(); // Ambil setting pertama atau buat baru dummy
        return view('admin.settings', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'school_latitude' => 'required|numeric',
            'school_longitude' => 'required|numeric',
            'radius_meters' => 'required|integer|min:10',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $settings = AppSetting::firstOrNew();

        // Update Data Text
        $settings->school_latitude = $request->school_latitude;
        $settings->school_longitude = $request->school_longitude;
        $settings->radius_meters = $request->radius_meters;

        // Handle Upload Logo
        if ($request->hasFile('logo')) {
            // Hapus logo lama jika ada (optional)
            if ($settings->logo_path && Storage::exists($settings->logo_path)) {
                Storage::delete($settings->logo_path);
            }
            // Simpan yang baru
            $path = $request->file('logo')->store('public/logos');
            $settings->logo_path = $path; // Simpan path storage
        }

        $settings->save();

        return back()->with('success', 'Pengaturan lokasi & aplikasi berhasil disimpan!');
    }
}
