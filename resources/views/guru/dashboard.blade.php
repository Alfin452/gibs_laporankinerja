@extends('layouts.app')

@section('header', 'Beranda')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    {{-- 1. HEADER: Welcome & Jam --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-2 bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-white/10 rounded-full blur-xl"></div>
            <div class="relative z-10 flex items-start justify-between">
                <div>
                    <p class="text-slate-400 text-sm font-medium mb-1">Selamat Datang,</p>
                    <h2 class="text-3xl font-bold tracking-tight">{{ Auth::user()->name }}</h2>
                    <p class="mt-2 text-slate-300 text-sm">
                        @if($attendance)
                            Status Hari Ini: 
                            @if($attendance->status == 'ontime')
                                <span class="bg-emerald-500/20 text-emerald-300 px-2 py-1 rounded font-bold">TEPAT WAKTU</span>
                            @else
                                <span class="bg-rose-500/20 text-rose-300 px-2 py-1 rounded font-bold">TERLAMBAT</span>
                            @endif
                        @else
                            Belum melakukan presensi hari ini.
                        @endif
                    </p>
                </div>
                <div class="hidden sm:block p-3 bg-white/10 rounded-xl backdrop-blur-sm">
                    <svg class="w-8 h-8 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex flex-col justify-center items-center text-center">
            <div id="clock" class="text-4xl font-bold text-slate-800 font-mono tracking-wider">00:00:00</div>
            <div class="text-slate-500 text-sm font-medium mt-1">
                {{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}
            </div>
            <div id="radius-status" class="mt-4 px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-500 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-gray-400 animate-pulse"></span>
                Mendeteksi Lokasi...
            </div>
        </div>
    </div>

    {{-- 2. CONTENT: Map & Buttons --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- MAP --}}
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden flex flex-col h-[400px]">
            <div class="p-4 border-b border-slate-50 flex justify-between items-center bg-slate-50/50">
                <h3 class="font-semibold text-slate-700 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Lokasi Presensi
                </h3>
                <span class="text-xs text-slate-500 bg-white px-2 py-1 rounded border border-slate-200">
                    Jarak: <span id="distance-val" class="font-mono font-bold text-slate-800">0</span> m
                </span>
            </div>
            <div id="map" class="w-full h-full z-0 relative"></div>
        </div>

        {{-- BUTTONS ACTION --}}
        <div class="space-y-6">
            
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <h3 class="font-semibold text-slate-800 mb-4">Aksi Presensi</h3>
                
                <div class="space-y-4">
                    
                    {{-- 1. TOMBOL MASUK --}}
                    @if(empty($attendance))
                        {{-- BELUM MASUK --}}
                        <form action="{{ route('guru.attendance.in') }}" method="POST" id="form-checkin">
                            @csrf
                            <input type="hidden" name="latitude" id="lat-in">
                            <input type="hidden" name="longitude" id="long-in">
                            
                            <button type="button" id="btn-checkin" disabled
                                class="w-full flex items-center justify-center px-6 py-4 rounded-xl bg-slate-100 text-slate-400 font-bold transition-all duration-300 disabled:cursor-not-allowed disabled:opacity-70 shadow-sm border border-transparent">
                                {{-- PERBAIKAN: Tambah SPAN id text-checkin --}}
                                <span id="text-checkin">ABSEN MASUK</span>
                            </button>
                        </form>
                    @else
                        {{-- SUDAH MASUK (CARD STATUS) --}}
                        <div class="w-full flex items-center gap-4 px-5 py-4 rounded-xl bg-emerald-50 border border-emerald-100 relative overflow-hidden group">
                            <div class="absolute right-0 top-0 -mt-2 -mr-2 w-16 h-16 bg-emerald-100 rounded-full blur-xl opacity-50"></div>
                            <div class="flex-shrink-0 w-10 h-10 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-emerald-700 text-sm uppercase tracking-wide">Sudah Check-In</h4>
                                <p class="text-emerald-600 text-xs mt-0.5 font-mono">
                                    Pukul {{ \Carbon\Carbon::parse($attendance->time_in)->format('H:i') }} WITA
                                </p>
                            </div>
                        </div>
                    @endif

                    {{-- 2. TOMBOL PULANG --}}
                    @if(empty($attendance))
                        {{-- Belum Masuk -> Tombol Pulang Mati Total --}}
                        <button disabled class="w-full flex items-center justify-center px-6 py-4 rounded-xl bg-slate-50 text-slate-300 font-bold border border-slate-100 cursor-not-allowed">
                            ABSEN PULANG
                        </button>

                    @elseif(empty($attendance->time_out))
                        {{-- Sudah Masuk -> Tombol Pulang Ada (Tapi Dicek Jam) --}}
                        <form action="{{ route('guru.attendance.out') }}" method="POST" id="form-checkout">
                            @csrf
                            <input type="hidden" name="latitude" id="lat-out">
                            <input type="hidden" name="longitude" id="long-out">

                            <button type="button" id="btn-checkout" disabled
                                class="w-full flex items-center justify-center px-6 py-4 rounded-xl bg-slate-100 text-slate-400 font-bold transition-all duration-300 disabled:cursor-not-allowed disabled:opacity-70 shadow-sm border border-transparent">
                                {{-- PERBAIKAN: Tambah SPAN id text-checkout --}}
                                <span id="text-checkout">ABSEN PULANG</span>
                            </button>
                        </form>

                    @else
                        {{-- SUDAH PULANG (CARD STATUS) --}}
                        <div class="w-full flex items-center gap-4 px-5 py-4 rounded-xl bg-indigo-50 border border-indigo-100 relative overflow-hidden group">
                            <div class="absolute right-0 top-0 -mt-2 -mr-2 w-16 h-16 bg-indigo-100 rounded-full blur-xl opacity-50"></div>
                            <div class="flex-shrink-0 w-10 h-10 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-indigo-700 text-sm uppercase tracking-wide">Sudah Check-Out</h4>
                                <p class="text-indigo-600 text-xs mt-0.5 font-mono">
                                    Pukul {{ \Carbon\Carbon::parse($attendance->time_out)->format('H:i') }} WITA
                                </p>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="mt-4 pt-4 border-t border-slate-50">
                    <p class="text-xs text-center text-slate-400">
                        Radius Sekolah: <span class="font-bold text-slate-600">{{ $setting->radius_meter ?? 160 }} meter</span>.
                    </p>
                </div>
            </div>

            {{-- INFO BOX --}}
            <div class="bg-blue-50 rounded-2xl border border-blue-100 p-5">
                <div class="flex items-start gap-3">
                    <svg class="w-6 h-6 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <h4 class="font-bold text-blue-900 text-sm">Info Jam Kerja</h4>
                        <ul class="mt-2 space-y-1 text-xs text-blue-700">
                            <li>• Masuk: 07:00 - 08:00 WITA</li>
                            <li>• Pulang: 16:00 - 17:00 WITA</li>
                            <li>• Tombol Pulang aktif otomatis pukul 16:00.</li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Status PHP ke JS
        const hasCheckIn = {{ $attendance ? 'true' : 'false' }};
        const hasCheckOut = {{ ($attendance && $attendance->clock_out) ? 'true' : 'false' }};
        
        let currentHour = new Date().getHours(); 

        function updateClock() {
            const now = new Date();
            currentHour = now.getHours(); 
            const timeString = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
            document.getElementById('clock').innerText = timeString;
        }
        setInterval(updateClock, 1000);
        updateClock();

        // Konfigurasi Peta
        const officeLat = {{ $setting->latitude ?? -3.229683 }};
        const officeLng = {{ $setting->longitude ?? 114.598840 }};
        const maxRadius = {{ $setting->radius_meter ?? 160 }};
        
        const map = L.map('map').setView([officeLat, officeLng], 18);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '© OpenStreetMap' }).addTo(map);
        L.circle([officeLat, officeLng], { color: '#6366f1', fillColor: '#818cf8', fillOpacity: 0.2, radius: maxRadius }).addTo(map);
        L.marker([officeLat, officeLng]).addTo(map).bindPopup("<b>GIBS School</b><br>Pusat Absensi").openPopup();
        
        let userMarker = null;

        // Element Referensi
        const btnCheckIn = document.getElementById('btn-checkin');
        const btnCheckOut = document.getElementById('btn-checkout');
        const textCheckIn = document.getElementById('text-checkin');
        const textCheckOut = document.getElementById('text-checkout');
        const statusBadge = document.getElementById('radius-status');
        const distanceDisplay = document.getElementById('distance-val');
        
        const latIn = document.getElementById('lat-in');
        const longIn = document.getElementById('long-in');
        const latOut = document.getElementById('lat-out');
        const longOut = document.getElementById('long-out');

        function updateStatus(isInside, dist) {
            if (distanceDisplay) distanceDisplay.innerText = Math.round(dist);
            
            if(isInside) {
                if(statusBadge) {
                    statusBadge.className = "mt-4 px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-600 flex items-center gap-2 border border-emerald-200";
                    statusBadge.innerHTML = `<span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Di Dalam Area`;
                }
                
                // --- LOGIKA TOMBOL ---
                
                // 1. LOGIKA TOMBOL MASUK
                if (!hasCheckIn) {
                    // Jika belum masuk -> Aktifkan Tombol
                    if(btnCheckIn) {
                        enableButton(btnCheckIn, 'bg-emerald-500', 'text-white', 'hover:bg-emerald-600');
                        if(textCheckIn) textCheckIn.innerText = "ABSEN MASUK";
                    }
                }

                // 2. LOGIKA TOMBOL PULANG (Validasi Jam 16:00)
                if (hasCheckIn && !hasCheckOut) {
                    if (currentHour >= 16) { 
                        // JAM >= 16:00 -> Boleh Pulang
                        if(btnCheckOut) {
                            enableButton(btnCheckOut, 'bg-rose-500', 'text-white', 'hover:bg-rose-600');
                            if(textCheckOut) textCheckOut.innerText = "ABSEN PULANG";
                        }
                    } else {
                        // JAM < 16:00 -> Tahan Tombol
                        if(btnCheckOut) {
                            disableButton(btnCheckOut);
                            // Beri visual warning (Kuning)
                            btnCheckOut.classList.add('bg-amber-100', 'text-amber-700');
                            btnCheckOut.classList.remove('text-slate-400');
                            if(textCheckOut) textCheckOut.innerText = "BELUM JAM PULANG (16:00)";
                        }
                    }
                }

            } else {
                // Di Luar Radius
                if(statusBadge) {
                    statusBadge.className = "mt-4 px-3 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-600 flex items-center gap-2 border border-rose-200";
                    statusBadge.innerHTML = `<span class="w-2 h-2 rounded-full bg-rose-500"></span> Di Luar Area`;
                }
                
                if(btnCheckIn) disableButton(btnCheckIn);
                if(btnCheckOut) disableButton(btnCheckOut);
            }
        }

        function enableButton(btn, ...classes) {
            btn.disabled = false;
            // Reset base classes yang mungkin konflik
            btn.className = "w-full flex items-center justify-center px-6 py-4 rounded-xl font-bold transition-all duration-300";
            btn.classList.add(...classes, 'shadow-lg');
            btn.type = 'submit'; 
        }

        function disableButton(btn) {
            btn.disabled = true;
            btn.className = "w-full flex items-center justify-center px-6 py-4 rounded-xl bg-slate-100 text-slate-400 font-bold transition-all duration-300 disabled:cursor-not-allowed disabled:opacity-70 shadow-sm border border-transparent";
            btn.type = 'button';
        }

        if (navigator.geolocation) {
            navigator.geolocation.watchPosition(
                (position) => {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    
                    if(latIn) latIn.value = lat;
                    if(longIn) longIn.value = lng;
                    if(latOut) latOut.value = lat;
                    if(longOut) longOut.value = lng;

                    if (userMarker) {
                        userMarker.setLatLng([lat, lng]);
                    } else {
                        userMarker = L.marker([lat, lng], {
                            icon: L.divIcon({
                                className: 'bg-transparent',
                                html: '<div class="w-4 h-4 bg-blue-500 rounded-full border-2 border-white shadow-lg animate-pulse"></div>'
                            })
                        }).addTo(map);
                    }

                    const dist = map.distance([lat, lng], [officeLat, officeLng]);
                    const isInside = dist <= maxRadius;

                    updateStatus(isInside, dist);
                },
                (error) => {
                    console.error("Error Geolocation: ", error);
                    if(statusBadge) statusBadge.innerText = "Gagal mendeteksi lokasi";
                },
                { enableHighAccuracy: true }
            );
        }
    });
</script>
@endpush
@endsection