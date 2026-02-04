<?php

namespace App\Http\Controllers;

use App\Models\ActivityType;
use Illuminate\Http\Request;

class ActivityTypeController extends Controller
{
    public function index()
    {
        $activities = ActivityType::orderBy('id')->get();
        return view('admin.activities.index', compact('activities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:activity_types,code',
            'name' => 'required',
            'input_type' => 'required|in:boolean,numeric',
        ]);

        ActivityType::create([
            'code' => $request->code,
            'name' => $request->name,
            'input_type' => $request->input_type,
            'is_active' => true
        ]);

        return back()->with('success', 'Kegiatan baru berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $activity = ActivityType::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'input_type' => 'required',
        ]);

        $activity->update([
            'name' => $request->name,
            'input_type' => $request->input_type,
            'is_active' => $request->has('is_active')
        ]);

        return back()->with('success', 'Kegiatan berhasil diperbarui.');
    }

    public function toggleStatus($id)
    {
        $activity = ActivityType::findOrFail($id);
        $activity->is_active = !$activity->is_active;
        $activity->save();

        return back()->with('success', 'Status kegiatan berhasil diubah.');
    }
}
