<x-app-layout>
    <div class="content" x-data="purchaseOrderManager()" data-delete-url="{{ route('purchase-orders.destroy', ':id') }}">
        {{-- HEADER --}}
        <x-content-header title="Daftar Purchase Order" buttonIndexUrl="{{ route('purchase-orders.create') }}"
            typeButtonIndexUrl="PO" />

        @if (session('success'))
            <x-alert type="success" title="Berhasil" :message="session('success')" />
        @elseif(session('error'))
            <x-alert type="error" title="Gagal" :message="session('error')" />
        @elseif(session('warning'))
            <x-alert type="warning" title="Perhatian" message="Data belum lengkap." />
        @endif

        {{-- Search & Filter --}}
        <x-search-and-filter action="{{ route('purchase-orders.index') }}" placeholder="Cari nomor PO atau customer...">
            <option value="">Semua Status</option>
            <option value="draft" @selected(request('filter') === 'draft')>Diproses</option>
            <option value="shipping" @selected(request('filter') === 'shipping')>Dikirim</option>
            <option value="unpaid" @selected(request('filter') === 'unpaid' || request('filter') === 'partial')>Belum Lunas</option>
            <option value="paid" @selected(request('filter') === 'paid')>Lunas</option>
            <option value="paid" @selected(request('filter') === 'paid')>Selesai</option>
        </x-search-and-filter>

        <x-pagenation-card type="purchaseOrder" :items="$purchaseOrders" />

        <x-delete-modal nameModal="delete-po-modal" typeModal="Purchase Order" />
    </div>

    <x-bottom-action-bar type="index" buttonUrl="{{ route('purchase-orders.create') }}" typeButtonUrl="PO" />
</x-app-layout>
