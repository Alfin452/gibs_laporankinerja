@extends('layouts.app')

@section('header', 'Target Substitute (Badal)')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-slate-200">

    <div class="p-6 border-b border-slate-100 bg-slate-50 rounded-t-xl flex flex-col md:flex-row justify-between items-center gap-4">
        <h3 class="font-bold text-slate-800">Rekap & Input Alpha Substitute</h3>

        <form method="GET" action="{{ route('admin.substitutes.index') }}" class="flex items-center gap-2">
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

    <form action="{{ route('admin.substitutes.store') }}" method="POST">
        @csrf
        <input type="hidden" name="month" value="{{ $month }}">
        <input type="hidden" name="year" value="{{ $year }}">

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-white text-slate-800 uppercase font-bold border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4">Nama Guru</th>
                        <th class="px-6 py-4 text-center bg-green-50 text-green-800">
                            Terlaksana (K2)
                            <span class="block text-[10px] font-normal normal-case text-green-600">Otomatis dari Laporan</span>
                        </th>
                        <th class="px-6 py-4 text-center bg-red-50 text-red-800 w-40">
                            Tidak Terlaksana (Alpha)
                            <span class="block text-[10px] font-normal normal-case text-red-600">Input Manual</span>
                        </th>
                        <th class="px-6 py-4 text-center font-bold">Total Beban</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($data as $row)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-3 font-bold text-slate-900">
                            {{ $row['user']->name }}
                        </td>

                        <td class="px-6 py-3 text-center">
                            <span class="inline-block px-3 py-1 bg-green-100 text-green-700 rounded-lg font-bold">
                                {{ $row['terlaksana'] }}x
                            </span>
                        </td>

                        <td class="px-6 py-3 text-center bg-red-50/30">
                            <input type="number" min="0"
                                name="alphas[{{ $row['user']->id }}]"
                                value="{{ $row['alpha'] }}"
                                class="w-24 text-center rounded-lg border-slate-300 focus:border-red-500 focus:ring-red-500 font-bold text-red-600"
                                placeholder="0">
                        </td>

                        <td class="px-6 py-3 text-center font-bold text-slate-700">
                            {{ $row['terlaksana'] + $row['alpha'] }}x
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-200 bg-slate-50 flex justify-end">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-xl shadow-lg transition-transform hover:scale-105 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                </svg>
                Simpan Data Alpha
            </button>
        </div>
    </form>
</div>
@endsection