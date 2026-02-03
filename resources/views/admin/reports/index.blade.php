@extends('layouts.app')

@section('header', 'Rekap Laporan Kinerja Guru')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">

    <div class="p-6 border-b border-slate-100 bg-slate-50">
        <form action="{{ route('admin.reports.index') }}" method="GET" class="flex flex-wrap items-center gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Pilih Bulan</label>
                <select name="month" class="rounded-lg border-gray-300 text-sm focus:ring-blue-500">
                    @for($i=1; $i<=12; $i++)
                        <option value="{{ $i }}" {{ $month == $i ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                        </option>
                        @endfor
                </select>
            </div>
            <button type="submit" class="mt-5 px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-bold hover:bg-blue-700 transition-colors">
                Tampilkan Laporan
            </button>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left border-collapse">
            <thead class="bg-slate-800 text-white uppercase text-xs">
                <tr>
                    <th class="px-4 py-3 border-r border-slate-700 sticky left-0 bg-slate-800 z-10">Nama Guru</th>
                    <th class="px-4 py-3 text-center border-r border-slate-700 bg-blue-900">K1 (Hadir)</th>
                    @foreach($activityTypes as $type)
                    <th class="px-4 py-3 text-center border-r border-slate-700">{{ $type->code }}</th>
                    @endforeach
                    <th class="px-4 py-3 text-center bg-green-700">Persentase</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @foreach($users as $user)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-4 py-3 font-medium text-slate-900 border-r sticky left-0 bg-white hover:bg-slate-50 shadow-[2px_0_5px_rgba(0,0,0,0.05)]">
                        {{ $user->name }}
                        <div class="text-[10px] text-slate-400 font-mono">{{ $user->nip }}</div>
                    </td>

                    <td class="px-4 py-3 text-center border-r font-bold text-blue-600 bg-blue-50">
                        {{ $attendances->where('user_id', $user->id)->where('status', 'ontime')->count() }}
                    </td>

                    @php
                    $totalTerlaksana = 0;
                    $totalKegiatanAktif = $activityTypes->count();
                    @endphp
                    @foreach($activityTypes as $type)
                    @php
                    $count = $logs->where('user_id', $user->id)->where('activity_type_id', $type->id)->count();
                    if($count > 0) $totalTerlaksana++;
                    @endphp
                    <td class="px-4 py-3 text-center border-r {{ $count > 0 ? 'text-slate-800 font-bold' : 'text-slate-300' }}">
                        {{ $count > 0 ? $count : '-' }}
                    </td>
                    @endforeach

                    <td class="px-4 py-3 text-center font-bold bg-green-50 text-green-700">
                        @php
                        // Logika persentase sederhana: (Kegiatan yang pernah dilakukan / Total Jenis Kegiatan)
                        $percent = ($totalTerlaksana / $totalKegiatanAktif) * 100;
                        @endphp
                        {{ number_format($percent, 0) }}%
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection