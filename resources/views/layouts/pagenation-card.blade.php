@props(['type' => null, 'items' => []])

@php
    $icons = [
        'purchaseOrder' => 'fa-file text-2xl',
        'product' => 'fa-cubes',
        'customer' => 'fa-user',
    ];

    $statusMap = [
        'delivered' => [
            'paid' => ['Selesai', 'bg-green-100 text-green-600 dark:bg-green-900/20 dark:text-green-400'],
            'partial' => ['Dibayar Sebagian', 'bg-amber-100 text-amber-600 dark:bg-amber-900/20 dark:text-amber-400'],
            'default' => ['Belum Dibayar', 'bg-rose-100 text-rose-600 dark:bg-rose-900/20 dark:text-rose-400'],
        ],
        'partially_delivered' => [
            'Dikirim Sebagian',
            'bg-blue-100 text-blue-600 dark:bg-blue-900/20 dark:text-blue-400',
        ],
        'default' => ['Diproses', 'bg-blue-100 text-blue-600 dark:bg-blue-900/20 dark:text-blue-400'],
    ];

    $resolveStatus = function ($item) use ($statusMap) {
        if ($item->delivery_status === 'delivered') {
            return $statusMap['delivered'][$item->payment_status] ?? $statusMap['delivered']['default'];
        }

        return $statusMap[$item->delivery_status] ?? $statusMap['default'];
    };

    $isPO = $type === 'purchaseOrder';
    $isCustomer = $type === 'customer';
@endphp

<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-2 md:gap-4">
    @forelse ($items as $item)
        @php
            [$status, $statusStyle] = $isPO ? $resolveStatus($item) : [null, null];

            $firstItem = $isPO ? $item->items->first() : null;

            $otherItemsCount = $isPO ? $item->items->count() - 1 : 0;

            $valueClick = $isPO ? '{{ $item->id }}, {{ Js::from($item->po_number) }}' : '{{ json_encode($item) }}';
        @endphp

        <div class="card">
            <div class="card-header flex items-center justify-between">
                <div class="flex gap-1 items-center">
                    <i class="fa-solid {{ $icons[$type] }}"></i>

                    <div class="flex flex-col font-semibold">
                        <p>{{ $isPO ? $item->po_number : $item->code }}</p>

                        @if ($isPO)
                            <div class="flex text-xs text-slate-500 divide-x divide-slate-200 dark:divide-slate-800">
                                <p>{{ $item->customer->name }}</p>
                                <p>{{ $item->order_date->format('d-m-Y') }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                @if ($isPO)
                    <span class="rounded-lg px-4 py-2 text-xs font-semibold {{ $statusStyle }}">
                        {{ $status }}
                    </span>
                @endif
            </div>

            <div class="card-body space-y-2">
                @if ($isCustomer)
                    <p class="text-base font-semibold">{{ $item->name }}</p>

                    @if ($item->address)
                        <p class="capitalize">{{ $item->address }}</p>
                    @else
                        <p class="text-slate-500">Belum memasukan alamat</p>
                    @endif
                @elseif ($isPO)
                    <div class="flex flex-col">
                        @if ($firstItem)
                            <p class="text-base font-semibold">{{ $firstItem->product['code'] ?? '' }} |
                                {{ $firstItem->product['name'] ?? 'Produk telah dihapus' }}</p>

                            <p class="text-xs text-slate-500">{{ $firstItem->quantity }} Barang</p>
                        @else
                            <p class="text-xs text-slate-500">Belum ada item</p>
                        @endif
                    </div>

                    <div class="sm:min-h-6">
                        @if ($otherItemsCount > 0)
                            <p class="text-base">+{{ $otherItemsCount }} Barang Lainnya</p>
                        @endif
                    </div>
                @else
                    <div class="flex flex-col">
                        <p class="text-base font-semibold">{{ $item->name }}</p>
                        <p class="text-xs text-slate-500">{{ $item->unit }}</p>
                    </div>
                @endif

                <div class="flex justify-between items-center">
                    @if ($isCustomer)
                        <div class="flex gap-2 items-center justify-center">
                            <i class="fa-solid fa-square-phone text-base"></i>
                            @if ($item->phone)
                                @if (str_contains($item->phone, ','))
                                    <div class="flex flex-col">
                                        @foreach (explode(',', $item->phone) as $phone)
                                            <p>{{ trim($phone) }}</p>
                                        @endforeach
                                    </div>
                                @else
                                    <p>{{ $item->phone }}</p>
                                @endif
                            @else
                                <p class="text-xs text-slate-500">Belum memasukan nomor telepon</p>
                            @endif
                        </div>
                    @else
                        <div class="flex flex-col">
                            <p class="text-xs">{{ $isPO ? 'Total Harga' : 'Harga Barang' }}</p>
                            <p class="text-base font-bold">
                                Rp {{ number_format($isPO ? $item->total_amount : $item->price, 0, ',', '.') }}
                            </p>
                        </div>
                    @endif

                    <div class="flex gap-2">
                        <x-danger-button @click="initDelete({{ $valueClick }})" class="btn-icon">
                            <span class="w-6 h-6"><i class="fa-solid fa-trash"></i></span>
                        </x-danger-button>

                        <a
                            href="{{ $isPO ? route('purchase-orders.show', $item) : route($type . 's.show', $item->id) }}">
                            <x-info-button class="btn-icon">
                                <span class="w-6 h-6"><i class="fa-solid fa-eye"></i></span>
                            </x-info-button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-span-full text-center text-sm text-slate-500 py-8">
            Tidak ada data yang cocok dengan pencarian
        </div>
    @endforelse
</div>

<div>
    {{ $items->links() }}
</div>
