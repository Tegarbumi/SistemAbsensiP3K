<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(Auth::user()->role === 'pppk')
                <!-- 1. Header Card Profil PPPK & Aksi Cepat -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200 p-6 md:p-8">
                    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                        <div class="space-y-1">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                Pegawai Pemerintah dengan Perjanjian Kerja (PPPK)
                            </span>
                            <h3 class="text-2xl font-extrabold text-gray-900 pt-1">
                                {{ $employee->nama ?? Auth::user()->name }}
                            </h3>
                            <p class="text-sm text-gray-600 font-medium">
                                NIP: {{ $employee->nip ?? '-' }} &bull; Jabatan: {{ $employee->jabatan ?? '-' }}
                            </p>
                            <p class="text-xs text-gray-500">
                                Unit Kerja: <span class="font-semibold text-gray-700">{{ $employee->unit->nama ?? '-' }}</span>
                            </p>
                        </div>

                        <!-- Tombol Menuju Absensi & Riwayat -->
                        <div class="flex flex-wrap gap-3 w-full md:w-auto">
                            <a href="{{ route('attendance.index') }}" class="flex-1 md:flex-none inline-flex items-center justify-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow transition">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                Buka Presensi (Kamera & GPS)
                            </a>
                            <a href="{{ route('attendance.history.index') }}" class="flex-1 md:flex-none inline-flex items-center justify-center px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-lg transition border border-gray-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Riwayat Presensi
                            </a>
                        </div>
                    </div>
                </div>

                <!-- 2. Kartu Status Absensi Hari Ini -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200 p-6">
                    <div class="flex items-center justify-between border-b border-gray-100 pb-4 mb-4">
                        <div>
                            <h4 class="text-base font-bold text-gray-900">Status Presensi Hari Ini</h4>
                            <p class="text-xs text-gray-500">{{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y') }}</p>
                        </div>
                        <div>
                            @if(!$todayAttendance)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-800">
                                    Belum Check-In
                                </span>
                            @elseif(!$todayAttendance->jam_pulang)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                                    Sudah Check-In
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800">
                                    Selesai Presensi Hari Ini
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-center sm:text-left">
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                            <span class="text-xs text-gray-500 block uppercase font-medium">Jam Masuk</span>
                            <span class="text-lg font-bold text-gray-900 mt-1 block font-mono">
                                {{ $todayAttendance->jam_masuk ?? '-' }}
                            </span>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                            <span class="text-xs text-gray-500 block uppercase font-medium">Jam Pulang</span>
                            <span class="text-lg font-bold text-gray-900 mt-1 block font-mono">
                                {{ $todayAttendance->jam_pulang ?? '-' }}
                            </span>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                            <span class="text-xs text-gray-500 block uppercase font-medium">Status Kehadiran</span>
                            <span class="text-lg font-bold text-indigo-700 mt-1 block">
                                {{ $todayAttendance ? ucfirst($todayAttendance->status) : 'Belum Ada Data' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- 3. Ringkasan Presensi Bulan Berjalan (Real Data Database) -->
                <div>
                    <div class="flex items-center justify-between mb-3 px-1">
                        <h4 class="text-sm font-bold text-gray-800 uppercase tracking-wider">
                            Rekapitulasi Bulan {{ $namaBulan }}
                        </h4>
                        <span class="text-xs text-gray-500 font-medium">Akumulasi otomatis dari database</span>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-7 gap-3">
                        <!-- Hadir -->
                        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm text-center">
                            <span class="text-xs font-medium text-emerald-600 uppercase block mb-1">Hadir</span>
                            <span class="text-2xl font-extrabold text-emerald-700">{{ $stats['hadir'] }}</span>
                            <span class="text-[10px] text-gray-400 block mt-0.5">Hari</span>
                        </div>

                        <!-- Terlambat -->
                        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm text-center">
                            <span class="text-xs font-medium text-amber-600 uppercase block mb-1">Terlambat</span>
                            <span class="text-2xl font-extrabold text-amber-600">{{ $stats['terlambat'] }}</span>
                            <span class="text-[10px] text-gray-400 block mt-0.5">Hari</span>
                        </div>

                        <!-- Izin -->
                        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm text-center">
                            <span class="text-xs font-medium text-blue-600 uppercase block mb-1">Izin</span>
                            <span class="text-2xl font-extrabold text-blue-600">{{ $stats['izin'] }}</span>
                            <span class="text-[10px] text-gray-400 block mt-0.5">Hari</span>
                        </div>

                        <!-- Sakit -->
                        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm text-center">
                            <span class="text-xs font-medium text-purple-600 uppercase block mb-1">Sakit</span>
                            <span class="text-2xl font-extrabold text-purple-600">{{ $stats['sakit'] }}</span>
                            <span class="text-[10px] text-gray-400 block mt-0.5">Hari</span>
                        </div>

                        <!-- Dinas -->
                        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm text-center">
                            <span class="text-xs font-medium text-indigo-600 uppercase block mb-1">Dinas</span>
                            <span class="text-2xl font-extrabold text-indigo-600">{{ $stats['dinas'] }}</span>
                            <span class="text-[10px] text-gray-400 block mt-0.5">Hari</span>
                        </div>

                        <!-- Cuti -->
                        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm text-center">
                            <span class="text-xs font-medium text-cyan-600 uppercase block mb-1">Cuti</span>
                            <span class="text-2xl font-extrabold text-cyan-600">{{ $stats['cuti'] }}</span>
                            <span class="text-[10px] text-gray-400 block mt-0.5">Hari</span>
                        </div>

                        <!-- Alpha -->
                        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm text-center col-span-2 sm:col-span-1">
                            <span class="text-xs font-medium text-rose-600 uppercase block mb-1">Alpha</span>
                            <span class="text-2xl font-extrabold text-rose-600">{{ $stats['alpha'] }}</span>
                            <span class="text-[10px] text-gray-400 block mt-0.5">Hari</span>
                        </div>
                    </div>
                </div>

            @else
                <!-- DASHBOARD ADMINISTRATOR -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200 p-6 md:p-8">
                    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 pb-6 border-b border-gray-200 mb-6">
                        <div>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">
                                Panel Administrator
                            </span>
                            <h3 class="text-2xl font-extrabold text-gray-900 mt-2">
                                Selamat Datang, {{ Auth::user()->name }}
                            </h3>
                            <p class="text-sm text-gray-600">
                                Dinas Kearsipan dan Perpustakaan Kabupaten Subang
                            </p>
                        </div>
                        <div>
                            <a href="{{ route('admin.employees.index') }}" class="inline-flex items-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow transition">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                                Kelola Data PPPK
                            </a>
                        </div>
                    </div>

                    <!-- Statistik Instansi Hari Ini -->
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 text-center">
                            <span class="text-xs text-gray-500 font-medium block">Total PPPK</span>
                            <span class="text-2xl font-bold text-gray-900 mt-1 block">{{ $adminStats['total_pppk'] }}</span>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 text-center">
                            <span class="text-xs text-gray-500 font-medium block">PPPK Aktif</span>
                            <span class="text-2xl font-bold text-emerald-600 mt-1 block">{{ $adminStats['pppk_aktif'] }}</span>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 text-center">
                            <span class="text-xs text-gray-500 font-medium block">Total Unit</span>
                            <span class="text-2xl font-bold text-indigo-600 mt-1 block">{{ $adminStats['total_unit'] }}</span>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 text-center">
                            <span class="text-xs text-gray-500 font-medium block">Hadir Hari Ini</span>
                            <span class="text-2xl font-bold text-green-600 mt-1 block">{{ $adminStats['hadir_hari_ini'] }}</span>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 text-center">
                            <span class="text-xs text-gray-500 font-medium block">Terlambat Hari Ini</span>
                            <span class="text-2xl font-bold text-amber-600 mt-1 block">{{ $adminStats['terlambat_hari_ini'] }}</span>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
