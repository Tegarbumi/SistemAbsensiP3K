<!-- Sidebar Container -->
<aside class="flex flex-col h-full bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800 shadow-sm select-none transition-colors duration-200">
    <!-- Brand / Header -->
    <div class="flex items-center gap-3 px-4 py-4 border-b border-slate-100 dark:border-slate-800">
        <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-slate-800 p-1 flex items-center justify-center ring-1 ring-indigo-100 dark:ring-slate-700 flex-shrink-0 shadow-xs">
            <img src="{{ asset('image/logo.jpg') }}" alt="Logo" class="w-full h-full object-contain rounded-lg">
        </div>
        <div class="min-w-0 flex-1">
            <h1 class="text-sm font-bold text-slate-900 dark:text-white tracking-tight leading-tight truncate">
                Absensi P3K Disarpus
            </h1>
            <p class="text-[11px] text-slate-500 dark:text-slate-400 font-medium truncate">
                Kabupaten Subang
            </p>
        </div>
        <!-- Hamburger / Toggle Button inside Sidebar Header -->
        <button 
            type="button" 
            @click="toggle()" 
            class="p-2 rounded-xl text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 active:bg-slate-200 dark:active:bg-slate-700 transition-colors focus:outline-none flex-shrink-0"
            title="Tutup / Sembunyikan Sidebar"
            aria-label="Toggle Sidebar"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
    </div>

    <!-- Navigation Menu Items -->
    <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-6 text-sm">
        @if(Auth::user()->role === 'admin')
            <!-- SECTION: UTAMA -->
            <div>
                <p class="px-3 mb-2 text-[11px] font-bold tracking-wider text-slate-400 dark:text-slate-500 uppercase">
                    Menu Utama
                </p>
                <div class="space-y-1">
                    <a href="{{ route('dashboard') }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-xl transition-all duration-150 {{ request()->routeIs('dashboard') ? 'bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-400 font-semibold border border-indigo-100/80 dark:border-indigo-900/50 shadow-xs' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-slate-800/80 font-medium' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('dashboard') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span>Dashboard</span>
                    </a>
                </div>
            </div>

            <!-- SECTION: DATA MASTER -->
            <div>
                <p class="px-3 mb-2 text-[11px] font-bold tracking-wider text-slate-400 dark:text-slate-500 uppercase">
                    Data Master
                </p>
                <div class="space-y-1">
                    <!-- Kelola PPPK -->
                    <a href="{{ route('admin.employees.index') }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-xl transition-all duration-150 {{ request()->routeIs('admin.employees.*') ? 'bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-400 font-semibold border border-indigo-100/80 dark:border-indigo-900/50 shadow-xs' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-slate-800/80 font-medium' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('admin.employees.*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <span>Kelola PPPK</span>
                    </a>

                    <!-- Unit Kerja -->
                    <a href="{{ route('admin.units.index') }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-xl transition-all duration-150 {{ request()->routeIs('admin.units.*') ? 'bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-400 font-semibold border border-indigo-100/80 dark:border-indigo-900/50 shadow-xs' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-slate-800/80 font-medium' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('admin.units.*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        <span>Unit Kerja</span>
                    </a>

                    <!-- Lokasi Kantor -->
                    <a href="{{ route('admin.office-locations.index') }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-xl transition-all duration-150 {{ request()->routeIs('admin.office-locations.*') ? 'bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-400 font-semibold border border-indigo-100/80 dark:border-indigo-900/50 shadow-xs' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-slate-800/80 font-medium' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('admin.office-locations.*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span>Lokasi Kantor</span>
                    </a>

                    <!-- Jadwal Kerja -->
                    <a href="{{ route('admin.work-schedules.index') }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-xl transition-all duration-150 {{ request()->routeIs('admin.work-schedules.*') ? 'bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-400 font-semibold border border-indigo-100/80 dark:border-indigo-900/50 shadow-xs' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-slate-800/80 font-medium' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('admin.work-schedules.*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Jadwal Kerja</span>
                    </a>
                </div>
            </div>

            <!-- SECTION: MONITORING & REKAP -->
            <div>
                <p class="px-3 mb-2 text-[11px] font-bold tracking-wider text-slate-400 dark:text-slate-500 uppercase">
                    Monitoring & Presensi
                </p>
                <div class="space-y-1">
                    <!-- Monitoring Absensi -->
                    <a href="{{ route('admin.attendance-monitoring.index') }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-xl transition-all duration-150 {{ request()->routeIs('admin.attendance-monitoring.*') ? 'bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-400 font-semibold border border-indigo-100/80 dark:border-indigo-900/50 shadow-xs' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-slate-800/80 font-medium' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('admin.attendance-monitoring.*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                        <span>Monitoring Absensi</span>
                    </a>

                    <!-- Monitoring GPS -->
                    <a href="{{ route('admin.gps-monitoring.index') }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-xl transition-all duration-150 {{ request()->routeIs('admin.gps-monitoring.*') ? 'bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-400 font-semibold border border-indigo-100/80 dark:border-indigo-900/50 shadow-xs' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-slate-800/80 font-medium' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('admin.gps-monitoring.*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                        </svg>
                        <span>Monitoring GPS</span>
                    </a>

                    <!-- Rekap Absensi -->
                    <a href="{{ route('admin.recap.index') }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-xl transition-all duration-150 {{ request()->routeIs('admin.recap.*') ? 'bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-400 font-semibold border border-indigo-100/80 dark:border-indigo-900/50 shadow-xs' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-slate-800/80 font-medium' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('admin.recap.*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span>Rekap Absensi</span>
                    </a>
                </div>
            </div>

            <!-- SECTION: LAPORAN & IZIN -->
            <div>
                <p class="px-3 mb-2 text-[11px] font-bold tracking-wider text-slate-400 dark:text-slate-500 uppercase">
                    Laporan & Izin
                </p>
                <div class="space-y-1">
                    <!-- Laporan -->
                    <a href="{{ route('admin.reports.index') }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-xl transition-all duration-150 {{ request()->routeIs('admin.reports.*') ? 'bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-400 font-semibold border border-indigo-100/80 dark:border-indigo-900/50 shadow-xs' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-slate-800/80 font-medium' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('admin.reports.*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span>Laporan Presensi</span>
                    </a>

                    <!-- Persetujuan Izin -->
                    <a href="{{ route('admin.leave-requests.index') }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-xl transition-all duration-150 {{ request()->routeIs('admin.leave-requests.*') ? 'bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-400 font-semibold border border-indigo-100/80 dark:border-indigo-900/50 shadow-xs' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-slate-800/80 font-medium' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('admin.leave-requests.*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        <span>Persetujuan Izin</span>
                    </a>
                </div>
            </div>
        @else
            <!-- PPPK MENU -->
            <!-- SECTION: UTAMA -->
            <div>
                <p class="px-3 mb-2 text-[11px] font-bold tracking-wider text-slate-400 dark:text-slate-500 uppercase">
                    Menu Utama
                </p>
                <div class="space-y-1">
                    <a href="{{ route('dashboard') }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-xl transition-all duration-150 {{ request()->routeIs('dashboard') ? 'bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-400 font-semibold border border-indigo-100/80 dark:border-indigo-900/50 shadow-xs' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-slate-800/80 font-medium' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('dashboard') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span>Dashboard</span>
                    </a>
                </div>
            </div>

            <!-- SECTION: PRESENSI & AKTIVITAS -->
            <div>
                <p class="px-3 mb-2 text-[11px] font-bold tracking-wider text-slate-400 dark:text-slate-500 uppercase">
                    Layanan Presensi
                </p>
                <div class="space-y-1">
                    <!-- Rekam Presensi -->
                    <a href="{{ route('attendance.index') }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-xl transition-all duration-150 {{ request()->routeIs('attendance.index') ? 'bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-400 font-semibold border border-indigo-100/80 dark:border-indigo-900/50 shadow-xs' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-slate-800/80 font-medium' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('attendance.index') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span>Presensi Masuk / Pulang</span>
                    </a>

                    <!-- Riwayat Presensi -->
                    <a href="{{ route('attendance.history.index') }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-xl transition-all duration-150 {{ request()->routeIs('attendance.history.*') ? 'bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-400 font-semibold border border-indigo-100/80 dark:border-indigo-900/50 shadow-xs' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-slate-800/80 font-medium' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('attendance.history.*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Riwayat Presensi</span>
                    </a>

                    <!-- Pengajuan Izin -->
                    <a href="{{ route('leave-requests.index') }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-xl transition-all duration-150 {{ request()->routeIs('leave-requests.*') ? 'bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-400 font-semibold border border-indigo-100/80 dark:border-indigo-900/50 shadow-xs' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-slate-800/80 font-medium' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('leave-requests.*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span>Pengajuan Izin</span>
                    </a>
                </div>
            </div>
        @endif
    </nav>
</aside>
