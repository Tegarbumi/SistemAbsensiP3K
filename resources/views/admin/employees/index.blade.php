<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Kelola Data PPPK') }}
                </h2>
                <p class="text-xs text-gray-500 mt-1">Dinas Kearsipan dan Perpustakaan Kabupaten Subang</p>
            </div>
            <a href="{{ route('admin.employees.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow transition">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah PPPK Baru
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Pesan Notifikasi Flash -->
            @if(session('success'))
                <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium flex items-center">
                    <svg class="w-5 h-5 mr-2 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-sm font-medium flex items-center">
                    <svg class="w-5 h-5 mr-2 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    {{ session('error') }}
                </div>
            @endif

            <!-- Form Filter & Pencarian -->
            <div class="bg-white dark:bg-slate-900 overflow-hidden shadow-sm sm:rounded-xl border border-gray-200 dark:border-slate-800 p-6 transition-colors duration-200">
                <form method="GET" action="{{ route('admin.employees.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 items-end">
                    <div>
                        <label for="search" class="block text-xs font-semibold text-gray-700 dark:text-slate-300 uppercase tracking-wider mb-1">Cari Nama / NIP</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Ketik nama atau NIP..." class="w-full rounded-lg border-gray-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label for="unit_id" class="block text-xs font-semibold text-gray-700 dark:text-slate-300 uppercase tracking-wider mb-1">Unit Kerja</label>
                        <select name="unit_id" id="unit_id" class="w-full rounded-lg border-gray-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Semua Unit Kerja</option>
                            @foreach($units as $unit)
                                <option value="{{ $unit->id }}" {{ (string)request('unit_id') === (string)$unit->id ? 'selected' : '' }}>
                                    {{ $unit->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="status" class="block text-xs font-semibold text-gray-700 dark:text-slate-300 uppercase tracking-wider mb-1">Status Kepegawaian</label>
                        <select name="status" id="status" class="w-full rounded-lg border-gray-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="all">Semua Status</option>
                            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow transition">
                            Cari
                        </button>
                        <a href="{{ route('admin.employees.index') }}" class="px-3 py-2 bg-gray-100 dark:bg-slate-800 hover:bg-gray-200 dark:hover:bg-slate-700 text-gray-700 dark:text-slate-200 text-sm font-semibold rounded-lg transition text-center border border-gray-200 dark:border-slate-700">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- Tabel Data PPPK -->
            <div class="bg-white dark:bg-slate-900 overflow-hidden shadow-sm sm:rounded-xl border border-gray-200 dark:border-slate-800 transition-colors duration-200">
                <div class="p-6 border-b border-gray-200 dark:border-slate-800 flex justify-between items-center">
                    <h3 class="text-base font-bold text-gray-900 dark:text-white">Daftar Pegawai PPPK</h3>
                    <span class="text-xs text-gray-500 dark:text-slate-400">Total: {{ $employees->total() }} pegawai</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-slate-800 text-sm text-left">
                        <thead class="bg-gray-50 dark:bg-slate-800/80 text-xs font-semibold text-gray-600 dark:text-slate-300 uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-3.5">Pegawai</th>
                                <th class="px-6 py-3.5">NIP / No PPPK</th>
                                <th class="px-6 py-3.5">Jabatan & Unit</th>
                                <th class="px-6 py-3.5">Akun Login</th>
                                <th class="px-6 py-3.5 text-center">Status</th>
                                <th class="px-6 py-3.5 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-slate-800 bg-white dark:bg-slate-900">
                            @forelse($employees as $emp)
                                <tr class="hover:bg-gray-50 dark:hover:bg-slate-800/50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center space-x-3">
                                            @if($emp->foto)
                                                <img src="{{ asset('storage/' . $emp->foto) }}" class="w-10 h-10 rounded-full object-cover border border-gray-200 dark:border-slate-700" alt="{{ $emp->nama }}">
                                            @else
                                                <div class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-400 flex items-center justify-center font-bold text-sm">
                                                    {{ strtoupper(substr($emp->nama, 0, 2)) }}
                                                </div>
                                            @endif
                                            <div>
                                                <div class="font-bold text-gray-900 dark:text-white">{{ $emp->nama }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-mono text-gray-900 dark:text-slate-200 text-xs">{{ $emp->nip }}</div>
                                        <div class="text-xs text-gray-500 dark:text-slate-400">{{ $emp->nomor_pppk ?: '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-gray-800 dark:text-slate-200 text-xs">{{ $emp->jabatan ?: '-' }}</div>
                                        <div class="text-xs text-gray-500 dark:text-slate-400">{{ $emp->unit->nama ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-600 dark:text-slate-400">
                                        {{ $emp->user->email ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-center whitespace-nowrap">
                                        @if($emp->status)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/60">
                                                Aktif
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 dark:bg-slate-800 text-gray-700 dark:text-slate-400 border border-gray-200 dark:border-slate-700">
                                                Nonaktif
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-xs">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <!-- Detail -->
                                            <a href="{{ route('admin.employees.show', $emp->id) }}" class="p-1.5 text-indigo-600 hover:bg-indigo-50 rounded" title="Lihat Detail">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </a>

                                            <!-- Edit -->
                                            <a href="{{ route('admin.employees.edit', $emp->id) }}" class="p-1.5 text-amber-600 hover:bg-amber-50 rounded" title="Edit Data">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </a>

                                            <!-- Toggle Status -->
                                            <form method="POST" action="{{ route('admin.employees.toggle-status', $emp->id) }}" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="p-1.5 {{ $emp->status ? 'text-gray-500 hover:bg-gray-100' : 'text-emerald-600 hover:bg-emerald-50' }} rounded" title="{{ $emp->status ? 'Nonaktifkan Pegawai' : 'Aktifkan Pegawai' }}" onclick="return confirm('Apakah Anda yakin ingin mengubah status kepegawaian {{ addslashes($emp->nama) }}?')">
                                                    @if($emp->status)
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                                                        </svg>
                                                    @else
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                    @endif
                                                </button>
                                            </form>

                                            <!-- Hapus -->
                                            <form method="POST" action="{{ route('admin.employees.destroy', $emp->id) }}" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1.5 text-red-600 hover:bg-red-50 rounded" title="Hapus Data" onclick="return confirm('Hapus pegawai {{ addslashes($emp->nama) }}? (Hanya dapat dihapus jika belum memiliki riwayat presensi)')">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                        Tidak ada data pegawai PPPK yang ditemukan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($employees->hasPages())
                    <div class="p-4 border-t border-gray-200">
                        {{ $employees->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
