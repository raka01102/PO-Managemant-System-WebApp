<x-app-layout>
    <div class="mx-auto max-w-7xl space-y-4 px-4 md:px-6 md:py-4 lg:px-8" x-data="purchaseOrderManager()">
        {{-- HEADER --}}
        <div
            class="flex pt-4 border-b border-slate-200 dark:border-slate-800 md:border-none md:pt-0 md:flex-row items-center md:justify-between">
            <h1 class="page-title">
                Daftar Purchase Order
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

        @if (session('success'))
            <x-alert type="success" title="Berhasil" :message="session('success')" />
        @elseif(session('error'))
            <x-alert type="error" title="Gagal" :message="session('error')" />
        @elseif(session('warning'))
            <x-alert type="warning" title="Perhatian" message="Data belum lengkap." />
        @endif

        {{-- Search --}}
        <div class="flex items-center gap-2 md:gap-4">
            <div class="flex-1 relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>

                <input type="text" placeholder="Cari nomor PO atau customer..."
                    class="w-full rounded-xl border border-slate-200 py-3 lg:py-2 pl-10 pr-4 dark:border-slate-700 dark:bg-slate-950 dark:text-white">
            </div>

            <x-secondary-button class="btn-left-icon" @click="$dispatch('open-modal', '')">
                <span class="h-6 w-6">
                    <i class="fa-solid fa-sliders"></i>
                </span>
                Filter
            </x-secondary-button>
        </div>

        {{-- Card PO --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-2 md:gap-4">
            @foreach ($purchaseOrders as $purchaseOrder)
                <div class="card">
                    <div class="card-header flex items-center justify-between">
                        <div class="flex gap-1 items-center">
                            <span class="flex items-center w-9 h-9">
                                <i class="fa-solid fa-file text-2xl"></i>
                            </span>

                            <div class="flex flex-col font-semibold">
                                <p>
                                    {{ $purchaseOrder->po_number }}
                                </p>

                                <div class="flex text-xs text-slate-500">
                                    <p>
                                        {{ $purchaseOrder->customer->name }} |
                                        {{ $purchaseOrder->order_date->format('d-m-Y') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        @php
                            if ($purchaseOrder->delivery_status === 'draft') {
                                $status = 'Diproses';
                                $style = 'bg-blue-100 text-blue-600 dark:bg-blue-900/20 dark:text-blue-400';
                            } elseif (
                                $purchaseOrder->delivery_status === 'partially_delivered' ||
                                $purchaseOrder->delivery_status === 'delivered'
                            ) {
                                $status = 'Dikirim';
                                $style = 'bg-blue-100 text-blue-600 dark:bg-blue-900/20 dark:text-blue-400';
                            } else {
                                if ($purchaseOrder->payment_status === 'unpaid') {
                                    $status = 'Belum Dibayar';
                                    $style = 'bg-rose-100 text-rose-600 dark:bg-rose-900/20 dark:text-rose-400';
                                } elseif ($purchaseOrder->payment_status === 'partially_paid') {
                                    $status = 'Dibayar Sebagian';
                                    $style = 'bg-amber-100 text-amber-600 dark:bg-amber-900/20 dark:text-amber-400';
                                } else {
                                    $status = 'Selesai';
                                    $style = 'bg-green-100 text-green-600 dark:bg-green-900/20 dark:text-green-400';
                                }
                            }
                        @endphp

                        <span class="rounded-lg px-4 py-2 text-xs font-semibold {{ $style }}">
                            {{ $status }}
                        </span>
                    </div>

                    <div class="card-body space-y-2">
                        @php
                            $firstItem = $purchaseOrder->items->first();
                            $otherItemsCount = $purchaseOrder->items->count() - 1;
                        @endphp
                        <div class="flex flex-col">
                            <p class="text-base font-semibold">{{ $firstItem->product['code'] }} |
                                {{ $firstItem->product['name'] }}</p>

                            <p class="text-xs text-slate-500">{{ $firstItem->quantity }} Barang</p>
                        </div>

                        <div class="min-h-6">
                            @if ($otherItemsCount > 0)
                                <p class="text-base">
                                    +{{ $otherItemsCount }} Barang Lainnya
                                </p>
                            @endif
                        </div>

                        <div class="flex justify-between items-center">
                            <div class="flex flex-col">
                                <p class="text-xs">Total Harga</p>

                                <p class="text-base font-bold">Rp
                                    {{ number_format($purchaseOrder->total_amount, 0, ',', '.') }}</p>
                            </div>

                            <div class="flex gap-2">
                                <x-danger-button @click="$dispatch('open-modal', 'delete-po-modal')" class="btn-icon">
                                    <span class="w-6 h-6">
                                        <i class="fa-solid fa-trash"></i>
                                    </span>
                                </x-danger-button>

                                <a href="{{ route('purchase-orders.show', $purchaseOrder) }}">
                                    <x-info-button class="btn-icon">
                                        <span class="w-6 h-6">
                                            <i class="fa-solid fa-eye"></i>
                                        </span>
                                    </x-info-button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- DELETE MODAL -->
        <x-modal name="delete-po-modal" maxWidth="md">
            <div class="flex flex-col p-6 gap-2 md:gap-4">
                <div class="flex gap-2 md:gap-4 items-center">
                    {{-- ICON --}}
                    <div
                        class="flex h-9 w-9 items-center justify-center rounded-lg bg-rose-100 text-rose-600 dark:bg-rose-900/20 dark:text-rose-400">
                        <i class="fa-solid fa-trash-can text-2xl"></i>
                    </div>

                    <h2 class="text-2xl font-bold text-slate-900 dark:text-white">
                        Hapus Data PO
                    </h2>
                </div>

                <p>Data yang telah dihapus tidak bisa dikembalikan. Apakah anda ingin melakukan tindakan ini?</p>

                <form action="{{ route('purchase-orders.destroy', $purchaseOrder->id) }}" method="POST"
                    class="flex items-center justify-end gap-2 md:gap-4">
                    @csrf
                    @method('DELETE')

                    <x-secondary-button @click="$dispatch('close-modal', 'delete-po-modal')">
                        Simpan Data
                    </x-secondary-button>

                    <x-danger-button class="btn-left-icon">
                        <i class="fa-solid fa-trash-can"></i>
                        Hapus PO
                    </x-danger-button>
                </form>
            </div>
        </x-modal>
    </div>

    {{-- Button for create PO Mobile --}}
    <div
        class="absolute bottom-16 text-center justify-center z-40 p-4 w-full border-t border-slate-200 bg-slate-50 dark:border-slate-800 dark:bg-slate-900 md:hidden">
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
