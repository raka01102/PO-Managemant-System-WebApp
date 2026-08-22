<x-guest-layout>
    <div class="mb-8 flex justify-center gap-2 items-center">
        <a href="/register">
            <x-application-logo class="w-20 h-20 fill-current text-gray-500 dark:text-slate-400" />
        </a>
        <div class="text-center">
            <h2 class="text-2xl font-black text-[#003399] dark:text-white">Pendaftaran Admin</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Buat akun untuk mulai mengelola pesanan</p>
        </div>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Nama Lengkap')" />
            <x-text-input id="name" class="block w-full mt-1" type="text" name="name" :value="old('name')"
                placeholder="Admin" required autofocus />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email Perusahaan')" />
            <x-text-input id="email" class="block w-full mt-1" type="email" name="email" :value="old('email')"
                placeholder="admin@gmail.com" required />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block w-full mt-1" type="password" name="password" placeholder="*****"
                required />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" />
            <x-text-input id="password_confirmation" class="block w-full mt-1" type="password"
                name="password_confirmation" placeholder="*****" required />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="pt-4">
            <x-primary-button class="btn justify-center w-full shadow-lg shadow-blue-100">
                {{ __('Selesaikan Pendaftaran') }}
            </x-primary-button>

            <div class="mt-6 text-center">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Sudah memiliki akun?
                    <a href="{{ route('login') }}"
                        class="font-bold text-[#003399] dark:text-blue-400 hover:underline">Login di sini</a>
                </p>
            </div>
        </div>
    </form>
</x-guest-layout>
