<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-base sm:text-lg text-slate-900 dark:text-white tracking-tight flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                <span>{{ __('Dashboard Utama') }}</span>
            </h2>
            <div class="text-[11px] sm:text-xs text-slate-500 dark:text-slate-400 font-medium hidden xs:block">
                Portal Presensi Digital PPPK
            </div>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6 lg:py-8">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 space-y-4 sm:space-y-6">

            @if(Auth::user()->role === 'pppk')
                <!-- 1. Header Card Profil PPPK & Aksi Cepat -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/90 dark:border-slate-700/80 p-4 sm:p-6 md:p-7 shadow-xs transition-colors duration-200">
                    <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-5 sm:gap-6">
                        <div class="flex items-start gap-3.5 sm:gap-4 min-w-0 w-full lg:w-auto">
                            @if(isset($employee) && $employee->foto)
                                <img src="{{ asset('storage/' . $employee->foto) }}" alt="{{ $employee->nama }}" class="w-14 h-14 sm:w-16 sm:h-16 rounded-xl object-cover border border-slate-200 dark:border-slate-700 shrink-0 shadow-xs">
                            @else
                                <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-xl bg-blue-50 dark:bg-slate-700 border border-blue-100 dark:border-slate-600 flex items-center justify-center text-blue-700 dark:text-blue-300 font-bold text-lg sm:text-xl shrink-0 shadow-xs">
                                    {{ strtoupper(substr($employee->nama ?? Auth::user()->name, 0, 2)) }}
                                </div>
                            @endif

                            <div class="space-y-1 min-w-0 flex-1">
                                <div class="flex flex-wrap items-center gap-1.5 sm:gap-2">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-md text-[10px] sm:text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200/60 dark:bg-blue-950/40 dark:text-blue-300 dark:border-blue-800/40">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-600 dark:bg-blue-400"></span>
                                        Pegawai PPPK
                                    </span>
                                    @if(isset($employee) && $employee->status)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] sm:text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200/60 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-800/40">
                                            Status Aktif
                                        </span>
                                    @endif
                                </div>

                                <h3 class="text-lg sm:text-2xl font-bold text-slate-900 dark:text-white tracking-tight truncate">
                                    {{ $employee->nama ?? Auth::user()->name }}
                                </h3>

                                <div class="flex flex-wrap items-center gap-y-1 gap-x-3 sm:gap-x-4 text-xs text-slate-600 dark:text-slate-300 pt-0.5">
                                    <span class="inline-flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                                        </svg>
                                        <span class="font-mono text-[11px] sm:text-xs">{{ $employee->nip ?? '-' }}</span>
                                    </span>
                                    <span class="inline-flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                        <span class="truncate max-w-[140px] xs:max-w-none">{{ $employee->jabatan ?? '-' }}</span>
                                    </span>
                                    <span class="inline-flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                        <span class="font-medium text-slate-800 dark:text-slate-200 truncate max-w-[160px] xs:max-w-none">{{ $employee->unit->nama ?? '-' }}</span>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons: full width stacked on mobile, row on tablet/desktop -->
                        <div class="flex flex-col xs:flex-row gap-2.5 w-full lg:w-auto shrink-0">
                            <a href="{{ route('attendance.index') }}" class="w-full xs:w-auto inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs sm:text-sm font-semibold rounded-xl shadow-xs transition">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span>Buka Presensi</span>
                            </a>
                            <a href="{{ route('attendance.history.index') }}" class="w-full xs:w-auto inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs sm:text-sm font-semibold rounded-xl border border-slate-200 dark:border-slate-700 shadow-2xs transition">
                                <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>Riwayat Presensi</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- 2. Status Presensi Hari Ini -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/90 dark:border-slate-700/80 p-4 sm:p-6 shadow-xs transition-colors duration-200">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2.5 sm:gap-3 border-b border-slate-100 dark:border-slate-700/80 pb-3 sm:pb-4 mb-4 sm:mb-5">
                        <div class="flex items-center gap-2.5">
                            <span class="w-7 h-7 rounded-lg bg-blue-50 dark:bg-slate-700 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </span>
                            <div>
                                <h4 class="text-xs sm:text-sm font-bold text-slate-900 dark:text-white">Status Presensi Hari Ini</h4>
                                <p class="text-[11px] sm:text-xs text-slate-500 dark:text-slate-400">{{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y') }}</p>
                            </div>
                        </div>

                        <div>
                            @if(!$todayAttendance)
                                <span class="inline-flex items-center gap-1.5 px-2.5 sm:px-3 py-1 rounded-full text-[11px] sm:text-xs font-semibold bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-300 border border-amber-200/70 dark:border-amber-800/60">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                    Belum Check-In
                                </span>
                            @elseif(!$todayAttendance->jam_pulang)
                                <span class="inline-flex items-center gap-1.5 px-2.5 sm:px-3 py-1 rounded-full text-[11px] sm:text-xs font-semibold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200/70 dark:border-emerald-800/60">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Sudah Check-In
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 sm:px-3 py-1 rounded-full text-[11px] sm:text-xs font-semibold bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 border border-blue-200/70 dark:border-blue-800/60">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>
                                    Selesai Presensi Hari Ini
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4">
                        <div class="bg-slate-50 dark:bg-slate-800/60 p-3.5 sm:p-4 rounded-xl border border-slate-200/70 dark:border-slate-700/60 flex items-center gap-3.5">
                            <span class="w-9 h-9 sm:w-10 sm:h-10 rounded-lg bg-white dark:bg-slate-700 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0 border border-slate-200/60 dark:border-slate-600 shadow-2xs">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                                </svg>
                            </span>
                            <div class="min-w-0 flex-1">
                                <span class="text-[10px] sm:text-[11px] text-slate-500 dark:text-slate-400 block uppercase font-bold tracking-wider">Jam Masuk</span>
                                <span class="text-base sm:text-lg font-bold text-slate-900 dark:text-white block font-mono mt-0.5">
                                    {{ $todayAttendance->jam_masuk ?? '-' }}
                                </span>
                            </div>
                        </div>

                        <div class="bg-slate-50 dark:bg-slate-800/60 p-3.5 sm:p-4 rounded-xl border border-slate-200/70 dark:border-slate-700/60 flex items-center gap-3.5">
                            <span class="w-9 h-9 sm:w-10 sm:h-10 rounded-lg bg-white dark:bg-slate-700 text-indigo-600 dark:text-indigo-400 flex items-center justify-center shrink-0 border border-slate-200/60 dark:border-slate-600 shadow-2xs">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                            </span>
                            <div class="min-w-0 flex-1">
                                <span class="text-[10px] sm:text-[11px] text-slate-500 dark:text-slate-400 block uppercase font-bold tracking-wider">Jam Pulang</span>
                                <span class="text-base sm:text-lg font-bold text-slate-900 dark:text-white block font-mono mt-0.5">
                                    {{ $todayAttendance->jam_pulang ?? '-' }}
                                </span>
                            </div>
                        </div>

                        <div class="bg-slate-50 dark:bg-slate-800/60 p-3.5 sm:p-4 rounded-xl border border-slate-200/70 dark:border-slate-700/60 flex items-center gap-3.5">
                            <span class="w-9 h-9 sm:w-10 sm:h-10 rounded-lg bg-white dark:bg-slate-700 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0 border border-slate-200/60 dark:border-slate-600 shadow-2xs">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </span>
                            <div class="min-w-0 flex-1">
                                <span class="text-[10px] sm:text-[11px] text-slate-500 dark:text-slate-400 block uppercase font-bold tracking-wider">Status Kehadiran</span>
                                <span class="text-base sm:text-lg font-bold text-blue-700 dark:text-blue-400 block mt-0.5 truncate">
                                    {{ $todayAttendance ? ucfirst($todayAttendance->status) : 'Belum Ada Data' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. Ringkasan Presensi Bulan Berjalan -->
                <div>
                    <div class="flex items-center justify-between mb-3 px-1">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-blue-600 shrink-0"></span>
                            <h4 class="text-xs font-bold text-slate-800 dark:text-slate-200 uppercase tracking-wider truncate">
                                Rekapitulasi Bulan {{ $namaBulan }}
                            </h4>
                        </div>
                        <span class="text-[11px] text-slate-400 dark:text-slate-500 hidden sm:inline">Akumulasi otomatis</span>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-7 gap-2.5 sm:gap-3">
                        <!-- Hadir -->
                        <div class="bg-white dark:bg-slate-800 p-3 sm:p-4 rounded-xl border border-slate-200/90 dark:border-slate-700/80 shadow-2xs text-center transition hover:border-emerald-300">
                            <span class="text-[11px] sm:text-xs font-semibold text-emerald-700 dark:text-emerald-400 uppercase block mb-0.5">Hadir</span>
                            <span class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white tabular-nums">{{ $stats['hadir'] }}</span>
                            <span class="text-[10px] text-slate-400 block mt-0.5">Hari</span>
                        </div>

                        <!-- Terlambat -->
                        <div class="bg-white dark:bg-slate-800 p-3 sm:p-4 rounded-xl border border-slate-200/90 dark:border-slate-700/80 shadow-2xs text-center transition hover:border-amber-300">
                            <span class="text-[11px] sm:text-xs font-semibold text-amber-700 dark:text-amber-400 uppercase block mb-0.5">Terlambat</span>
                            <span class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white tabular-nums">{{ $stats['terlambat'] }}</span>
                            <span class="text-[10px] text-slate-400 block mt-0.5">Hari</span>
                        </div>

                        <!-- Izin -->
                        <div class="bg-white dark:bg-slate-800 p-3 sm:p-4 rounded-xl border border-slate-200/90 dark:border-slate-700/80 shadow-2xs text-center transition hover:border-blue-300">
                            <span class="text-[11px] sm:text-xs font-semibold text-blue-700 dark:text-blue-400 uppercase block mb-0.5">Izin</span>
                            <span class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white tabular-nums">{{ $stats['izin'] }}</span>
                            <span class="text-[10px] text-slate-400 block mt-0.5">Hari</span>
                        </div>

                        <!-- Sakit -->
                        <div class="bg-white dark:bg-slate-800 p-3 sm:p-4 rounded-xl border border-slate-200/90 dark:border-slate-700/80 shadow-2xs text-center transition hover:border-purple-300">
                            <span class="text-[11px] sm:text-xs font-semibold text-purple-700 dark:text-purple-400 uppercase block mb-0.5">Sakit</span>
                            <span class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white tabular-nums">{{ $stats['sakit'] }}</span>
                            <span class="text-[10px] text-slate-400 block mt-0.5">Hari</span>
                        </div>

                        <!-- Dinas -->
                        <div class="bg-white dark:bg-slate-800 p-3 sm:p-4 rounded-xl border border-slate-200/90 dark:border-slate-700/80 shadow-2xs text-center transition hover:border-indigo-300">
                            <span class="text-[11px] sm:text-xs font-semibold text-indigo-700 dark:text-indigo-400 uppercase block mb-0.5">Dinas</span>
                            <span class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white tabular-nums">{{ $stats['dinas'] }}</span>
                            <span class="text-[10px] text-slate-400 block mt-0.5">Hari</span>
                        </div>

                        <!-- Cuti -->
                        <div class="bg-white dark:bg-slate-800 p-3 sm:p-4 rounded-xl border border-slate-200/90 dark:border-slate-700/80 shadow-2xs text-center transition hover:border-cyan-300">
                            <span class="text-[11px] sm:text-xs font-semibold text-cyan-700 dark:text-cyan-400 uppercase block mb-0.5">Cuti</span>
                            <span class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white tabular-nums">{{ $stats['cuti'] }}</span>
                            <span class="text-[10px] text-slate-400 block mt-0.5">Hari</span>
                        </div>

                        <!-- Alpha -->
                        <div class="bg-white dark:bg-slate-800 p-3 sm:p-4 rounded-xl border border-slate-200/90 dark:border-slate-700/80 shadow-2xs text-center col-span-2 sm:col-span-1 transition hover:border-rose-300">
                            <span class="text-[11px] sm:text-xs font-semibold text-rose-700 dark:text-rose-400 uppercase block mb-0.5">Alpha</span>
                            <span class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white tabular-nums">{{ $stats['alpha'] }}</span>
                            <span class="text-[10px] text-slate-400 block mt-0.5">Hari</span>
                        </div>
                    </div>
                </div>

            @else
                <!-- DASHBOARD ADMINISTRATOR -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/90 dark:border-slate-700/80 p-4 sm:p-6 md:p-8 shadow-xs transition-colors duration-200">
                    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 pb-5 sm:pb-6 border-b border-slate-200/80 dark:border-slate-700/80 mb-5 sm:mb-6">
                        <div>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200/60 dark:bg-blue-950/40 dark:text-blue-300 dark:border-blue-800/40">
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>
                                Administrator Utama
                            </span>
                            <h3 class="text-lg sm:text-2xl font-bold text-slate-900 dark:text-white mt-2 tracking-tight">
                                Selamat Datang, {{ Auth::user()->name }}
                            </h3>
                            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-0.5">
                                Dinas Kearsipan dan Perpustakaan Pemerintah Kabupaten Subang
                            </p>
                        </div>
                        <div class="w-full sm:w-auto">
                            <a href="{{ route('admin.employees.index') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs sm:text-sm font-semibold rounded-xl shadow-xs transition">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                                <span>Kelola Data PPPK</span>
                            </a>
                        </div>
                    </div>

                    <!-- Statistik Instansi Hari Ini -->
                    <div>
                        <p class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-3">
                            Statistik Presensi Hari Ini
                        </p>
                        <div class="grid grid-cols-2 md:grid-cols-5 gap-2.5 sm:gap-4">
                            <!-- Total PPPK -->
                            <div class="bg-slate-50 dark:bg-slate-800/60 p-3.5 sm:p-4 rounded-xl border border-slate-200/70 dark:border-slate-700/60">
                                <div class="flex items-center justify-between">
                                    <span class="text-[11px] sm:text-xs text-slate-500 dark:text-slate-400 font-semibold uppercase">Total PPPK</span>
                                    <span class="w-7 h-7 rounded-lg bg-white dark:bg-slate-700 text-slate-600 dark:text-slate-300 flex items-center justify-center shrink-0 border border-slate-200/60 dark:border-slate-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                    </span>
                                </div>
                                <span class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white mt-2 block tabular-nums">{{ $adminStats['total_pppk'] }}</span>
                                <span class="text-[10px] sm:text-[11px] text-slate-400 block mt-0.5">Pegawai terdaftar</span>
                            </div>

                            <!-- PPPK Aktif -->
                            <div class="bg-slate-50 dark:bg-slate-800/60 p-3.5 sm:p-4 rounded-xl border border-slate-200/70 dark:border-slate-700/60">
                                <div class="flex items-center justify-between">
                                    <span class="text-[11px] sm:text-xs text-emerald-700 dark:text-emerald-400 font-semibold uppercase">PPPK Aktif</span>
                                    <span class="w-7 h-7 rounded-lg bg-emerald-50 dark:bg-slate-700 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0 border border-emerald-200/60 dark:border-slate-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </span>
                                </div>
                                <span class="text-xl sm:text-2xl font-bold text-emerald-700 dark:text-emerald-400 mt-2 block tabular-nums">{{ $adminStats['pppk_aktif'] }}</span>
                                <span class="text-[10px] sm:text-[11px] text-slate-400 block mt-0.5">Status bertugas</span>
                            </div>

                            <!-- Total Unit -->
                            <div class="bg-slate-50 dark:bg-slate-800/60 p-3.5 sm:p-4 rounded-xl border border-slate-200/70 dark:border-slate-700/60">
                                <div class="flex items-center justify-between">
                                    <span class="text-[11px] sm:text-xs text-slate-500 dark:text-slate-400 font-semibold uppercase">Total Unit</span>
                                    <span class="w-7 h-7 rounded-lg bg-white dark:bg-slate-700 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0 border border-slate-200/60 dark:border-slate-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                    </span>
                                </div>
                                <span class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white mt-2 block tabular-nums">{{ $adminStats['total_unit'] }}</span>
                                <span class="text-[10px] sm:text-[11px] text-slate-400 block mt-0.5">Unit kerja instansi</span>
                            </div>

                            <!-- Hadir Hari Ini -->
                            <div class="bg-slate-50 dark:bg-slate-800/60 p-3.5 sm:p-4 rounded-xl border border-slate-200/70 dark:border-slate-700/60">
                                <div class="flex items-center justify-between">
                                    <span class="text-[11px] sm:text-xs text-blue-700 dark:text-blue-400 font-semibold uppercase">Hadir</span>
                                    <span class="w-7 h-7 rounded-lg bg-blue-50 dark:bg-slate-700 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0 border border-blue-200/60 dark:border-slate-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </span>
                                </div>
                                <span class="text-xl sm:text-2xl font-bold text-blue-700 dark:text-blue-400 mt-2 block tabular-nums">{{ $adminStats['hadir_hari_ini'] }}</span>
                                <span class="text-[10px] sm:text-[11px] text-slate-400 block mt-0.5">Check-in hari ini</span>
                            </div>

                            <!-- Terlambat Hari Ini -->
                            <div class="bg-slate-50 dark:bg-slate-800/60 p-3.5 sm:p-4 rounded-xl border border-slate-200/70 dark:border-slate-700/60 col-span-2 md:col-span-1">
                                <div class="flex items-center justify-between">
                                    <span class="text-[11px] sm:text-xs text-amber-700 dark:text-amber-400 font-semibold uppercase">Terlambat</span>
                                    <span class="w-7 h-7 rounded-lg bg-amber-50 dark:bg-slate-700 text-amber-600 dark:text-amber-400 flex items-center justify-center shrink-0 border border-amber-200/60 dark:border-slate-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </span>
                                </div>
                                <span class="text-xl sm:text-2xl font-bold text-amber-600 dark:text-amber-400 mt-2 block tabular-nums">{{ $adminStats['terlambat_hari_ini'] }}</span>
                                <span class="text-[10px] sm:text-[11px] text-slate-400 block mt-0.5">Melebihi jadwal</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>