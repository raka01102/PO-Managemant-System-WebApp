<x-app-layout>
    <div class="content" x-data="customerManager()">
        <x-content-header title="Daftar Customer" buttonIndexUrl="{{ route('customers.create') }}"
            typeButtonIndexUrl="Customer" />

        @if (session('success'))
            <x-alert type="success" title="Berhasil" :message="session('success')" />
        @elseif(session('error'))
            <x-alert type="error" title="Gagal" :message="session('error')" />
        @elseif(session('warning'))
            <x-alert type="warning" title="Perhatian" message="Data belum lengkap." />
        @endif

        <x-search-and-filter action="{{ route('customers.index') }}" placeholder="Cari nama customer..." />

        <x-pagenation-card type="customer" :items="$customers" />

        <x-delete-modal nameModal="delete-customer-modal" typeModal="Customer" />
    </div>

    <x-bottom-action-bar type="index" buttonUrl="{{ route('customers.create') }}" typeButtonUrl="Customer" />
</x-app-layout>
