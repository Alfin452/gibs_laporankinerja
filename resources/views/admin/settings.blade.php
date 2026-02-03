@extends('layouts.app')

@section('header', 'Setting Aplikasi')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200">
        <h3 class="text-lg font-bold text-slate-800 mb-4">Konfigurasi Lokasi</h3>

        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Latitude</label>
                    <input type="text" name="school_latitude" id="lat" value="{{ $settings->school_latitude }}" class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500 bg-slate-50" required readonly>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Longitude</label>
                    <input type="text" name="school_longitude" id="lng" value="{{ $settings->school_longitude }}" class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500 bg-slate-50" required readonly>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700 mb-1">Radius Izin (Meter)</label>
                <input type="number" name="radius_meters" value="{{ $settings->radius_meters }}" class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500" required>
                <p class="text-xs text-slate-500 mt-1">Jarak maksimal guru bisa melakukan absensi dari titik pusat.</p>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-slate-700 mb-1">Logo Sekolah</label>
                @if($settings->logo_path)
                <div class="mb-2">
                    <img src="{{ Storage::url($settings->logo_path) }}" alt="Logo" class="h-16 w-auto border p-1 rounded">
                </div>
                @endif
                <input type="file" name="logo" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                Simpan Perubahan
            </button>
        </form>
    </div>

    <div class="bg-white p-1 rounded-xl shadow-sm border border-slate-200 h-96 lg:h-auto flex flex-col">
        <div id="map" class="w-full h-full rounded-lg z-0 min-h-[400px]"></div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Ambil data dari PHP
        let lat = {
            {
                $settings -> school_latitude ?? -3.319363
            }
        };
        let lng = {
            {
                $settings -> school_longitude ?? 114.589803
            }
        };
        let radius = {
            {
                $settings -> radius_meters ?? 100
            }
        };

        // Init Map
        var map = L.map('map').setView([lat, lng], 17);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        }).addTo(map);

        // Marker & Circle
        var marker = L.marker([lat, lng], {
            draggable: true
        }).addTo(map);
        var circle = L.circle([lat, lng], {
            color: 'green',
            fillColor: '#4ade80',
            fillOpacity: 0.2,
            radius: radius
        }).addTo(map);

        // Event: Saat Marker Digeser (Drag End)
        marker.on('dragend', function(e) {
            var position = marker.getLatLng();
            updatePosition(position.lat, position.lng);
        });

        // Event: Saat Peta Diklik
        map.on('click', function(e) {
            updatePosition(e.latlng.lat, e.latlng.lng);
        });

        function updatePosition(newLat, newLng) {
            marker.setLatLng([newLat, newLng]);
            circle.setLatLng([newLat, newLng]);

            // Update Input Form
            document.getElementById('lat').value = newLat;
            document.getElementById('lng').value = newLng;
        }
    });
</script>
@endpush
@endsection