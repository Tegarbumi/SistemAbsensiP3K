<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-lg text-slate-900 dark:text-white tracking-tight">
                    {{ __('Kelola Data PPPK') }}
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Dinas Kearsipan dan Perpustakaan Kabupaten Subang</p>
            </div>
            <a href="{{ route('admin.employees.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs sm:text-sm font-semibold rounded-lg shadow-xs transition">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>Tambah PPPK Baru</span>
            </a>
        </div>
    </x-slot>

    <div class="py-6 sm:py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Pesan Notifikasi Flash -->
            @if(session('success'))
                <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200/80 dark:border-emerald-800/60 text-emerald-800 dark:text-emerald-300 text-xs sm:text-sm font-medium flex items-center gap-2.5 shadow-2xs">
                    <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200/80 dark:border-rose-800/60 text-rose-800 dark:text-rose-300 text-xs sm:text-sm font-medium flex items-center gap-2.5 shadow-2xs">
                    <svg class="w-5 h-5 text-rose-600 dark:text-rose-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <!-- Form Filter & Pencarian -->
            <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200/90 dark:border-slate-700/80 p-5 shadow-xs transition-colors duration-200">
                <form method="GET" action="{{ route('admin.employees.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 items-end">
                    <div>
                        <label for="search" class="block text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider mb-1.5">Cari Nama / NIP</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Ketik nama atau NIP..." class="w-full rounded-lg border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/60 text-slate-900 dark:text-white shadow-2xs text-xs sm:text-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20">
                    </div>

                    <div>
                        <label for="unit_id" class="block text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider mb-1.5">Unit Kerja</label>
                        <select name="unit_id" id="unit_id" class="w-full rounded-lg border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/60 text-slate-900 dark:text-white shadow-2xs text-xs sm:text-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20">
                            <option value="">Semua Unit Kerja</option>
                            @foreach($units as $unit)
                                <option value="{{ $unit->id }}" {{ (string)request('unit_id') === (string)$unit->id ? 'selected' : '' }}>
                                    {{ $unit->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="status" class="block text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider mb-1.5">Status Kepegawaian</label>
                        <select name="status" id="status" class="w-full rounded-lg border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/60 text-slate-900 dark:text-white shadow-2xs text-xs sm:text-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20">
                            <option value="all">Semua Status</option>
                            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs sm:text-sm font-semibold rounded-lg shadow-xs transition">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <span>Cari</span>
                        </button>
                        <a href="{{ route('admin.employees.index') }}" class="px-3.5 py-2 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 text-xs sm:text-sm font-semibold rounded-lg transition text-center border border-slate-200 dark:border-slate-600">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- Tabel Data PPPK -->
            <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200/90 dark:border-slate-700/80 shadow-xs overflow-hidden transition-colors duration-200">
                <div class="p-5 border-b border-slate-100 dark:border-slate-700/80 flex justify-between items-center">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white">Daftar Pegawai PPPK</h3>
                        <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">Seluruh tenaga PPPK resmi terdaftar</p>
                    </div>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-semibold bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-600">
                        Total: {{ $employees->total() }} pegawai
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700 text-xs sm:text-sm text-left">
                        <thead class="bg-slate-50 dark:bg-slate-800/90 text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                            <tr>
                                <th class="px-5 py-3.5">Pegawai</th>
                                <th class="px-5 py-3.5">NIP / No PPPK</th>
                                <th class="px-5 py-3.5">Jabatan & Unit</th>
                                <th class="px-5 py-3.5">Akun Login</th>
                                <th class="px-5 py-3.5 text-center">Status</th>
                                <th class="px-5 py-3.5 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700/60 bg-white dark:bg-slate-800">
                            @forelse($employees as $emp)
                                <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-700/40 transition">
                                    <td class="px-5 py-3.5 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            @if($emp->foto)
                                                <img src="{{ asset('storage/' . $emp->foto) }}" class="w-9 h-9 rounded-lg object-cover border border-slate-200 dark:border-slate-700 shrink-0" alt="{{ $emp->nama }}">
                                            @else
                                                <div class="w-9 h-9 rounded-lg bg-blue-50 dark:bg-slate-700 text-blue-700 dark:text-blue-300 border border-blue-100 dark:border-slate-600 flex items-center justify-center font-bold text-xs shrink-0">
                                                    {{ strtoupper(substr($emp->nama, 0, 2)) }}
                                                </div>
                                            @endif
                                            <div class="min-w-0">
                                                <div class="font-bold text-slate-900 dark:text-white truncate">{{ $emp->nama }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-5 py-3.5 whitespace-nowrap">
                                        <div class="font-mono text-slate-800 dark:text-slate-200 font-semibold text-xs">{{ $emp->nip }}</div>
                                        <div class="text-[11px] text-slate-400 mt-0.5">{{ $emp->nomor_pppk ?: '-' }}</div>
                                    </td>
                                    <td class="px-5 py-3.5">
                                        <div class="font-semibold text-slate-800 dark:text-slate-200 text-xs">{{ $emp->jabatan ?: '-' }}</div>
                                        <div class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">{{ $emp->unit->nama ?? '-' }}</div>
                                    </td>
                                    <td class="px-5 py-3.5 whitespace-nowrap text-xs text-slate-600 dark:text-slate-400">
                                        {{ $emp->user->email ?? '-' }}
                                    </td>
                                    <td class="px-5 py-3.5 text-center whitespace-nowrap">
                                        @if($emp->status)
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/60">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                Aktif
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 border border-slate-200 dark:border-slate-600">
                                                <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                                Nonaktif
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3.5 whitespace-nowrap text-center text-xs">
                                        <div class="inline-flex items-center gap-1.5">
                                            <!-- Detail -->
                                            <a href="{{ route('admin.employees.show', $emp->id) }}" class="w-8 h-8 rounded-lg flex items-center justify-center text-blue-600 hover:text-blue-700 hover:bg-blue-50 dark:hover:bg-blue-950/50 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 transition shadow-2xs" title="Lihat Detail">
                                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </a>

                                            <!-- Edit -->
                                            <a href="{{ route('admin.employees.edit', $emp->id) }}" class="w-8 h-8 rounded-lg flex items-center justify-center text-amber-600 hover:text-amber-700 hover:bg-amber-50 dark:hover:bg-amber-950/50 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 transition shadow-2xs" title="Edit Data">
                                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </a>

                                            <!-- Toggle Status -->
                                            <form method="POST" action="{{ route('admin.employees.toggle-status', $emp->id) }}" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="w-8 h-8 rounded-lg flex items-center justify-center {{ $emp->status ? 'text-slate-500 hover:text-slate-800 hover:bg-slate-100' : 'text-emerald-600 hover:text-emerald-700 hover:bg-emerald-50' }} border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 transition shadow-2xs" title="{{ $emp->status ? 'Nonaktifkan Pegawai' : 'Aktifkan Pegawai' }}" onclick="return confirm('Apakah Anda yakin ingin mengubah status kepegawaian {{ addslashes($emp->nama) }}?')">
                                                    @if($emp->status)
                                                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                                                        </svg>
                                                    @else
                                                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                    @endif
                                                </button>
                                            </form>

                                            <!-- Hapus -->
                                            <form method="POST" action="{{ route('admin.employees.destroy', $emp->id) }}" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-8 h-8 rounded-lg flex items-center justify-center text-rose-600 hover:text-rose-700 hover:bg-rose-50 dark:hover:bg-rose-950/50 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 transition shadow-2xs" title="Hapus Data" onclick="return confirm('Hapus pegawai {{ addslashes($emp->nama) }}? (Hanya dapat dihapus jika belum memiliki riwayat presensi)')">
                                                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-slate-500 dark:text-slate-400">
                                        Tidak ada data pegawai PPPK yang ditemukan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($employees->hasPages())
                    <div class="p-4 border-t border-slate-200 dark:border-slate-700">
                        {{ $employees->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>