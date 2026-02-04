@extends('layouts.app')

@section('header', 'Dashboard Administrator')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 transition-transform hover:scale-105">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">Total Guru</p>
                <h3 class="text-2xl font-bold text-slate-800 mt-1">
                    {{ \App\Models\User::where('role', 'guru')->count() }}
                </h3>
            </div>
            <div class="p-3 bg-blue-50 rounded-full">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 transition-transform hover:scale-105">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">Hadir Hari Ini (K1)</p>
                <h3 class="text-2xl font-bold text-green-600 mt-1">
                    {{ \App\Models\Attendance::whereDate('date', \Carbon\Carbon::today())->count() }}
                </h3>
            </div>
            <div class="p-3 bg-green-50 rounded-full">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 transition-transform hover:scale-105">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">Aktivitas Terlapor</p>
                <h3 class="text-2xl font-bold text-teal-600 mt-1">
                    {{ \App\Models\ActivityLog::whereDate('date', \Carbon\Carbon::today())->count() }}
                </h3>
            </div>
            <div class="p-3 bg-teal-50 rounded-full">
                <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 transition-transform hover:scale-105">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">Belum Absen</p>
                <h3 class="text-2xl font-bold text-red-600 mt-1">
                    @php
                    $totalGuru = \App\Models\User::where('role', 'guru')->count();
                    $sudahAbsen = \App\Models\Attendance::whereDate('date', \Carbon\Carbon::today())->count();
                    @endphp
                    {{ $totalGuru - $sudahAbsen }}
                </h3>
            </div>
            <div class="p-3 bg-red-50 rounded-full">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 p-8 text-center">
    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-100 mb-4">
        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
        </svg>
    </div>
    <h2 class="text-2xl font-bold text-slate-800 mb-2">Selamat Datang di Panel Admin GIBS</h2>
    <p class="text-slate-600 max-w-2xl mx-auto">
        Sistem Laporan Kinerja Guru Terintegrasi. Silakan gunakan menu di samping untuk mengelola Data Guru, Memantau Kinerja Harian, dan Melakukan Pengaturan Aplikasi.
    </p>
</div>
@endsection