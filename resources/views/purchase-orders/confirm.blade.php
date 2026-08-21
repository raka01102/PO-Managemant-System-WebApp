<x-app-layout>
    <div class="mx-auto max-w-7xl space-y-4 px-4 md:px-6 md:py-4 lg:px-8" x-data="purchaseOrderConfirm({{ json_encode($extracted['items'] ?? []) }})">
        {{-- HEADER --}}
        <div
            class="flex flex-col pt-4 border-b border-slate-200 dark:border-slate-800 md:border-none md:pt-0 md:flex-row md:items-center md:justify-between md:gap-4">
            <h1 class="page-title">
                Konfirmasi Purchase Order
            </h1>
        </div>

        {{-- ALERT --}}
        <div class="card">
            <div class="flex items-center gap-2 md:gap-4 p-2 md:p-4">
                <div
                    class="flex h-9 w-9 items-center justify-center rounded-full bg-primary-100 text-primary-600 dark:bg-primary-900/20 dark:text-primary-400">
                    <i class="fa-solid fa-circle-info text-2xl"></i>
                </div>

                <div>
                    <p class="font-semibold text-slate-900 dark:text-white">
                        OCR Tidak Tersedia
                    </p>

                    <p class="text-sm text-slate-500 dark:text-slate-400">
                        Dokumen tidak diproses secara otomatis. Silakan masukkan data purchase order secara manual.
                    </p>
                </div>
            </div>
        </div>

        <form id="purchaseOrderForm" action="{{ route('purchase-orders.store') }}" method="POST">
            @csrf
            <input type="hidden" name="file_path" value="{{ $filePath }}">

            <div class="max-w-7xl mx-auto">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- KOLOM KIRI: INFO UTAMA -->
                    <div class="lg:col-span-1 lg:sticky lg:top-6 lg:self-start space-y-6">
                        <div class="card">
                            <div class="card-header">
                                <h2 class="text-lg font-semibold">
                                    Detail Konfirmasi
                                </h2>
                            </div>

                            <div class="card-body space-y-2 md:space-y-4">
                                <!-- Nomor PO -->
                                <div>
                                    <x-input-label for="po_number" :value="__('Nomor Purchase Order')" />
                                    <x-text-input id="po_number" class="block w-full mt-1" type="text"
                                        name="po_number" :value="old('po_number')" placeholder="contoh: PO-0001" required
                                        autocomplete="po_number" />
                                    <x-input-error :messages="$errors->get('po_number')" class="mt-2" />
                                </div>

                                <!-- Customer (Text Input) -->
                                <div>
                                    <x-input-label for="customer_name" :value="__('Nama Customer')" />
                                    <x-text-input id="customer_name" class="block w-full mt-1" type="text"
                                        name="customer_name" :value="old('customer_name')" placeholder="contoh: PT. ABC" required
                                        autocomplete="customer_name" />
                                    <x-input-error :messages="$errors->get('customer_name')" class="mt-2" />
                                </div>

                                <!-- Tanggal -->
                                <div>
                                    <x-input-label for="order_date" :value="__('Tanggal Dibuat')" />
                                    <x-text-input id="order_date" class="block w-full mt-1" type="date"
                                        name="order_date" :value="old('order_date', date('Y-m-d'))" placeholder="contoh: PT. ABC" required
                                        autocomplete="order_date" />
                                    <x-input-error :messages="$errors->get('order_date')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        {{-- Summary Card --}}
                        <div class="card hidden lg:block">
                            <div class="card-body flex flex-col gap-2">
                                <div class="grid grid-cols-2 gap-4 border-b border-slate-200 dark:border-slate-800">
                                    <div>
                                        <p class="text-xs text-slate-500">
                                            Total Item
                                        </p>
                                        <p class="mt-1 text-2xl font-black text-primary-600" x-text="totalItems">
                                        </p>
                                    </div>

                                    <div>
                                        <p class="text-xs text-slate-500">
                                            Total Qty
                                        </p>
                                        <p class="mt-1 text-2xl font-black text-emerald-600" x-text="totalQty">
                                        </p>
                                    </div>
                                </div>

                                <div>
                                    <p class="text-xs text-slate-500">
                                        Grand Total
                                    </p>

                                    <p class="mt-1 text-2xl font-black text-primary-600"
                                        x-text="formatRupiah(grandTotal)">
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Preview File (Opsional) -->
                        <div class="card">
                            <div class="card-header">
                                <h2 class="text-lg font-semibold">
                                    Dokumen Terlampir
                                </h2>
                            </div>

                            <div class="card-body flex items-center justify-between">
                                <div class="flex items-center gap-2 min-w-0 flex-1">
                                    <span class="h-9 w-9 flex items-center justify-center">
                                        @if (pathinfo($filePath, PATHINFO_EXTENSION) === 'pdf')
                                            <i class="fa-solid fa-file-pdf text-2xl text-red-400"></i>
                                        @else
                                            <i class="fa-solid fa-file-image text-2xl text-blue-400"></i>
                                        @endif
                                    </span>
                                    <span class="font-medium text-gray-600 dark:text-slate-400 truncate block">
                                        {{ basename($filePath) }}
                                    </span>
                                </div>

                                <div class="flex justify-center">
                                    @if (in_array(pathinfo($filePath, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif']))
                                        <x-secondary-button @click="previewDokumen('{{ $filePath }}')"
                                            class="btn-left-icon shrink-0">
                                            <i class="fa-solid fa-eye"></i>
                                            Preview
                                        </x-secondary-button>
                                    @endif
                                </div>
                            </div>

                            <!-- Image Preview Modal -->
                            @php
                                $extension = pathinfo($filePath, PATHINFO_EXTENSION);
                            @endphp

                            <x-modal name="preview-file-modal" maxWidth="xl">
                                <div class="p-6">
                                    @if (in_array(strtolower($extension), ['jpg', 'jpeg', 'png']))
                                        <img src="{{ asset('storage/' . $filePath) }}" class="w-full rounded-xl">
                                    @elseif(strtolower($extension) == 'pdf')
                                        <iframe src="{{ asset('storage/' . $filePath) }}" class=" h-[80vh] w-full ">
                                        </iframe>
                                    @else
                                        <p class=" text-center text-gray-400 italic ">
                                            Tidak dapat menampilkan preview untuk jenis file ini.
                                        </p>
                                    @endif
                                </div>
                            </x-modal>
                        </div>
                    </div>

                    <div class="card sticky top-0 z-50 bg-white dark:bg-slate-900 shadow lg:hidden">
                        <div class="flex items-center justify-between px-4 py-3">
                            <div>
                                <p class="text-xs text-slate-500">Grand Total</p>
                                <p class="font-bold text-primary-600" x-text="formatRupiah(grandTotal)">
                                </p>
                            </div>

                            <div class="text-right">
                                <p class="text-xs text-slate-500">
                                    <span x-text="totalItems"></span> Item
                                </p>
                                <p class="text-xs text-slate-500">
                                    Qty <span x-text="totalQty"></span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- KOLOM KANAN: DAFTAR ITEM -->
                    <div class="lg:col-span-2 space-y-4">
                        <div class="card overflow-hidden">
                            <div class="card-header flex justify-between items-center">
                                <h1 class="text-lg font-semibold text-slate-900 dark:text-white">
                                    Daftar Barang
                                </h1>
                                <x-secondary-button @click="addItem()" class="btn-left-icon">
                                    <i class="fa-solid fa-plus"></i>
                                    Item
                                </x-secondary-button>
                            </div>

                            <div class="overflow-x-auto">
                                <template x-for="(item, index) in items" :key="index">
                                    <div
                                        class="p-4 space-y-2 md:space-y-4 border-b border-slate-200 dark:border-slate-800">
                                        <div class="flex items-center justify-between">
                                            <h3 class="font-bold text-slate-900 dark:text-white">
                                                Item #
                                                <span x-text="index + 1"></span>
                                            </h3>

                                            <x-danger-button type="button" class="btn-icon"
                                                @click="removeItem(index)">
                                                <i class="fa-solid fa-trash"></i>
                                            </x-danger-button>
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                            {{-- Name Product --}}
                                            <div>
                                                <x-input-label x-bind:for="'name-items-' + index" :value="__('Nama Barang')" />
                                                <x-text-input x-bind:id="'name-items-' + index"
                                                    class="block w-full mt-1" type="text"
                                                    x-bind:name="`items[${index}][name]`" x-model="item.name"
                                                    placeholder="contoh: Kertas A4" required />
                                                <div x-show="errors[`items.${index}.name`]"
                                                    class="mt-2 text-sm text-red-600"
                                                    x-text="errors[`items.${index}.name`]?.[0]">
                                                </div>
                                            </div>

                                            {{-- Unit Product --}}
                                            <div>
                                                <x-input-label x-bind:for="'unit-items-' + index" :value="__('Satuan')" />
                                                <x-text-input x-bind:id="'unit-items-' + index"
                                                    class="block w-full mt-1" type="text"
                                                    x-bind:name="`items[${index}][unit]`" x-model="item.unit"
                                                    placeholder="contoh: meter" required />
                                                <div x-show="errors[`items.${index}.unit`]"
                                                    class="mt-2 text-sm text-red-600"
                                                    x-text="errors[`items.${index}.unit`]?.[0]"></div>
                                            </div>

                                            {{-- Price Product --}}
                                            <div>
                                                <x-input-label x-bind:for="'price_at_time-items-' + index"
                                                    :value="__('Harga Barang')" />
                                                <x-text-input x-bind:id="'price_at_time-items-' + index"
                                                    class="block w-full mt-1" type="text" inputmode="numeric"
                                                    x-bind:name="`items[${index}][price_at_time]`"
                                                    x-model="item.price_at_time" placeholder="contoh: 10000"
                                                    oninput="this.value = this.value.replace(/\D/g, '')" required />
                                                <div x-show="errors[`items.${index}.price_at_time`]"
                                                    class="mt-2 text-sm text-red-600"
                                                    x-text="errors[`items.${index}.price_at_time`]?.[0]">
                                                </div>
                                            </div>

                                            {{-- Quantity Product --}}
                                            <div>
                                                <x-input-label x-bind:for="'quantity-items-' + index"
                                                    :value="__('Jumlah Barang')" />
                                                <x-text-input x-bind:id="'quantity-items-' + index"
                                                    class="block w-full mt-1" type="text" inputmode="numeric"
                                                    x-bind:name="`items[${index}][quantity]`" x-model="item.quantity"
                                                    placeholder="contoh: 10"
                                                    oninput="this.value = this.value.replace(/\D/g, '')" required />
                                                <div x-show="errors[`items.${index}.quantity`]"
                                                    class="mt-2 text-sm text-red-600"
                                                    x-text="errors[`items.${index}.quantity`]?.[0]"></div>
                                            </div>
                                        </div>

                                        <div class="text-right">
                                            <p class="font-semibold">Total Harga</p>

                                            <p class="mt-1 text-2xl font-black text-primary-600"
                                                x-text="formatRupiah(subTotal)">
                                            </p>
                                        </div>
                                </template>
                            </div>
                        </div>

                        <!-- Footer Actions -->
                        <div class="flex justify-end gap-4">
                            <a href="{{ route('purchase-orders.create') }}">
                                <x-secondary-button class="btn">
                                    Batal
                                </x-secondary-button>
                            </a>

                            <x-primary-button class="btn-left-icon">
                                <span class="h-6 w-6">
                                    <i class="fa-solid fa-floppy-disk"></i>
                                </span>
                                Simpan
                            </x-primary-button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        {{-- Error Validation Modal --}}
        <x-modal name="validation-modal" maxWidth="md">
            <div class="p-6">
                <h2 class="text-lg font-bold text-rose-600">
                    Data Belum Lengkap
                </h2>

                <p class="mt-3 text-sm text-slate-500 dark:text-slate-400">
                    Pastikan semua item sudah diisi dengan benar. Periksa kembali data yang dimasukkan.
                </p>

                <div class="mt-6 text-right">
                    <button type="button" class="btn-primary"
                        @click=" $dispatch( 'close-modal', 'validation-modal' )">
                        Mengerti
                    </button>
                </div>
            </div>
        </x-modal>

        {{-- Confirmation Modal --}}
        <x-modal name="confirm-save-modal" maxWidth="md">
            <div class="p-6">
                <h2 class="text-xl font-bold text-slate-900 dark:text-white">
                    Simpan Purchase Order?
                </h2>
                <p class="mt-3 text-sm text-slate-500">
                    Pastikan seluruh data sudah benar sebelum disimpan.
                </p>

                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" class="btn-secondary"
                        @click="$dispatch( 'close-modal', 'confirm-save-modal' )">
                        Periksa Lagi
                    </button>

                    <button type="button" class="btn-primary" @click="confirmSubmit()">
                        Simpan
                    </button>
                </div>
            </div>
        </x-modal>

        {{-- Error Table Modal --}}
        <x-modal name="error-table-modal" maxWidth="md">
            <div class="p-6 text-center">
                <div
                    class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full border border-rose-100 bg-rose-50 text-rose-600 dark:border-rose-900 dark:bg-rose-900/20 dark:text-rose-400">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>

                <h2 class="text-lg font-bold text-rose-600">
                    Harus Memiliki Item
                </h2>

                <p class="mt-3 text-sm text-slate-500 dark:text-slate-400">
                    Purchase Order harus memiliki minimal satu item
                </p>

                <div class="mt-6 text-right">
                    <button type="button" class="btn-primary"
                        @click=" $dispatch( 'close-modal', 'error-table-modal' )">
                        Mengerti
                    </button>
                </div>
            </div>
        </x-modal>
    </div>
</x-app-layout>
