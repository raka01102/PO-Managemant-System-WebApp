<x-app-layout>
    <div class="content pb-56 sm:pb-4" x-data="purchaseOrderShowManager()">
        {{-- HEADER --}}
        <div class="content-header">
            <a href="{{ route('purchase-orders.index') }}" class="md:hidden mr-2">
                <span class="h-8 w-8 md:h-10 md:w-10">
                    <i class="fa-solid fa-arrow-left"></i>
                </span>
            </a>

            <h1 class="page-title">
                Detail Purchase Order
            </h1>
        </div>

        <div class="hidden lg:block card">
            <div class="card-body">
                <div class="grid grid-cols-2 gap-6">
                    {{-- Informasi PO --}}
                    <div class="space-y-2">
                        {{-- Nomor PO --}}
                        <x-info-item icon="fa-file" label="Nomor PO" :value="$purchaseOrder->po_number" />

                        {{-- Nama Customer --}}
                        <x-info-item icon="fa-user" label="Nama Customer" :value="$purchaseOrder->customer->name" />

                        {{-- Tanggal Order --}}
                        <x-info-item icon="fa-calendar" label="Tanggal Order" :value="$purchaseOrder->order_date->format('d M Y')" />
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div class="grid grid-rows-2 gap-2">
                            <a href="{{ route('purchase-orders.payment', $purchaseOrder) }}">
                                <x-secondary-button
                                    class="btn-left-icon !bg-emerald-600 !hover:bg-emerald-700 !h-full !w-full">
                                    <i class="fa-solid fa-wallet text-xl"></i>
                                    Update Pembayaran
                                </x-secondary-button>
                            </a>

                            <a href="{{ route('purchase-orders.shipping', $purchaseOrder) }}">
                                <x-primary-button type="button" class="btn-left-icon !h-full !w-full">
                                    <i class="fa-solid fa-truck-fast text-xl"></i>
                                    Update Pengiriman
                                </x-primary-button>
                            </a>
                        </div>

                        <div class="grid grid-rows-2 gap-2">
                            <a href="{{ route('purchase-orders.edit', $purchaseOrder) }}">
                                <x-info-button class="btn-left-icon !h-full !w-full">
                                    <i class="fa-solid fa-pen-to-square text-xl"></i>
                                    Edit PO
                                </x-info-button>
                            </a>

                            <a href="{{ route('purchase-orders.index') }}">
                                <x-secondary-button class="btn-left-icon !h-full !w-full">
                                    <i class="fa-solid fa-arrow-left text-xl"></i>
                                    Kembali
                                </x-secondary-button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @php
            $deliverySteps = [
                'draft' => [
                    'label' => 'Draft',
                    'icon' => 'fa-file-circle-plus',
                ],
                'partially_delivered' => [
                    'label' => 'Dikirim',
                    'icon' => 'fa-truck-fast',
                ],
                'delivered' => [
                    'label' => 'Sampai',
                    'icon' => 'fa-box-open',
                ],
            ];

            $paymentSteps = [
                'unpaid' => [
                    'label' => 'Belum',
                    'icon' => 'fa-xmark',
                    'bg' => 'bg-rose-100',
                    'text' => 'text-rose-600',
                ],
                'partially_paid' => [
                    'label' => 'Sebagian',
                    'icon' => 'fa-credit-card',
                    'bg' => 'bg-amber-100',
                    'text' => 'text-amber-600',
                ],
                'paid' => [
                    'label' => 'Lunas',
                    'icon' => 'fa-circle-check',
                    'bg' => 'bg-emerald-100',
                    'text' => 'text-emerald-600',
                ],
            ];

            $deliveryKeys = array_keys($deliverySteps);
            $paymentKeys = array_keys($paymentSteps);

            $currentDeliveryStep = array_search($purchaseOrder->delivery_status, $deliveryKeys);
            $currentPaymentStep = array_search($purchaseOrder->payment_status, $paymentKeys);
        @endphp

        {{-- INFORMASI STATUS PENGIRIMAN & PEMBAYARAN --}}
        <div class="grid gap-4 sm:gap-6 grid-cols-2">
            {{-- STATUS PENGIRIMAN --}}
            <x-status-stepper title="Pengiriman" :steps="$deliverySteps" :currentStep="$currentDeliveryStep" />

            {{-- STATUS PEMBAYARAN --}}
            <x-status-stepper title="Pembayaran" type="secondary" :steps="$paymentSteps" :currentStep="$currentPaymentStep" />
        </div>

        {{-- INFORMASI PO MOBILE --}}
        <div class="card lg:hidden">
            <div class="card-header">
                <h2 class="font-bold">
                    Detail Purchase Order
                </h2>

                <p class="text-xs text-slate-500">
                    {{ $purchaseOrder->order_date->format('d M Y') }}
                </p>
            </div>

            <div class="card-body">
                <div class="grid grid-rows-2 gap-2">
                    <x-info-item icon="fa-file" label="Nomor PO" :value="$purchaseOrder->po_number ?? ''" />

                    <x-info-item icon="fa-user" label="Nama Customer" :value="$purchaseOrder->customer->name ?? '-'" />
                </div>
            </div>
        </div>

        {{-- DAFTAR BARANG --}}
        <div class="card">
            <div class="card-header">
                <h2 class="font-bold">
                    Daftar Barang
                </h2>

                <p class="text-xs text-slate-500 text-right">
                    Total {{ $purchaseOrder->items->sum('quantity') ?? 0 }} Barang
                </p>
            </div>

            <div class="divide-y divide-slate-200 dark:divide-slate-800">
                @forelse ($purchaseOrder->items as $poItem)
                    <div class="space-y-2 card-body">
                        <div>
                            <p class="font-semibold">
                                {{ $poItem->product->name ?? 'Produk telah dihapus' }}
                            </p>

                            <p class="text-xs text-slate-500">
                                {{ $poItem->product->unit ?? 'N/A' }}
                            </p>
                        </div>

                        <div class="flex items-center justify-between">
                            <p class="font-bold">
                                Rp {{ number_format($poItem->subtotal, 0, ',', '.') ?? 0 }}
                            </p>

                            <p class="text-sm text-slate-500">
                                {{ $poItem->quantity ?? 0 }} x Rp
                                {{ number_format($poItem->price_at_time, 0, ',', '.') ?? 0 }}
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="card-body text-center">
                        <p class="text-sm text-slate-500 py-2">
                            Tidak ada item.
                        </p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- DETAIL PEMBAYARAN --}}
        <div class="card">
            <div class="card-header">
                <h2 class="font-bold">Detail Pembayaran</h2>
            </div>

            <div class="divide-y divide-slate-200 dark:divide-slate-800">
                @forelse ($purchaseOrder->payments as $payment)
                    <div
                        class="card-body flex items-center justify-between pb-4 border-b border-slate-200 dark:border-slate-800">
                        <div class="flex items-center gap-3">
                            <div class="flex h-9 w-9 items-center justify-center">
                                @if ($payment->payment_method == 'transfer')
                                    <i class="fa-solid fa-building-columns"></i>
                                @else
                                    <i class="fa-solid fa-money-bill-wave"></i>
                                @endif
                            </div>

                            <div>
                                <p class="font-medium">
                                    {{ ucfirst($payment->payment_method) }}
                                </p>

                                <p class="text-xs text-slate-500">
                                    Transaksi Pembayaran
                                </p>
                            </div>
                        </div>

                        <p class="font-semibold">
                            Rp {{ number_format($payment->amount, 0, ',', '.') ?? 0 }}
                        </p>
                    </div>
                @empty
                    <div class="card-body text-center">
                        <p class="text-sm text-slate-500 py-2">
                            Belum ada pembayaran
                        </p>
                    </div>
                @endforelse

                @if ($purchaseOrder->payment_status != 'paid')
                    <div class="grid grid-cols-2 items-center card-body">
                        <div class="flex items-center gap-1 sm:gap-2">
                            <div class="flex h-9 w-9 items-center justify-center">
                                <i class="fa-solid fa-file-invoice-dollar text-xl"></i>
                            </div>

                            <p class="text-xs font-semibold">Total Belum Dibayar</p>
                        </div>

                        <p class="font-semibold text-right text-rose-600">Rp
                            {{ number_format($purchaseOrder->remaining_amount, 0, ',', '.') ?? 0 }}</p>
                    </div>
                @endif
            </div>
        </div>

        <div x-data="filePreview">
            {{-- BUKTI PEMBUATAN PO --}}
            <x-attachment-section title="Bukti Pembuatan PO" :attachments="$purchaseOrder->getAttachmentsByType('po')" />

            {{-- BUKTI PENGIRIMAN PO --}}
            <x-attachment-section title="Bukti Pengiriman PO" :attachments="$purchaseOrder->getAttachmentsByType('pengiriman')" />

            {{-- BUKTI PEMBAYARAN --}}
            <x-attachment-section title="Bukti Pembayaran PO" :attachments="$purchaseOrder->getAttachmentsByType('pembayaran')" />

            <x-modal name="preview-file-modal" maxWidth="xl">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="font-semibold text-slate-900 dark:text-white">Preview Dokumen</h2>

                        <button @click="closePreviewDokumen()"
                            class="flex h-10 w-10 items-center justify-center rounded-xl text-slate-400 transition hover:bg-slate-100 hover:text-slate-700 dark:hover:bg-slate-800 dark:hover:text-slate-200">
                            <i class="fa-solid fa-xmark text-lg"></i>
                        </button>
                    </div>

                    {{-- Image --}}
                    <template x-if="isImage">
                        <img :src="selectedFileUrl" class="w-full rounded-xl" :alt="fileName">
                    </template>

                    {{-- PDF --}}
                    <template x-if="isPdf">
                        <iframe :src="selectedFileUrl" class="w-full h-[80vh] rounded-xl"></iframe>
                    </template>

                    {{-- UnSupported --}}
                    <template x-if="selectedFile && !isImage && !isPdf">
                        <p class="py-10 text-center text-slate-400">
                            Tidak dapat menampilkan preview untuk jenis file ini
                        </p>
                    </template>
                </div>
            </x-modal>
        </div>
    </div>

    <x-bottom-action-bar>
        <div class="flex flex-col w-full space-y-2">
            <a href="{{ route('purchase-orders.payment', $purchaseOrder) }}">
                <x-secondary-button class="btn-left-icon !bg-emerald-600 !hover:bg-emerald-700 !h-full !w-full">
                    <i class="fa-solid fa-wallet text-xl"></i>
                    Update Pembayaran
                </x-secondary-button>
            </a>
            <a href="{{ route('purchase-orders.shipping', $purchaseOrder) }}">
                <x-primary-button type="button" class="btn-left-icon !h-full !w-full">
                    <i class="fa-solid fa-truck-fast text-xl"></i>
                    Update Pengiriman
                </x-primary-button>
            </a>
            <a href="{{ route('purchase-orders.edit', $purchaseOrder) }}">
                <x-info-button class="btn-left-icon !h-full !w-full">
                    <i class="fa-solid fa-pen-to-square text-xl"></i>
                    Edit PO
                </x-info-button>
            </a>
        </div>
    </x-bottom-action-bar>
</x-app-layout>
