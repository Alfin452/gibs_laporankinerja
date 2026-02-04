@extends('layouts.app')

@section('header', 'Input Harian Substitute')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 flex flex-col md:flex-row items-center justify-between gap-6 relative z-20">

        <div class="flex items-center gap-4">
            <div class="p-3 bg-indigo-50 text-indigo-600 rounded-xl shadow-sm shadow-indigo-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest">Periode Data</label>
                <p class="text-slate-800 font-medium text-lg">Pilih Tanggal Laporan</p>
            </div>
        </div>

        <div class="w-full md:w-72"
            x-data="datePickerComponent('{{ $date }}')"
            x-init="init()"
            @click.outside="showDatepicker = false">

            <form action="{{ route('admin.substitutes.daily') }}" method="GET" x-ref="dateForm">
                <input type="hidden" name="date" x-model="selectedDate">

                <div class="relative">
                    <button type="button"
                        @click="showDatepicker = !showDatepicker"
                        class="w-full bg-slate-50 border border-slate-200 text-slate-700 font-semibold py-3 px-4 rounded-xl shadow-sm hover:bg-white hover:border-indigo-300 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 transition-all flex items-center justify-between group">

                        <span x-text="formattedDateDisplay" class="text-base"></span>

                        <svg class="w-5 h-5 text-slate-400 group-hover:text-indigo-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <div x-show="showDatepicker"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 translate-y-2"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 translate-y-2"
                        class="absolute top-full right-0 mt-3 w-80 bg-white rounded-2xl shadow-xl border border-slate-100 p-4 z-50"
                        x-cloak>

                        <div class="flex justify-between items-center mb-4">
                            <div>
                                <span x-text="MONTH_NAMES[month]" class="text-lg font-bold text-slate-800"></span>
                                <span x-text="year" class="ml-1 text-lg font-normal text-slate-500"></span>
                            </div>
                            <div class="flex items-center gap-1">
                                <button type="button" @click="prevMonth()" class="p-1.5 rounded-lg hover:bg-slate-100 text-slate-500 hover:text-indigo-600 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                    </svg>
                                </button>
                                <button type="button" @click="nextMonth()" class="p-1.5 rounded-lg hover:bg-slate-100 text-slate-500 hover:text-indigo-600 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="grid grid-cols-7 mb-2">
                            <template x-for="day in DAYS" :key="day">
                                <div class="text-center">
                                    <div class="text-xs font-bold text-slate-400 uppercase tracking-wide" x-text="day"></div>
                                </div>
                            </template>
                        </div>

                        <div class="grid grid-cols-7 gap-1">
                            <template x-for="blank in blankDays" :key="blank">
                                <div class="h-9 w-9"></div>
                            </template>

                            <template x-for="(date, dateIndex) in no_of_days" :key="dateIndex">
                                <div class="relative">
                                    <button type="button"
                                        @click="selectDate(date)"
                                        class="h-9 w-9 rounded-full flex items-center justify-center text-sm font-medium transition-all duration-200 relative z-10"
                                        :class="{
                                                'bg-indigo-600 text-white shadow-md shadow-indigo-500/30 font-bold scale-110': isSelectedDate(date),
                                                'text-slate-700 hover:bg-indigo-50 hover:text-indigo-600': !isSelectedDate(date),
                                                'bg-slate-100 text-slate-400': isToday(date) && !isSelectedDate(date)
                                            }">
                                        <span x-text="date"></span>
                                        <div x-show="isToday(date) && !isSelectedDate(date)" class="absolute bottom-1 w-1 h-1 bg-indigo-500 rounded-full"></div>
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden relative z-10">
        <form action="{{ route('admin.substitutes.storeDaily') }}" method="POST">
            @csrf
            <input type="hidden" name="date" value="{{ $date }}">

            <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                <h3 class="font-bold text-slate-800 text-lg flex items-center gap-2">
                    <span class="w-2 h-6 bg-indigo-500 rounded-full"></span>
                    Input Kinerja Guru
                </h3>
                <span class="px-3 py-1 bg-white border border-slate-200 rounded-full text-xs font-semibold text-slate-600 flex items-center gap-2">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    {{ \Carbon\Carbon::parse($date)->locale('id')->translatedFormat('l, d F Y') }}
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-white border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-4 font-semibold text-slate-700 w-1/3">Nama Guru</th>
                            <th class="px-6 py-4 font-semibold text-slate-700 text-center w-1/3">Terlaksana</th>
                            <th class="px-6 py-4 font-semibold text-slate-700 text-center w-1/3">Alpha (Kosong)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 bg-white">
                        @foreach($users as $user)
                        @php
                        $log = $logs->get($user->id);
                        @endphp
                        <tr class="hover:bg-slate-50 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-xs group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="font-medium text-slate-800 text-base">{{ $user->name }}</div>
                                        <div class="text-xs text-slate-400">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4 text-center">
                                <div class="relative max-w-[100px] mx-auto">
                                    <input type="number"
                                        name="data[{{ $user->id }}][terlaksana]"
                                        value="{{ $log ? $log->terlaksana : 0 }}"
                                        min="0"
                                        class="block w-full text-center rounded-xl border-slate-200 font-bold text-emerald-600 focus:border-emerald-500 focus:ring-emerald-500 bg-slate-50 focus:bg-white shadow-sm transition-all"
                                        placeholder="0">
                                </div>
                            </td>

                            <td class="px-6 py-4 text-center">
                                <div class="relative max-w-[100px] mx-auto">
                                    <input type="number"
                                        name="data[{{ $user->id }}][alpha]"
                                        value="{{ $log ? $log->alpha : 0 }}"
                                        min="0"
                                        class="block w-full text-center rounded-xl border-slate-200 font-bold text-rose-600 focus:border-rose-500 focus:ring-rose-500 bg-slate-50 focus:bg-white shadow-sm transition-all"
                                        placeholder="0">
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-6 border-t border-slate-100 bg-slate-50 flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-2 text-xs text-slate-500">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Isi "0" jika guru tidak memiliki jadwal. Data otomatis disimpan saat tombol diklik.</span>
                </div>
                <button type="submit" class="w-full md:w-auto inline-flex justify-center items-center px-6 py-3 border border-transparent text-sm font-medium rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-lg shadow-indigo-500/30 transition-all hover:scale-[1.02]">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                    </svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Script Khusus Datepicker --}}
<script>
    function datePickerComponent(initialDate) {
        return {
            MONTH_NAMES: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
            DAYS: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
            showDatepicker: false,
            selectedDate: initialDate,
            month: '',
            year: '',
            no_of_days: [],
            blankdays: [],

            init() {
                let today = new Date(this.selectedDate);
                this.month = today.getMonth();
                this.year = today.getFullYear();
                this.getNoOfDays();
            },

            // Format tanggal untuk tampilan teks tombol (Contoh: Rabu, 04 Februari 2026)
            get formattedDateDisplay() {
                if (!this.selectedDate) return '';
                let date = new Date(this.selectedDate);
                return new Intl.DateTimeFormat('id-ID', {
                    weekday: 'long',
                    day: '2-digit',
                    month: 'long',
                    year: 'numeric'
                }).format(date);
            },

            isToday(date) {
                const today = new Date();
                const d = new Date(this.year, this.month, date);
                return today.toDateString() === d.toDateString();
            },

            isSelectedDate(date) {
                const d = new Date(this.year, this.month, date);
                const selected = new Date(this.selectedDate);
                return d.toDateString() === selected.toDateString();
            },

            selectDate(date) {
                // Format YYYY-MM-DD manual agar timezone aman
                let selected = new Date(Date.UTC(this.year, this.month, date));
                this.selectedDate = selected.toISOString().split('T')[0];

                this.showDatepicker = false;

                // Submit Form
                this.$nextTick(() => {
                    this.$refs.dateForm.submit();
                });
            },

            getNoOfDays() {
                let daysInMonth = new Date(this.year, this.month + 1, 0).getDate();
                let dayOfWeek = new Date(this.year, this.month).getDay();

                let blankdaysArray = [];
                for (var i = 1; i <= dayOfWeek; i++) {
                    blankdaysArray.push(i);
                }

                let daysArray = [];
                for (var i = 1; i <= daysInMonth; i++) {
                    daysArray.push(i);
                }

                this.blankDays = blankdaysArray;
                this.no_of_days = daysArray;
            },

            prevMonth() {
                if (this.month == 0) {
                    this.month = 11;
                    this.year--;
                } else {
                    this.month--;
                }
                this.getNoOfDays();
            },

            nextMonth() {
                if (this.month == 11) {
                    this.month = 0;
                    this.year++;
                } else {
                    this.month++;
                }
                this.getNoOfDays();
            }
        }
    }
</script>
@endsection