<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Pengajuan Izin / Sakit / Cuti') }}
                </h2>
                <p class="text-xs text-gray-500 mt-1">Kelola permohonan izin dan pantau status persetujuan dari admin.</p>
            </div>
            <a href="{{ route('leave-requests.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow transition">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Ajukan Permohonan Baru
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Flash Alert -->
            @if(session('success'))
                <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium flex items-center">
                    <svg class="w-5 h-5 mr-2 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            <!-- Tabel Riwayat Pengajuan -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50 text-gray-500 uppercase text-xs tracking-wider">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left font-semibold">Jenis Pengajuan</th>
                                <th scope="col" class="px-6 py-3 text-left font-semibold">Rentang Tanggal</th>
                                <th scope="col" class="px-6 py-3 text-left font-semibold">Alasan</th>
                                <th scope="col" class="px-6 py-3 text-center font-semibold">Dokumen</th>
                                <th scope="col" class="px-6 py-3 text-center font-semibold">Status</th>
                                <th scope="col" class="px-6 py-3 text-right font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($leaveRequests as $req)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $jenisBadge = match($req->jenis) {
                                                'izin' => 'bg-blue-100 text-blue-800 border-blue-200',
                                                'sakit' => 'bg-purple-100 text-purple-800 border-purple-200',
                                                'cuti' => 'bg-cyan-100 text-cyan-800 border-cyan-200',
                                                'dinas' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
                                                default => 'bg-gray-100 text-gray-800 border-gray-200',
                                            };
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $jenisBadge }}">
                                            {{ ucfirst($req->jenis) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-700">
                                        <div class="font-semibold">{{ \Carbon\Carbon::parse($req->tanggal_mulai)->locale('id')->isoFormat('D MMM Y') }}</div>
                                        @if($req->tanggal_mulai != $req->tanggal_selesai)
                                            <div class="text-gray-400">s/d {{ \Carbon\Carbon::parse($req->tanggal_selesai)->locale('id')->isoFormat('D MMM Y') }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-xs text-gray-800 max-w-xs truncate">{{ $req->alasan }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-xs">
                                        @if($req->dokumen)
                                            <a href="{{ asset('storage/' . $req->dokumen) }}" target="_blank" class="inline-flex items-center text-indigo-600 hover:text-indigo-800 font-semibold">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                                </svg>
                                                Lihat
                                            </a>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if($req->status === 'disetujui')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                                Disetujui
                                            </span>
                                        @elseif($req->status === 'ditolak')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-800 border border-red-200">
                                                Ditolak
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-800 border border-amber-200">
                                                Menunggu
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-xs font-medium">
                                        <a href="{{ route('leave-requests.show', $req) }}" class="text-indigo-600 hover:text-indigo-800 font-semibold">
                                            Detail &rarr;
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                        Belum ada riwayat permohonan izin / sakit.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($leaveRequests->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $leaveRequests->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
