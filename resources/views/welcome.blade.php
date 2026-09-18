<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Absensi P3K Disarpus - Dinas Kearsipan dan Perpustakaan Kabupaten Subang</title>

    <!-- Theme Initialization to prevent flash of wrong theme -->
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

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
}" class="bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-100 antialiased min-h-screen flex flex-col justify-between transition-colors duration-200 selection:bg-indigo-500 selection:text-white relative overflow-x-hidden">

    <!-- Decorative Top Ambient Glow -->
    <div class="absolute -top-40 left-1/2 -translate-x-1/2 w-full max-w-6xl h-96 bg-gradient-to-tr from-indigo-500/15 via-purple-500/10 to-transparent blur-3xl pointer-events-none -z-10"></div>

    <!-- Header Navigasi -->
    <header class="bg-white/85 dark:bg-slate-900/85 backdrop-blur-md border-b border-slate-200/80 dark:border-slate-800 shadow-2xs sticky top-0 z-30 transition-colors duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-18 flex items-center justify-between">
            <div class="flex items-center space-x-3.5">
                <div class="w-11 h-11 rounded-xl bg-indigo-50 dark:bg-slate-800 p-1.5 flex items-center justify-center ring-1 ring-indigo-100 dark:ring-slate-700 shadow-xs flex-shrink-0">
                    <img src="{{ asset('image/logo.jpg') }}" alt="Logo Subang" class="w-full h-full object-contain rounded-lg">
                </div>
                <div>
                    <h1 class="text-base font-extrabold text-slate-900 dark:text-white tracking-tight leading-tight">
                        Absensi P3K Disarpus
                    </h1>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">
                        Dinas Kearsipan dan Perpustakaan Kab. Subang
                    </p>
                </div>
            </div>

            <nav class="flex items-center space-x-3 sm:space-x-4">
                <!-- Theme Toggle Button -->
                <button 
                    type="button" 
                    @click="toggleTheme()" 
                    class="p-2.5 rounded-xl text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-100 hover:bg-slate-100 dark:hover:bg-slate-800 active:bg-slate-200 dark:active:bg-slate-700 transition-colors focus:outline-none"
                    :title="isDark ? 'Beralih ke Light Mode' : 'Beralih ke Night Mode'"
                    aria-label="Toggle Night/Light Mode">
                    <!-- Sun icon -->
                    <svg x-show="isDark" class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <!-- Moon icon -->
                    <svg x-show="!isDark" class="w-5 h-5 text-slate-600 dark:text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                </button>

                @if (Route::has('login'))
                    @auth
                        @if(Auth::user()->role === 'pppk')
                            <a href="{{ route('attendance.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-md shadow-indigo-600/20 hover:shadow-lg transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span>Buka Presensi</span>
                            </a>
                        @else
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-md shadow-indigo-600/20 hover:shadow-lg transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                                <span>Dashboard Admin</span>
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-md shadow-indigo-600/20 hover:shadow-lg transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                            </svg>
                            <span>Masuk / Log In</span>
                        </a>
                    @endauth
                @endif
            </nav>
        </div>
    </header>

    <!-- Konten Utama Hero Section -->
    <main class="flex-grow flex items-center justify-center py-16 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl w-full text-center space-y-10">
            
            <!-- Hero Header -->
            <div class="space-y-5">
                <!-- Status Badge -->
                <div class="inline-flex items-center gap-2.5 px-4 py-1.5 rounded-full text-xs font-semibold bg-white dark:bg-slate-900 text-indigo-600 dark:text-indigo-400 border border-slate-200/80 dark:border-slate-800 shadow-xs">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                    <span>Sistem Presensi Resmi PPPK Kab. Subang</span>
                </div>

                <!-- Main Heading -->
                <h2 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-slate-900 dark:text-white tracking-tight leading-[1.15]">
                    Presensi Mandiri PPPK <br class="hidden sm:inline">
                    <span class="bg-gradient-to-r from-indigo-600 via-indigo-500 to-purple-600 bg-clip-text text-transparent">
                        Digital & Terverifikasi
                    </span>
                </h2>

                <!-- Subtitle Description -->
                <p class="text-base sm:text-lg text-slate-600 dark:text-slate-400 max-w-2xl mx-auto font-normal leading-relaxed">
                    Platform presensi online resmi bagi Pegawai Pemerintah dengan Perjanjian Kerja (PPPK) di lingkungan Dinas Kearsipan dan Perpustakaan Kabupaten Subang dengan validasi titik lokasi GPS akurat dan verifikasi foto selfie.
                </p>
            </div>

            <!-- Action Button -->
            <div class="flex flex-col sm:flex-row justify-center items-center gap-4 pt-2">
                @auth
                    @if(Auth::user()->role === 'pppk')
                        <a href="{{ route('attendance.index') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-4 bg-indigo-600 hover:bg-indigo-700 text-white text-base font-bold rounded-2xl shadow-lg shadow-indigo-600/25 hover:shadow-xl hover:shadow-indigo-600/35 hover:-translate-y-0.5 transition-all duration-200">
                            <span>Lakukan Absensi Hari Ini</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-4 bg-indigo-600 hover:bg-indigo-700 text-white text-base font-bold rounded-2xl shadow-lg shadow-indigo-600/25 hover:shadow-xl hover:shadow-indigo-600/35 hover:-translate-y-0.5 transition-all duration-200">
                            <span>Buka Dashboard Admin</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-4 bg-indigo-600 hover:bg-indigo-700 text-white text-base font-bold rounded-2xl shadow-lg shadow-indigo-600/25 hover:shadow-xl hover:shadow-indigo-600/35 hover:-translate-y-0.5 transition-all duration-200">
                        <span>Masuk ke Portal Absensi</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </a>
                @endauth
            </div>

            <!-- 3 Keunggulan Utama Card Showcase -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-left pt-6">
                <!-- Card 1 -->
                <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-xs p-6 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs hover:shadow-md hover:-translate-y-1 transition-all duration-200">
                    <div class="w-12 h-12 rounded-xl bg-indigo-50 dark:bg-indigo-950/70 text-indigo-600 dark:text-indigo-400 flex items-center justify-center mb-4 ring-1 ring-indigo-100 dark:ring-indigo-900/50">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-base text-slate-900 dark:text-white mb-1.5">Kamera Wajah Real-time</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed font-normal">
                        Verifikasi foto selfie langsung dari perangkat saat presensi masuk dan pulang sebagai bukti kehadiran yang otentik.
                    </p>
                </div>

                <!-- Card 2 -->
                <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-xs p-6 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs hover:shadow-md hover:-translate-y-1 transition-all duration-200">
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-950/70 text-emerald-600 dark:text-emerald-400 flex items-center justify-center mb-4 ring-1 ring-emerald-100 dark:ring-emerald-900/50">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-base text-slate-900 dark:text-white mb-1.5">Presisi Radius GPS</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed font-normal">
                        Validasi jarak geofencing akurat terhadap koordinat kantor Disarpus untuk memastikan kehadiran fisik di lokasi tugas.
                    </p>
                </div>

                <!-- Card 3 -->
                <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-xs p-6 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs hover:shadow-md hover:-translate-y-1 transition-all duration-200">
                    <div class="w-12 h-12 rounded-xl bg-blue-50 dark:bg-blue-950/70 text-blue-600 dark:text-blue-400 flex items-center justify-center mb-4 ring-1 ring-blue-100 dark:ring-blue-900/50">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-base text-slate-900 dark:text-white mb-1.5">Waktu Server Resmi</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed font-normal">
                        Pencatatan jam presensi terpusat berbasis waktu server resmi, terlindungi dari rekayasa atau manipulasi jam lokal perangkat.
                    </p>
                </div>
            </div>

            <!-- Feature Highlight Badges -->
            <div class="inline-flex flex-wrap justify-center items-center gap-6 pt-4 text-xs font-medium text-slate-500 dark:text-slate-400">
                <span class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Proses Presensi Cepat < 10 Detik
                </span>
                <span class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Validasi Geofencing Terverifikasi
                </span>
                <span class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Rekapitulasi & Laporan Otomatis
                </span>
            </div>

        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white/85 dark:bg-slate-900/85 backdrop-blur-md border-t border-slate-200 dark:border-slate-800 py-6 transition-colors duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-500 dark:text-slate-400">
            <div>
                &copy; {{ date('Y') }} Dinas Kearsipan dan Perpustakaan Kabupaten Subang.
            </div>
            <div class="text-slate-400 dark:text-slate-500">
                Pemerintah Daerah Kabupaten Subang, Jawa Barat
            </div>
        </div>
    </footer>
</body>
</html>
