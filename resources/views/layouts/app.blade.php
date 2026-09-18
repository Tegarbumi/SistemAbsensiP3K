<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Absensi P3K Disarpus') }}</title>

        <!-- Theme Initialization to prevent Flash of unstyled theme -->
        <script>
            if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        </script>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @stack('styles')
    </head>
    <body class="font-sans antialiased text-slate-800 dark:text-slate-100 bg-slate-100/70 dark:bg-slate-950 transition-colors duration-200">
        <div x-data="{ 
            desktopOpen: localStorage.getItem('sidebar_desktop') !== 'false', 
            mobileOpen: false,
            isDark: document.documentElement.classList.contains('dark'),
            toggle() {
                if (window.innerWidth >= 1024) {
                    this.desktopOpen = !this.desktopOpen;
                    localStorage.setItem('sidebar_desktop', this.desktopOpen);
                } else {
                    this.mobileOpen = !this.mobileOpen;
                }
            },
            toggleTheme() {
                this.isDark = !this.isDark;
                if (this.isDark) {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                } else {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                }
            }
        }" class="min-h-screen flex flex-col">
            
            <!-- Mobile Off-Canvas Sidebar Backdrop -->
            <div 
                x-show="mobileOpen" 
                x-transition:enter="transition-opacity ease-linear duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity ease-linear duration-300"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-40 lg:hidden"
                @click="mobileOpen = false"
                style="display: none;"
                aria-hidden="true">
            </div>

            <!-- Mobile Off-Canvas Sidebar Drawer -->
            <div 
                x-show="mobileOpen"
                x-transition:enter="transition ease-in-out duration-300 transform"
                x-transition:enter-start="-translate-x-full"
                x-transition:enter-end="translate-x-0"
                x-transition:leave="transition ease-in-out duration-300 transform"
                x-transition:leave-start="translate-x-0"
                x-transition:leave-end="-translate-x-full"
                class="fixed inset-y-0 left-0 z-50 w-72 max-w-[85vw] bg-white dark:bg-slate-900 lg:hidden shadow-2xl flex flex-col"
                style="display: none;">
                @include('layouts.sidebar')
            </div>

            <!-- Desktop Sidebar (Fixed Left, Collapsible with Hamburger) -->
            <div 
                class="hidden lg:flex lg:w-64 xl:w-72 lg:flex-col lg:fixed lg:inset-y-0 z-30 transition-transform duration-300 ease-in-out shadow-sm"
                :class="desktopOpen ? 'translate-x-0' : '-translate-x-full'">
                @include('layouts.sidebar')
            </div>

            <!-- Main Content Area -->
            <div 
                class="flex-1 flex flex-col min-w-0 min-h-screen transition-[padding] duration-300 ease-in-out"
                :class="desktopOpen ? 'lg:pl-64 xl:pl-72' : 'lg:pl-0'">
                
                <!-- Top Navigation Header -->
                <header class="sticky top-0 z-20 bg-white/95 dark:bg-slate-900/95 backdrop-blur-md border-b border-slate-200/80 dark:border-slate-800 px-4 sm:px-6 lg:px-8 py-3.5 flex items-center justify-between shadow-2xs transition-colors duration-200">
                    <div class="flex items-center gap-3">
                        <!-- Hamburger Menu Button (Shown when sidebar is closed on desktop, or always on mobile) -->
                        <button 
                            type="button" 
                            @click="toggle()" 
                            class="p-2 rounded-xl text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 active:bg-slate-200 dark:active:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 transition-all duration-150"
                            :class="desktopOpen ? 'lg:hidden' : 'block'"
                            title="Buka Sidebar"
                            aria-label="Toggle Sidebar">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>

                        <!-- Context Breadcrumb -->
                        <div class="flex items-center gap-2 text-xs sm:text-sm font-medium text-slate-500 dark:text-slate-400">
                            <span class="hidden sm:inline-block font-semibold text-slate-800 dark:text-slate-200">Absensi P3K Disarpus</span>
                            <span class="hidden sm:inline-block text-slate-300 dark:text-slate-600">/</span>
                            <span class="text-slate-700 dark:text-slate-300 font-semibold truncate max-w-[200px] sm:max-w-none">
                                @if(request()->routeIs('dashboard'))
                                    Dashboard
                                @elseif(request()->routeIs('admin.employees.*'))
                                    Kelola PPPK
                                @elseif(request()->routeIs('admin.units.*'))
                                    Unit Kerja
                                @elseif(request()->routeIs('admin.office-locations.*'))
                                    Lokasi Kantor
                                @elseif(request()->routeIs('admin.work-schedules.*'))
                                    Jadwal Kerja
                                @elseif(request()->routeIs('admin.attendance-monitoring.*'))
                                    Monitoring Absensi
                                @elseif(request()->routeIs('admin.gps-monitoring.*'))
                                    Monitoring GPS
                                @elseif(request()->routeIs('admin.recap.*'))
                                    Rekap Absensi
                                @elseif(request()->routeIs('admin.reports.*'))
                                    Laporan Presensi
                                @elseif(request()->routeIs('admin.leave-requests.*'))
                                    Persetujuan Izin
                                @elseif(request()->routeIs('attendance.*'))
                                    Presensi
                                @elseif(request()->routeIs('leave-requests.*'))
                                    Pengajuan Izin
                                @elseif(request()->routeIs('profile.*'))
                                    Profil Pengguna
                                @else
                                    Panel
                                @endif
                            </span>
                        </div>
                    </div>

                    <!-- Right Header Info & User Dropdown -->
                    <div class="flex items-center gap-2 sm:gap-3">
                        <!-- Theme Toggle Button (Light / Night Mode) -->
                        <button 
                            type="button" 
                            @click="toggleTheme()" 
                            class="p-2 rounded-xl text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-100 hover:bg-slate-100 dark:hover:bg-slate-800 active:bg-slate-200 dark:active:bg-slate-700 transition-colors focus:outline-none"
                            :title="isDark ? 'Beralih ke Light Mode' : 'Beralih ke Night Mode'"
                            aria-label="Toggle Night/Light Mode">
                            <!-- Sun icon (shown when dark mode is on) -->
                            <svg x-show="isDark" class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                            <!-- Moon icon (shown when light mode is on) -->
                            <svg x-show="!isDark" class="w-5 h-5 text-slate-600 dark:text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                            </svg>
                        </button>

                        <!-- Date Pill (Hidden on mobile) -->
                        <div class="hidden md:inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-slate-50 dark:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs font-medium border border-slate-200 dark:border-slate-700">
                            <svg class="w-3.5 h-3.5 text-indigo-500 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span>{{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y') }}</span>
                        </div>

                        <!-- User Profile Dropdown -->
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center gap-2 px-3 py-2 border border-slate-200 dark:border-slate-700 text-sm font-medium rounded-lg text-slate-700 dark:text-slate-200 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors focus:outline-none shadow-2xs">
                                    <span>{{ Auth::user()->name }}</span>
                                    <svg class="w-4 h-4 text-slate-400 dark:text-slate-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <x-dropdown-link :href="route('profile.edit')">
                                    {{ __('Profile') }}
                                </x-dropdown-link>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')"
                                            onclick="event.preventDefault(); this.closest('form').submit();">
                                        {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                </header>

                <!-- Page Heading (if defined in blade view) -->
                @isset($header)
                    <div class="bg-white dark:bg-slate-900 border-b border-slate-200/80 dark:border-slate-800 shadow-2xs transition-colors duration-200">
                        <div class="max-w-7xl mx-auto py-5 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </div>
                @endisset

                <!-- Page Content -->
                <main class="flex-1">
                    {{ $slot }}
                </main>
            </div>
        </div>

        @stack('scripts')
    </body>
</html>
