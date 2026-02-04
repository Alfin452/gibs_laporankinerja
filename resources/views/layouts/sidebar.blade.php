<aside
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    class="fixed inset-y-0 left-0 z-50 w-64 h-screen bg-slate-900 border-r border-slate-800 transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-auto flex flex-col"
>

    @php
    $setting = \App\Models\AppSetting::first();
    $logoImage = $setting && $setting->logo_path
    ? Storage::url($setting->logo_path)
    : asset('build/assets/img/logo.png');
    @endphp

    <div class="px-6 pt-8 pb-4 shrink-0">
        <div class="flex items-center justify-center mb-8">
            <h2 class="text-2xl font-bold tracking-tight text-white">GIBS <span class="text-slate-400 font-normal">Kinerja</span></h2>
        </div>

        <div class="flex items-center gap-4 px-2 mb-2 p-2 rounded-xl bg-slate-800/50 border border-slate-700/50">
            <img class="object-cover w-10 h-10 rounded-full border border-slate-600"
                src="{{ $logoImage }}"
                alt="Logo Sekolah">

            <div class="overflow-hidden">
                <h4 class="font-semibold text-gray-100 truncate text-sm">
                    {{ Auth::user()->name }}
                </h4>
                <p class="text-xs text-slate-400 truncate">
                    {{ Auth::user()->role === 'admin' ? 'Administrator' : 'Guru Staff' }}
                </p>
            </div>
        </div>
    </div>

    <div class="flex flex-col flex-1 px-4 overflow-y-auto mt-2">
        <nav class="space-y-1.5">

            @if(Auth::user()->role === 'admin')
            <p class="px-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2 mt-2">Admin Menu</p>

            <a class="flex items-center px-4 py-2.5 transition-all duration-200 group rounded-lg
               {{ request()->routeIs('admin.dashboard') 
                  ? 'text-white bg-slate-800 shadow-md shadow-slate-900/10' 
                  : 'text-slate-400 hover:text-white hover:bg-white/5' }}"
                href="{{ route('admin.dashboard') }}">
                <svg class="w-5 h-5 transition-colors {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-slate-500 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                </svg>
                <span class="mx-3 font-medium">Dashboard</span>
            </a>

            <a class="flex items-center px-4 py-2.5 transition-all duration-200 group rounded-lg
               {{ request()->routeIs('admin.reports.*') 
                  ? 'text-white bg-slate-800 shadow-md shadow-slate-900/10' 
                  : 'text-slate-400 hover:text-white hover:bg-white/5' }}"
                href="{{ route('admin.reports.index') }}">
                <svg class="w-5 h-5 transition-colors {{ request()->routeIs('admin.reports.*') ? 'text-white' : 'text-slate-500 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span class="mx-3 font-medium">Laporan Kinerja</span>
            </a>

            <a class="flex items-center px-4 py-2.5 transition-all duration-200 group rounded-lg
               {{ request()->routeIs('admin.substitute') 
                  ? 'text-white bg-slate-800 shadow-md shadow-slate-900/10' 
                  : 'text-slate-400 hover:text-white hover:bg-white/5' }}"
                href="#">
                <svg class="w-5 h-5 transition-colors {{ request()->routeIs('admin.substitute') ? 'text-white' : 'text-slate-500 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
                <span class="mx-3 font-medium">Target Substitute</span>
            </a>

            <a class="flex items-center px-4 py-2.5 transition-all duration-200 group rounded-lg
               {{ request()->routeIs('admin.activities.*') 
                  ? 'text-white bg-slate-800 shadow-md shadow-slate-900/10' 
                  : 'text-slate-400 hover:text-white hover:bg-white/5' }}"
                href="{{ route('admin.activities.index') }}">
                <svg class="w-5 h-5 transition-colors {{ request()->routeIs('admin.activities.*') ? 'text-white' : 'text-slate-500 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                </svg>
                <span class="mx-3 font-medium">Master Kegiatan</span>
            </a>

            <a class="flex items-center px-4 py-2.5 transition-all duration-200 group rounded-lg
               {{ request()->routeIs('admin.users.*') 
                  ? 'text-white bg-slate-800 shadow-md shadow-slate-900/10' 
                  : 'text-slate-400 hover:text-white hover:bg-white/5' }}"
                href="{{ route('admin.users.index') }}">
                <svg class="w-5 h-5 transition-colors {{ request()->routeIs('admin.users.*') ? 'text-white' : 'text-slate-500 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                <span class="mx-3 font-medium">Manajemen Guru</span>
            </a>

            <a class="flex items-center px-4 py-2.5 transition-all duration-200 group rounded-lg
               {{ request()->routeIs('admin.settings') 
                  ? 'text-white bg-slate-800 shadow-md shadow-slate-900/10' 
                  : 'text-slate-400 hover:text-white hover:bg-white/5' }}"
                href="{{ route('admin.settings') }}">
                <svg class="w-5 h-5 transition-colors {{ request()->routeIs('admin.settings') ? 'text-white' : 'text-slate-500 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <span class="mx-3 font-medium">Setting Lokasi</span>
            </a>
            @endif

            @if(Auth::user()->role === 'guru')
            <p class="px-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2 mt-2">Guru Menu</p>

            <a class="flex items-center px-4 py-2.5 transition-all duration-200 group rounded-lg
               {{ request()->routeIs('guru.dashboard') 
                  ? 'text-white bg-slate-800 shadow-md shadow-slate-900/10' 
                  : 'text-slate-400 hover:text-white hover:bg-white/5' }}"
                href="{{ route('guru.dashboard') }}">
                <svg class="w-5 h-5 transition-colors {{ request()->routeIs('guru.dashboard') ? 'text-white' : 'text-slate-500 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="mx-3 font-medium">Absensi (K1)</span>
            </a>

            <a class="flex items-center px-4 py-2.5 transition-all duration-200 group rounded-lg
               {{ request()->routeIs('guru.activities.*') 
                  ? 'text-white bg-slate-800 shadow-md shadow-slate-900/10' 
                  : 'text-slate-400 hover:text-white hover:bg-white/5' }}"
                href="{{ route('guru.activities.index') }}">
                <svg class="w-5 h-5 transition-colors {{ request()->routeIs('guru.activities.*') ? 'text-white' : 'text-slate-500 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                <span class="mx-3 font-medium">Input Kegiatan</span>
            </a>

            <a class="flex items-center px-4 py-2.5 transition-all duration-200 group rounded-lg
               {{ request()->routeIs('guru.history') 
                  ? 'text-white bg-slate-800 shadow-md shadow-slate-900/10' 
                  : 'text-slate-400 hover:text-white hover:bg-white/5' }}"
                href="{{ route('guru.history') }}">
                <svg class="w-5 h-5 transition-colors {{ request()->routeIs('guru.history') ? 'text-white' : 'text-slate-500 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
                <span class="mx-3 font-medium">Riwayat Saya</span>
            </a>
            @endif

        </nav>
    </div>

    <div class="px-4 py-6 mt-auto shrink-0 border-t border-slate-800">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex w-full items-center px-4 py-2.5 text-slate-400 transition-colors duration-300 transform rounded-lg hover:text-white hover:bg-red-900/30 group">
                <svg class="w-5 h-5 text-slate-500 group-hover:text-red-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                </svg>
                <span class="mx-3 font-medium">Sign Out</span>
            </button>
        </form>
    </div>
</aside>