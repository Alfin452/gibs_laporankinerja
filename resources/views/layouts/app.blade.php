<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'GIBS Performance') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <style>
        [x-cloak] {
            display: none !important;
        }

        .swal2-popup.swal2-toast {
            font-size: 0.875rem !important;
        }
    </style>
</head>

<body class="font-sans antialiased bg-gray-50 text-slate-600"
    x-data="{ sidebarOpen: window.innerWidth >= 1024 }">

    <div class="flex h-screen overflow-hidden">

        <div x-show="sidebarOpen"
            @click="sidebarOpen = false"
            x-transition:enter="transition-opacity ease-linear duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-linear duration-300"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-40 bg-gray-900/50 backdrop-blur-sm lg:hidden"
            x-cloak>
        </div>

        @include('layouts.sidebar')

        <div :class="sidebarOpen ? 'lg:ml-64' : 'lg:ml-0'"
            class="relative flex flex-col flex-1 overflow-y-auto overflow-x-hidden transition-all duration-300 ease-in-out">

            <header class="sticky top-0 z-30 flex items-center justify-between px-6 py-3 bg-white border-b border-gray-200 shadow-sm">
                <div class="flex items-center gap-4">

                    <button
                        x-show="!sidebarOpen"
                        x-cloak
                        @click="sidebarOpen = true"
                        class="text-gray-500 hover:text-gray-700 focus:outline-none transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>

                    <h1 class="text-xl font-bold text-slate-800 tracking-tight">
                        @yield('header', 'Dashboard')
                    </h1>
                </div>

                <div class="flex items-center gap-3" x-data="{ open: false }">
                    <span class="text-sm text-right hidden md:block">
                        <div class="font-medium text-slate-700">{{ Auth::user()->name }}</div>
                        <div class="text-xs text-slate-500">{{ Auth::user()->role === 'admin' ? 'Administrator' : 'Guru Staff' }}</div>
                    </span>

                    <div class="relative">
                        <button @click="open = !open" class="flex items-center focus:outline-none">
                            <img class="w-10 h-10 rounded-full border border-gray-200 object-cover shadow-sm"
                                src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=0D9488&color=fff"
                                alt="Avatar">
                        </button>

                        <div x-show="open" @click.away="open = false" x-cloak
                            class="absolute right-0 w-48 mt-2 origin-top-right bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 py-1 z-50">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full px-4 py-2 text-sm text-left text-gray-700 hover:bg-gray-100">
                                    Sign out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <main class="w-full grow p-6">
                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        @if(session('success'))
        Toast.fire({
            icon: 'success',
            title: "{{ session('success') }}"
        });
        @endif

        @if(session('error'))
        Toast.fire({
            icon: 'error',
            title: "{{ session('error') }}"
        });
        @endif
    </script>

    @stack('scripts')
</body>

</html>