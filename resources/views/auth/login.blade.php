<x-guest-layout>
    <div class="w-full max-w-md bg-white dark:bg-slate-900 p-6 sm:p-8 rounded-lg border border-slate-300 dark:border-slate-800 shadow-xs transition-colors duration-150">
        
        <!-- Header Card: Logo & Identitas Resmi -->
        <div class="text-center mb-6">
            <div class="w-14 h-14 rounded-md bg-slate-50 dark:bg-slate-800 p-1.5 border border-slate-200 dark:border-slate-700 mx-auto flex items-center justify-center mb-3">
                <img src="{{ asset('image/logo.jpg') }}" alt="Logo Subang" class="w-full h-full object-contain rounded">
            </div>
            <p class="text-xs font-semibold tracking-wider text-slate-500 dark:text-slate-400 uppercase">
                Pemerintah Kabupaten Subang
            </p>
            <h2 class="text-xl font-bold text-slate-900 dark:text-white mt-1 tracking-tight">
                Masuk ke Portal Presensi
            </h2>
            <p class="text-xs text-slate-600 dark:text-slate-400 mt-1">
                Dinas Kearsipan dan Perpustakaan Kab. Subang
            </p>
        </div>

        <!-- Session Status / Flash Alert -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- Form Login -->
        <form method="POST" action="{{ route('login') }}" class="space-y-4" x-data="{ showPass: false }">
            @csrf

            <!-- Email Address atau NIP -->
            <div class="space-y-1">
                <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                    Alamat Email / NIP
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206"/>
                        </svg>
                    </div>
                    <input 
                        id="email" 
                        type="text" 
                        name="email" 
                        value="{{ old('email') }}" 
                        required 
                        autofocus 
                        autocomplete="username" 
                        placeholder="nama@subang.go.id atau NIP" 
                        class="w-full pl-9 pr-3 py-2.5 rounded-md border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm placeholder-slate-400 focus:ring-1 focus:ring-gov-blue focus:border-gov-blue transition-colors">
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs text-gov-red" />
            </div>

            <!-- Password -->
            <div class="space-y-1">
                <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                    Kata Sandi
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <input 
                        id="password" 
                        :type="showPass ? 'text' : 'password'" 
                        name="password" 
                        required 
                        autocomplete="current-password" 
                        placeholder="••••••••" 
                        class="w-full pl-9 pr-10 py-2.5 rounded-md border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm placeholder-slate-400 focus:ring-1 focus:ring-gov-blue focus:border-gov-blue transition-colors">
                    
                    <!-- Toggle Show/Hide Password Eye Button -->
                    <button 
                        type="button" 
                        @click="showPass = !showPass" 
                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 focus:outline-none" 
                        tabindex="-1"
                        aria-label="Tampilkan / Sembunyikan Kata Sandi">
                        <svg x-show="!showPass" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <svg x-show="showPass" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                        </svg>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs text-gov-red" />
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="flex items-center justify-between text-xs pt-1">
                <label for="remember_me" class="inline-flex items-center cursor-pointer select-none">
                    <input 
                        id="remember_me" 
                        type="checkbox" 
                        name="remember" 
                        class="w-4 h-4 rounded border-slate-300 dark:border-slate-700 text-gov-blue focus:ring-gov-blue dark:bg-slate-800">
                    <span class="ms-2 text-slate-600 dark:text-slate-400">Ingat Saya</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="text-gov-blue hover:underline text-xs font-medium transition-colors" href="{{ route('password.request') }}">
                        Lupa kata sandi?
                    </a>
                @endif
            </div>

            <!-- Submit Button -->
            <div class="pt-2">
                <button 
                    type="submit" 
                    class="w-full py-2.5 px-4 bg-gov-blue hover:bg-gov-blue-hover active:bg-gov-blue-hover text-white font-semibold text-sm rounded-md shadow-xs transition-colors flex items-center justify-center gap-2">
                    <span>Masuk ke Akun</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </button>
            </div>
        </form>
    </div>
</x-guest-layout>
