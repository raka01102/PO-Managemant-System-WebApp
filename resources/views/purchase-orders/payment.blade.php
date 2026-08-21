<x-app-layout>

    <div class="min-h-screen bg-slate-50 dark:bg-slate-950">

        <div class="mx-auto max-w-4xl px-4 py-6 sm:px-6 lg:px-8">

            <!-- HEADER -->
            <div class="mb-6">

                <h1 class="page-title">
                    Update Pembayaran
                </h1>

                <p class="page-subtitle">
                    Perbarui status pembayaran Purchase Order
                    <span class="font-semibold">
                        {{ $purchaseOrder->po_number }}
                    </span>
                </p>

            </div>

            <!-- INFO PO -->
            <div class="card mb-6">

                <div class="grid gap-6 p-6 md:grid-cols-3">

                    <div>

                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                            No. PO
                        </p>

                        <p class="mt-2 font-bold text-slate-900 dark:text-white">
                            {{ $purchaseOrder->po_number }}
                        </p>

                    </div>

                    <div>

                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Customer
                        </p>

                        <p class="mt-2 font-bold text-slate-900 dark:text-white">
                            {{ $purchaseOrder->customer->name }}
                        </p>

                    </div>

                    <div>

                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Total Tagihan
                        </p>

                        <p class="mt-2 text-lg font-black text-primary-600">
                            Rp {{ number_format($purchaseOrder->total_amount, 0, ',', '.') }}
                        </p>

                    </div>

                </div>

            </div>

            <!-- FORM -->
            <form action="{{ route('purchase-orders.payment.update', $purchaseOrder) }}" method="POST"
                enctype="multipart/form-data" class="card">

                @csrf
                @method('PATCH')

                <div class="space-y-6 p-6">

                    <!-- STATUS -->
                    <div>

                        <label class="label">
                            Status Pembayaran
                        </label>

                        <select name="is_paid" class="input mt-2" required>

                            <option value="0" @selected(!$purchaseOrder->is_paid)>
                                Belum Lunas
                            </option>

                            <option value="1" @selected($purchaseOrder->is_paid)>
                                Lunas
                            </option>

                        </select>

                        <x-input-error :messages="$errors->get('is_paid')" class="mt-2" />

                    </div>

                    <!-- NOTE -->
                    <div>

                        <label class="label">
                            Catatan Pembayaran
                        </label>

                        <textarea name="note" rows="4" placeholder="Tambahkan catatan pembayaran..." class="input mt-2">{{ old('note') }}</textarea>

                        <x-input-error :messages="$errors->get('note')" class="mt-2" />

                    </div>

                    <!-- FILE -->
                    <div x-data="{ fileName: '' }">

                        <label class="label">
                            Bukti Pembayaran
                        </label>

                        <div
                            class="
                                mt-2 rounded-2xl
                                border-2 border-dashed
                                border-slate-300
                                bg-slate-50
                                p-8

                                dark:border-slate-700
                                dark:bg-slate-900
                            ">

                            <div class="text-center">

                                <div
                                    class="
                                        mx-auto mb-4
                                        flex h-14 w-14 items-center justify-center
                                        rounded-2xl

                                        bg-emerald-100
                                        text-emerald-600

                                        dark:bg-emerald-900/20
                                        dark:text-emerald-400
                                    ">

                                    <i class="fa-solid fa-file-arrow-up text-lg"></i>

                                </div>

                                <label
                                    class="
                                        inline-flex cursor-pointer
                                        items-center gap-2

                                        rounded-xl
                                        bg-emerald-600
                                        px-4 py-2

                                        font-semibold
                                        text-white

                                        hover:bg-emerald-700
                                    ">

                                    <i class="fa-solid fa-upload"></i>

                                    Pilih File

                                    <input type="file" name="attachment" class="hidden"
                                        @change="
                                            fileName = $event.target.files[0]
                                                ? $event.target.files[0].name
                                                : ''
                                        ">

                                </label>

                                <p class="mt-3 text-sm text-slate-500"
                                    x-text="
                                        fileName
                                            ? fileName
                                            : 'JPG, PNG, PDF (Maks 2MB)'
                                    ">
                                </p>

                            </div>

                        </div>

                        <x-input-error :messages="$errors->get('attachment')" class="mt-2" />

                    </div>

                </div>

                <!-- FOOTER -->
                <div
                    class="
                        flex justify-end gap-3
                        border-t border-slate-200
                        px-6 py-5

                        dark:border-slate-800
                    ">

                    <a href="{{ route('purchase-orders.show', $purchaseOrder) }}" class="btn-secondary">
                        Kembali
                    </a>

                    <button type="submit" class="btn-primary">

                        <i class="fa-solid fa-wallet"></i>

                        Simpan Pembayaran

                    </button>

                </div>

            </form>

        </div>

    </div>

</x-app-layout>
