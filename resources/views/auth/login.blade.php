<x-guest-layout>
    <!-- Brand Header -->
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-red-600 mb-3 shadow-lg shadow-red-500/20">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
            </svg>
        </div>
        <h2 class="text-2xl font-black text-gray-900 dark:text-white tracking-tight uppercase">Telkomsel</h2>
        <p class="text-xs font-semibold text-red-600 dark:text-red-400 tracking-wider uppercase">Sistem Manajemen Inventaris</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">Email Karyawan</label>
            <input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" 
                placeholder="nama@telkomsel.com"
                class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-red-500 focus:ring-red-500 shadow-sm text-sm" />
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- Password -->
        <div>
            <div class="flex justify-between items-center mb-1">
                <label for="password" class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300">Kata Sandi</label>
                @if (Route::has('password.request'))
                    <a class="text-xs text-red-600 dark:text-red-400 hover:underline font-semibold" href="{{ route('password.request') }}">
                        {{ __('Lupa sandi?') }}
                    </a>
                @endif
            </div>
            <input id="password" type="password" name="password" required autocomplete="current-password" 
                placeholder="••••••••"
                class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-red-500 focus:ring-red-500 shadow-sm text-sm" />
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" name="remember" 
                    class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-red-600 shadow-sm focus:ring-red-500 dark:focus:ring-red-600 dark:focus:ring-offset-gray-800">
                <span class="ms-2 text-xs font-semibold text-gray-600 dark:text-gray-400">{{ __('Ingat Saya') }}</span>
            </label>
            
            <a href="{{ route('register') }}" class="text-xs text-slate-500 dark:text-slate-400 hover:underline">
                Belum punya akun? Daftar
            </a>
        </div>

        <!-- Log In Button -->
        <div>
            <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-red-600 hover:bg-red-700 active:bg-red-800 text-white font-bold text-xs uppercase tracking-widest rounded-md shadow-md shadow-red-500/10 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition duration-150">
                {{ __('Masuk Ke Sistem') }}
            </button>
        </div>
    </form>
</x-guest-layout>
