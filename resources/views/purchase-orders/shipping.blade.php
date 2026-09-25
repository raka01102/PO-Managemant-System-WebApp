<x-app-layout>
    <div class="content">
        <x-content-header :isIndex="false" title="Update Pengiriman"
            unDoUrl="{{ route('purchase-orders.show', $purchaseOrder) }}" />

        @php
            $steps = [
                'draft' => [
                    'label' => 'Draft',
                    'icon' => 'fa-file-circle-plus',
                ],
                'partially_delivered' => [
                    'label' => 'Dikirim Sebagian',
                    'icon' => 'fa-truck-fast',
                ],
                'delivered' => [
                    'label' => 'Terkirim',
                    'icon' => 'fa-box-open',
                ],
                'completed' => [
                    'label' => 'Selesai',
                    'icon' => 'fa-circle-check',
                ],
                'cancelled' => [
                    'label' => 'Dibatalkan',
                    'icon' => 'fa-ban',
                ],
            ];

            $nextStatus = match ($purchaseOrder->delivery_status) {
                'draft' => 'partially_delivered',
                'partially_delivered' => 'delivered',
                'delivered' => 'completed',
                default => null,
            };
        @endphp

        @if ($nextStatus)
            <form action="{{ route('purchase-orders.shipping.update', $purchaseOrder) }}" method="POST"
                enctype="multipart/form-data" class="card p-6 space-y-6">
                @csrf
                @method('PATCH')

                <div class="flex items-center justify-between">
                    <div class="flex flex-col items-center gap-3">
                        <div
                            class="flex
                            h-14 w-14 items-center justify-center rounded-full bg-primary-100 text-primary-600">
                            <i class="fa-solid {{ $steps[$purchaseOrder->delivery_status]['icon'] }}"></i>
                        </div>

                        <span
                            class="text-xs font-bold uppercase tracking-wide text-primary-600 dark:text-primary-100">{{ $steps[$purchaseOrder->delivery_status]['label'] }}</span>
                    </div>

                    <div>
                        <i class="fa-solid fa-arrow-right"></i>
                    </div>

                    <div class="flex flex-col items-center gap-3">
                        <div
                            class="flex h-14 w-14 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
                            <i class="fa-solid {{ $steps[$nextStatus]['icon'] }}"></i>
                        </div>

                        <span
                            class="text-xs font-bold uppercase tracking-wide text-emerald-600 dark:text-emerald-100">{{ $steps[$nextStatus]['label'] }}</span>
                    </div>
                </div>

                <div>
                    <label class="label">
                        Catatan
                    </label>

                    <textarea name="note" rows="4" class="input mt-2">{{ old('note') }}</textarea>

                    <x-input-error :messages="$errors->get('note')" class="mt-2" />
                </div>

                <div>
                    <label class="label">
                        Upload Bukti
                    </label>

                    <input type="file" name="attachment" class="input mt-2 py-3">

                    <x-input-error :messages="$errors->get('attachment')" class="mt-2" />
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('purchase-orders.show', $purchaseOrder) }}" class="btn-secondary">
                        Batal
                    </a>

                    <button type="submit" class="btn-primary">
                        Simpan Pengiriman
                    </button>
                </div>
            </form>
        @else
            <div class="card p-6 space-y-4">
                <div class="flex flex-col items-center gap-3">
                    <div
                        class="flex h-14 w-14 items-center justify-center rounded-full bg-primary-100 text-primary-600">
                        <i
                            class="fa-solid {{ $steps[$purchaseOrder->delivery_status]['icon'] ?? 'fa-circle-check' }}"></i>
                    </div>

                    <span class="text-xs font-bold uppercase tracking-wide text-primary-600 dark:text-primary-100">
                        {{ $steps[$purchaseOrder->delivery_status]['label'] ?? $purchaseOrder->delivery_status }}
                    </span>
                </div>

                <p class="text-center text-sm text-slate-500 dark:text-slate-400">
                    PO ini sudah berada pada status pengiriman akhir dan tidak dapat diperbarui lebih lanjut.
                </p>

                <div class="flex justify-center">
                    <a href="{{ route('purchase-orders.show', $purchaseOrder) }}" class="btn-secondary">
                        Kembali
                    </a>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
