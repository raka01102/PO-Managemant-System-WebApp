<x-app-layout>
    <div class="content">
        @if ($errors->any())
            <div class="bg-red-100 p-4 rounded">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <x-content-header :isIndex="false" title="Edit Produk" unDoUrl="{{ route('products.show', $product->id) }}" />

        <form method="POST" action="{{ route('products.update', $product->id) }}">
            @csrf
            @method('PUT')

            <div class="card">
                <div class="flex flex-col p-2 md:p-4 space-y-2 md:space-y-4">
                    {{-- Product Code --}}
                    <div>
                        <x-input-label for="code" :value="__('Kode Produk')" />
                        <x-text-input id="code" class="block w-full mt-1" type="text" name="code"
                            :value="old('code', $product->code)" placeholder="contoh: PRD-0001" required autocomplete="code" />
                        <x-input-error :messages="$errors->get('code')" class="mt-2" />
                    </div>

                    {{-- Product Name --}}
                    <div>
                        <x-input-label for="name" :value="__('Nama Produk')" />
                        <x-text-input id="name" class="block w-full mt-1" type="text" name="name"
                            :value="old('name', $product->name)" placeholder="contoh: Kertas A4" required autocomplete="name" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    {{-- Product Unit --}}
                    <div>
                        <x-input-label for="unit" :value="__('Satuan Produk')" />
                        <x-text-input id="unit" class="block w-full mt-1" type="text" name="unit"
                            :value="old('unit', $product->unit)" placeholder="contoh: meter" required autocomplete="unit" />
                        <x-input-error :messages="$errors->get('unit')" class="mt-2" />
                    </div>

                    {{-- Product Price --}}
                    <div>
                        <x-input-label for="price" :value="__('Harga Produk')" />
                        <x-text-input id="price" class="block w-full mt-1" type="text" name="price"
                            inputmode="numeric" pattern="[0-9]*" :value="old('price', number_format($product->price, 0, ',', ''))" placeholder="contoh: 50000" required
                            min="0" autocomplete="price" oninput="this.value = this.value.replace(/\D/g, '')" />
                        <x-input-error :messages="$errors->get('price')" class="mt-2" />
                    </div>
                </div>
            </div>

            <x-bottom-action-bar :showOnDesktop="true" type="edit" buttonUrl="{{ route('products.show', $product) }}" />
        </form>
    </div>
</x-app-layout>
