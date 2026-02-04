@extends('layouts.app')

@section('header')
<div class="flex items-center gap-2">
    <a href="{{ route('admin.reports.index', ['month' => $month, 'year' => $year]) }}" class="text-slate-400 hover:text-slate-600">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
    </a>
    <span>Detail Kinerja: {{ $user->name }}</span>
</div>
@endsection

@section('content')
<div class="space-y-6">

    <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200 flex justify-between items-center">
        <div>
            <h2 class="text-xl font-bold text-slate-800">
                Periode: {{ \Carbon\Carbon::create()->month($month)->translatedFormat('F') }} {{ $year }}
            </h2>
            <p class="text-slate-500">Rekapitulasi Harian</p>
        </div>
        <button onclick="window.print()" class="bg-slate-800 hover:bg-slate-900 text-white px-4 py-2 rounded-lg font-bold text-sm flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
            </svg>
            Cetak
        </button>
    </div>

    <div class="grid grid-cols-1 gap-4">
        @foreach($dates as $data)
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 hover:border-blue-300 transition-colors">
            <div class="flex flex-col md:flex-row gap-4">

                <div class="md:w-1/4 border-b md:border-b-0 md:border-r border-slate-100 pb-4 md:pb-0 md:pr-4">
                    <h4 class="font-bold text-slate-800 text-lg">{{ $data['display_date'] }}</h4>

                    <div class="mt-3 space-y-2">
                        @if($data['attendance'])
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500">Masuk:</span>
                            <span class="font-mono font-bold text-blue-600">{{ \Carbon\Carbon::parse($data['attendance']->clock_in)->format('H:i') }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500">Pulang:</span>
                            @if($data['attendance']->clock_out)
                            <span class="font-mono font-bold text-green-600">{{ \Carbon\Carbon::parse($data['attendance']->clock_out)->format('H:i') }}</span>
                            @else
                            <span class="text-slate-400">-</span>
                            @endif
                        </div>
                        @else
                        <div class="px-3 py-1 bg-red-50 text-red-600 text-xs font-bold rounded text-center mt-2">
                            Tidak Ada Absensi
                        </div>
                        @endif
                    </div>
                </div>

                <div class="md:w-3/4 md:pl-4">
                    <h5 class="text-xs font-bold text-slate-400 uppercase mb-2">Kegiatan Terlapor</h5>

                    @if(count($data['logs']) > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        @foreach($data['logs'] as $log)
                        <div class="flex items-center gap-2 text-sm p-2 rounded bg-slate-50 border border-slate-100">
                            <span class="bg-blue-100 text-blue-700 font-bold px-2 py-0.5 rounded text-xs">
                                {{ $log->activityType->code }}
                            </span>
                            <span class="text-slate-700 truncate" title="{{ $log->activityType->name }}">
                                {{ $log->activityType->name }}
                            </span>
                            @if($log->activityType->input_type == 'numeric')
                            <span class="ml-auto font-bold text-slate-900 bg-white px-2 rounded border border-slate-200">
                                {{ $log->value }} Jam
                            </span>
                            @else
                            <span class="ml-auto text-green-500">✔</span>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-slate-400 text-sm italic">Belum ada kegiatan yang diinput.</p>
                    @endif
                </div>

            </div>
        </div>
        @endforeach
    </div>

</div>
@endsection