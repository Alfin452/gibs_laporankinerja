@extends('layouts.app')

@section('header', 'Laporan Kinerja Bulanan')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-slate-200 flex flex-col h-full">

    <div class="p-6 border-b border-slate-100 bg-slate-50 rounded-t-xl flex flex-col md:flex-row justify-between items-center gap-4">
        <h3 class="font-bold text-slate-800">Rekapitulasi Kinerja Guru</h3>

        <form method="GET" action="{{ route('admin.reports.index') }}" class="flex items-center gap-2">
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
                Tampilkan
            </button>
        </form>
    </div>

    <div class="overflow-x-auto w-full">
        <table class="w-full text-left text-sm text-slate-600 border-collapse">
            <thead class="bg-slate-100 text-slate-800 uppercase font-bold border-b border-slate-300">
                <tr>
                    <th class="p-4 border border-slate-300 sticky left-0 bg-slate-100 z-10 w-64 min-w-[200px]">
                        Nama Guru
                    </th>
                    <th class="p-4 border border-slate-300 text-center min-w-[60px] bg-blue-50 text-blue-800">
                        K1
                    </th>
                    @foreach($activityTypes as $type)
                    <th class="p-4 border border-slate-300 text-center min-w-[60px]" title="{{ $type->name }}">
                        {{ $type->code }}
                    </th>
                    @endforeach
                    <th class="p-4 border border-slate-300 text-center sticky right-0 bg-slate-100 z-10">
                        Detail
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @foreach($report as $row)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="p-3 border border-slate-200 sticky left-0 bg-white z-10 font-medium text-slate-900">
                        {{ $row['user']->name }}
                    </td>

                    <td class="p-3 border border-slate-200 text-center font-bold text-blue-700 bg-blue-50">
                        {{ $row['k1'] }}
                    </td>

                    @foreach($activityTypes as $type)
                    <td class="p-3 border border-slate-200 text-center">
                        @php
                        $val = $row['activities'][$type->code] ?? 0;
                        @endphp

                        @if($val > 0)
                        <span class="font-bold text-slate-700">{{ $val }}</span>
                        @else
                        <span class="text-slate-300">-</span>
                        @endif
                    </td>
                    @endforeach

                    <td class="p-3 border border-slate-200 text-center sticky right-0 bg-white z-10">
                        <a href="{{ route('admin.reports.daily', ['id' => $row['user']->id, 'month' => $month, 'year' => $year]) }}"
                            class="bg-blue-100 hover:bg-blue-200 text-blue-700 p-2 rounded-lg inline-block">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection