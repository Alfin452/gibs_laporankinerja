<aside class="flex flex-col w-64 h-screen bg-white border-r rtl:border-r-0 rtl:border-l dark:bg-gray-900 dark:border-gray-700">

    @php
    $setting = \App\Models\AppSetting::first();

    $logoImage = $setting && $setting->logo_path
    ? Storage::url($setting->logo_path)
    : asset('build/assets/img/logo.png'); // <--- INI PATH YANG KAMU MINTA
        @endphp

        <div class="px-4 pt-8 pb-2 shrink-0">

        <div class="flex items-center justify-center mb-6">
            <h2 class="text-2xl font-bold text-blue-600">GIBS <span class="text-gray-600 text-lg">Kinerja</span></h2>
        </div>

        <div class="flex flex-col items-center -mx-2 mb-6">
            <img class="object-cover w-24 h-24 mx-2 rounded-full border-4 border-white shadow-lg ring-2 ring-gray-100"
                src="{{ $logoImage }}"
                alt="Logo Sekolah">

            <h4 class="mx-2 mt-3 font-bold text-gray-800 dark:text-gray-200 text-center text-lg">
                {{ Auth::user()->name }}
            </h4>
            <p class="mx-2 text-sm font-medium text-gray-500 dark:text-gray-400">
                {{ Auth::user()->role === 'admin' ? 'Administrator' : 'Guru Staff' }}
            </p>
        </div>
        </div>

        <div class="flex flex-col flex-1 px-4 overflow-y-auto">
            <nav class="space-y-1">
                @if(Auth::user()->role === 'admin')
                <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 mt-2">Admin Menu</p>

                <a class="flex items-center px-4 py-2 text-gray-700 rounded-md dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-100 dark:bg-gray-800' : '' }}" href="{{ route('admin.dashboard') }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                    </svg>
                    <span class="mx-4 font-medium">Dashboard</span>
                </a>

                <a class="flex items-center px-4 py-2 mt-3 text-gray-600 transition-colors duration-300 transform rounded-md hover:bg-gray-100 dark:hover:bg-gray-800 dark:hover:text-gray-200 {{ request()->routeIs('admin.reports.*') ? 'bg-gray-100 dark:bg-gray-800' : '' }}" href="{{ route('admin.reports.index') }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span class="mx-4 font-medium">Laporan Kinerja</span>
                </a>

                <a class="flex items-center px-4 py-2 mt-3 text-gray-600 transition-colors duration-300 transform rounded-md hover:bg-gray-100 dark:hover:bg-gray-800 dark:hover:text-gray-200 {{ request()->routeIs('admin.users.*') ? 'bg-gray-100 dark:bg-gray-800' : '' }}" href="{{ route('admin.users.index') }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <span class="mx-4 font-medium">Manajemen Guru</span>
                </a>

                <a class="flex items-center px-4 py-2 mt-3 text-gray-600 transition-colors duration-300 transform rounded-md hover:bg-gray-100 dark:hover:bg-gray-800 dark:hover:text-gray-200 {{ request()->routeIs('admin.activities.*') ? 'bg-gray-100 dark:bg-gray-800' : '' }}" href="{{ route('admin.activities.index') }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                    <span class="mx-4 font-medium">Master Kegiatan</span>
                </a>

                <a class="flex items-center px-4 py-2 mt-3 text-gray-600 transition-colors duration-300 transform rounded-md hover:bg-gray-100 dark:hover:bg-gray-800 dark:hover:text-gray-200" href="#">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    <span class="mx-4 font-medium">Target Substitute</span>
                </a>

                <a class="flex items-center px-4 py-2 mt-3 text-gray-600 transition-colors duration-300 transform rounded-md hover:bg-gray-100 dark:hover:bg-gray-800 dark:hover:text-gray-200 {{ request()->routeIs('admin.settings') ? 'bg-gray-100 dark:bg-gray-800' : '' }}" href="{{ route('admin.settings') }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span class="mx-4 font-medium">Setting Lokasi</span>
                </a>
                @endif

                @if(Auth::user()->role === 'guru')
                <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 mt-2">Guru Menu</p>

                <a class="flex items-center px-4 py-2 text-gray-700 rounded-md dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 {{ request()->routeIs('guru.dashboard') ? 'bg-gray-100 dark:bg-gray-800' : '' }}" href="{{ route('guru.dashboard') }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="mx-4 font-medium">Absensi (K1)</span>
                </a>

                <a class="flex items-center px-4 py-2 mt-3 text-gray-600 transition-colors duration-300 transform rounded-md hover:bg-gray-100 dark:hover:bg-gray-800 dark:hover:text-gray-200 {{ request()->routeIs('guru.activities.*') ? 'bg-gray-100 dark:bg-gray-800' : '' }}" href="{{ route('guru.activities.index') }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    <span class="mx-4 font-medium">Input Kegiatan (K2-K20)</span>
                </a>

                <a class="flex items-center px-4 py-2 mt-3 text-gray-600 transition-colors duration-300 transform rounded-md hover:bg-gray-100 dark:hover:bg-gray-800 dark:hover:text-gray-200 {{ request()->routeIs('guru.history') ? 'bg-gray-100 dark:bg-gray-800' : '' }}" href="{{ route('guru.history') }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                    <span class="mx-4 font-medium">Riwayat Saya</span>
                </a>
                @endif
            </nav>
        </div>

        <div class="px-4 py-6 mt-auto shrink-0">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex w-full items-center px-4 py-2 text-gray-600 transition-colors duration-300 transform rounded-md hover:bg-red-100 hover:text-red-600 dark:hover:bg-gray-800 dark:hover:text-gray-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    <span class="mx-4 font-medium">Sign Out</span>
                </button>
            </form>
        </div>
</aside>