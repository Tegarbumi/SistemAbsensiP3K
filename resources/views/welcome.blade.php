<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Portal Presensi Pegawai PPPK - Dinas Kearsipan dan Perpustakaan Kab. Subang</title>

    <!-- Theme Initialization: default is light, dark only if explicitly set -->
    <script>
        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <!-- Font: IBM Plex Sans (Formal, Tegas, Standar Portal Pemerintah) -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=ibm-plex-sans:400,500,600,700&display=swap" rel="stylesheet" />

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
}" class="font-sans bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 antialiased min-h-screen flex flex-col justify-between transition-colors duration-200 selection:bg-blue-600 selection:text-white">

    <!-- Top Notice Banner Instansi -->
    <div class="bg-blue-900 text-white text-[10px] sm:text-[11px] font-medium py-1.5 px-3 sm:px-4 text-center border-b border-blue-950/30">
        <div class="max-w-7xl mx-auto flex items-center justify-center gap-2">
            <span class="inline-block w-2 h-2 rounded-full bg-emerald-400 shrink-0"></span>
            <span class="truncate sm:overflow-visible">Portal Resmi Presensi PPPK &bull; Dinas Kearsipan dan Perpustakaan Kab. Subang</span>
        </div>
    </div>

    <!-- Header Navigasi Resmi -->
    <header class="bg-white/95 dark:bg-slate-900/95 backdrop-blur-md border-b border-slate-200/90 dark:border-slate-800 sticky top-0 z-30 transition-colors duration-200">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            
            <!-- Identitas Instansi: Logo + Nama -->
            <div class="flex items-center gap-2.5 sm:gap-3 min-w-0 mr-2">
                <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-lg bg-white dark:bg-slate-800 p-1 border border-slate-200 dark:border-slate-700 flex items-center justify-center shrink-0 shadow-2xs">
                    <img src="{{ asset('image/logo.jpg') }}" alt="Logo Subang" class="w-full h-full object-contain rounded">
                </div>
                <div class="min-w-0">
                    <span class="hidden xs:block text-[10px] font-bold text-slate-400 dark:text-slate-500 tracking-wider uppercase leading-none">
                        Pemerintah Kabupaten Subang
                    </span>
                    <h1 class="text-xs sm:text-sm font-bold text-slate-900 dark:text-white leading-tight mt-0.5 sm:mt-1 truncate max-w-[150px] xs:max-w-[220px] sm:max-w-none">
                        Dinas Kearsipan dan Perpustakaan
                    </h1>
                </div>
            </div>

            <!-- Navigasi Masuk & Pengaturan Tema -->
            <nav class="flex items-center gap-1.5 sm:gap-3 shrink-0">
                <!-- Theme Toggle Button -->
                <button 
                    type="button" 
                    @click="toggleTheme()" 
                    class="w-9 h-9 rounded-lg flex items-center justify-center text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-100 hover:bg-slate-100 dark:hover:bg-slate-800 border border-slate-200 dark:border-slate-700 transition focus:outline-none shrink-0"
                    :title="isDark ? 'Beralih ke Tampilan Terang' : 'Beralih ke Tampilan Gelap'"
                    aria-label="Pengaturan Mode Tampilan">
                    <svg x-show="isDark" class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <svg x-show="!isDark" class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                </button>

                @if (Route::has('login'))
                    @auth
                        @if(Auth::user()->role === 'pppk')
                            <a href="{{ route('attendance.index') }}" class="inline-flex items-center gap-1.5 sm:gap-2 px-3 sm:px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs sm:text-sm font-semibold rounded-lg shadow-xs transition">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span>Presensi</span>
                            </a>
                        @else
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-1.5 sm:gap-2 px-3 sm:px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs sm:text-sm font-semibold rounded-lg shadow-xs transition">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                                <span>Dashboard</span>
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 sm:gap-2 px-3 sm:px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs sm:text-sm font-semibold rounded-lg shadow-xs transition">
                            <span>Masuk</span>
                            <span class="hidden xs:inline">ke Akun</span>
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                            </svg>
                        </a>
                    @endauth
                @endif
            </nav>
        </div>
    </header>

    <!-- Konten Utama Halaman -->
    <main class="flex-grow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 space-y-12 sm:space-y-16">

            <!-- HERO SECTION: Simetris, Rapi & Elegan -->
            <section class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
                
                <!-- Sisi Kiri: Pesan Utama & Call To Action -->
                <div class="lg:col-span-7 space-y-6">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full border border-blue-200/70 dark:border-blue-800/60 bg-blue-50/70 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 text-xs font-semibold shadow-2xs">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 shrink-0"></span>
                        <span>Portal Presensi Digital Terintegrasi</span>
                    </div>

                    <div class="space-y-3">
                        <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight leading-tight">
                            Pencatatan Kehadiran PPPK Dinas Kearsipan & Perpustakaan
                        </h2>
                        <p class="text-sm sm:text-base text-slate-600 dark:text-slate-300 leading-relaxed font-normal">
                            Sistem resmi pelaporan presensi harian bagi Pegawai Pemerintah dengan Perjanjian Kerja (PPPK) Kabupaten Subang. Dilengkapi validasi radius GPS kantor, verifikasi swafoto kamera langsung, dan audit waktu berstandar instansi pemerintah.
                        </p>
                    </div>

                    <!-- Tombol Aksi Utama -->
                    <div class="pt-2 flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                        @auth
                            @if(Auth::user()->role === 'pppk')
                                <a href="{{ route('attendance.index') }}" class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg shadow-xs transition">
                                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                    </svg>
                                    <span>Lakukan Presensi Hari Ini</span>
                                </a>
                                <a href="{{ route('attendance.history.index') }}" class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 text-sm font-semibold rounded-lg border border-slate-200 dark:border-slate-700 shadow-2xs transition">
                                    <span>Riwayat Presensi</span>
                                </a>
                            @else
                                <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg shadow-xs transition">
                                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                    </svg>
                                    <span>Buka Dashboard Administrator</span>
                                </a>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg shadow-xs transition">
                                <span>Masuk ke Portal Presensi</span>
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                </svg>
                            </a>
                            <a href="#panduan" class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 text-sm font-semibold rounded-lg border border-slate-200 dark:border-slate-700 shadow-2xs transition">
                                <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>Tata Cara Penggunaan</span>
                            </a>
                        @endauth
                    </div>

                    <!-- Keunggulan Singkat (Badges Row) -->
                    <div class="pt-2 grid grid-cols-1 sm:grid-cols-3 gap-3 border-t border-slate-200/80 dark:border-slate-800 pt-5">
                        <div class="flex items-center gap-2 text-xs font-medium text-slate-600 dark:text-slate-300">
                            <span class="w-6 h-6 rounded-md bg-blue-50 dark:bg-slate-800 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0 border border-blue-100 dark:border-slate-700">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                            </span>
                            <span>GPS Geofencing Akurat</span>
                        </div>
                        <div class="flex items-center gap-2 text-xs font-medium text-slate-600 dark:text-slate-300">
                            <span class="w-6 h-6 rounded-md bg-blue-50 dark:bg-slate-800 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0 border border-blue-100 dark:border-slate-700">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/></svg>
                            </span>
                            <span>Swafoto Kamera Asli</span>
                        </div>
                        <div class="flex items-center gap-2 text-xs font-medium text-slate-600 dark:text-slate-300">
                            <span class="w-6 h-6 rounded-md bg-blue-50 dark:bg-slate-800 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0 border border-blue-100 dark:border-slate-700">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </span>
                            <span>Sinkronisasi Waktu Server</span>
                        </div>
                    </div>
                </div>

                <!-- Sisi Kanan: Live Terminal Status Widget (Rapi & Modern) -->
                <div class="lg:col-span-5 bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/90 dark:border-slate-700/80 p-4 sm:p-6 shadow-xs transition-colors duration-200" 
                     x-data="{
                        time: '',
                        date: '',
                        updateTime() {
                            const now = new Date();
                            this.time = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false });
                            this.date = now.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
                        }
                     }" 
                     x-init="updateTime(); setInterval(() => updateTime(), 1000)">

                    <!-- Widget Top Header -->
                    <div class="pb-3 sm:pb-4 border-b border-slate-100 dark:border-slate-700/80 flex items-center justify-between gap-2">
                        <div class="flex items-center gap-2 min-w-0">
                            <span class="w-2.5 h-2.5 rounded-full bg-blue-600 shrink-0"></span>
                            <span class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider truncate">
                                Status Server Operasional
                            </span>
                        </div>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-md text-[10px] sm:text-[11px] font-semibold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/60 shrink-0">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            Aktif
                        </span>
                    </div>

                    <!-- Display Jam & Tanggal Server Real-time -->
                    <div class="py-4 sm:py-5 text-center bg-slate-50 dark:bg-slate-900/50 rounded-xl my-3 sm:my-4 border border-slate-200/70 dark:border-slate-700/50">
                        <div class="text-[10px] sm:text-[11px] font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">
                            Waktu Resmi Presensi (WIB)
                        </div>
                        <div class="text-2xl xs:text-3xl sm:text-4xl font-mono font-bold text-blue-700 dark:text-blue-400 tabular-nums tracking-tight">
                            <span x-text="time || '00:00:00'">00:00:00</span>
                            <span class="text-xs font-sans text-slate-500 dark:text-slate-400 font-semibold ml-1">WIB</span>
                        </div>
                        <div class="text-[11px] sm:text-xs font-medium text-slate-600 dark:text-slate-300 mt-1" x-text="date || 'Memuat tanggal...'">
                            Memuat penanggalan resmi...
                        </div>
                    </div>

                    <!-- Grid Jadwal Kerja Harian -->
                    <div class="space-y-2 mb-3 sm:mb-4">
                        <div class="text-[10px] sm:text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">
                            Ketentuan Jam Kerja Reguler
                        </div>
                        <div class="grid grid-cols-2 gap-2.5 sm:gap-3">
                            <div class="bg-white dark:bg-slate-800/80 p-2.5 sm:p-3 rounded-xl border border-slate-200/80 dark:border-slate-700/80 shadow-2xs">
                                <span class="text-[10px] sm:text-[11px] text-slate-400 font-medium block">Jam Masuk</span>
                                <span class="text-sm sm:text-base font-bold text-slate-900 dark:text-white font-mono mt-0.5 block">07.30 WIB</span>
                                <span class="text-[9px] sm:text-[10px] text-emerald-600 dark:text-emerald-400 font-medium block mt-0.5">Toleransi s/d 07.45</span>
                            </div>
                            <div class="bg-white dark:bg-slate-800/80 p-2.5 sm:p-3 rounded-xl border border-slate-200/80 dark:border-slate-700/80 shadow-2xs">
                                <span class="text-[10px] sm:text-[11px] text-slate-400 font-medium block">Jam Pulang</span>
                                <span class="text-sm sm:text-base font-bold text-slate-900 dark:text-white font-mono mt-0.5 block">16.00 WIB</span>
                                <span class="text-[9px] sm:text-[10px] text-slate-400 font-medium block mt-0.5">Senin s/d Jumat</span>
                            </div>
                        </div>
                    </div>

                    <!-- Lokasi Kantor Validasi -->
                    <div class="pt-2.5 sm:pt-3 border-t border-slate-100 dark:border-slate-700/80">
                        <div class="flex flex-wrap items-center justify-between text-[11px] sm:text-xs text-slate-500 dark:text-slate-400 gap-1">
                            <span>Lokasi Terdaftar:</span>
                            <span class="font-semibold text-slate-800 dark:text-slate-200">Disarpus &bull; Perpusda &bull; Lapangan</span>
                        </div>
                    </div>

                </div>

            </section>

            <!-- SECTION FITUR UTAMA (3 Kolom Berimbang) -->
            <section class="space-y-6">
                <div class="text-center max-w-2xl mx-auto space-y-2">
                    <span class="text-xs font-bold text-blue-600 dark:text-blue-400 uppercase tracking-wider">Standar Akuntabilitas ASN</span>
                    <h3 class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white tracking-tight">
                        Fitur Keamanan & Validasi Presensi
                    </h3>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400">
                        Dirancang khusus untuk memastikan akurasi data kehadiran yang transparan, objektif, dan akuntabel.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Kartu 1 -->
                    <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-200/90 dark:border-slate-700/80 shadow-xs transition hover:border-blue-300 dark:hover:border-blue-700 space-y-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-slate-700 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0 border border-blue-100 dark:border-slate-600 shadow-2xs">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <h4 class="text-base font-bold text-slate-900 dark:text-white">Geofencing Presisi</h4>
                        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                            Pencatatan absensi otomatis menghitung radius jarak meter fisik pegawai terhadap titik kantor penugasan secara *real-time*.
                        </p>
                    </div>

                    <!-- Kartu 2 -->
                    <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-200/90 dark:border-slate-700/80 shadow-xs transition hover:border-blue-300 dark:hover:border-blue-700 space-y-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-slate-700 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0 border border-blue-100 dark:border-slate-600 shadow-2xs">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <h4 class="text-base font-bold text-slate-900 dark:text-white">Verifikasi Swafoto Asli</h4>
                        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                            Mencegah kecurangan atau manipulasi kehadiran dengan pengambilan foto wajah langsung kamera perangkat saat check-in dan check-out.
                        </p>
                    </div>

                    <!-- Kartu 3 -->
                    <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-200/90 dark:border-slate-700/80 shadow-xs transition hover:border-blue-300 dark:hover:border-blue-700 space-y-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-slate-700 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0 border border-blue-100 dark:border-slate-600 shadow-2xs">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <h4 class="text-base font-bold text-slate-900 dark:text-white">Layanan Izin & Rekapitulasi</h4>
                        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                            Pengajuan permohonan izin, sakit, cuti, atau dinas luar langsung diverifikasi secara digital oleh administrator kepegawaian dinas.
                        </p>
                    </div>
                </div>
            </section>

            <!-- TATA CARA PRESENSI (Grid 3 Langkah Modern & Berurutan) -->
            <section id="panduan" class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/90 dark:border-slate-700/80 p-6 sm:p-8 shadow-xs">
                <div class="max-w-2xl mx-auto text-center space-y-2 mb-8">
                    <span class="text-xs font-bold text-blue-600 dark:text-blue-400 uppercase tracking-wider">Panduan Pegawai</span>
                    <h3 class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white tracking-tight">
                        Alur Presensi Mandiri Pegawai PPPK
                    </h3>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400">
                        Pencatatan kehadiran harian dapat diselesaikan dalam 3 langkah mudah melalui peramban ponsel atau komputer.
                    </p>
                </div>

                <!-- 3 Kolom Alur Bertahap -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Langkah 1 -->
                    <div class="bg-slate-50 dark:bg-slate-900/60 p-5 rounded-xl border border-slate-200/70 dark:border-slate-700/60 relative space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="w-8 h-8 rounded-lg bg-blue-600 text-white font-bold text-xs flex items-center justify-center shadow-xs">
                                01
                            </span>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Langkah 1</span>
                        </div>
                        <h4 class="text-sm font-bold text-slate-900 dark:text-white">
                            Masuk ke Akun
                        </h4>
                        <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
                            Gunakan NIP 18 digit atau email resmi instansi beserta kata sandi yang telah diverifikasi oleh bagian kepegawaian Disarpus.
                        </p>
                    </div>

                    <!-- Langkah 2 -->
                    <div class="bg-slate-50 dark:bg-slate-900/60 p-5 rounded-xl border border-slate-200/70 dark:border-slate-700/60 relative space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="w-8 h-8 rounded-lg bg-blue-600 text-white font-bold text-xs flex items-center justify-center shadow-xs">
                                02
                            </span>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Langkah 2</span>
                        </div>
                        <h4 class="text-sm font-bold text-slate-900 dark:text-white">
                            Izinkan GPS & Kamera
                        </h4>
                        <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
                            Aktifkan fitur lokasi pada ponsel dan beri izin akses kamera browser agar sistem dapat memvalidasi koordinat radius kantor.
                        </p>
                    </div>

                    <!-- Langkah 3 -->
                    <div class="bg-slate-50 dark:bg-slate-900/60 p-5 rounded-xl border border-slate-200/70 dark:border-slate-700/60 relative space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="w-8 h-8 rounded-lg bg-blue-600 text-white font-bold text-xs flex items-center justify-center shadow-xs">
                                03
                            </span>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Langkah 3</span>
                        </div>
                        <h4 class="text-sm font-bold text-slate-900 dark:text-white">
                            Ambil Foto & Rekam
                        </h4>
                        <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
                            Arahkan kamera ke wajah secara jelas, pilih unit kerja penugasan Anda, lalu tekan tombol "Presensi Masuk" atau "Pulang".
                        </p>
                    </div>
                </div>
            </section>

            <!-- KETENTUAN DAN DISIPLIN KEPEGAWAIAN (2 Kolom Rapi) -->
            <section class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/90 dark:border-slate-700/80 p-6 sm:p-8 shadow-xs">
                <div class="border-b border-slate-100 dark:border-slate-700/80 pb-4 mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white">
                            Ketentuan Resmi Kehadiran PPPK
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                            Tata tertib dan peraturan disiplin aparatur yang wajib dipatuhi oleh seluruh pegawai.
                        </p>
                    </div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md text-xs font-semibold bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300">
                        Disiplin ASN / PPPK
                    </span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div class="p-4 rounded-xl border border-slate-200/80 dark:border-slate-700/80 bg-slate-50/50 dark:bg-slate-900/40 space-y-1">
                        <div class="font-bold text-slate-900 dark:text-white flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>
                            Batas Radius Kantor
                        </div>
                        <p class="text-slate-600 dark:text-slate-400 leading-relaxed">
                            Presensi reguler hanya dapat terekam jika posisi GPS pegawai berada di dalam radius toleransi kantor yang dipilih (150m kantor Disarpus, 100m Perpustakaan Daerah).
                        </p>
                    </div>

                    <div class="p-4 rounded-xl border border-slate-200/80 dark:border-slate-700/80 bg-slate-50/50 dark:bg-slate-900/40 space-y-1">
                        <div class="font-bold text-slate-900 dark:text-white flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>
                            Verifikasi Swafoto Asli
                        </div>
                        <p class="text-slate-600 dark:text-slate-400 leading-relaxed">
                            Swafoto diambil langsung di tempat penugasan. Tidak diperkenankan menutup wajah, memakai kacamata hitam, atau diwakilkan oleh pihak lain.
                        </p>
                    </div>

                    <div class="p-4 rounded-xl border border-slate-200/80 dark:border-slate-700/80 bg-slate-50/50 dark:bg-slate-900/40 space-y-1">
                        <div class="font-bold text-slate-900 dark:text-white flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>
                            Sinkronisasi Waktu Server
                        </div>
                        <p class="text-slate-600 dark:text-slate-400 leading-relaxed">
                            Pencatatan jam masuk dan pulang mengacu mutlak pada waktu server resmi instansi (WIB), sehingga perubahan waktu lokal di perangkat tidak dapat memanipulasi absensi.
                        </p>
                    </div>

                    <div class="p-4 rounded-xl border border-slate-200/80 dark:border-slate-700/80 bg-slate-50/50 dark:bg-slate-900/40 space-y-1">
                        <div class="font-bold text-slate-900 dark:text-white flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>
                            Audit Log & Akuntabilitas
                        </div>
                        <p class="text-slate-600 dark:text-slate-400 leading-relaxed">
                            Seluruh rekaman kehadiran, keterlambatan, dan riwayat izin terdokumentasi rapi dalam basis data untuk keperluan rekapitulasi penilaian kinerja berkala.
                        </p>
                    </div>
                </div>
            </section>

        </div>
    </main>

    <!-- Footer Resmi Instansi Pemerintah (Tersusun Rapi) -->
    <footer class="bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800 py-10 transition-colors duration-200 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-xs">
                
                <!-- Identitas Kantor -->
                <div class="space-y-3">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-slate-800 p-1 border border-blue-100 dark:border-slate-700 flex items-center justify-center shrink-0">
                            <img src="{{ asset('image/logo.jpg') }}" alt="Logo" class="w-full h-full object-contain rounded">
                        </div>
                        <div class="font-bold text-slate-900 dark:text-white text-sm">
                            Dinas Kearsipan dan Perpustakaan
                        </div>
                    </div>
                    <p class="text-slate-600 dark:text-slate-400 leading-relaxed">
                        Pemerintah Daerah Kabupaten Subang, Provinsi Jawa Barat. Pengelola kearsipan daerah, dokumentasi resmi, dan perpustakaan umum kabupaten.
                    </p>
                </div>

                <!-- Alamat & Lokasi -->
                <div class="space-y-2">
                    <div class="font-bold text-slate-900 dark:text-white uppercase tracking-wider text-[11px]">
                        Alamat Kantor Pusat
                    </div>
                    <p class="text-slate-600 dark:text-slate-400 leading-relaxed">
                        Jl. MT Haryono No. 9, Karanganyar, Kec. Subang, Kabupaten Subang, Jawa Barat 41211
                    </p>
                    <p class="text-slate-500 text-[11px] pt-1">
                        Jam Pelayanan: Senin - Jumat (07.30 - 16.00 WIB)
                    </p>
                </div>

                <!-- Kontak & Bantuan Kepegawaian -->
                <div class="space-y-2">
                    <div class="font-bold text-slate-900 dark:text-white uppercase tracking-wider text-[11px]">
                        Bantuan Teknis Kepegawaian
                    </div>
                    <p class="text-slate-600 dark:text-slate-400 leading-relaxed">
                        Subbag Umum dan Kepegawaian Disarpus Kab. Subang. Untuk pembaruan data, kendala akun login, atau permohonan reset kata sandi silakan hubungi admin kepegawaian.
                    </p>
                </div>

            </div>

            <div class="pt-6 border-t border-slate-100 dark:border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-500 dark:text-slate-400">
                <div>
                    &copy; {{ date('Y') }} Dinas Kearsipan dan Perpustakaan Kabupaten Subang. Hak cipta dilindungi.
                </div>
                <div class="flex items-center gap-4 text-[11px]">
                    <span>Portal Presensi Digital PPPK</span>
                    <span>&bull;</span>
                    <span>Kabupaten Subang</span>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>