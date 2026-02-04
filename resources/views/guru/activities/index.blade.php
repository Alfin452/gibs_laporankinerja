@extends('layouts.app')

@section('header', 'Input Kegiatan Harian')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- KOLOM KIRI: Form Input --}}
    <div class="lg:col-span-1 space-y-6">

        {{-- Navigasi Tanggal --}}
        <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200">
            <label class="block text-sm font-medium text-slate-700 mb-2">Pilih Tanggal Laporan</label>
            <form method="GET" action="{{ route('guru.activities.index') }}" class="flex gap-2">
                <input type="date" name="date" value="{{ $date }}"
                    class="w-full rounded-lg border-slate-300 focus:ring-blue-500 focus:border-blue-500 font-bold text-slate-700"
                    onchange="this.form.submit()">
            </form>
        </div>

        {{-- Form Input --}}
        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200">
            <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Tambah Kegiatan Baru
            </h3>

            <form action="{{ route('guru.activities.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <input type="hidden" name="date" value="{{ $date }}">

                {{-- Pilih Kegiatan --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Jenis Kegiatan</label>
                    <select name="activity_type_id" id="activity_type_id" required
                        class="w-full rounded-lg border-slate-300 focus:ring-blue-500 focus:border-blue-500 text-sm">
                        <option value="">-- Pilih Kegiatan --</option>
                        @foreach($activityTypes as $type)
                        <option value="{{ $type->id }}"
                            data-unit="{{ $type->unit }}"
                            data-type="{{ $type->input_type }}">
                            [{{ $type->code }}] {{ $type->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- Input Value (Dinamis) --}}
                <div id="value-container">
                    <label class="block text-sm font-medium text-slate-700 mb-1">
                        Volume / Jumlah <span id="unit-label" class="text-xs text-slate-500 font-normal"></span>
                    </label>
                    <input type="number" name="value" id="value-input" min="0" step="0.1"
                        class="w-full rounded-lg border-slate-300 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Contoh: 2">
                </div>

                {{-- Deskripsi --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Keterangan (Opsional)</label>
                    <textarea name="description" rows="2"
                        class="w-full rounded-lg border-slate-300 focus:ring-blue-500 focus:border-blue-500 text-sm"
                        placeholder="Detail kegiatan..."></textarea>
                </div>

                {{-- Upload File --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Bukti Dukung (Foto/PDF)</label>
                    <input type="file" name="file" accept=".jpg,.jpeg,.png,.pdf"
                        class="block w-full text-sm text-slate-500
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-full file:border-0
                        file:text-xs file:font-semibold
                        file:bg-blue-50 file:text-blue-700
                        hover:file:bg-blue-100">
                    <p class="text-[10px] text-slate-400 mt-1">Maks: 2MB</p>
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 rounded-lg shadow-sm transition-all flex justify-center items-center gap-2">
                    Simpan Kegiatan
                </button>
            </form>
        </div>
    </div>

    {{-- KOLOM KANAN: Daftar Log Hari Ini --}}
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                <h3 class="font-bold text-slate-800">Riwayat Kegiatan Tanggal {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}</h3>
                <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-bold">
                    Total: {{ $logs->count() }} Kegiatan
                </span>
            </div>

            @if($logs->isEmpty())
            <div class="p-10 text-center text-slate-400">
                <svg class="w-16 h-16 mx-auto mb-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                </svg>
                <p>Belum ada kegiatan yang diinput pada tanggal ini.</p>
            </div>
            @else
            <div class="divide-y divide-slate-100">
                @foreach($logs as $log)
                <div class="p-4 hover:bg-slate-50 transition-colors flex flex-col md:flex-row gap-4">
                    {{-- Icon / Kode --}}
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center font-bold text-lg">
                            {{ $log->activityType->code }}
                        </div>
                    </div>

                    {{-- Konten --}}
                    <div class="flex-grow">
                        <h4 class="font-bold text-slate-800">{{ $log->activityType->name }}</h4>
                        <div class="flex flex-wrap gap-x-4 gap-y-1 mt-1 text-sm text-slate-600">
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ \Carbon\Carbon::parse($log->created_at)->format('H:i') }} WIB
                            </span>

                            {{-- Tampilkan Volume jika Numeric --}}
                            @if($log->activityType->input_type == 'numeric')
                            <span class="flex items-center gap-1 font-semibold text-blue-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                                </svg>
                                {{ $log->value }} {{ $log->activityType->unit }}
                            </span>
                            @endif
                        </div>

                        @if($log->description)
                        <p class="mt-2 text-sm text-slate-600 bg-slate-50 p-2 rounded border border-slate-100 italic">
                            "{{ $log->description }}"
                        </p>
                        @endif

                        @if($log->file_path)
                        <div class="mt-2">
                            <a href="{{ asset('storage/' . $log->file_path) }}" target="_blank" class="text-xs text-blue-600 hover:underline flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                </svg>
                                Lihat Bukti Dukung
                            </a>
                        </div>
                        @endif
                    </div>

                    {{-- Action (Hapus) --}}
                    <div class="flex items-center">
                        <form action="{{ route('guru.activities.destroy', $log->id) }}" method="POST" onsubmit="return confirm('Hapus kegiatan ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 p-2 rounded-lg hover:bg-red-50 transition-colors" title="Hapus">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</div>

{{-- Script Sederhana untuk Unit Label --}}
<script>
    document.getElementById('activity_type_id').addEventListener('change', function() {
        const selected = this.options[this.selectedIndex];
        const unit = selected.getAttribute('data-unit');
        const type = selected.getAttribute('data-type');
        const inputContainer = document.getElementById('value-container');
        const unitLabel = document.getElementById('unit-label');
        const valueInput = document.getElementById('value-input');

        if (unit) {
            unitLabel.textContent = `(Satuan: ${unit})`;
        } else {
            unitLabel.textContent = '';
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
</script>
@endsection