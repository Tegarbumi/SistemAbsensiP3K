<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Ekspor Laporan Rekapitulasi Absensi') }}
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Cetak dan ekspor data kehadiran pegawai PPPK dalam format PDF atau Excel / CSV
                </p>
            </div>
            <a href="{{ route('admin.recap.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium text-sm rounded-lg transition">
                <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Rekap
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Form Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 sm:p-8">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4 border-b pb-3 dark:border-gray-700">
                    Parameter Laporan
                </h3>

                <form id="reportForm" method="GET" class="space-y-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <!-- Periode Bulan -->
                        <div>
                            <label for="month" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Bulan
                            </label>
                            <select name="month" id="month" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @foreach($months as $num => $name)
                                    <option value="{{ $num }}" {{ $selectedMonth == $num ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Periode Tahun -->
                        <div>
                            <label for="year" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Tahun
                            </label>
                            <select name="year" id="year" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @for($y = now()->year - 2; $y <= now()->year + 1; $y++)
                                    <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>
                                        {{ $y }}
                                    </option>
                                @endfor
                            </select>
                        </div>

                        <!-- Unit Kerja -->
                        <div>
                            <label for="unit_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Unit Kerja
                            </label>
                            <select name="unit_id" id="unit_id" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Semua Unit Kerja</option>
                                @foreach($units as $unit)
                                    <option value="{{ $unit->id }}" {{ $selectedUnitId == $unit->id ? 'selected' : '' }}>
                                        {{ $unit->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Pegawai Tertentu -->
                        <div>
                            <label for="employee_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Pegawai PPPK (Opsional)
                            </label>
                            <select name="employee_id" id="employee_id" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Semua Pegawai</option>
                                @foreach($employees as $emp)
                                    <option value="{{ $emp->id }}" {{ $selectedEmployeeId == $emp->id ? 'selected' : '' }}>
                                        {{ $emp->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Pilihan Tombol Ekspor -->
                    <div class="pt-6 border-t border-gray-100 dark:border-gray-700 flex flex-col sm:flex-row gap-4 justify-end">
                        <button type="button" onclick="submitExport('{{ route('admin.reports.export-pdf') }}')" class="inline-flex justify-center items-center px-6 py-3 bg-rose-600 hover:bg-rose-700 text-white font-medium text-sm rounded-lg shadow transition">
                            <svg class="w-5 h-5 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                            Unduh Dokumen PDF
                        </button>

                        <button type="button" onclick="submitExport('{{ route('admin.reports.export-excel') }}')" class="inline-flex justify-center items-center px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-medium text-sm rounded-lg shadow transition">
                            <svg class="w-5 h-5 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Unduh File Excel (CSV)
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function submitExport(url) {
            const form = document.getElementById('reportForm');
            form.action = url;
            form.submit();
        }
    </script>
</x-app-layout>
