<x-app-layout>
    <div class="mx-auto max-w-7xl px-4 py-6" x-data="purchaseOrderEditManager()" x-init="customer_name = @js($purchaseOrder->customer->name);
    items = @js($items)">
        <form action="{{ route('purchase-orders.update', $purchaseOrder) }}" method="POST" id="purchaseOrderForm">
            @csrf
            @method('PUT')
            <div class="card">
                <div class="card-header">
                    <h1 class="text-xl font-bold">
                        Edit Purchase Order
                    </h1>

                    <p class="text-sm text-slate-500">
                        Perbarui data purchase order.
                    </p>
                </div>

                <div class="card-body space-y-6">
                    {{-- PO NUMBER --}}
                    <div>
                        <label class="label">
                            Nomor PO
                        </label>

                        <input type="text" name="po_number" value="{{ old('po_number', $purchaseOrder->po_number) }}"
                            class="input" required>
                    </div>

                    {{-- DATE --}}
                    <div>
                        <label class="label">
                            Tanggal PO
                        </label>

                        <input type="date" name="order_date"
                            value="{{ old('order_date', $purchaseOrder->order_date->format('Y-m-d')) }}" class="input"
                            required>
                    </div>

                    {{-- CUSTOMER --}}
                    <div>
                        <label class="label">
                            Nama Customer
                        </label>

                        <input id="customer_name" type="text" name="customer_name" x-model="customer_name"
                            @input.debounce.500ms="searchCustomers()" class="input"
                            placeholder="Masukan Nama Customer...">

                        <div x-show="suggestions.length"
                            class=" absolute z-50 mt-2 w-full rounded-2xl border border-slate-200 bg-white shadow-lg dark:border-slate-700 dark:bg-slate-900">
                            <template x-for="customer in suggestions" :key="customer.id">
                                <button type="button" @click="selectCustomer(customer)"
                                    class=" block w-full px-4 py-3 text-left hover:bg-slate-50 dark:hover:bg-slate-800">
                                    <span x-text="customer.name"></span>
                                </button>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ITEMS --}}
            <div class="card mt-6">
                <div class="card-header flex items-center justify-between">
                    <h2 class="font-semibold">
                        Item Purchase Order
                    </h2>

                    <button type="button" @click="addItem()" class="btn-primary">
                        <i class="fa-solid fa-plus"></i>
                        Tambah Item
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="table-head">
                            <tr>
                                <th class="px-4 py-3">
                                    Produk
                                </th>

                                <th class="px-4 py-3">
                                    Qty
                                </th>

                                <th class="px-4 py-3">
                                    Satuan
                                </th>

                                <th class="px-4 py-3">
                                    Harga
                                </th>

                                <th class="px-4 py-3">
                                    Subtotal
                                </th>

                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="(item,index) in items" :key="index">
                                <tr>
                                    <td class="p-3">
                                        <input type="hidden" :name="`items[${index}][id]`" x-model="item.id">
                                        <input :name="`items[${index}][name]`" x-model="item.name" class="input">
                                    </td>

                                    <td class="p-3">
                                        <input type="number" :name="`items[${index}][qty]`" x-model="item.qty"
                                            class="input">
                                    </td>

                                    <td class="p-3">
                                        <input type="text" :name="`items[${index}][unit]`" x-model="item.unit"
                                            class="input">
                                    </td>

                                    <td class="p-3">
                                        <input type="number" :name="`items[${index}][price_at_time]`"
                                            x-model="item.price_at_time" class="input">
                                    </td>

                                    <td class="p-3 font-bold">
                                        <span
                                            x-text="new Intl.NumberFormat('id-ID').format( (item.qty || 0) * (item.price_at_time || 0))"></span>
                                    </td>

                                    <td class="p-3">
                                        <button type="button" @click="removeItem(index)" class="text-rose-600">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- ACTION --}}
            <div class="mt-6 flex justify-end gap-3">
                <a href="{{ route('purchase-orders.show', $purchaseOrder) }}" class="btn-secondary">
                    Batal
                </a>

                <button type="submit" class="btn-primary">
                    <i class="fa-solid fa-floppy-disk"></i>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    <script>
        function purchaseOrderEdit() {
            return {

                customer_name: @json($purchaseOrder->customer->name) ?? '',
                suggestions: [],

                items: @json($items),

                async searchCustomers() {
                    if (this.customer_name.length < 2) {
                        this.suggestions = []
                        return
                    }

                    const response = await fetch(
                        `/customers/search?search=${this.customer_name}`
                    )

                    this.suggestions = await response.json()
                },

                selectCustomer(customer) {
                    this.customer_name = customer.name
                    this.suggestions = []
                },

                addItem() {
                    this.items.push({
                        product_name: '',
                        qty: 1,
                        unit: '',
                        price_at_time: 0,
                    })
                },

                get grandTotal() {
                    return this.items.reduce(
                        (sum, item) => {
                            const qty =
                                Number(item.qty) || 0
                            const price =
                                Number(item.price_at_time) || 0
                            return sum + (qty * price)
                        },
                        0
                    )
                },

                removeItem(index) {
                    this.items.splice(index, 1)
                }

            }
        }
    </script>

</x-app-layout>
