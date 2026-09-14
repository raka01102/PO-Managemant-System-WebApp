<x-app-layout>
    <div class="content" x-data="purchaseOrderEditManager(
        @js(old('customer_name', $purchaseOrder->customer->name ?? '')),
        @js(old('items', $items)),
        @js($errors->toArray())
    )">
        <div class="content-header">
            <a href="{{ route('purchase-orders.show', $purchaseOrder) }}" class="md:hidden mr-2">
                <span class="h-8 w-8 md:h-10 md:w-10">
                    <i class="fa-solid fa-arrow-left"></i>
                </span>
            </a>

            <h1 class="page-title">
                Edit Purchase Order
            </h1>
        </div>

        <form action="{{ route('purchase-orders.update', $purchaseOrder) }}" method="POST" id="purchaseOrderForm">
            @csrf
            @method('PUT')
            <div>
                <div class="card">
                    <div class="card-body space-y-4">
                        {{-- PO NUMBER --}}
                        <div>
                            <x-input-label for="po_number" :value="__('Nomor Purchase Order')" />

                            <x-text-input id="po_number" class="block w-full mt-1" type="text" name="po_number"
                                :value="old('po_number', $purchaseOrder->po_number)" placeholder="contoh: PO-0001" required autocomplete="po_number" />

                            <x-input-error :messages="$errors->get('po_number')" class="mt-2" />
                        </div>

                        {{-- CUSTOMER --}}
                        <div>
                            <x-input-label for="customer_name" :value="__('Nama Customer')" />

                            <x-text-input id="customer_name" class="block w-full mt-1" type="text"
                                name="customer_name" :value="old('customer_name', $purchaseOrder->customer->name)" placeholder="contoh: PT. ABC" required
                                autocomplete="customer_name" />

                            <x-input-error :messages="$errors->get('customer_name')" class="mt-2" />
                        </div>

                        {{-- DATE --}}
                        <div>
                            <x-input-label for="order_date" :value="__('Tanggal Dibuat')" />

                            <x-text-input id="order_date" class="block w-full mt-1" type="date" name="order_date"
                                :value="old(
                                    'order_date',
                                    $purchaseOrder->order_date->format('Y-m-d') ?? date('Y-m-d'),
                                )" placeholder="contoh: PT. ABC" required autocomplete="order_date" />

                            <x-input-error :messages="$errors->get('order_date')" class="mt-2" />
                        </div>
                    </div>
                </div>

                {{-- ITEMS --}}
                <div class="card mt-4">
                    <div class="card-header">
                        <h2 class="font-semibold">
                            Item Purchase Order
                        </h2>

                        <x-info-button @click="addItem()" class="btn-left-icon">
                            <i class="fa-solid fa-plus"></i>
                            Tambah Item
                        </x-info-button>
                    </div>

                    <div class="overflow-x-auto">
                        <template x-for="(item, index) in items" :key="item.uid">
                            <div class="p-4 space-y-2 md:space-y-4 border-b border-slate-200 dark:border-slate-800">
                                <input type="hidden" x-bind:name="`items[${index}][id]`" x-model="item.id">

                                <div class="flex items-center justify-between">
                                    <h3 class="font-bold text-slate-900 dark:text-white">
                                        Item #
                                        <span x-text="index + 1"></span>
                                    </h3>

                                    <x-danger-button type="button" class="btn-icon" @click="removeItem(index)">
                                        <i class="fa-solid fa-trash"></i>
                                    </x-danger-button>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                    {{-- Name Product --}}
                                    <div>
                                        <x-input-label x-bind:for="'name-items-' + index" :value="__('Nama Barang')" />

                                        <x-text-input x-bind:id="'name-items-' + index" class="block w-full mt-1"
                                            type="text" x-bind:name="`items[${index}][name]`" x-model="item.name"
                                            placeholder="contoh: Kertas A4" required />

                                        <div x-cloak x-show="errors[`items.${index}.name`]"
                                            class="mt-2 text-sm text-red-600"
                                            x-text="errors[`items.${index}.name`]?.[0]">
                                        </div>
                                    </div>

                                    {{-- Unit Product --}}
                                    <div>
                                        <x-input-label x-bind:for="'unit-items-' + index" :value="__('Satuan')" />

                                        <x-text-input x-bind:id="'unit-items-' + index" class="block w-full mt-1"
                                            type="text" x-bind:name="`items[${index}][unit]`" x-model="item.unit"
                                            placeholder="contoh: meter" required />

                                        <div x-cloak x-show="errors[`items.${index}.unit`]"
                                            class="mt-2 text-sm text-red-600"
                                            x-text="errors[`items.${index}.unit`]?.[0]">
                                        </div>
                                    </div>

                                    {{-- Price Product --}}
                                    <div>
                                        <x-input-label x-bind:for="'price_at_time-items-' + index" :value="__('Harga Barang')" />

                                        <x-text-input x-bind:id="'price_at_time-items-' + index"
                                            class="block w-full mt-1" type="text" inputmode="numeric"
                                            x-bind:name="`items[${index}][price_at_time]`" x-model="item.price_at_time"
                                            placeholder="contoh: 10000"
                                            oninput="this.value = this.value.replace(/\D/g, '')" required />

                                        <div x-cloak x-show="errors[`items.${index}.price_at_time`]"
                                            class="mt-2 text-sm text-red-600"
                                            x-text="errors[`items.${index}.price_at_time`]?.[0]">
                                        </div>
                                    </div>

                                    {{-- Quantity Product --}}
                                    <div>
                                        <x-input-label x-bind:for="'quantity-items-' + index" :value="__('Jumlah Barang')" />

                                        <x-text-input x-bind:id="'quantity-items-' + index" class="block w-full mt-1"
                                            type="text" inputmode="numeric"
                                            x-bind:name="`items[${index}][quantity]`" x-model="item.quantity"
                                            placeholder="contoh: 10"
                                            oninput="this.value = this.value.replace(/\D/g, '')" required />

                                        <div x-cloak x-show="errors[`items.${index}.quantity`]"
                                            class="mt-2 text-sm text-red-600"
                                            x-text="errors[`items.${index}.quantity`]?.[0]"></div>
                                    </div>
                                </div>

                                <div class="text-right">
                                    <p class="font-semibold">Total Harga</p>

                                    <p class="mt-1 text-2xl font-black text-blue-600"
                                        x-text="formatRupiah(itemSubTotal(item))">
                                    </p>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- ACTION --}}
                <x-bottom-action-bar :showOnDesktop="true">
                    <a href="{{ route('purchase-orders.show', $purchaseOrder) }}" class="hidden md:block">
                        <x-secondary-button class="btn-left-icon">
                            <i class="fa-solid fa-arrow-left"></i>
                            Batal
                        </x-secondary-button>
                    </a>

                    <x-primary-button class="btn-left-icon w-full md:w-auto">
                        <span class="h-6 w-6">
                            <i class="fa-solid fa-plus"></i>
                        </span>
                        Simpan Perubahan
                    </x-primary-button>
                </x-bottom-action-bar>
            </div>
        </form>
    </div>
</x-app-layout>
