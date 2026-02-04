@extends('layouts.app')

@section('header', 'Input Harian Substitute')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-slate-200">

    {{-- Header & Navigasi Tanggal --}}
    <div class="p-6 border-b border-slate-100 bg-slate-50 rounded-t-xl flex flex-col md:flex-row justify-between items-center gap-4">

        {{-- Bagian Kiri: Tombol Kembali & Judul --}}
        <div class="flex items-center gap-4">
            {{-- Tombol Kembali --}}
            <a href="{{ route('admin.substitutes.index') }}"
                class="bg-white border border-slate-300 text-slate-600 hover:bg-slate-100 hover:text-slate-800 p-2.5 rounded-xl shadow-sm transition-all"
                title="Kembali ke Rekap Bulanan">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>

            <div>
                <h3 class="font-bold text-slate-800 text-lg">Input Data Harian</h3>
                <p class="text-slate-500 text-sm">Data otomatis ter-update jika tanggal sama.</p>
            </div>
        </div>

        {{-- Bagian Kanan: Navigasi Tanggal --}}
        <div class="flex items-center bg-white rounded-lg shadow-sm border border-slate-300 p-1">
            {{-- Tombol Mundur (Kemarin) --}}
            <a href="{{ route('admin.substitutes.daily', ['date' => \Carbon\Carbon::parse($date)->subDay()->format('Y-m-d')]) }}"
                class="p-2 hover:bg-slate-100 rounded-md text-slate-600 transition-colors"
                title="Hari Sebelumnya">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>

            {{-- Input Tanggal (Picker) --}}
            <form method="GET" action="{{ route('admin.substitutes.daily') }}">
                <input type="date" name="date" value="{{ $date }}"
                    class="border-0 focus:ring-0 text-slate-700 font-bold text-sm cursor-pointer"
                    onchange="this.form.submit()">
            </form>

            {{-- Tombol Maju (Besok) --}}
            <a href="{{ route('admin.substitutes.daily', ['date' => \Carbon\Carbon::parse($date)->addDay()->format('Y-m-d')]) }}"
                class="p-2 hover:bg-slate-100 rounded-md text-slate-600 transition-colors"
                title="Hari Berikutnya">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>
    </div>

    <form action="{{ route('admin.substitutes.storeDaily') }}" method="POST">
        @csrf
        <input type="hidden" name="date" value="{{ $date }}">

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-800 uppercase font-bold border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4">Nama Guru</th>
                        <th class="px-6 py-4 text-center w-32 bg-blue-50/50 text-blue-800">Terlaksana</th>
                        <th class="px-6 py-4 text-center w-32 bg-red-50/50 text-red-800">Alpha</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($users as $user)
                    @php
                    $log = $logs[$user->id] ?? null;
                    $hasData = ($log && ($log->terlaksana > 0 || $log->alpha > 0));
                    $rowClass = $hasData ? 'bg-blue-50/10' : 'hover:bg-slate-50';
                    @endphp
                    <tr class="{{ $rowClass }} transition-colors">
                        <td class="px-6 py-3 font-medium text-slate-900">
                            {{ $user->name }}
                            @if($hasData)
                            <span class="ml-2 inline-block w-2 h-2 rounded-full bg-blue-500" title="Data tersimpan"></span>
                            @endif
                        </td>

                        <td class="px-6 py-3 text-center">
                            <input type="number" min="0"
                                name="data[{{ $user->id }}][terlaksana]"
                                value="{{ $log ? $log->terlaksana : 0 }}"
                                class="w-20 text-center rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500 text-blue-600 font-bold bg-blue-50/30 focus:bg-white transition-colors">
                        </td>

                        <td class="px-6 py-3 text-center">
                            <input type="number" min="0"
                                name="data[{{ $user->id }}][alpha]"
                                value="{{ $log ? $log->alpha : 0 }}"
                                class="w-20 text-center rounded-lg border-slate-300 focus:border-red-500 focus:ring-red-500 text-red-600 font-bold bg-red-50/30 focus:bg-white transition-colors">
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-200 bg-slate-50 flex justify-end sticky bottom-0 z-10 bg-white">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-xl shadow-lg transition-transform hover:scale-105 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                </svg>
                Simpan Perubahan ({{ \Carbon\Carbon::parse($date)->translatedFormat('d M') }})
            </button>
        </div>
    </form>
</div>
@endsection