@extends('layouts.app')

@section('header', 'Riwayat Kinerja')

@section('content')
<div class="space-y-6" x-data="{ search: '{{ $search }}', isLoading: false }">

    {{-- 1. Widget Rekapitulasi (Statik / Tidak perlu loading state) --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Kegiatan</p>
                <p class="text-2xl font-bold text-slate-800 mt-1">{{ $rekap['total_kegiatan'] }}</p>
            </div>
            <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                </svg>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Volume</p>
                <p class="text-2xl font-bold text-slate-800 mt-1">{{ number_format($rekap['total_volume'], 0, ',', '.') }}</p>
            </div>
            <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                </svg>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Bukti Fisik</p>
                <p class="text-2xl font-bold text-slate-800 mt-1">{{ $rekap['dengan_bukti'] }} <span class="text-xs font-normal text-slate-400">files</span></p>
            </div>
            <div class="p-3 bg-purple-50 text-purple-600 rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                </svg>
            </div>
        </div>
    </div>

    {{-- 2. Filter & Search --}}
    <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-200 flex flex-col md:flex-row gap-4 justify-between items-center">
        <form id="filterForm" method="GET" action="{{ route('guru.history') }}" class="flex flex-wrap items-center gap-2 w-full md:w-auto">
            
            <div class="relative">
                <select name="month" @change="isLoading = true; $el.form.submit()" class="appearance-none pl-4 pr-10 py-2 rounded-xl border-slate-200 text-sm font-medium text-slate-700 focus:border-indigo-500 focus:ring-indigo-500 cursor-pointer bg-slate-50 hover:bg-white transition-colors">
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
                <select name="year" @change="isLoading = true; $el.form.submit()" class="appearance-none pl-4 pr-10 py-2 rounded-xl border-slate-200 text-sm font-medium text-slate-700 focus:border-indigo-500 focus:ring-indigo-500 cursor-pointer bg-slate-50 hover:bg-white transition-colors">
                    @foreach(range(now()->year, now()->year - 2) as $y)
                    <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-slate-500">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
            </div>
        </form>

        <form method="GET" action="{{ route('guru.history') }}" class="w-full md:w-auto relative">
            <input type="hidden" name="month" value="{{ $month }}">
            <input type="hidden" name="year" value="{{ $year }}">
            
            <input type="text" name="search" placeholder="Cari kegiatan..."
                   value="{{ $search }}"
                   x-on:input="isLoading = true" 
                   x-on:input.debounce.500ms="$el.form.submit()"
                   class="w-full md:w-64 pl-10 pr-4 py-2 rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500 placeholder-slate-400 bg-slate-50 focus:bg-white transition-all">
            
            <div class="absolute left-3.5 top-3 text-slate-400">
                <svg x-show="!isLoading" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <svg x-show="isLoading" class="w-4 h-4 animate-spin text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" x-cloak>
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
        </form>
    </div>

    {{-- 3. Tabel Data --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden min-h-[400px]">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4 font-semibold text-slate-700 w-48">Tanggal</th>
                        <th class="px-6 py-4 font-semibold text-slate-700">Jenis Kegiatan</th>
                        <th class="px-6 py-4 font-semibold text-slate-700 text-center w-24">Volume</th>
                        <th class="px-6 py-4 font-semibold text-slate-700 text-center w-24">Bukti</th>
                        <th class="px-6 py-4 font-semibold text-slate-700 text-right w-20">Aksi</th>
                    </tr>
                </thead>

                <tbody x-show="!isLoading" class="divide-y divide-slate-100 bg-white">
                    @forelse($logs as $log)
                    <tr class="hover:bg-slate-50/80 transition-colors group">
                        <td class="px-6 py-4 align-top">
                            <div class="flex flex-col">
                                <span class="font-bold text-slate-700 text-base">
                                    {{ \Carbon\Carbon::parse($log->date)->format('d') }}
                                </span>
                                <span class="text-xs font-medium text-slate-500 uppercase">
                                    {{ \Carbon\Carbon::parse($log->date)->translatedFormat('M Y') }}
                                </span>
                                <span class="text-[10px] text-slate-400 mt-1">
                                    {{ \Carbon\Carbon::parse($log->time_recorded ?? '00:00:00')->format('H:i') }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 align-top">
                            <div class="font-semibold text-indigo-900 mb-1">
                                {{ $log->activityType->name }}
                            </div>
                            @if($log->description)
                            <div class="text-slate-500 text-sm leading-relaxed">
                                {{ $log->description }}
                            </div>
                            @else
                            <span class="text-slate-400 italic text-xs">- Tidak ada deskripsi -</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 align-top text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800 border border-slate-200">
                                {{ $log->value }}
                            </span>
                        </td>
                        <td class="px-6 py-4 align-top text-center">
                            @if($log->file_path)
                            <a href="{{ Storage::url($log->file_path) }}" target="_blank" 
                               class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 hover:scale-110 transition-all"
                               title="Lihat Bukti">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </a>
                            @else
                            <span class="text-slate-300">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 align-top text-right">
                            <form action="{{ route('guru.activities.destroy', $log->id) }}" method="POST" 
                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus kegiatan ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-slate-400 hover:text-red-500 transition-colors p-1 rounded-md hover:bg-red-50" title="Hapus">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                    <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-slate-900 font-medium mb-1">Belum ada kegiatan</h3>
                                <p class="text-slate-500 text-xs">Data untuk periode ini tidak ditemukan.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>

                <tbody x-show="isLoading" class="divide-y divide-slate-100 bg-white" x-cloak>
                    @for($i = 0; $i < 5; $i++)
                    <tr class="animate-pulse">
                        <td class="px-6 py-4 align-top">
                            <div class="h-4 bg-slate-200 rounded w-8 mb-2"></div>
                            <div class="h-3 bg-slate-200 rounded w-12 mb-1"></div>
                            <div class="h-2 bg-slate-100 rounded w-10"></div>
                        </td>
                        <td class="px-6 py-4 align-top">
                            <div class="h-5 bg-slate-200 rounded w-1/3 mb-2"></div>
                            <div class="h-3 bg-slate-100 rounded w-3/4 mb-1"></div>
                            <div class="h-3 bg-slate-100 rounded w-1/2"></div>
                        </td>
                        <td class="px-6 py-4 align-top text-center">
                            <div class="h-6 bg-slate-200 rounded-full w-8 mx-auto"></div>
                        </td>
                        <td class="px-6 py-4 align-top text-center">
                            <div class="h-8 w-8 bg-slate-200 rounded-lg mx-auto"></div>
                        </td>
                        <td class="px-6 py-4 align-top text-right">
                            <div class="h-5 w-5 bg-slate-200 rounded ml-auto"></div>
                        </td>
                    </tr>
                    @endfor
                </tbody>
            </table>
        </div>
        
        @if($logs->hasPages())
        <div class="p-4 border-t border-slate-100 bg-slate-50/50" x-show="!isLoading">
            {{ $logs->links() }}
        </div>
        @endif
    </div>
</div>
@endsection