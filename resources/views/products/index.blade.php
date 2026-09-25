<x-app-layout>
    <div class="content" x-data="productManager()">
        <x-content-header title="Daftar Produk" buttonIndexUrl="{{ route('products.create') }}"
            typeButtonIndexUrl="Produk" />

        @if (session('success'))
            <x-alert type="success" title="Berhasil" :message="session('success')" />
        @elseif(session('error'))
            <x-alert type="error" title="Gagal" :message="session('error')" />
        @elseif(session('warning'))
            <x-alert type="warning" title="Perhatian" message="Data belum lengkap." />
        @endif

        <x-search-and-filter action="{{ route('products.index') }}" placeholder="Cari nama produk...">
            <option value="">Semua Satuan</option>
            @foreach ($units as $unitOption)
                <option value="{{ $unitOption }}" @selected(request('unit') === $unitOption)>
                    {{ $unitOption }}
                </option>
            @endforeach
        </x-search-and-filter>

        <x-pagenation-card type="product" :items="$products" />

        <x-delete-modal nameModal="delete-product-modal" typeModal="Produk" />
    </div>

    <x-bottom-action-bar type="index" buttonUrl="{{ route('products.create') }}" typeButtonUrl="Produk" />
</x-app-layout>
