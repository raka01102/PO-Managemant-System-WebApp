<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="layout()" x-init="init()">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        {{ config('app.name', 'PO Management') }}
    </title>

    <!-- PREVENT DARKMODE FLASH -->
    <script>
        if (
            localStorage.getItem('theme') === 'dark' ||
            (!localStorage.getItem('theme') &&
                window.matchMedia('(prefers-color-scheme: dark)').matches)
        ) {
            document.documentElement.classList.add('dark')
        }
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-50 text-slate-900 antialiased dark:bg-slate-950 dark:text-slate-100">
    <div class="flex h-screen overflow-hidden">
        <!-- SIDEBAR -->
        @include('layouts.sidebar')

        <!-- CONTENT -->
        <div class="flex flex-1 flex-col overflow-hidden">
            <!-- TOPBAR -->
            <header
                class="hidden md:flex h-16 items-center justify-between border-b border-slate-200 bg-white px-4 dark:border-slate-800 dark:bg-slate-900 lg:px-8">
                <!-- LEFT -->
                <div class="flex items-center gap-4">
                    <!-- MOBILE SIDEBAR -->
                    <button @click="sidebarOpen = true"
                        class="
                                flex h-10 w-10 items-center justify-center
                                rounded-xl
                                text-slate-500
                                transition-colors duration-200

                                hover:bg-slate-100
                                hover:text-slate-700

                                dark:text-slate-400
                                dark:hover:bg-slate-800
                                dark:hover:text-white

                                lg:hidden
                            ">
                        <i class="fa-solid fa-bars-staggered"></i>
                    </button>

                    <!-- TITLE -->
                    <div>
                        <h2 class="text-base font-semibold text-slate-900 dark:text-white">
                            PO Management
                        </h2>
                        <p class="hidden text-xs text-slate-500 dark:text-slate-400 sm:block">
                            Management System
                        </p>
                    </div>
                </div>

                <!-- RIGHT -->
                <div class="flex items-center gap-3">
                    <!-- DARKMODE -->
                    <button @click="toggleDarkMode()"
                        class="
                                flex h-10 w-10 items-center justify-center
                                rounded-xl

                                border border-slate-200
                                bg-white
                                text-slate-500

                                transition-colors duration-200

                                hover:bg-slate-100
                                hover:text-slate-700

                                dark:border-slate-700
                                dark:bg-slate-900
                                dark:text-yellow-400
                                dark:hover:bg-slate-800
                            ">

                        <i
                            :class="darkMode
                                ?
                                'fa-solid fa-sun' :
                                'fa-solid fa-moon'"></i>
                    </button>

                    <!-- PROFILE -->
                    <div
                        class="
                                flex items-center gap-3
                                rounded-2xl
                                border border-slate-200
                                bg-white
                                px-3 py-2

                                dark:border-slate-700
                                dark:bg-slate-900
                            ">

                        <div class="hidden text-right md:block">
                            <p class="text-sm font-semibold text-slate-900 dark:text-white">
                                {{ Auth::user()->name }}
                            </p>

                            <p class="text-xs text-slate-500 dark:text-slate-400">
                                Administrator
                            </p>
                        </div>

                        <div
                            class="
                                    flex h-10 w-10 items-center justify-center
                                    rounded-xl
                                    bg-primary-600
                                    text-sm font-bold text-white
                                ">

                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                    </div>
                </div>
            </header>

            <!-- MAIN -->
            <main class="flex-1 overflow-y-auto">
                {{ $slot }}
            </main>

            {{-- Tabbar For Mobile --}}
            <div
                class="md:hidden h-16 grid grid-cols-5 items-center text-center text-base justify-between border-t border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900">
                <x-nav-link href="{{ route('dashboard') }}" class="flex flex-col" :active="request()->routeIs('dashboard') || request()->routeIs('/')">
                    <span>
                        <i class="fa-solid fa-house"></i>
                    </span>
                    Home
                </x-nav-link>

                <x-nav-link href="{{ route('customers.index') }}" class="flex flex-col" :active="request()->routeIs('customers.index') ||
                    request()->routeIs('customers.create') ||
                    request()->routeIs('customers.show') ||
                    request()->routeIs('customers.edit')">
                    <span>
                        <i class="fa-solid fa-users"></i>
                    </span>
                    Customer
                </x-nav-link>

                <x-nav-link href="{{ route('purchase-orders.index') }}" class="flex flex-col" :active="request()->routeIs('purchase-orders.index')">
                    <span>
                        <i class="fa-solid fa-file-lines"></i>
                    </span>
                    PO
                </x-nav-link>

                <x-nav-link href="{{ route('products.index') }}" class="flex flex-col" :active="request()->routeIs('products.index') ||
                    request()->routeIs('products.create') ||
                    request()->routeIs('products.show') ||
                    request()->routeIs('products.edit')">
                    <span>
                        <i class="fa-solid fa-box-archive"></i>
                    </span>
                    Produk
                </x-nav-link>

                <x-nav-link href="" class="flex flex-col">
                    <span>
                        <i class="fa-solid fa-cog"></i>
                    </span>
                    Setting
                </x-nav-link>
            </div>
        </div>
    </div>
</body>

</html>
