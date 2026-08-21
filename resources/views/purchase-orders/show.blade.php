<x-app-layout>
    <div class="mx-auto max-w-7xl flex flex-col gap-6 px-4 sm:py-6 pb-52 pt-6" x-data="purchaseOrderShowManager()">
        {{-- HEADER --}}
        <div class="hidden lg:block card">
            <div class="card-body">
                <div class="grid grid-cols-2 items-center">
                    <div class="grid grid-rows-3 gap-2">
                        <div class="flex items-center gap-2">
                            <div class="flex h-9 w-9 items-center justify-center">
                                <i class="fa-solid fa-file text-xl"></i>
                            </div>

                            <div>
                                <p class="text-xs text-slate-500">
                                    Nomor PO
                                </p>

                                <p class="font-semibold">
                                    {{ $purchaseOrder->po_number }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-1 sm:gap-2">
                            <div class="flex h-9 w-9 items-center justify-center">
                                <i class="fa-solid fa-user text-xl"></i>
                            </div>

                            <div>
                                <p class="text-xs text-slate-500">
                                    Nama Customer
                                </p>

                                <p class="font-semibold">
                                    {{ $purchaseOrder->customer->name }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-1 sm:gap-2">
                            <div class="flex h-9 w-9 items-center justify-center">
                                <i class="fa-solid fa-calendar text-xl"></i>
                            </div>

                            <div>
                                <p class="text-xs text-slate-500">
                                    Tanggal Order
                                </p>

                                <p class="font-semibold">
                                    {{ $purchaseOrder->order_date->format('d M Y') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2 items-center">
                        <div class="grid grid-rows-2 gap-2">
                            <a href="{{ route('purchase-orders.payment', $purchaseOrder) }}"
                                class="btn  bg-emerald-600 hover:bg-emerald-700 text-white">
                                <div class="flex h-9 w-9 items-center justify-center">
                                    <i class="fa-solid fa-wallet text-xl"></i>
                                </div>
                                <p class="">Update Pembayaran</p>
                            </a>
                            <a href="{{ route('purchase-orders.shipping', $purchaseOrder) }}" class="btn-primary">
                                <div class="flex h-9 w-9 items-center justify-center">
                                    <i class="fa-solid fa-truck-fast text-xl"></i>
                                </div>
                                <p class="">Update Status</p>
                            </a>
                        </div>
                        <div class="grid grid-rows-2 gap-2">
                            <a href="{{ route('purchase-orders.edit', $purchaseOrder) }}" class="btn-primary">
                                <div class="flex h-9 w-9 items-center justify-center">
                                    <i class="fa-solid fa-pen-to-square text-xl"></i>
                                </div>
                                <p class="">Edit PO</p>
                            </a>
                            <a href="{{ route('purchase-orders.index', $purchaseOrder) }}" class="btn-secondary">
                                <div class="flex h-9 w-9 items-center justify-center">
                                    <i class="fa-solid fa-arrow-left text-xl"></i>
                                </div>
                                <p class="">Kembali</p>
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

            $currentDeliveryStep = array_search($purchaseOrder->delivery_status, array_keys($deliverySteps));
            $currentPaymentStep = array_search($purchaseOrder->payment_status, array_keys($paymentSteps));
        @endphp

        {{-- INFORMASI UMUM --}}
        <div class="grid gap-4 sm:gap-6 grid-cols-2">
            {{-- STATUS PENGIRIMAN --}}
            <div class="card">
                <div class="card-header text-center">
                    <h2 class="sm:text-lg font-bold text-slate-900 dark:text-white">
                        Pengiriman
                    </h2>
                </div>

                <div class="card-body">
                    <div class="grid grid-cols-3 gap-0">
                        @foreach ($deliverySteps as $key => $step)
                            @php
                                $stepIndex = array_search($key, array_keys($deliverySteps));
                                $active = $stepIndex <= $currentDeliveryStep;
                            @endphp

                            <div class="relative">
                                <div class="relative flex justify-center">
                                    @if (!$loop->last)
                                        <div
                                            class="absolute left-1/2 top-1/2 h-1 w-full {{ $stepIndex < $currentDeliveryStep ? 'bg-primary-600' : 'bg-slate-200 dark:bg-slate-700' }}">
                                        </div>
                                    @endif

                                    <div
                                        class="flex h-9 w-9 sm:h-14 sm:w-14 items-center justify-center rounded-full z-10 {{ $active ? 'bg-primary-100 text-primary-600' : 'bg-slate-200 text-slate-500 dark:bg-slate-700' }}">
                                        <i class="fa-solid {{ $step['icon'] }}"></i>
                                    </div>
                                </div>

                                <p
                                    class="my-2 text-center text-xs font-bold {{ $active ? 'text-primary-600 dark:text-primary-100' : 'text-slate-400' }}">
                                    {{ $step['label'] }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- STATUS PEMBAYARAN --}}
            <div class="card">
                <div class="card-header text-center">
                    <h2 class="sm:text-lg font-bold text-slate-900 dark:text-white">
                        Pembayaran
                    </h2>
                </div>

                <div class="card-body">
                    <div class="grid grid-cols-3 gap-0">
                        @foreach ($paymentSteps as $key => $step)
                            @php
                                $stepIndex = array_search($key, array_keys($paymentSteps));
                                $active = $stepIndex <= $currentPaymentStep;
                            @endphp

                            <div class="relative">
                                <div class="relative flex justify-center">
                                    @if (!$loop->last)
                                        <div
                                            class="absolute left-1/2 top-1/2 h-1 w-full {{ $stepIndex < $currentPaymentStep ? 'bg-primary-600' : 'bg-slate-200 dark:bg-slate-700' }}">
                                        </div>
                                    @endif

                                    <div
                                        class="flex h-9 w-9 sm:h-14 sm:w-14 items-center justify-center rounded-full z-10 {{ $active ? $step['bg'] . ' ' . $step['text'] : 'bg-slate-200 text-slate-500 dark:bg-slate-700' }}">
                                        <i class="fa-solid {{ $step['icon'] }}"></i>
                                    </div>
                                </div>

                                <p
                                    class="my-2 text-center text-xs font-bold {{ $active ? $step['text'] : 'text-slate-400' }}">
                                    {{ $step['label'] }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- INFORMASI PO MOBILE --}}
        <div class="card lg:hidden">
            <div class="card-header">
                <div class="grid grid-cols-2 items-center">
                    <h2 class="font-bold">
                        Detail Purchase Order
                    </h2>

                    <p class="text-xs text-slate-500 text-right">
                        {{ $purchaseOrder->order_date->format('d M Y') }}
                    </p>
                </div>
            </div>

            <div class="card-body">
                <div class="grid grid-rows-2 gap-2">
                    <div class="flex items-center gap-1 sm:gap-2">
                        <div class="flex h-9 w-9 items-center justify-center">
                            <i class="fa-solid fa-file text-xl"></i>
                        </div>

                        <div>
                            <p class="text-xs text-slate-500">
                                Nomor PO
                            </p>

                            <p class="font-semibold">
                                {{ $purchaseOrder->po_number }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-1 sm:gap-2">
                        <div class="flex h-9 w-9 items-center justify-center">
                            <i class="fa-solid fa-user text-xl"></i>
                        </div>

                        <div>
                            <p class="text-xs text-slate-500">
                                Nama Customer
                            </p>

                            <p class="font-semibold">
                                {{ $purchaseOrder->customer->name }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- DAFTAR BARANG --}}
        <div class="card">
            <div class="card-header">
                <div class="grid grid-cols-2 items-center">
                    <h2 class="font-bold">
                        Daftar Barang
                    </h2>

                    <p class="text-xs text-slate-500 text-right">
                        Total {{ $purchaseOrder->items->sum('quantity') }} Barang
                    </p>
                </div>
            </div>

            <div class="hidden lg:block overflow-x-auto">
                <table class="w-full">
                    <thead class="table-head">
                        <tr>
                            <th class="px-6 py-4">
                                Nama
                            </th>

                            <th class="px-6 py-4">
                                Qty
                            </th>

                            <th class="px-6 py-4">
                                Satuan
                            </th>

                            <th class="px-6 py-4">
                                Harga
                            </th>

                            <th class="px-6 py-4">
                                Total
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($purchaseOrder->items as $item)
                            <tr class="table-row">
                                <td class="px-6 py-4">
                                    {{ $item->product->name ?? 'Produk telah dihapus' }}
                                </td>

                                <td class="px-6 py-4 text-center">
                                    {{ $item->quantity }}
                                </td>

                                <td class="px-6 py-4 text-center">
                                    {{ $item->product->unit ?? 'N/A' }}
                                </td>

                                <td class="px-6 py-4 text-center">
                                    Rp {{ number_format($item->price_at_time) }}
                                </td>

                                <td class="px-6 py-4 font-bold text-center">
                                    Rp {{ number_format($item->subtotal) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="space-y-4 lg:hidden">
                @foreach ($purchaseOrder->items as $item)
                    <div class="m-4 gap-4 border-b dark:border-slate-800">
                        <div class="grid grid-rows-3 gap-1 items-center">
                            <p class="font-semibold">{{ $item->product->name }}</p>

                            <p class="text-xs text-slate-500 capitalize">{{ $item->product->unit }}</p>

                            <div class="grid grid-cols-2 items-center">
                                <p class="font-semibold">Rp {{ number_format($item->subtotal) }}</p>

                                <p class="text-xs text-slate-500 text-right">{{ $item->quantity }} &times;</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- DETAIL PEMBAYARAN --}}
        <div class="card">
            <div class="card-header">
                <h2 class="font-bold">Detail Pembayaran</h2>
            </div>
            <div class="card-body">
                @foreach ($purchaseOrder->payments as $payment)
                    <div class="grid grid-cols-2 items-center gap-1 sm:gap-2 border-b dark:border-slate-800">
                        <div class="flex items-center gap-1 sm:gap-2">
                            <div class="flex h-9 w-9 items-center justify-center">
                                @if ($payment->payment_method == 'transfer')
                                    <i class="fa-solid fa-building-columns text-xl"></i>
                                @else
                                    <i class="fa-solid fa-money-bill-wave text-xl"></i>
                                @endif
                            </div>

                            <p class="text-xs text-slate-500 text-right">Transaksi</p>
                        </div>

                        <p class="font-semibold">
                            {{ $payment->payment_method }}
                        </p>
                    </div>
                @endforeach

                @if ($purchaseOrder->payment_status != 'paid')
                    <div class="grid grid-cols-2 items-center">
                        <div class="flex items-center gap-1 sm:gap-2">
                            <div class="flex h-9 w-9 items-center justify-center">
                                <i class="fa-solid fa-file-invoice-dollar text-xl"></i>
                            </div>

                            <p class="text-xs font-semibold">Total Yang Belum Dibayar</p>
                        </div>

                        <p class="font-semibold text-right text-rose-600">Rp
                            {{ $purchaseOrder->getRemainingAmountAttribute() }}</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- BUKTI PEMBUATAN PO --}}
        @php
            $purchaseOrdersAttachments = $purchaseOrder->attachments->filter(
                fn($a) => str_contains(strtolower($a->file_type), 'po'),
            );
        @endphp
        @if ($purchaseOrdersAttachments->isNotEmpty())
            <div x-data="{ open: true }" class="card overflow-hidden">
                <div class="card-header">
                    <div class="grid grid-cols-2 items-center">
                        <h2 class="font-bold">
                            Bukti Pembuatan PO
                        </h2>

                        <button @click="open = !open" class="flex items-center justify-end">
                            <div class="flex h-9 w-9 items-center justify-center">
                                <i class="fa-solid fa-chevron-down text-xl" :class="{ 'rotate-180': !open }"></i>
                            </div>
                        </button>
                    </div>
                </div>

                <div x-show="open" class="card-body">
                    <div class="grid grid-cols-2">
                        @foreach ($purchaseOrdersAttachments as $item)
                            @php
                                $extension = pathinfo($item->file_path, PATHINFO_EXTENSION);
                            @endphp
                            <div class="flex items-center gap-1 sm:gap-2">
                                <div class="flex h-9 w-9 items-center justify-center">
                                    @if ($extension === 'pdf')
                                        <i class="fa-solid fa-file-pdf text-xl"></i>
                                    @else
                                        <i class="fa-solid fa-file-image text-xl"></i>
                                    @endif
                                </div>

                                <span class="text-xs font-medium text-gray-600 dark:text-slate-400 truncate block">
                                    {{ basename($item->file_type) }}
                                </span>
                            </div>

                            <button type="button" @click="previewDokumen('{{ $item->file_path }}')"
                                class="flex justify-end">
                                <div class="flex h-9 w-9 items-center justify-center">
                                    <i class="fa-solid fa-eye text-xl"></i>
                                </div>
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        {{-- BUKTI PENGIRIMAN PO --}}
        @php
            $shippingAttachments = $purchaseOrder->attachments->filter(
                fn($a) => str_contains(strtolower($a->file_type), 'pengiriman'),
            );
        @endphp
        @if ($shippingAttachments->isNotEmpty())
            <div x-data="{ open: true }" class="card overflow-hidden">
                <div class="card-header">
                    <div class="grid grid-cols-2 items-center">
                        <h2 class="font-bold">
                            Bukti Pengiriman
                        </h2>

                        <button @click="open = !open" class="flex items-center justify-end">
                            <div class="flex h-9 w-9 items-center justify-center">
                                <i class="fa-solid fa-chevron-down text-xl" :class="{ 'rotate-180': !open }"></i>
                            </div>
                        </button>
                    </div>
                </div>

                <div x-show="open" class="card-body">
                    <div class="grid grid-cols-2">
                        @foreach ($shippingAttachments as $item)
                            @php
                                $extension = pathinfo($item->file_path, PATHINFO_EXTENSION);
                            @endphp
                            <div class="flex items-center gap-1 sm:gap-2">
                                <div class="flex h-9 w-9 items-center justify-center">
                                    @if ($extension === 'pdf')
                                        <i class="fa-solid fa-file-pdf text-xl"></i>
                                    @else
                                        <i class="fa-solid fa-file-image text-xl"></i>
                                    @endif
                                </div>

                                <span class="text-xs font-medium text-gray-600 dark:text-slate-400 truncate block">
                                    {{ basename($item->file_type) }}
                                </span>
                            </div>

                            <button type="button" @click="previewDokumen('{{ $item->file_path }}')"
                                class="flex justify-end">
                                <div class="flex h-9 w-9 items-center justify-center">
                                    <i class="fa-solid fa-eye text-xl"></i>
                                </div>
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        {{-- BUKTI PEMBAYARAN --}}
        @php
            $paymentAttachments = $purchaseOrder->attachments->filter(
                fn($a) => str_contains(strtolower($a->file_type), 'pembayaran'),
            );
        @endphp
        @if ($paymentAttachments->isNotEmpty())
            <div x-data="{ open: true }" class="card overflow-hidden">
                <div class="card-header">
                    <div class="grid grid-cols-2 items-center">
                        <h2 class="font-bold">
                            Bukti Pembayaran
                        </h2>

                        <button @click="open = !open" class="flex items-center justify-end">
                            <div class="flex h-9 w-9 items-center justify-center">
                                <i class="fa-solid fa-chevron-down text-xl" :class="{ 'rotate-180': !open }"></i>
                            </div>
                        </button>
                    </div>
                </div>

                <div x-show="open" class="card-body">
                    <div class="grid grid-cols-2">
                        @foreach ($paymentAttachments as $item)
                            @php
                                $extension = pathinfo($item->file_path, PATHINFO_EXTENSION);
                            @endphp
                            <div class="flex items-center gap-1 sm:gap-2">
                                <div class="flex h-9 w-9 items-center justify-center">
                                    @if ($extension === 'pdf')
                                        <i class="fa-solid fa-file-pdf text-xl"></i>
                                    @else
                                        <i class="fa-solid fa-file-image text-xl"></i>
                                    @endif
                                </div>

                                <span class="text-xs font-medium text-gray-600 dark:text-slate-400 truncate block">
                                    {{ basename($item->file_type) }}
                                </span>
                            </div>

                            <button type="button" @click="previewDokumen('{{ $item->file_path }}')"
                                class="flex justify-end">
                                <div class="flex h-9 w-9 items-center justify-center">
                                    <i class="fa-solid fa-eye text-xl"></i>
                                </div>
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        @php
            $item = $purchaseOrder->attachments->first();
        @endphp
        <x-modal name="preview-file-modal" maxWidth="xl">
            <div class="p-6">
                <div class="flex items-center justify-end mb-4">
                    <button @click="closePreviewDokumen()"
                        class="flex h-10 w-10 items-center justify-center rounded-xl text-slate-400 transition hover:bg-slate-100 hover:text-slate-700 dark:hover:bg-slate-800 dark:hover:text-slate-200">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>
                @if (in_array(strtolower($extension), ['jpg', 'jpeg', 'png']))
                    <img src="{{ asset('storage/' . $item->file_path) }}" class="w-full rounded-xl">
                @elseif(strtolower($extension) == 'pdf')
                    <iframe src="{{ asset('storage/' . $item->file_path) }}" class=" h-[80vh] w-full ">
                    </iframe>
                @else
                    <p class=" text-center text-gray-400 italic ">
                        Tidak dapat menampilkan preview untuk jenis file ini.
                    </p>
                @endif
            </div>
        </x-modal>
    </div>

    <div class="sm:hidden">
        <div
            class="fixed bottom-0 left-0 z-50 pt-4 pb-8 px-4 border-t w-full bg-slate-50 text-slate-800 dark:bg-slate-900 dark:text-slate-200 border-slate-200 dark:border-slate-800">
            <div class="grid grid-cols-2 gap-4 items-center">
                <div class="grid grid-rows-2 gap-2">
                    <a href="{{ route('purchase-orders.payment', $purchaseOrder) }}"
                        class="btn bg-emerald-600 hover:bg-emerald-700 text-white">
                        <div class="flex h-9 w-9 items-center justify-center">
                            <i class="fa-solid fa-wallet text-xl"></i>
                        </div>
                        <p class="">Update Pembayaran</p>
                    </a>
                    <a href="{{ route('purchase-orders.shipping', $purchaseOrder) }}" class="btn-primary">
                        <div class="flex h-9 w-9 items-center justify-center">
                            <i class="fa-solid fa-truck-fast text-xl"></i>
                        </div>
                        <p class="">Update Status</p>
                    </a>
                </div>
                <div class="grid grid-rows-2 gap-2">
                    <a href="{{ route('purchase-orders.edit', $purchaseOrder) }}" class="btn-primary">
                        <div class="flex h-9 w-9 items-center justify-center">
                            <i class="fa-solid fa-pen-to-square text-xl"></i>
                        </div>
                        <p class="">Edit PO</p>
                    </a>
                    <a href="{{ route('purchase-orders.index', $purchaseOrder) }}" class="btn-secondary">
                        <div class="flex h-9 w-9 items-center justify-center">
                            <i class="fa-solid fa-arrow-left text-xl"></i>
                        </div>
                        <p class="">Kembali</p>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
