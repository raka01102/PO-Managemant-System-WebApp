<!-- MOBILE BACKDROP -->
<div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 z-40 bg-black/50 lg:hidden"
    @click="sidebarOpen = false"></div>

<!-- SIDEBAR -->
<aside x-cloak :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    class="fixed inset-y-0 left-0 z-50 flex w-72 flex-col border-r border-slate-200 bg-white transition-transform duration-300 dark:border-slate-800 dark:bg-slate-900 lg:static lg:translate-x-0">
    <!-- LOGO -->
    <div class="flex h-16 items-center border-b border-slate-200 px-6 dark:border-slate-800">
        <div class="flex items-center gap-3">
            <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-primary-600 text-white">
                <i class="fa-solid fa-box-archive"></i>
            </div>

            <div>
                <h1 class="text-lg font-bold tracking-tight text-slate-900 dark:text-white">
                    PO System
                </h1>

                <p class="text-xs text-slate-500 dark:text-slate-400">
                    Management Dashboard
                </p>
            </div>
        </div>
    </div>

    <!-- NAVIGATION -->
    <nav class="flex-1 space-y-1 overflow-y-auto px-4 py-6">
        <x-side-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
            <i class="fa-solid fa-chart-pie w-5"></i>
            Dashboard
        </x-side-link>

        <x-side-link :href="route('purchase-orders.index')" :active="request()->routeIs('purchase-orders.*')">
            <i class="fa-solid fa-file-invoice w-5"></i>
            Purchase Orders
        </x-side-link>

        <x-side-link :href="route('products.index')" :active="request()->routeIs('products.*')">
            <i class="fa-solid fa-box-open w-5"></i>
            Products
        </x-side-link>

        <x-side-link :href="route('customers.index')" :active="request()->routeIs('customers.*')">
            <i class="fa-solid fa-users w-5"></i>
            Customers
        </x-side-link>

        <!-- SECTION -->
        <div class="px-3 pt-6">
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                System
            </p>
        </div>

        <x-side-link href="#">
            <i class="fa-solid fa-gear w-5"></i>
            Settings
        </x-side-link>
    </nav>

    <!-- FOOTER -->
    <div class="border-t border-slate-200 p-4 dark:border-slate-800">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <x-danger-button class="btn-left-icon w-full !bg-transparent !text-rose-600">
                <i class="fa-solid fa-right-from-bracket"></i>
                Keluar
            </x-danger-button>
        </form>
    </div>
</aside>
