<x-guest-layout>
    <div class="mb-8 flex justify-center gap-2 items-center">
        <a href="/login">
            <x-application-logo class="w-20 h-20 fill-current text-gray-500 dark:text-slate-400" />
        </a>
        <div class="text-center">
            <h2 class="text-2xl font-black text-[#003399] dark:text-white">Selamat Datang</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Silakan masuk ke sistem manajemen PO</p>
        </div>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block w-full mt-1" type="email" name="email" :value="old('email')"
                placeholder="admin@gmail.com" autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <div class="flex justify-between items-center mb-1">
                <x-input-label for="password" :value="__('Password')" />
                @if (Route::has('password.request'))
                    <a class="text-xs font-bold text-[#003399] dark:text-blue-400 hover:underline"
                        href="{{ route('password.request') }}">
                        Lupa Password?
                    </a>
                @endif
            </div>
            <x-text-input id="password" class="block w-full mt-1" type="password" name="password" placeholder="*****"
                required />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox"
                    class="text-[#003399] border-gray-300 dark:border-slate-600 rounded focus:ring-[#003399] dark:bg-slate-900"
                    name="remember">
                <span class="text-sm text-gray-600 dark:text-gray-400 ms-2">Ingat saya di perangkat ini</span>
            </label>
        </div>

        <div class="pt-2">
            <x-primary-button class="btn justify-center w-full shadow-lg shadow-blue-100">
                {{ __('Masuk Ke Sistem') }}
            </x-primary-button>

            <div class="mt-6 text-center">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Belum punya akses?
                    <a href="{{ route('register') }}"
                        class="font-bold text-[#003399] dark:text-blue-400 hover:underline">Daftar Akun Baru</a>
                </p>
            </div>
        </div>
    </form>
</x-guest-layout>
