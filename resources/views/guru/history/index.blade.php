@extends('layouts.app')

@section('header', 'Riwayat Kinerja Saya')

@section('content')
<div class="space-y-6">

    {{-- Filter Bulan & Tahun --}}
    <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200 flex flex-col md:flex-row justify-between items-center gap-4">
        <h3 class="font-bold text-slate-700">Filter Periode</h3>

        <form method="GET" action="{{ route('guru.history') }}" class="flex items-center gap-2">
            <select name="month" class="rounded-lg border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                @foreach(range(1, 12) as $m)
                <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>
                    {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                </option>
                @endforeach
            </select>
            <select name="year" class="rounded-lg border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                @foreach(range(now()->year, now()->year - 2) as $y)
                <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                @endforeach
            </select>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-bold transition-colors">
                Tampilkan
            </button>
        </form>
    </div>

    {{-- Daftar Kegiatan --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        @if($logs->isEmpty())
        <div class="p-12 text-center text-slate-400">
            <svg class="w-16 h-16 mx-auto mb-4 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="text-lg font-medium text-slate-500">Belum ada riwayat kegiatan.</p>
            <p class="text-sm">Silakan input kegiatan melalui menu "Input Kegiatan".</p>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-800 uppercase font-bold border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4">Tanggal & Jam</th>
                        <th class="px-6 py-4">Nama Kegiatan</th>
                        <th class="px-6 py-4 text-center">Volume</th>
                        <th class="px-6 py-4">Bukti Dukung</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($logs as $log)
                    <tr class="hover:bg-slate-50 transition-colors">
                        {{-- Tanggal --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-bold text-slate-800">
                                {{ $log->date->translatedFormat('d F Y') }}
                            </div>
                            <div class="text-xs text-slate-500 flex items-center gap-1 mt-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ \Carbon\Carbon::parse($log->time_recorded)->format('H:i') }}
                            </div>
                        </td>

                        {{-- Nama Kegiatan --}}
                        <td class="px-6 py-4">
                            <div class="flex items-start gap-3">
                                <span class="bg-blue-100 text-blue-700 text-xs font-bold px-2 py-1 rounded flex-shrink-0">
                                    {{ $log->activityType->code }}
                                </span>
                                <div>
                                    <p class="font-bold text-slate-800">{{ $log->activityType->name }}</p>
                                    @if($log->description)
                                    <p class="text-xs text-slate-500 mt-1 italic">"{{ $log->description }}"</p>
                                    @endif
                                </div>
                            </div>
                        </td>

                        {{-- Volume --}}
                        <td class="px-6 py-4 text-center font-medium">
                            @if($log->activityType->input_type == 'numeric')
                            {{ $log->value }} {{ $log->activityType->unit }}
                            @else
                            <span class="text-green-600 flex justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </span>
                            @endif
                        </td>

                        {{-- Bukti --}}
                        <td class="px-6 py-4">
                            @if($log->file_path)
                            <a href="{{ asset('storage/' . $log->file_path) }}" target="_blank" class="text-blue-600 hover:text-blue-800 hover:underline text-xs flex items-center gap-1 font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                </svg>
                                Lihat File
                            </a>
                            @else
                            <span class="text-slate-400 text-xs">-</span>
                            @endif
                        </td>

                        {{-- Aksi --}}
                        <td class="px-6 py-4 text-right">
                            <form action="{{ route('guru.activities.destroy', $log->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 p-2 rounded-lg transition-colors" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>
@endsection