@extends('layouts.app')

@section('header', 'Target Substitute (Badal)')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-slate-200">
    {{-- Bagian Header & Filter tetap sama --}}
    <div class="p-6 border-b border-slate-100 bg-slate-50 rounded-t-xl flex flex-col md:flex-row justify-between items-center gap-4">
        <h3 class="font-bold text-slate-800">Input Manual Kinerja Substitute</h3>

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
                        <th class="px-6 py-4 text-center bg-blue-50 text-blue-800 w-40">
                            Terlaksana
                            <span class="block text-[10px] font-normal normal-case text-blue-600">Input Manual</span>
                        </th>
                        <th class="px-6 py-4 text-center bg-red-50 text-red-800 w-40">
                            Tidak Terlaksana (Alpha)
                            <span class="block text-[10px] font-normal normal-case text-red-600">Input Manual</span>
                        </th>
                        <th class="px-6 py-4 text-center font-bold bg-slate-50">
                            Persentase
                            <span class="block text-[10px] font-normal normal-case text-slate-500">Capaian Kinerja</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($data as $row)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-3 font-bold text-slate-900">
                            {{ $row['user']->name }}
                        </td>

                        {{-- INPUT TERLAKSANA --}}
                        <td class="px-6 py-3 text-center bg-blue-50/30">
                            <input type="number" min="0"
                                name="terlaksanas[{{ $row['user']->id }}]"
                                value="{{ $row['terlaksana'] }}"
                                class="w-24 text-center rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500 font-bold text-blue-600"
                                placeholder="0">
                        </td>

                        {{-- INPUT ALPHA --}}
                        <td class="px-6 py-3 text-center bg-red-50/30">
                            <input type="number" min="0"
                                name="alphas[{{ $row['user']->id }}]"
                                value="{{ $row['alpha'] }}"
                                class="w-24 text-center rounded-lg border-slate-300 focus:border-red-500 focus:ring-red-500 font-bold text-red-600"
                                placeholder="0">
                        </td>

                        {{-- OUTPUT PERSENTASE --}}
                        <td class="px-6 py-3 text-center">
                            @php
                            $color = $row['persentase'] >= 90 ? 'text-green-600' : ($row['persentase'] >= 70 ? 'text-yellow-600' : 'text-red-600');
                            @endphp
                            <span class="font-bold text-lg {{ $color }}">
                                {{ $row['persentase'] }}%
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-200 bg-slate-50 flex justify-end">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-xl shadow-lg transition-transform hover:scale-105 flex items-center gap-2">
                Simpan Data
            </button>
        </div>
    </form>
</div>
@endsection