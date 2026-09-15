<x-app-layout>
    <div class="mx-auto max-w-7xl space-y-4 px-4 md:px-6 md:py-4 lg:px-8">
        {{-- HEADER --}}
        <div
            class="flex flex-col pt-4 border-b border-slate-200 dark:border-slate-800 md:border-none md:pt-0 md:flex-row md:items-center md:justify-between md:gap-4">
            <h1 class="page-title">
                Dashboard
            </h1>

            <a href="{{ route('purchase-orders.create') }}" class="hidden md:block">
                <x-primary-button type="button" class="btn-left-icon">
                    <span class="h-6 w-6">
                        <i class="fa-solid fa-plus"></i>
                    </span>
                    Buat PO Baru
                </x-primary-button>
            </a>
        </div>

        <div class="grid grid-cols-2 gap-2 md:gap-4 md:grid-cols-4">
            {{-- TOTAL ORDER --}}
            <div class="card">
                <div class="card-body">
                    <div class="flex items-start justify-between">
                        <div class="flex flex-col gap-2">
                            <p class="text-base text-slate-500 dark:text-slate-400">
                                Total Order
                            </p>

                            <p class="text-2xl font-semibold">
                                {{ $stats['total'] ?? 0 }}
                            </p>
                        </div>

                        <span
                            class="flex h-12 w-12 items-center justify-center rounded-lg bg-blue-100 text-blue-600 dark:bg-blue-900/20 dark:text-blue-400">
                            <i class="fa-solid fa-file-lines"></i>
                        </span>
                    </div>
                </div>
            </div>

            {{-- UNPAID --}}
            <a href="{{ route('purchase-orders.index', ['filter' => 'unpaid']) }}"
                class="card hover:border-rose-300 dark:hover:border-rose-700">
                <div class="card-body">
                    <div class="flex items-start justify-between">
                        <div class="flex flex-col gap-2">
                            <p class="text-base text-slate-500 dark:text-slate-400">
                                Belum Dibayar
                            </p>

                            <p class="text-2xl font-semibold">
                                {{ $stats['unpaid'] ?? 0 }}
                            </p>
                        </div>

                        <span
                            class="flex h-12 w-12 items-center justify-center rounded-lg bg-rose-100 text-rose-600 dark:bg-rose-900/20 dark:text-rose-400">
                            <i class="fa-solid fa-credit-card"></i>
                        </span>
                    </div>
                </div>
            </a>

            {{-- DELIVERING --}}
            <a href="{{ route('purchase-orders.index', ['filter' => 'no_sj']) }}"
                class="card hover:border-orange-300 dark:hover:border-orange-700">
                <div class="card-body">
                    <div class="flex items-start justify-between">
                        <div class="flex flex-col gap-2">
                            <p class="text-base text-slate-500 dark:text-slate-400">
                                Proses Pengiriman
                            </p>

                            <p class="text-2xl font-semibold">
                                {{ $stats['no_sj'] ?? 0 }}
                            </p>
                        </div>

                        <span
                            class="flex h-12 w-12 items-center justify-center rounded-lg bg-orange-100 text-orange-600 dark:bg-orange-900/20 dark:text-orange-400">
                            <i class="fa-solid fa-truck-fast"></i>
                        </span>
                    </div>
                </div>
            </a>

            {{-- Successed --}}
            <div class="card">
                <div class="card-body">
                    <div class="flex items-start justify-between">
                        <div class="flex flex-col gap-2">
                            <p class="text-base text-slate-500 dark:text-slate-400">
                                Sudah Selesai
                            </p>

                            <p class="text-2xl font-semibold">
                                {{ $stats['delivered'] ?? 0 }}
                            </p>
                        </div>

                        <span
                            class="flex h-12 w-12 items-center justify-center rounded-lg bg-green-100 text-green-600 dark:bg-green-900/20 dark:text-green-400">
                            <i class="fa-solid fa-circle-check"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- NEW ACTIVITY --}}
        <div class="card overflow-hidden">
            <div class="card-header flex items-center justify-between">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white">
                    Aktivitas Terbaru
                </h2>

                <a href="{{ route('purchase-orders.index') }}"
                    class="text-sm font-semibold text-primary-600 hover:text-primary-700 dark:text-primary-400">
                    Lihat Semua
                </a>
            </div>

            @foreach ($recentOrders as $order)
                <div class="px-2 py-2 grid grid-cols-1 md:grid-cols-2 md:gap-4">
                    <div class="flex items-center gap-2">
                        <span
                            class="h-6 w-6 flex items-center justify-center rounded-full text-blue-600 dark:text-blue-400">
                            <i class="fa-solid fa-circle text-xs"></i>
                        </span>

                        @php
                            $log = $order->logs->last();

                            if ($log && $log->type == 'Status Pengiriman') {
                                switch ($log->new_value) {
                                    case 'partially_delivered':
                                        $status = 'Dikirim X Barang';
                                        break;

                                    case 'delivered':
                                        $status = 'Semua Barang Telah Dikirim';
                                        break;

                                    case 'completed':
                                        $status = 'PO Selesai';
                                        break;

                                    default:
                                        $status = 'Dibuat';
                                        break;
                                }
                            } elseif ($log) {
                                if ($log->new_value == 'unpaid') {
                                    $status = 'Belum Dibayar';
                                } elseif ($log->new_value == 'partial') {
                                    $status = 'Dibayar Sebagian';
                                } else {
                                    $status = 'Dibayar';
                                }
                            } else {
                                $status = 'Tidak Ada Log';
                            }
                        @endphp

                        <p class="text-base">
                            {{ $order->order_date->format('d-m-Y') }} |
                            {{ $order->customer->name }} |
                            {{ $order->po_number }} {{ $status }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Button for create PO Mobile --}}
    <div
        class="absolute bottom-16 text-center justify-center z-50 p-4 w-full border-t border-slate-200 bg-slate-50 dark:border-slate-800 dark:bg-slate-900 md:hidden">
        <a href="{{ route('purchase-orders.create') }}" class="md:hidden">
            <x-primary-button type="button" class="btn-left-icon w-full">
                <span class="h-6 w-6">
                    <i class="fa-solid fa-plus"></i>
                </span>
                Buat PO Baru
            </x-primary-button>
        </a>
    </div>
</x-app-layout>
