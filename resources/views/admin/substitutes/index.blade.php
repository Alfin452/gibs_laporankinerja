@extends('layouts.app')

@section('header', 'Rekap Substitute (Badal)')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-slate-200">

    {{-- Header --}}
    <div class="p-6 border-b border-slate-100 bg-slate-50 rounded-t-xl flex flex-col md:flex-row justify-between items-center gap-4">
        <h3 class="font-bold text-slate-800">Rekap Bulanan Substitute</h3>

        <div class="flex gap-2">
            {{-- Tombol menuju Input Harian --}}
            <a href="{{ route('admin.substitutes.daily') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-bold flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Input Harian
            </a>

            {{-- Filter Bulan --}}
            <form method="GET" action="{{ route('admin.substitutes.index') }}" class="flex items-center gap-2 border-l pl-2 border-slate-300">
                <select name="month" class="rounded-lg border-slate-300 text-sm">
                    @foreach(range(1, 12) as $m)
                    <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                    </option>
                    @endforeach
                </select>
                <select name="year" class="rounded-lg border-slate-300 text-sm">
                    @foreach(range(now()->year, now()->year - 2) as $y)
                    <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-bold">
                    Cari
                </button>
            </form>
        </div>
    </div>

    {{-- Tabel Rekap --}}
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-slate-600">
            <thead class="bg-white text-slate-800 uppercase font-bold border-b border-slate-200">
                <tr>
                    <th class="px-6 py-4">Nama Guru</th>
                    <th class="px-6 py-4 text-center">Total Terlaksana</th>
                    <th class="px-6 py-4 text-center">Total Alpha</th>
                    <th class="px-6 py-4 text-center">Persentase</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($data as $row)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-3 font-bold text-slate-900">{{ $row['user']->name }}</td>

                    <td class="px-6 py-3 text-center">
                        <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full font-bold text-xs">
                            {{ $row['terlaksana'] }}x
                        </span>
                    </td>

                    <td class="px-6 py-3 text-center">
                        <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full font-bold text-xs">
                            {{ $row['alpha'] }}x
                        </span>
                    </td>

                    <td class="px-6 py-3 text-center">
                        @php
                        $color = $row['persentase'] >= 90 ? 'text-green-600' : ($row['persentase'] >= 70 ? 'text-yellow-600' : 'text-red-600');
                        @endphp
                        <span class="font-bold {{ $color }}">
                            {{ $row['persentase'] }}%
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-8 text-center text-slate-400">Belum ada data di bulan ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection