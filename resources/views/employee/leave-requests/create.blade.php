<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Formulir Pengajuan Izin / Sakit') }}
                </h2>
                <p class="text-xs text-gray-500 mt-1">Isi formulir secara lengkap dan lampirkan dokumen pendukung jika diperlukan.</p>
            </div>
            <a href="{{ route('leave-requests.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-lg transition">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Riwayat
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200 p-6 sm:p-8">
                <form action="{{ route('leave-requests.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <!-- Jenis Pengajuan -->
                    <div>
                        <label for="jenis" class="block text-sm font-semibold text-gray-700">
                            Jenis Pengajuan <span class="text-red-500">*</span>
                        </label>
                        <select name="jenis" id="jenis" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500 @error('jenis') border-red-500 @enderror">
                            <option value="">Pilih Jenis Pengajuan</option>
                            <option value="izin" {{ old('jenis') === 'izin' ? 'selected' : '' }}>Izin Keperluan Pribadi / Keluarga</option>
                            <option value="sakit" {{ old('jenis') === 'sakit' ? 'selected' : '' }}>Sakit</option>
                            <option value="cuti" {{ old('jenis') === 'cuti' ? 'selected' : '' }}>Cuti Resmi</option>
                            <option value="dinas" {{ old('jenis') === 'dinas' ? 'selected' : '' }}>Dinas Luar Kantor</option>
                        </select>
                        @error('jenis')
                            <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Rentang Tanggal -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="tanggal_mulai" class="block text-sm font-semibold text-gray-700">
                                Tanggal Mulai <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ old('tanggal_mulai', date('Y-m-d')) }}" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500 @error('tanggal_mulai') border-red-500 @enderror">
                            @error('tanggal_mulai')
                                <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="tanggal_selesai" class="block text-sm font-semibold text-gray-700">
                                Tanggal Selesai <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="tanggal_selesai" id="tanggal_selesai" value="{{ old('tanggal_selesai', date('Y-m-d')) }}" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500 @error('tanggal_selesai') border-red-500 @enderror">
                            @error('tanggal_selesai')
                                <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Alasan / Keterangan -->
                    <div>
                        <label for="alasan" class="block text-sm font-semibold text-gray-700">
                            Alasan / Keterangan Pengajuan <span class="text-red-500">*</span>
                        </label>
                        <textarea name="alasan" id="alasan" rows="4" required placeholder="Jelaskan alasan pengajuan izin atau kondisi sakit Anda..." class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500 @error('alasan') border-red-500 @enderror">{{ old('alasan') }}</textarea>
                        @error('alasan')
                            <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Dokumen Pendukung -->
                    <div>
                        <label for="dokumen" class="block text-sm font-semibold text-gray-700">
                            Dokumen / Berkas Pendukung (Opsional)
                        </label>
                        <input type="file" name="dokumen" id="dokumen" accept=".pdf,.jpg,.jpeg,.png" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition @error('dokumen') border-red-500 @enderror">
                        <p class="mt-1 text-xs text-gray-500">Format yang didukung: PDF, JPG, PNG. Maksimal ukuran berkas 2MB (contoh: Surat Dokter, Surat Tugas).</p>
                        @error('dokumen')
                            <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                        <a href="{{ route('leave-requests.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                            Batal
                        </a>
                        <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow transition">
                            Kirim Permohonan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
