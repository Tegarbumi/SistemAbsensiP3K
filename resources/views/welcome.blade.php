<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Presensi Pegawai PPPK - Dinas Kearsipan dan Perpustakaan Kabupaten Subang</title>

    <!-- Theme Initialization to prevent flash of wrong theme -->
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
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
}" class="font-sans bg-slate-100/70 dark:bg-slate-950 text-slate-800 dark:text-slate-100 antialiased min-h-screen flex flex-col justify-between transition-colors duration-150">

    <!-- Header Navigasi Resmi Instansi -->
    <header class="bg-white dark:bg-slate-900 border-b border-slate-300 dark:border-slate-800 sticky top-0 z-30 transition-colors duration-150">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-18 flex items-center justify-between">
            
            <!-- Identitas Instansi: Logo + Nama -->
            <div class="flex items-center space-x-3">
                <div class="w-11 h-11 rounded-md bg-white dark:bg-slate-800 p-1 border border-slate-300 dark:border-slate-700 flex items-center justify-center flex-shrink-0">
                    <img src="{{ asset('image/logo.jpg') }}" alt="Logo Subang" class="w-full h-full object-contain rounded">
                </div>
                <div>
                    <span class="block text-[10px] sm:text-[11px] font-semibold text-slate-500 dark:text-slate-400 tracking-wider uppercase">
                        Pemerintah Kabupaten Subang
                    </span>
                    <h1 class="text-xs sm:text-sm font-bold text-slate-900 dark:text-white leading-tight">
                        Dinas Kearsipan dan Perpustakaan
                    </h1>
                </div>
            </div>

            <!-- Navigasi Masuk & Pengaturan Tema -->
            <nav class="flex items-center space-x-2 sm:space-x-3">
                <!-- Theme Toggle Button -->
                <button 
                    type="button" 
                    @click="toggleTheme()" 
                    class="p-2 rounded-md text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 border border-slate-300 dark:border-slate-700 transition-colors focus:outline-none"
                    :title="isDark ? 'Beralih ke Mode Terang' : 'Beralih ke Mode Gelap'"
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
                            <a href="{{ route('attendance.index') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-gov-blue hover:bg-gov-blue-hover text-white text-xs font-semibold rounded-md border border-gov-blue-hover transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span>Buka Presensi</span>
                            </a>
                        @else
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-gov-blue hover:bg-gov-blue-hover text-white text-xs font-semibold rounded-md border border-gov-blue-hover transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                                <span>Dashboard Admin</span>
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-gov-blue hover:bg-gov-blue-hover text-white text-xs font-semibold rounded-md border border-gov-blue-hover transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                            </svg>
                            <span>Masuk</span>
                        </a>
                    @endauth
                @endif
            </nav>
        </div>
    </header>

    <!-- Konten Utama Halaman -->
    <main class="flex-grow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 space-y-10">

            <!-- HERO SECTION ASIMETRIS (Rata Kiri + Panel Informasi di Kanan) -->
            <section class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <!-- Sisi Kiri: Judul Lugas, Penjelasan & Tombol Aksi -->
                <div class="lg:col-span-7 space-y-5">
                    <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 text-xs font-medium">
                        <span class="w-2 h-2 rounded-full bg-gov-green"></span>
                        <span>Portal Presensi Resmi Pegawai PPPK</span>
                    </div>

                    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-slate-900 dark:text-white tracking-tight leading-snug">
                        Presensi Pegawai PPPK Disarpus Kabupaten Subang
                    </h2>

                    <p class="text-sm sm:text-base text-slate-600 dark:text-slate-300 leading-relaxed font-normal">
                        Sistem informasi pencatatan kehadiran kerja harian bagi Pegawai Pemerintah dengan Perjanjian Kerja (PPPK) di lingkungan Dinas Kearsipan dan Perpustakaan Kabupaten Subang dengan validasi koordinat GPS radius kantor dan verifikasi swafoto perangkat.
                    </p>

                    <!-- Tombol Masuk Utama -->
                    <div class="pt-2 flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                        @auth
                            @if(Auth::user()->role === 'pppk')
                                <a href="{{ route('attendance.index') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-gov-blue hover:bg-gov-blue-hover text-white text-sm font-semibold rounded-md border border-gov-blue-hover transition-colors shadow-xs">
                                    <span>Lakukan Presensi Hari Ini</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                    </svg>
                                </a>
                            @else
                                <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-gov-blue hover:bg-gov-blue-hover text-white text-sm font-semibold rounded-md border border-gov-blue-hover transition-colors shadow-xs">
                                    <span>Buka Dashboard Administrator</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                    </svg>
                                </a>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-gov-blue hover:bg-gov-blue-hover text-white text-sm font-semibold rounded-md border border-gov-blue-hover transition-colors shadow-xs">
                                <span>Masuk ke Portal Absensi</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                </svg>
                            </a>
                        @endauth
                    </div>

                    <!-- Keterangan Tambahan -->
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        * Masuk menggunakan NIP 18 digit atau alamat email resmi dinas beserta kata sandi yang telah didaftarkan.
                    </p>
                </div>

                <!-- Sisi Kanan: Panel Informasi Operasional Real-time -->
                <div class="lg:col-span-5 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-800 rounded-md p-5 shadow-xs" 
                     x-data="{
                        time: '',
                        date: '',
                        updateTime() {
                            const now = new Date();
                            this.time = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false }) + ' WIB';
                            this.date = now.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
                        }
                     }" 
                     x-init="updateTime(); setInterval(() => updateTime(), 1000)">

                    <!-- Header Panel -->
                    <div class="pb-3 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                            Informasi Layanan
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded text-[11px] font-semibold bg-emerald-50 dark:bg-emerald-950/60 text-gov-green border border-emerald-300 dark:border-emerald-800">
                            <span class="w-1.5 h-1.5 rounded-full bg-gov-green"></span>
                            Server Aktif
                        </span>
                    </div>

                    <!-- Jam & Tanggal Server Real-time -->
                    <div class="py-4 border-b border-slate-200 dark:border-slate-800">
                        <div class="text-[11px] font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wide">
                            Waktu Server Resmi
                        </div>
                        <div class="text-2xl sm:text-3xl font-mono font-bold text-slate-900 dark:text-white mt-1" x-text="time || 'Memuat jam...'">
                            --:--:-- WIB
                        </div>
                        <div class="text-xs text-slate-600 dark:text-slate-300 mt-1" x-text="date || 'Memuat tanggal...'">
                            Memuat tanggal...
                        </div>
                    </div>

                    <!-- Jam Kerja Resmi Instansi -->
                    <div class="py-4 border-b border-slate-200 dark:border-slate-800 space-y-2">
                        <div class="text-[11px] font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wide">
                            Jadwal Kerja Reguler PPPK
                        </div>
                        <div class="grid grid-cols-2 gap-3 text-xs">
                            <div class="bg-slate-50 dark:bg-slate-800/60 p-2.5 rounded border border-slate-200 dark:border-slate-700">
                                <span class="block text-slate-500 dark:text-slate-400 text-[11px]">Jam Masuk</span>
                                <span class="font-bold text-slate-900 dark:text-white text-sm">07.30 WIB</span>
                                <span class="block text-[10px] text-slate-500 mt-0.5">Toleransi 15 Menit</span>
                            </div>
                            <div class="bg-slate-50 dark:bg-slate-800/60 p-2.5 rounded border border-slate-200 dark:border-slate-700">
                                <span class="block text-slate-500 dark:text-slate-400 text-[11px]">Jam Pulang</span>
                                <span class="font-bold text-slate-900 dark:text-white text-sm">16.00 WIB</span>
                                <span class="block text-[10px] text-slate-500 mt-0.5">Senin s/d Jumat</span>
                            </div>
                        </div>
                    </div>

                    <!-- Lokasi Kantor Resmi -->
                    <div class="pt-4 space-y-2">
                        <div class="text-[11px] font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wide">
                            Titik Lokasi Presensi
                        </div>
                        <ul class="text-xs space-y-2 text-slate-700 dark:text-slate-300">
                            <li class="flex items-start gap-2">
                                <span class="font-semibold text-slate-900 dark:text-white">•</span>
                                <div>
                                    <span class="font-semibold text-slate-900 dark:text-white">Kantor Dinas Kearsipan dan Perpustakaan:</span>
                                    <span class="text-slate-500 dark:text-slate-400 block text-[11px]">Jl. MT Haryono No. 9, Subang (Radius 150 meter)</span>
                                </div>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="font-semibold text-slate-900 dark:text-white">•</span>
                                <div>
                                    <span class="font-semibold text-slate-900 dark:text-white">Perpustakaan Daerah Kabupaten Subang:</span>
                                    <span class="text-slate-500 dark:text-slate-400 block text-[11px]">Kabupaten Subang (Radius 100 meter)</span>
                                </div>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="font-semibold text-slate-900 dark:text-white">•</span>
                                <div>
                                    <span class="font-semibold text-slate-900 dark:text-white">Unit Dinas Lapangan:</span>
                                    <span class="text-slate-500 dark:text-slate-400 block text-[11px]">Khusus pegawai tugas luar via koordinat GPS</span>
                                </div>
                            </li>
                        </ul>
                    </div>

                </div>

            </section>

            <!-- TATA CARA PRESENSI (Daftar Vertikal Bernomor Rapi, Bukan Kartu Pastel) -->
            <section class="bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-800 rounded-md p-6 sm:p-8 shadow-xs">
                <div class="border-b border-slate-200 dark:border-slate-800 pb-4 mb-6">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white tracking-tight">
                        Tata Cara Presensi Mandiri Pegawai
                    </h3>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                        Panduan langkah pencatatan kehadiran harian pegawai PPPK melalui perangkat ponsel atau peramban web.
                    </p>
                </div>

                <!-- 3 Langkah Terstruktur -->
                <div class="space-y-4">
                    <!-- Langkah 1 -->
                    <div class="p-4 rounded border border-slate-200 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-800/40 flex flex-col sm:flex-row sm:items-center gap-4">
                        <div class="w-10 h-10 rounded bg-gov-blue text-white flex items-center justify-center font-bold text-sm flex-shrink-0">
                            01
                        </div>
                        <div class="flex-grow">
                            <h4 class="text-sm font-bold text-slate-900 dark:text-white">
                                Masuk ke Akun Pegawai
                            </h4>
                            <p class="text-xs text-slate-600 dark:text-slate-400 mt-0.5">
                                Akses tombol Masuk, ketikkan NIP 18 digit atau alamat email resmi dan kata sandi yang telah diverifikasi oleh pengelola kepegawaian.
                            </p>
                        </div>
                    </div>

                    <!-- Langkah 2 -->
                    <div class="p-4 rounded border border-slate-200 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-800/40 flex flex-col sm:flex-row sm:items-center gap-4">
                        <div class="w-10 h-10 rounded bg-gov-blue text-white flex items-center justify-center font-bold text-sm flex-shrink-0">
                            02
                        </div>
                        <div class="flex-grow">
                            <h4 class="text-sm font-bold text-slate-900 dark:text-white">
                                Aktifkan Izin Lokasi GPS & Kamera
                            </h4>
                            <p class="text-xs text-slate-600 dark:text-slate-400 mt-0.5">
                                Pastikan GPS ponsel aktif dan peramban diberikan izin membaca koordinat serta kamera. Sistem akan memvalidasi posisi Anda terhadap radius kantor yang berlaku.
                            </p>
                        </div>
                    </div>

                    <!-- Langkah 3 -->
                    <div class="p-4 rounded border border-slate-200 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-800/40 flex flex-col sm:flex-row sm:items-center gap-4">
                        <div class="w-10 h-10 rounded bg-gov-blue text-white flex items-center justify-center font-bold text-sm flex-shrink-0">
                            03
                        </div>
                        <div class="flex-grow">
                            <h4 class="text-sm font-bold text-slate-900 dark:text-white">
                                Ambil Swafoto & Rekam Kehadiran
                            </h4>
                            <p class="text-xs text-slate-600 dark:text-slate-400 mt-0.5">
                                Arahkan kamera pada wajah secara jelas, pilih unit kerja penugasan yang sesuai, lalu tekan tombol "Presensi Masuk" atau "Presensi Pulang".
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- KETENTUAN DAN DISIPLIN KEPEGAWAIAN -->
            <section class="border border-slate-300 dark:border-slate-800 bg-white dark:bg-slate-900 rounded-md p-6 sm:p-8 shadow-xs">
                <div class="border-b border-slate-200 dark:border-slate-800 pb-4 mb-5 flex items-center justify-between">
                    <div>
                        <h3 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white">
                            Ketentuan Presensi Resmi
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                            Aturan dan ketentuan pencatatan kehadiran yang berlaku bagi seluruh pegawai PPPK.
                        </p>
                    </div>
                    <span class="hidden sm:inline-block text-[11px] font-semibold text-slate-500 uppercase tracking-wider">
                        Disiplin ASN / PPPK
                    </span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div class="p-3.5 rounded border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/30">
                        <div class="font-bold text-slate-900 dark:text-white mb-1 flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-gov-blue"></span>
                            Batas Radius Kantor
                        </div>
                        <p class="text-slate-600 dark:text-slate-400 leading-relaxed">
                            Presensi reguler hanya dapat dikirim jika jarak pegawai terverifikasi di dalam radius kantor kerja yang dipilih (150m untuk kantor Disarpus, 100m untuk Perpustakaan Daerah).
                        </p>
                    </div>

                    <div class="p-3.5 rounded border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/30">
                        <div class="font-bold text-slate-900 dark:text-white mb-1 flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-gov-blue"></span>
                            Verifikasi Swafoto Asli
                        </div>
                        <p class="text-slate-600 dark:text-slate-400 leading-relaxed">
                            Foto wajah diambil langsung pada saat pengiriman presensi. Tidak diperkenankan menggunakan foto dari galeri, menutup wajah, atau diwakilkan oleh orang lain.
                        </p>
                    </div>

                    <div class="p-3.5 rounded border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/30">
                        <div class="font-bold text-slate-900 dark:text-white mb-1 flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-gov-blue"></span>
                            Sinkronisasi Waktu Server
                        </div>
                        <p class="text-slate-600 dark:text-slate-400 leading-relaxed">
                            Jam kehadiran dicatat mutlak mengacu pada waktu server resmi Disarpus Subang, sehingga perubahan jam manual pada perangkat pengguna tidak memengaruhi rekam waktu.
                        </p>
                    </div>

                    <div class="p-3.5 rounded border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/30">
                        <div class="font-bold text-slate-900 dark:text-white mb-1 flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-gov-blue"></span>
                            Pelaporan & Audit Kehadiran
                        </div>
                        <p class="text-slate-600 dark:text-slate-400 leading-relaxed">
                            Seluruh data presensi dan permohonan izin terekam dalam log audit kepegawaian sebagai dasar evaluasi kinerja dan rekapitulasi kehadiran berkala instansi.
                        </p>
                    </div>
                </div>
            </section>

        </div>
    </main>

    <!-- Footer Resmi Instansi Pemerintah -->
    <footer class="bg-white dark:bg-slate-900 border-t border-slate-300 dark:border-slate-800 py-8 transition-colors duration-150">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pb-6 border-b border-slate-200 dark:border-slate-800 text-xs">
                
                <!-- Identitas Kantor -->
                <div class="space-y-2">
                    <div class="font-bold text-slate-900 dark:text-white text-sm">
                        Dinas Kearsipan dan Perpustakaan
                    </div>
                    <p class="text-slate-600 dark:text-slate-400 leading-relaxed">
                        Pemerintah Daerah Kabupaten Subang, Jawa Barat. Unit pengelola kearsipan daerah dan perpustakaan umum kabupaten.
                    </p>
                </div>

                <!-- Alamat & Lokasi -->
                <div class="space-y-1">
                    <div class="font-bold text-slate-900 dark:text-white">
                        Alamat Kantor
                    </div>
                    <p class="text-slate-600 dark:text-slate-400 leading-relaxed">
                        Jl. MT Haryono No. 9, Karanganyar, Kec. Subang, Kabupaten Subang, Jawa Barat 41211
                    </p>
                </div>

                <!-- Kontak & Bantuan Kepegawaian -->
                <div class="space-y-1">
                    <div class="font-bold text-slate-900 dark:text-white">
                        Layanan Kepegawaian
                    </div>
                    <p class="text-slate-600 dark:text-slate-400 leading-relaxed">
                        Bagian Pengawasan, Kearsipan, dan Kepegawaian Disarpus Kab. Subang. Untuk pembaruan akun atau reset kata sandi, hubungi admin sistem.
                    </p>
                </div>

            </div>

            <div class="pt-4 flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-slate-500 dark:text-slate-400">
                <div>
                    &copy; {{ date('Y') }} Dinas Kearsipan dan Perpustakaan Kabupaten Subang.
                </div>
                <div>
                    Sistem Presensi Pegawai PPPK &bull; Versi Resmi
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
