<x-app-layout>
    <div class="mx-auto max-w-4xl px-4 py-6">
        <div class="mb-6">
            <h1 class="page-title">
                Update Pengiriman
            </h1>

            <p class="page-subtitle">
                {{ $purchaseOrder->po_number }}
            </p>
        </div>

        @php
            $steps = [
                'draft' => [
                    'label' => 'Draft',
                    'icon' => 'fa-file-circle-plus',
                ],
                'sent' => [
                    'label' => 'Dikirim',
                    'icon' => 'fa-truck-fast',
                ],
                'delivered' => [
                    'label' => 'Sampai',
                    'icon' => 'fa-box-open',
                ],
                'completed' => [
                    'label' => 'Selesai',
                    'icon' => 'fa-circle-check',
                ],
            ];

            $currentStep = array_search($purchaseOrder->delivery_status, array_keys($steps));

            $nextStatus = match ($purchaseOrder->delivery_status) {
                'draft' => 'sent',
                'partially_delivered' => 'delivered',
                'delivered' => 'completed',
                default => null,
            };
        @endphp

        <form action="{{ route('purchase-orders.shipping.update', $purchaseOrder) }}" method="POST"
            enctype="multipart/form-data" class="card p-6 space-y-6">
            @csrf
            @method('PATCH')

            <input type="hidden" name="status" value="{{ $purchaseOrder->delivery_status }}">

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

                <textarea name="note" rows="4" class="input mt-2"></textarea>
            </div>

            <div>
                <label class="label">
                    Upload Bukti
                </label>

                <input type="file" name="attachment" class="input mt-2 py-3">
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
    </div>
</x-app-layout>
