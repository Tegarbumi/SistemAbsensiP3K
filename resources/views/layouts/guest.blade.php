<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Absensi P3K Disarpus') }}</title>

        <!-- Theme Initialization: Light default, Dark only if explicitly set -->
        <script>
            if (localStorage.getItem('theme') === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        </script>

        <!-- Fonts: IBM Plex Sans (Formal Government Typography) -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=ibm-plex-sans:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body x-data="{ 
        isDark: document.documentElement.classList.contains('dark'),
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
    }" class="font-sans text-slate-800 dark:text-slate-100 antialiased bg-slate-50 dark:bg-slate-900 min-h-screen flex flex-col justify-between items-center py-6 px-4 transition-colors duration-200">
        
        <!-- Top Bar: Kembali ke Beranda & Theme Switcher -->
        <div class="w-full max-w-md flex justify-between items-center mb-6">
            <!-- Back to Beranda Button with Icon -->
            <a href="{{ url('/') }}" 
               class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-slate-700 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-white dark:hover:bg-slate-800 transition border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-semibold shadow-2xs"
               title="Kembali ke Beranda">
                <svg class="w-3.5 h-3.5 text-slate-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span>Kembali ke Beranda</span>
            </a>

            <!-- Theme Switcher -->
            <button 
                type="button" 
                @click="toggleTheme()" 
                class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-100 hover:bg-white dark:hover:bg-slate-800 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 transition focus:outline-none shadow-2xs shrink-0"
                :title="isDark ? 'Beralih ke Tampilan Terang' : 'Beralih ke Tampilan Gelap'"
                aria-label="Toggle Theme">
                <svg x-show="isDark" class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <svg x-show="!isDark" class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                </svg>
            </button>
        </div>

        <!-- Main Slot (Login Card) -->
        <div class="w-full flex-1 flex items-center justify-center">
            {{ $slot }}
        </div>

        <!-- Footer -->
        <footer class="mt-8 text-center text-xs text-slate-400 dark:text-slate-500">
            &copy; {{ date('Y') }} Dinas Kearsipan dan Perpustakaan Kabupaten Subang
        </footer>
    </body>
</html>