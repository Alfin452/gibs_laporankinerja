@extends('layouts.app')

@section('header', 'Rekapitulasi Substitute')

@section('content')
<div class="space-y-6">

    @php
        // Hitung total dari data yang dikirim controller
        $totalTerlaksana = collect($data)->sum('terlaksana');
        $totalAlpha = collect($data)->sum('alpha');
        $totalTarget = $totalTerlaksana + $totalAlpha;
        $avgPersentase = count($data) > 0 ? collect($data)->avg('persentase') : 0;
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-slate-400 text-xs font-bold uppercase tracking-wider">Total Terlaksana</p>
                <h3 class="text-3xl font-bold text-emerald-600 mt-1">{{ number_format($totalTerlaksana) }}</h3>
            </div>
            <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-slate-400 text-xs font-bold uppercase tracking-wider">Total Kosong (Alpha)</p>
                <h3 class="text-3xl font-bold text-rose-600 mt-1">{{ number_format($totalAlpha) }}</h3>
            </div>
            <div class="p-3 bg-rose-50 text-rose-600 rounded-xl">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>

        <div class="bg-gradient-to-br from-indigo-500 to-blue-600 rounded-2xl p-6 text-white shadow-lg shadow-indigo-500/20 relative overflow-hidden">
            <div class="relative z-10">
                <p class="text-indigo-100 text-xs font-bold uppercase tracking-wider">Performa Tim</p>
                <h3 class="text-3xl font-bold mt-1">{{ number_format($avgPersentase, 1) }}%</h3>
                <p class="text-xs text-indigo-100 mt-1 opacity-80">Rata-rata keberhasilan bulan ini</p>
            </div>
            <div class="absolute right-0 top-0 p-4 opacity-10">
                <svg class="w-20 h-20" fill="currentColor" viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
            </div>
        </div>
    </div>

    <div class="flex flex-col md:flex-row justify-between items-center gap-4 bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
        <h2 class="text-lg font-bold text-slate-700">Kinerja Individu</h2>
        
        <form method="GET" action="{{ route('admin.substitutes.index') }}" class="flex flex-wrap items-center gap-2 w-full md:w-auto">
            <div class="relative">
                <select name="month" onchange="this.form.submit()" class="appearance-none pl-4 pr-10 py-2 rounded-xl border-slate-200 text-sm font-medium text-slate-700 focus:border-indigo-500 focus:ring-indigo-500 cursor-pointer bg-slate-50 hover:bg-white transition-colors">
                    @foreach(range(1, 12) as $m)
                    <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                    </option>
                    @endforeach
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-slate-500">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
            </div>

            <div class="relative">
                <select name="year" onchange="this.form.submit()" class="appearance-none pl-4 pr-10 py-2 rounded-xl border-slate-200 text-sm font-medium text-slate-700 focus:border-indigo-500 focus:ring-indigo-500 cursor-pointer bg-slate-50 hover:bg-white transition-colors">
                    @foreach(range(now()->year, now()->year - 2) as $y)
                    <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-slate-500">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
            </div>

            <a href="{{ route('admin.substitutes.daily') }}" class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-xl hover:bg-indigo-700 transition-colors shadow-md shadow-indigo-200 ml-2">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Input Harian
            </a>
        </form>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4 font-semibold text-slate-700">Nama Guru</th>
                        <th class="px-6 py-4 font-semibold text-slate-700 text-center">Terlaksana</th>
                        <th class="px-6 py-4 font-semibold text-slate-700 text-center">Alpha (Kosong)</th>
                        <th class="px-6 py-4 font-semibold text-slate-700 text-center">Total Target</th>
                        <th class="px-6 py-4 font-semibold text-slate-700 text-center">Persentase</th>
                        <th class="px-6 py-4 font-semibold text-slate-700 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($data as $row)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 font-bold text-xs">
                                    {{ substr($row['user']->name, 0, 1) }}
                                </div>
                                <span class="font-medium text-slate-800">{{ $row['user']->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-block px-3 py-1 bg-emerald-50 text-emerald-700 rounded-lg font-bold">
                                {{ $row['terlaksana'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-block px-3 py-1 bg-rose-50 text-rose-700 rounded-lg font-bold">
                                {{ $row['alpha'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center text-slate-600 font-mono">
                            {{ $row['terlaksana'] + $row['alpha'] }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <span class="font-bold {{ $row['persentase'] >= 80 ? 'text-indigo-600' : 'text-amber-500' }}">
                                    {{ $row['persentase'] }}%
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($row['persentase'] >= 100)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 border border-emerald-200">Excellent</span>
                            @elseif($row['persentase'] >= 80)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-100 text-blue-700 border border-blue-200">Good</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-700 border border-amber-200">Low</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 mb-2 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                <span>Belum ada data user.</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection