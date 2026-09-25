<x-app-layout>
    <div class="content">
        <x-content-header :isIndex="false" :isShow="true" title="Detail Produk"
            unDoUrl="{{ route('products.index') }}" buttonUrl="{{ route('products.edit', $product->id) }}"
            typeButtonUrl="Produk" />

        <div class="card">
            <div class="flex flex-col p-2 md:p-4 space-y-2 md:space-y-4">
                {{-- Product Code --}}
                <div>
                    <p class="text-xs text-slate-500">
                        Kode Produk
                    </p>

                    <p class="font-semibold">
                        {{ $product->code }}
                    </p>
                </div>

                {{-- Product Name --}}
                <div>
                    <p class="text-xs text-slate-500">
                        Nama Produk
                    </p>

                    <p class="font-semibold">
                        {{ $product->name }}
                    </p>
                </div>

                {{-- Product Unit --}}
                <div>
                    <p class="text-xs text-slate-500">
                        Satuan Produk
                    </p>

                    <p class="font-semibold">
                        {{ $product->unit }}
                    </p>
                </div>

                {{-- Product Price --}}
                <div>
                    <p class="text-xs text-slate-500">
                        Harga Produk
                    </p>

                    <p class="font-semibold">
                        {{ number_format($product->price, 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>

        <x-bottom-action-bar type="show" buttonUrl="{{ route('products.edit', $product->id) }}"
            typeButtonUrl="Produk" />
    </div>
</x-app-layout>
