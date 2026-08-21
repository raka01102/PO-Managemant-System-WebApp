<x-app-layout>
    <div class="mx-auto max-w-7xl space-y-4 md:px-6 md:py-4 lg:px-8">
        {{-- HEADER --}}
        <div
            class="flex px-4 pt-4 border-b border-slate-200 dark:border-slate-800 md:border-none md:pt-0 md:flex-row items-center md:justify-between">
            <a href="{{ route('products.index') }}" class="md:hidden mr-2">
                <span class="h-8 w-8 md:h-10 md:w-10">
                    <i class="fa-solid fa-arrow-left"></i>
                </span>
            </a>

            <h1 class="page-title">
                Detail Produk
            </h1>

            <div class="flex gap-4">
                <a href="{{ route('products.index') }}">
                    <x-secondary-button class="btn-left-icon hidden md:block">
                        <span class="h-6 w-6">
                            <i class="fa-solid fa-arrow-left"></i>
                        </span>
                        Kembali
                    </x-secondary-button>
                </a>

                <a href="{{ route('products.edit', $product->id) }}">
                    <x-primary-button type="button" class="btn-left-icon hidden md:block">
                        <span class="h-6 w-6">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </span>
                        Edit Produk
                    </x-primary-button>
                </a>
            </div>
        </div>

        <div class="card mx-4 ">
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

        {{-- Button for create PO Mobile --}}
        <div
            class="absolute bottom-16 text-center justify-center z-50 p-4 w-full border-t border-slate-200 bg-slate-50 dark:border-slate-800 dark:bg-slate-900 md:hidden">
            <a href="{{ route('products.edit', $product->id) }}">
                <x-primary-button type="button" class="btn-left-icon w-full">
                    <span class="h-6 w-6">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </span>
                    Edit Produk
                </x-primary-button>
            </a>
        </div>
    </div>
</x-app-layout>
