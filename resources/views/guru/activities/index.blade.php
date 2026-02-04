@extends('layouts.app')

@section('header', 'Input Kegiatan Harian')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 flex flex-col md:flex-row items-center justify-between gap-6 relative z-30">
        <div class="flex items-center gap-4">
            <div class="p-3 bg-indigo-50 text-indigo-600 rounded-xl shadow-sm shadow-indigo-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest">Periode Laporan</label>
                <p class="text-slate-800 font-medium text-lg">Pilih Tanggal Kegiatan</p>
            </div>
        </div>

        <div class="w-full md:w-72" 
             x-data="datePickerComponent('{{ $date }}')" 
             x-init="init()" 
             @click.outside="showDatepicker = false">
            
            <form action="{{ route('guru.activities.index') }}" method="GET" x-ref="dateForm">
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
                                <button type="button" @click="prevMonth()" class="p-1.5 rounded-lg hover:bg-slate-100 text-slate-500 hover:text-indigo-600 transition-colors"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg></button>
                                <button type="button" @click="nextMonth()" class="p-1.5 rounded-lg hover:bg-slate-100 text-slate-500 hover:text-indigo-600 transition-colors"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></button>
                            </div>
                        </div>

                        <div class="grid grid-cols-7 mb-2">
                            <template x-for="day in DAYS" :key="day">
                                <div class="text-center"><div class="text-xs font-bold text-slate-400 uppercase tracking-wide" x-text="day"></div></div>
                            </template>
                        </div>

                        <div class="grid grid-cols-7 gap-1">
                            <template x-for="blank in blankDays" :key="blank"><div class="h-9 w-9"></div></template>
                            <template x-for="(date, dateIndex) in no_of_days" :key="dateIndex">
                                <div class="relative">
                                    <button type="button" @click="selectDate(date)"
                                            class="h-9 w-9 rounded-full flex items-center justify-center text-sm font-medium transition-all duration-200 relative z-10"
                                            :class="{'bg-indigo-600 text-white shadow-md shadow-indigo-500/30 font-bold scale-110': isSelectedDate(date), 'text-slate-700 hover:bg-indigo-50 hover:text-indigo-600': !isSelectedDate(date), 'bg-slate-100 text-slate-400': isToday(date) && !isSelectedDate(date)}">
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start relative z-10">
        
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden sticky top-24">
                <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="font-bold text-slate-800 flex items-center gap-2">
                        <span class="p-1.5 bg-blue-100 text-blue-600 rounded-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        </span>
                        Form Kegiatan
                    </h3>
                </div>

                <div class="p-6">
                    <form action="{{ route('guru.activities.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                        @csrf
                        <input type="hidden" name="date" value="{{ $date }}">

                        <div class="space-y-1.5">
                            <label class="text-sm font-semibold text-slate-700 ml-1">Jenis Kegiatan</label>
                            <div class="relative">
                                <select name="activity_type_id" id="activity_type_id" required
                                    class="w-full appearance-none rounded-xl border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium text-slate-700 shadow-sm focus:border-indigo-500 focus:bg-white focus:ring-indigo-500 cursor-pointer transition-all">
                                    <option value="">-- Pilih Jenis Kegiatan --</option>
                                    @foreach($activityTypes as $type)
                                    <option value="{{ $type->id }}"
                                        data-unit="{{ $type->unit }}"
                                        data-type="{{ $type->input_type }}">
                                        [{{ $type->code }}] {{ $type->name }}
                                    </option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                        </div>

                        <div id="value-container" class="space-y-1.5">
                            <label class="text-sm font-semibold text-slate-700 ml-1 flex justify-between">
                                <span>Volume / Jumlah</span>
                                <span id="unit-label" class="text-xs font-bold text-indigo-500 bg-indigo-50 px-2 rounded-md"></span>
                            </label>
                            <div class="relative">
                                <input type="number" name="value" id="value-input" min="0" step="0.1"
                                    class="w-full rounded-xl border-slate-200 px-4 py-3 font-bold text-slate-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 placeholder-slate-300"
                                    placeholder="0">
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-sm font-semibold text-slate-700 ml-1">Keterangan <span class="text-slate-400 font-normal text-xs">(Opsional)</span></label>
                            <textarea name="description" rows="3"
                                class="w-full rounded-xl border-slate-200 px-4 py-3 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 placeholder-slate-300"
                                placeholder="Tuliskan detail kegiatan jika diperlukan..."></textarea>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-sm font-semibold text-slate-700 ml-1">Bukti Dukung</label>
                            <div class="relative group">
                                <label for="file-upload" class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-slate-300 rounded-xl cursor-pointer bg-slate-50 hover:bg-indigo-50/50 hover:border-indigo-300 transition-all group-hover:scale-[1.01]">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <svg class="w-8 h-8 mb-3 text-slate-400 group-hover:text-indigo-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                        </svg>
                                        <p class="mb-2 text-xs text-slate-500"><span class="font-bold text-slate-700">Klik upload</span> atau drag file</p>
                                        <p class="text-[10px] text-slate-400">PDF, JPG, PNG (Max 2MB)</p>
                                    </div>
                                    <input id="file-upload" name="file" type="file" class="hidden" accept=".jpg,.jpeg,.png,.pdf" onchange="showFileName(this)" />
                                </label>
                            </div>
                            <div id="file-name-display" class="hidden items-center gap-2 text-xs text-emerald-600 font-medium bg-emerald-50 p-2 rounded-lg border border-emerald-100 mt-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span class="truncate"></span>
                            </div>
                        </div>

                        <div class="pt-2">
                            <button type="submit" class="w-full inline-flex justify-center items-center px-6 py-3.5 border border-transparent text-sm font-bold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-lg shadow-indigo-500/30 transition-all hover:scale-[1.02]">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                                </svg>
                                Simpan Kegiatan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="font-bold text-lg text-slate-800 flex items-center gap-2">
                    <span class="w-1.5 h-6 bg-indigo-500 rounded-full"></span>
                    Riwayat Kegiatan
                </h3>
                <span class="bg-white border border-slate-200 px-3 py-1 rounded-full text-xs font-semibold text-slate-600 shadow-sm">
                    {{ \Carbon\Carbon::parse($date)->locale('id')->translatedFormat('d F Y') }}
                </span>
            </div>

            @if($logs->isEmpty())
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-12 text-center">
                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 relative">
                        <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        <div class="absolute inset-0 border-2 border-slate-100 rounded-full animate-ping opacity-20"></div>
                    </div>
                    <h4 class="text-slate-800 font-bold text-lg mb-1">Belum Ada Kegiatan</h4>
                    <p class="text-slate-400 text-sm max-w-xs mx-auto">Kegiatan yang Anda input untuk tanggal ini akan muncul di sini.</p>
                </div>
            @else
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="divide-y divide-slate-100">
                        @foreach($logs as $log)
                        <div class="p-5 hover:bg-slate-50 transition-colors group">
                            <div class="flex items-start gap-4">
                                <div class="shrink-0">
                                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 text-white flex flex-col items-center justify-center shadow-md shadow-indigo-200">
                                        <span class="text-[10px] font-medium opacity-80">KODE</span>
                                        <span class="font-bold text-lg leading-none">{{ $log->activityType->code }}</span>
                                    </div>
                                </div>

                                <div class="grow min-w-0">
                                    <div class="flex flex-wrap items-start justify-between gap-2 mb-1">
                                        <h4 class="font-bold text-slate-800 text-base">{{ $log->activityType->name }}</h4>
                                        <span class="text-xs font-mono text-slate-400 bg-slate-100 px-2 py-0.5 rounded">
                                            {{ \Carbon\Carbon::parse($log->created_at)->format('H:i') }}
                                        </span>
                                    </div>

                                    <div class="flex flex-wrap gap-2 mb-2">
                                        @if($log->activityType->input_type == 'numeric')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                                            {{ $log->value }} {{ $log->activityType->unit }}
                                        </span>
                                        @endif
                                        
                                        @if($log->file_path)
                                        <a href="{{ Storage::url($log->file_path) }}" target="_blank" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium bg-blue-50 text-blue-600 border border-blue-100 hover:bg-blue-100 transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                            Lihat Bukti
                                        </a>
                                        @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium bg-slate-50 text-slate-400 border border-slate-100">
                                            Tanpa Bukti
                                        </span>
                                        @endif
                                    </div>

                                    @if($log->description)
                                    <p class="text-sm text-slate-600 leading-relaxed bg-slate-50 p-3 rounded-xl border border-slate-100">
                                        {{ $log->description }}
                                    </p>
                                    @endif
                                </div>

                                <div class="shrink-0 self-center">
                                    <form action="{{ route('guru.activities.destroy', $log->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kegiatan ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-slate-300 hover:text-red-500 p-2 rounded-lg hover:bg-red-50 transition-colors opacity-0 group-hover:opacity-100" title="Hapus Kegiatan">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- SCRIPT: Alpine Datepicker & Form Logic --}}
<script>
    // Logic untuk File Upload Display Name
    function showFileName(input) {
        const display = document.getElementById('file-name-display');
        const span = display.querySelector('span');
        if (input.files && input.files[0]) {
            span.textContent = input.files[0].name;
            display.classList.remove('hidden');
            display.classList.add('flex');
        } else {
            display.classList.add('hidden');
            display.classList.remove('flex');
        }
    }

    // Logic untuk Unit Label
    document.getElementById('activity_type_id').addEventListener('change', function() {
        const selected = this.options[this.selectedIndex];
        const unit = selected.getAttribute('data-unit');
        const type = selected.getAttribute('data-type');
        
        const inputContainer = document.getElementById('value-container');
        const unitLabel = document.getElementById('unit-label');
        const valueInput = document.getElementById('value-input');

        if (unit) {
            unitLabel.textContent = unit;
            unitLabel.style.display = 'inline-block';
        } else {
            unitLabel.style.display = 'none';
        }

        // Jika tipe 'check' (ceklis), sembunyikan input angka karena otomatis 1
        if (type === 'check') {
            inputContainer.style.display = 'none';
            valueInput.removeAttribute('required');
        } else {
            inputContainer.style.display = 'block';
            valueInput.setAttribute('required', 'required');
        }
    });

    // Logic Datepicker Component (Sama seperti Admin Daily)
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
                let selected = new Date(Date.UTC(this.year, this.month, date));
                this.selectedDate = selected.toISOString().split('T')[0];
                this.showDatepicker = false;
                this.$nextTick(() => { this.$refs.dateForm.submit(); });
            },

            getNoOfDays() {
                let daysInMonth = new Date(this.year, this.month + 1, 0).getDate();
                let dayOfWeek = new Date(this.year, this.month).getDay();
                let blankdaysArray = [];
                for (var i = 1; i <= dayOfWeek; i++) { blankdaysArray.push(i); }
                let daysArray = [];
                for (var i = 1; i <= daysInMonth; i++) { daysArray.push(i); }
                this.blankDays = blankdaysArray;
                this.no_of_days = daysArray;
            },

            prevMonth() {
                if (this.month == 0) { this.month = 11; this.year--; } else { this.month--; }
                this.getNoOfDays();
            },

            nextMonth() {
                if (this.month == 11) { this.month = 0; this.year++; } else { this.month++; }
                this.getNoOfDays();
            }
        }
    }
</script>
@endsection