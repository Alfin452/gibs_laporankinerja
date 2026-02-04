@extends('layouts.app')

@section('header', 'Master Kegiatan (K1-K20)')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-slate-200">
    <div class="p-6 border-b border-slate-100 flex justify-between items-center">
        <h3 class="font-bold text-slate-800">Daftar Jenis Kegiatan</h3>
        <button onclick="openModal('add')" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold py-2 px-4 rounded-lg transition-colors">
            + Tambah Kode
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-slate-600">
            <thead class="bg-slate-50 text-slate-800 uppercase font-bold">
                <tr>
                    <th class="px-6 py-3">Kode</th>
                    <th class="px-6 py-3">Nama Kegiatan</th>
                    <th class="px-6 py-3">Tipe Input</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($activities as $activity)
                <tr class="hover:bg-slate-50">
                    <td class="px-6 py-4 font-bold text-blue-600">{{ $activity->code }}</td>
                    <td class="px-6 py-4">{{ $activity->name }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded text-xs font-bold {{ $activity->input_type == 'numeric' ? 'bg-orange-100 text-orange-600' : 'bg-purple-100 text-purple-600' }}">
                            {{ strtoupper($activity->input_type) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        @if($activity->is_active)
                        <span class="text-green-500 flex items-center gap-1">
                            <span class="w-2 h-2 bg-green-500 rounded-full"></span> Aktif
                        </span>
                        @else
                        <span class="text-red-400 flex items-center gap-1">
                            <span class="w-2 h-2 bg-red-400 rounded-full"></span> Nonaktif
                        </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right flex justify-end gap-3">
                        <form action="{{ route('admin.activities.toggle', $activity->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="text-xs {{ $activity->is_active ? 'text-red-500' : 'text-green-500' }} font-bold hover:underline">
                                {{ $activity->is_active ? 'Matikan' : 'Aktifkan' }}
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div id="modal-activity" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl max-w-md w-full p-6 shadow-2xl">
        <h2 class="text-xl font-bold mb-4">Tambah Kegiatan</h2>
        <form action="{{ route('admin.activities.store') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Kode (Contoh: K21)</label>
                    <input type="text" name="code" class="w-full rounded-lg border-slate-300" required placeholder="K21">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Nama Kegiatan</label>
                    <input type="text" name="name" class="w-full rounded-lg border-slate-300" required placeholder="Contoh: Pembina Ekstrakurikuler">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Tipe Input</label>
                    <select name="input_type" class="w-full rounded-lg border-slate-300">
                        <option value="boolean">Boolean (Ceklis Hadir)</option>
                        <option value="numeric">Numeric (Input Jam/Angka)</option>
                    </select>
                </div>
            </div>
            <div class="mt-6 flex justify-end gap-3">
                <button type="button" onclick="closeModal()" class="px-4 py-2 text-slate-500 font-bold">Batal</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg font-bold">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal() {
        document.getElementById('modal-activity').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('modal-activity').classList.add('hidden');
    }
</script>
@endsection