@extends('layouts.app')

@section('header', 'Manajemen Jenis Kegiatan (K)')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-lg font-bold text-slate-800">Daftar Kode Kegiatan</h2>
        <button class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-bold">+ Tambah K baru</button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @foreach($activityTypes as $type)
        <div class="p-4 border border-slate-100 rounded-xl bg-slate-50 flex justify-between items-center">
            <div>
                <span class="text-blue-600 font-bold block">{{ $type->code }}</span>
                <span class="text-slate-700 text-sm">{{ $type->name }}</span>
            </div>
            <div class="text-xs {{ $type->input_type == 'numeric' ? 'bg-orange-100 text-orange-600' : 'bg-blue-100 text-blue-600' }} px-2 py-1 rounded">
                {{ ucfirst($type->input_type) }}
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection