<x-guest-layout>
    <div class="w-full max-w-md bg-white/95 dark:bg-slate-900/90 backdrop-blur-md p-8 sm:p-10 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-xl shadow-slate-200/50 dark:shadow-black/40 transition-all duration-200">
        
        <!-- Header Card: Logo & Selamat Datang -->
        <div class="text-center mb-7">
            <div class="w-14 h-14 rounded-2xl bg-indigo-50 dark:bg-slate-800 p-2 shadow-xs ring-1 ring-indigo-100 dark:ring-slate-700 mx-auto flex items-center justify-center mb-4">
                <img src="{{ asset('image/logo.jpg') }}" alt="Logo Subang" class="w-full h-full object-contain rounded-xl">
            </div>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">
                Selamat Datang
            </h2>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1.5 leading-relaxed">
                Silakan masuk ke Sistem Absensi P3K Disarpus Kab. Subang
            </p>
        </div>

        <!-- Session Status / Flash Alert -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- Form Login -->
        <form method="POST" action="{{ route('login') }}" class="space-y-5" x-data="{ showPass: false }">
            @csrf

            <!-- Email Address -->
            <div class="space-y-1.5">
                <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                    Alamat Email
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206"/>
                        </svg>
                    </div>
                    <input 
                        id="email" 
                        type="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        required 
                        autofocus 
                        autocomplete="username" 
                        placeholder="nama@subang.go.id" 
                        class="w-full pl-11 pr-4 py-3 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800/80 text-slate-900 dark:text-white text-sm placeholder-slate-400 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-colors shadow-2xs">
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs" />
            </div>

            <!-- Password -->
            <div class="space-y-1.5">
                <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                    Kata Sandi
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                        class="w-full pl-11 pr-11 py-3 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800/80 text-slate-900 dark:text-white text-sm placeholder-slate-400 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-colors shadow-2xs">
                    
                    <!-- Toggle Show/Hide Password Eye Button -->
                    <button 
                        type="button" 
                        @click="showPass = !showPass" 
                        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 focus:outline-none" 
                        tabindex="-1"
                        aria-label="Tampilkan / Sembunyikan Kata Sandi">
                        <svg x-show="!showPass" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <svg x-show="showPass" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                        </svg>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs" />
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="flex items-center justify-between text-xs pt-1">
                <label for="remember_me" class="inline-flex items-center cursor-pointer select-none">
                    <input 
                        id="remember_me" 
                        type="checkbox" 
                        name="remember" 
                        class="w-4 h-4 rounded border-slate-300 dark:border-slate-700 text-indigo-600 focus:ring-indigo-500/20 dark:bg-slate-800">
                    <span class="ms-2 text-slate-600 dark:text-slate-400 font-medium">Ingat Saya</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 font-semibold transition-colors" href="{{ route('password.request') }}">
                        Lupa kata sandi?
                    </a>
                @endif
            </div>

            <!-- Submit Button -->
            <div class="pt-2">
                <button 
                    type="submit" 
                    class="w-full py-3.5 px-4 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white font-bold text-sm rounded-xl shadow-lg shadow-indigo-600/25 hover:shadow-xl hover:shadow-indigo-600/35 hover:-translate-y-0.5 transition-all duration-150 flex items-center justify-center gap-2">
                    <span>Masuk ke Akun</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </button>
            </div>

            <!-- Link Kembali ke Beranda -->
            <div class="pt-2 text-center">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-slate-500 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors group">
                    <svg class="w-4 h-4 text-slate-400 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 -translate-x-0.5 group-hover:-translate-x-1.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        
                    </svg>
                    
                </a>
            </div>
        </form>
    </div>
</x-guest-layout>
