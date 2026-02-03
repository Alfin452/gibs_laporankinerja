@extends('layouts.app')

@section('header', 'Dashboard Absensi')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex flex-col justify-between">
        <div>
            <h2 class="text-lg font-semibold text-slate-800">Halo, {{ Auth::user()->name }} 👋</h2>
            <p class="text-slate-500 text-sm">Jangan lupa absensi tepat waktu ya!</p>
        </div>

        <div class="py-8 text-center">
            <div class="text-6xl font-bold text-slate-800 tracking-tight" id="digital-clock">00:00:00</div>
            <div class="text-slate-500 font-medium mt-2">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</div>
        </div>

        <div class="mt-4">
            <div id="location-status" class="mb-4 p-3 rounded-lg text-sm bg-yellow-50 text-yellow-700 flex items-center justify-between hidden">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    <span id="status-text">Mencari Lokasi Anda...</span>
                </div>
                <button type="button" onclick="requestLocation()" class="ml-2 text-xs bg-yellow-200 hover:bg-yellow-300 text-yellow-800 px-3 py-1 rounded font-bold transition-colors shadow-sm">
                    🔄 Coba Lagi
                </button>
            </div>

            <div id="debug-msg" class="hidden mb-4 p-2 text-xs text-red-600 bg-red-50 border border-red-200 rounded font-mono"></div>

            @if(!$attendance)
            <form action="{{ route('guru.attendance.in') }}" method="POST">
                @csrf
                <input type="hidden" name="latitude" id="lat_in">
                <input type="hidden" name="longitude" id="long_in">

                <button type="submit" id="btn-submit" disabled
                    class="w-full py-4 rounded-xl text-white font-bold text-lg shadow-lg bg-gray-400 cursor-not-allowed transition-colors duration-200">
                    📍 Menunggu GPS...
                </button>
            </form>
            @elseif($attendance && !$attendance->clock_out)
            <form action="{{ route('guru.attendance.out') }}" method="POST">
                @csrf
                <input type="hidden" name="latitude" id="lat_in">
                <input type="hidden" name="longitude" id="long_in">

                <div class="bg-blue-50 border border-blue-200 p-4 rounded-lg mb-4 text-center">
                    <p class="text-blue-800 text-sm font-semibold">Anda Check In Pukul:</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $attendance->clock_in->format('H:i') }}</p>
                </div>

                <button type="submit" id="btn-submit" disabled
                    class="w-full py-4 rounded-xl text-white font-bold text-lg shadow-lg bg-gray-400 cursor-not-allowed transition-colors duration-200">
                    📍 Menunggu GPS...
                </button>
            </form>
            @else
            <div class="bg-green-50 border border-green-200 p-6 rounded-xl text-center">
                <div class="flex justify-center mb-3">
                    <div class="bg-green-100 p-3 rounded-full">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                </div>
                <h3 class="text-green-800 font-bold text-lg">Absensi Selesai!</h3>
                <p class="text-green-600 text-sm mt-1">Terima kasih atas kinerja Anda hari ini.</p>
            </div>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-1 flex flex-col relative">
        <div id="map" class="w-full rounded-lg z-0" style="height: 400px; min-height: 400px;"></div>

        <div class="absolute bottom-4 left-4 right-4 bg-white/90 backdrop-blur-md p-3 rounded-lg shadow-lg border border-slate-200 z-10 flex justify-between items-center">
            <div>
                <p class="text-xs text-slate-500 uppercase font-bold">Jarak ke Sekolah</p>
                <p class="text-lg font-bold text-slate-800" id="distance-display">-- Meter</p>
            </div>
            <div id="radius-indicator" class="px-3 py-1 rounded-full text-xs font-bold bg-gray-200 text-gray-500">
                Menunggu GPS
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
    // 1. JAM DIGITAL
    function updateClock() {
        const now = new Date();
        const clockElement = document.getElementById('digital-clock');
        if (clockElement) {
            clockElement.innerText = now.toLocaleTimeString('id-ID', {
                hour12: false
            });
        }
    }
    setInterval(updateClock, 1000);
    updateClock();

    // 2. LOGIC PETA & GPS
    document.addEventListener("DOMContentLoaded", function() {
        // PERBAIKAN: Gunakan format satu baris dan casting angka yang benar
        const schoolLat = Number("{{ $settings->school_latitude }}");
        const schoolLng = Number("{{ $settings->school_longitude }}");
        const radiusMeters = Number("{{ $settings->radius_meters }}");

        // Validasi data koordinat agar Leaflet tidak crash
        if (!schoolLat || !schoolLng) {
            console.error("Koordinat sekolah tidak ditemukan di database.");
            return;
        }

        // Inisialisasi Peta
        var map = L.map('map').setView([schoolLat, schoolLng], 16);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        }).addTo(map);

        // Marker Sekolah
        var schoolIcon = L.icon({
            iconUrl: 'https://cdn-icons-png.flaticon.com/512/167/167707.png',
            iconSize: [32, 32],
            iconAnchor: [16, 32]
        });
        L.marker([schoolLat, schoolLng], {
            icon: schoolIcon
        }).addTo(map).bindPopup("Lokasi Sekolah").openPopup();

        // Lingkaran Radius
        L.circle([schoolLat, schoolLng], {
            color: 'green',
            fillColor: '#4ade80',
            fillOpacity: 0.2,
            radius: radiusMeters
        }).addTo(map);

        var userMarker = L.marker([0, 0]).addTo(map);

        // UI Elements
        const btnSubmit = document.getElementById('btn-submit');
        const locStatus = document.getElementById('location-status');
        const statusText = document.getElementById('status-text');
        const inputLat = document.getElementById('lat_in');
        const inputLong = document.getElementById('long_in');
        const distanceDisplay = document.getElementById('distance-display');
        const radiusIndicator = document.getElementById('radius-indicator');

        function deg2rad(deg) {
            return deg * (Math.PI / 180);
        }

        function getDistanceFromLatLonInKm(lat1, lon1, lat2, lon2) {
            var R = 6371;
            var dLat = deg2rad(lat2 - lat1);
            var dLon = deg2rad(lon2 - lon1);
            var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) * Math.sin(dLon / 2) * Math.sin(dLon / 2);
            var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
            return (R * c) * 1000; // Meter
        }

        window.requestLocation = function() {
            if (!navigator.geolocation) {
                alert("Browser tidak mendukung GPS.");
                return;
            }

            locStatus.classList.remove('hidden');
            statusText.innerText = "Meminta izin lokasi...";

            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const userLat = position.coords.latitude;
                    const userLng = position.coords.longitude;

                    if (inputLat) inputLat.value = userLat;
                    if (inputLong) inputLong.value = userLng;

                    userMarker.setLatLng([userLat, userLng]);
                    map.setView([userLat, userLng], 17);

                    const distance = getDistanceFromLatLonInKm(userLat, userLng, schoolLat, schoolLng);
                    distanceDisplay.innerText = Math.round(distance) + " Meter";

                    if (distance <= radiusMeters) {
                        if (btnSubmit) {
                            btnSubmit.disabled = false;
                            btnSubmit.classList.remove('bg-gray-400', 'cursor-not-allowed');
                            btnSubmit.classList.add('bg-blue-600', 'hover:bg-blue-700');
                            btnSubmit.innerHTML = "📍 ABSEN SEKARANG";
                        }
                        radiusIndicator.className = "px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700";
                        radiusIndicator.innerText = "✅ Dalam Radius";
                        locStatus.classList.add('hidden');
                    } else {
                        if (btnSubmit) {
                            btnSubmit.disabled = true;
                            btnSubmit.innerHTML = "❌ Terlalu Jauh (" + Math.round(distance) + "m)";
                        }
                        radiusIndicator.className = "px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700";
                        radiusIndicator.innerText = "⛔ Luar Jangkauan";
                        locStatus.classList.remove('hidden');
                        statusText.innerText = "Anda berada di luar radius sekolah.";
                    }
                },
                function(error) {
                    let msg = "Gagal deteksi lokasi.";
                    if (error.code === 1) msg = "Izin lokasi ditolak browser.";
                    statusText.innerText = msg;
                }, {
                    enableHighAccuracy: true,
                    timeout: 10000
                }
            );
        };

        requestLocation();
    });
</script>
@endpush
@endsection