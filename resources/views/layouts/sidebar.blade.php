<!-- Sidebar Container -->
<aside class="flex flex-col h-full bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800 shadow-xs select-none transition-colors duration-200">
    <!-- Brand / Header -->
    <div class="flex items-center gap-3 px-4 py-4 border-b border-slate-100 dark:border-slate-800">
        <div class="w-9 h-9 rounded-lg bg-blue-50 dark:bg-slate-800 p-1 flex items-center justify-center border border-blue-100 dark:border-slate-700 shrink-0 shadow-xs">
            <img src="{{ asset('image/logo.jpg') }}" alt="Logo" class="w-full h-full object-contain rounded">
        </div>
        <div class="min-w-0 flex-1">
            <h1 class="text-sm font-bold text-slate-900 dark:text-white tracking-tight leading-none truncate">
                Absensi P3K
            </h1>
            <p class="text-[11px] text-slate-500 dark:text-slate-400 font-medium truncate mt-1">
                Disarpus Kab. Subang
            </p>
        </div>
        <!-- Hamburger / Toggle Button inside Sidebar Header -->
        <button 
            type="button" 
            @click="toggle()" 
            class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors focus:outline-none shrink-0"
            title="Tutup Sidebar"
            aria-label="Toggle Sidebar">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
            </svg>
        </button>
    </div>

    <!-- Navigation Menu Items -->
    <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-5 text-sm">
        @if(Auth::user()->role === 'admin')
            <!-- SECTION: UTAMA -->
            <div>
                <p class="px-3 mb-2 text-[10px] font-bold tracking-wider text-slate-400 dark:text-slate-500 uppercase">
                    Menu Utama
                </p>
                <div class="space-y-1">
                    <a href="{{ route('dashboard') }}" 
                       class="group flex items-center gap-3 px-2.5 py-2 rounded-xl transition-all duration-150 {{ request()->routeIs('dashboard') ? 'bg-blue-50/90 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 font-semibold border border-blue-200/70 dark:border-blue-800/50 shadow-xs' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100/70 dark:hover:bg-slate-800 font-medium' }}">
                        <span class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 transition-colors {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white shadow-xs' : 'bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 group-hover:bg-white dark:group-hover:bg-slate-700 group-hover:text-slate-800 dark:group-hover:text-slate-100 border border-slate-200/60 dark:border-slate-700/60' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                        </span>
                        <span class="truncate">Dashboard</span>
                    </a>
                </div>
            </div>

            <!-- SECTION: DATA MASTER -->
            <div>
                <p class="px-3 mb-2 text-[10px] font-bold tracking-wider text-slate-400 dark:text-slate-500 uppercase">
                    Data Master
                </p>
                <div class="space-y-1">
                    <!-- Kelola PPPK -->
                    <a href="{{ route('admin.employees.index') }}" 
                       class="group flex items-center gap-3 px-2.5 py-2 rounded-xl transition-all duration-150 {{ request()->routeIs('admin.employees.*') ? 'bg-blue-50/90 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 font-semibold border border-blue-200/70 dark:border-blue-800/50 shadow-xs' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100/70 dark:hover:bg-slate-800 font-medium' }}">
                        <span class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 transition-colors {{ request()->routeIs('admin.employees.*') ? 'bg-blue-600 text-white shadow-xs' : 'bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 group-hover:bg-white dark:group-hover:bg-slate-700 group-hover:text-slate-800 dark:group-hover:text-slate-100 border border-slate-200/60 dark:border-slate-700/60' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </span>
                        <span class="truncate">Kelola PPPK</span>
                    </a>

                    <!-- Unit Kerja -->
                    <a href="{{ route('admin.units.index') }}" 
                       class="group flex items-center gap-3 px-2.5 py-2 rounded-xl transition-all duration-150 {{ request()->routeIs('admin.units.*') ? 'bg-blue-50/90 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 font-semibold border border-blue-200/70 dark:border-blue-800/50 shadow-xs' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100/70 dark:hover:bg-slate-800 font-medium' }}">
                        <span class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 transition-colors {{ request()->routeIs('admin.units.*') ? 'bg-blue-600 text-white shadow-xs' : 'bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 group-hover:bg-white dark:group-hover:bg-slate-700 group-hover:text-slate-800 dark:group-hover:text-slate-100 border border-slate-200/60 dark:border-slate-700/60' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </span>
                        <span class="truncate">Unit Kerja</span>
                    </a>

                    <!-- Lokasi Kantor -->
                    <a href="{{ route('admin.office-locations.index') }}" 
                       class="group flex items-center gap-3 px-2.5 py-2 rounded-xl transition-all duration-150 {{ request()->routeIs('admin.office-locations.*') ? 'bg-blue-50/90 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 font-semibold border border-blue-200/70 dark:border-blue-800/50 shadow-xs' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100/70 dark:hover:bg-slate-800 font-medium' }}">
                        <span class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 transition-colors {{ request()->routeIs('admin.office-locations.*') ? 'bg-blue-600 text-white shadow-xs' : 'bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 group-hover:bg-white dark:group-hover:bg-slate-700 group-hover:text-slate-800 dark:group-hover:text-slate-100 border border-slate-200/60 dark:border-slate-700/60' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </span>
                        <span class="truncate">Lokasi Kantor</span>
                    </a>

                    <!-- Jadwal Kerja -->
                    <a href="{{ route('admin.work-schedules.index') }}" 
                       class="group flex items-center gap-3 px-2.5 py-2 rounded-xl transition-all duration-150 {{ request()->routeIs('admin.work-schedules.*') ? 'bg-blue-50/90 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 font-semibold border border-blue-200/70 dark:border-blue-800/50 shadow-xs' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100/70 dark:hover:bg-slate-800 font-medium' }}">
                        <span class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 transition-colors {{ request()->routeIs('admin.work-schedules.*') ? 'bg-blue-600 text-white shadow-xs' : 'bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 group-hover:bg-white dark:group-hover:bg-slate-700 group-hover:text-slate-800 dark:group-hover:text-slate-100 border border-slate-200/60 dark:border-slate-700/60' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </span>
                        <span class="truncate">Jadwal Kerja</span>
                    </a>
                </div>
            </div>

            <!-- SECTION: MONITORING & REKAP -->
            <div>
                <p class="px-3 mb-2 text-[10px] font-bold tracking-wider text-slate-400 dark:text-slate-500 uppercase">
                    Monitoring Presensi
                </p>
                <div class="space-y-1">
                    <!-- Monitoring Absensi -->
                    <a href="{{ route('admin.attendance-monitoring.index') }}" 
                       class="group flex items-center gap-3 px-2.5 py-2 rounded-xl transition-all duration-150 {{ request()->routeIs('admin.attendance-monitoring.*') ? 'bg-blue-50/90 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 font-semibold border border-blue-200/70 dark:border-blue-800/50 shadow-xs' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100/70 dark:hover:bg-slate-800 font-medium' }}">
                        <span class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 transition-colors {{ request()->routeIs('admin.attendance-monitoring.*') ? 'bg-blue-600 text-white shadow-xs' : 'bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 group-hover:bg-white dark:group-hover:bg-slate-700 group-hover:text-slate-800 dark:group-hover:text-slate-100 border border-slate-200/60 dark:border-slate-700/60' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                        </span>
                        <span class="truncate">Monitoring Absensi</span>
                    </a>

                    <!-- Monitoring GPS -->
                    <a href="{{ route('admin.gps-monitoring.index') }}" 
                       class="group flex items-center gap-3 px-2.5 py-2 rounded-xl transition-all duration-150 {{ request()->routeIs('admin.gps-monitoring.*') ? 'bg-blue-50/90 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 font-semibold border border-blue-200/70 dark:border-blue-800/50 shadow-xs' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100/70 dark:hover:bg-slate-800 font-medium' }}">
                        <span class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 transition-colors {{ request()->routeIs('admin.gps-monitoring.*') ? 'bg-blue-600 text-white shadow-xs' : 'bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 group-hover:bg-white dark:group-hover:bg-slate-700 group-hover:text-slate-800 dark:group-hover:text-slate-100 border border-slate-200/60 dark:border-slate-700/60' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                            </svg>
                        </span>
                        <span class="truncate">Monitoring GPS</span>
                    </a>

                    <!-- Rekap Absensi -->
                    <a href="{{ route('admin.recap.index') }}" 
                       class="group flex items-center gap-3 px-2.5 py-2 rounded-xl transition-all duration-150 {{ request()->routeIs('admin.recap.*') ? 'bg-blue-50/90 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 font-semibold border border-blue-200/70 dark:border-blue-800/50 shadow-xs' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100/70 dark:hover:bg-slate-800 font-medium' }}">
                        <span class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 transition-colors {{ request()->routeIs('admin.recap.*') ? 'bg-blue-600 text-white shadow-xs' : 'bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 group-hover:bg-white dark:group-hover:bg-slate-700 group-hover:text-slate-800 dark:group-hover:text-slate-100 border border-slate-200/60 dark:border-slate-700/60' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </span>
                        <span class="truncate">Rekap Absensi</span>
                    </a>
                </div>
            </div>

            <!-- SECTION: LAPORAN & IZIN -->
            <div>
                <p class="px-3 mb-2 text-[10px] font-bold tracking-wider text-slate-400 dark:text-slate-500 uppercase">
                    Laporan & Izin
                </p>
                <div class="space-y-1">
                    <!-- Laporan -->
                    <a href="{{ route('admin.reports.index') }}" 
                       class="group flex items-center gap-3 px-2.5 py-2 rounded-xl transition-all duration-150 {{ request()->routeIs('admin.reports.*') ? 'bg-blue-50/90 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 font-semibold border border-blue-200/70 dark:border-blue-800/50 shadow-xs' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100/70 dark:hover:bg-slate-800 font-medium' }}">
                        <span class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 transition-colors {{ request()->routeIs('admin.reports.*') ? 'bg-blue-600 text-white shadow-xs' : 'bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 group-hover:bg-white dark:group-hover:bg-slate-700 group-hover:text-slate-800 dark:group-hover:text-slate-100 border border-slate-200/60 dark:border-slate-700/60' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </span>
                        <span class="truncate">Laporan Presensi</span>
                    </a>

                    <!-- Persetujuan Izin -->
                    <a href="{{ route('admin.leave-requests.index') }}" 
                       class="group flex items-center gap-3 px-2.5 py-2 rounded-xl transition-all duration-150 {{ request()->routeIs('admin.leave-requests.*') ? 'bg-blue-50/90 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 font-semibold border border-blue-200/70 dark:border-blue-800/50 shadow-xs' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100/70 dark:hover:bg-slate-800 font-medium' }}">
                        <span class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 transition-colors {{ request()->routeIs('admin.leave-requests.*') ? 'bg-blue-600 text-white shadow-xs' : 'bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 group-hover:bg-white dark:group-hover:bg-slate-700 group-hover:text-slate-800 dark:group-hover:text-slate-100 border border-slate-200/60 dark:border-slate-700/60' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </span>
                        <span class="truncate">Persetujuan Izin</span>
                    </a>
                </div>
            </div>
        @else
            <!-- PPPK MENU -->
            <!-- SECTION: UTAMA -->
            <div>
                <p class="px-3 mb-2 text-[10px] font-bold tracking-wider text-slate-400 dark:text-slate-500 uppercase">
                    Menu Utama
                </p>
                <div class="space-y-1">
                    <a href="{{ route('dashboard') }}" 
                       class="group flex items-center gap-3 px-2.5 py-2 rounded-xl transition-all duration-150 {{ request()->routeIs('dashboard') ? 'bg-blue-50/90 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 font-semibold border border-blue-200/70 dark:border-blue-800/50 shadow-xs' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100/70 dark:hover:bg-slate-800 font-medium' }}">
                        <span class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 transition-colors {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white shadow-xs' : 'bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 group-hover:bg-white dark:group-hover:bg-slate-700 group-hover:text-slate-800 dark:group-hover:text-slate-100 border border-slate-200/60 dark:border-slate-700/60' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                        </span>
                        <span class="truncate">Dashboard</span>
                    </a>
                </div>
            </div>

            <!-- SECTION: PRESENSI & AKTIVITAS -->
            <div>
                <p class="px-3 mb-2 text-[10px] font-bold tracking-wider text-slate-400 dark:text-slate-500 uppercase">
                    Layanan Presensi
                </p>
                <div class="space-y-1">
                    <!-- Rekam Presensi -->
                    <a href="{{ route('attendance.index') }}" 
                       class="group flex items-center gap-3 px-2.5 py-2 rounded-xl transition-all duration-150 {{ request()->routeIs('attendance.index') ? 'bg-blue-50/90 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 font-semibold border border-blue-200/70 dark:border-blue-800/50 shadow-xs' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100/70 dark:hover:bg-slate-800 font-medium' }}">
                        <span class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 transition-colors {{ request()->routeIs('attendance.index') ? 'bg-blue-600 text-white shadow-xs' : 'bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 group-hover:bg-white dark:group-hover:bg-slate-700 group-hover:text-slate-800 dark:group-hover:text-slate-100 border border-slate-200/60 dark:border-slate-700/60' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </span>
                        <span class="truncate">Presensi Masuk / Pulang</span>
                    </a>

                    <!-- Riwayat Presensi -->
                    <a href="{{ route('attendance.history.index') }}" 
                       class="group flex items-center gap-3 px-2.5 py-2 rounded-xl transition-all duration-150 {{ request()->routeIs('attendance.history.*') ? 'bg-blue-50/90 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 font-semibold border border-blue-200/70 dark:border-blue-800/50 shadow-xs' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100/70 dark:hover:bg-slate-800 font-medium' }}">
                        <span class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 transition-colors {{ request()->routeIs('attendance.history.*') ? 'bg-blue-600 text-white shadow-xs' : 'bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 group-hover:bg-white dark:group-hover:bg-slate-700 group-hover:text-slate-800 dark:group-hover:text-slate-100 border border-slate-200/60 dark:border-slate-700/60' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </span>
                        <span class="truncate">Riwayat Presensi</span>
                    </a>

                    <!-- Pengajuan Izin -->
                    <a href="{{ route('leave-requests.index') }}" 
                       class="group flex items-center gap-3 px-2.5 py-2 rounded-xl transition-all duration-150 {{ request()->routeIs('leave-requests.*') ? 'bg-blue-50/90 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 font-semibold border border-blue-200/70 dark:border-blue-800/50 shadow-xs' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100/70 dark:hover:bg-slate-800 font-medium' }}">
                        <span class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 transition-colors {{ request()->routeIs('leave-requests.*') ? 'bg-blue-600 text-white shadow-xs' : 'bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 group-hover:bg-white dark:group-hover:bg-slate-700 group-hover:text-slate-800 dark:group-hover:text-slate-100 border border-slate-200/60 dark:border-slate-700/60' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </span>
                        <span class="truncate">Pengajuan Izin</span>
                    </a>
                </div>
            </div>
        @endif
    </nav>

    <!-- Footer of Sidebar -->
    <div class="p-3 border-t border-slate-100 dark:border-slate-800">
        <div class="px-3 py-2 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200/60 dark:border-slate-700/50 flex items-center gap-2.5">
            <span class="w-2 h-2 rounded-full bg-emerald-500 shrink-0 animate-pulse"></span>
            <div class="min-w-0 flex-1">
                <p class="text-[11px] font-semibold text-slate-700 dark:text-slate-300 truncate leading-none">
                    Sistem Absensi Online
                </p>
                <p class="text-[10px] text-slate-400 dark:text-slate-500 truncate mt-1">
                    v1.0 &bull; Disarpus Subang
                </p>
            </div>
        </div>
    </div>
</aside>