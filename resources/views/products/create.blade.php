<x-app-layout>
    <div class="mx-auto max-w-7xl md:px-6 md:py-4 lg:px-8">
        {{-- FORM --}}
        <form method="POST" action="{{ route('products.store') }}">
            @csrf
            {{-- HEADER --}}
            <div
                class="flex px-4 pt-4 border-b border-slate-200 dark:border-slate-800 md:border-none md:pt-0 md:flex-row items-center md:justify-between">
                <a href="{{ route('products.index') }}" class="md:hidden mr-2">
                    <span class="h-8 w-8 md:h-10 md:w-10">
                        <i class="fa-solid fa-arrow-left"></i>
                    </span>
                </a>

                <h1 class="page-title">
                    Buat Produk Baru
                </h1>
            </div>

            @if ($errors->any())
                <div class="bg-red-100 p-4 rounded">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card m-4">
                <div class="flex flex-col p-2 md:p-4 space-y-2 md:space-y-4">
                    {{-- Product Code --}}
                    <div>
                        <x-input-label for="code" :value="__('Kode Produk')" />
                        <x-text-input id="code" class="block w-full mt-1" type="text" name="code"
                            :value="old('code', $newCode)" placeholder="contoh: PRD-0001" required autocomplete="code" />
                        <x-input-error :messages="$errors->get('code')" class="mt-2" />
                    </div>

                    {{-- Product Name --}}
                    <div>
                        <x-input-label for="name" :value="__('Nama Produk')" />
                        <x-text-input id="name " class="block w-full mt-1" type="text" name="name"
                            :value="old('name')" placeholder="contoh: Kertas A4" required autocomplete="name" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    {{-- Product Unit --}}
                    <div>
                        <x-input-label for="unit" :value="__('Satuan Produk')" />
                        <x-text-input id="unit" class="block w-full mt-1" type="text" name="unit"
                            :value="old('unit')" placeholder="contoh: meter" required autocomplete="unit" />
                        <x-input-error :messages="$errors->get('unit')" class="mt-2" />
                    </div>

                    {{-- Product Price --}}
                    <div>
                        <x-input-label for="price" :value="__('Harga Produk')" />
                        <x-text-input id="price" class="block w-full mt-1" type="text" name="price"
                            inputmode="numeric" pattern="[0-9]*" :value="old('price')" placeholder="contoh: 50000" required
                            autocomplete="price" oninput="this.value = this.value.replace(/\D/g, '')" />
                        <x-input-error :messages="$errors->get('price')" class="mt-2" />
                    </div>

                    {{-- Button --}}
                    <div class="flex gap-4 justify-end">
                        <a href="{{ route('products.index') }}" class="hidden md:block">
                            <x-secondary-button class="btn-left-icon">
                                <span class="h-6 w-6">
                                    <i class="fa-solid fa-arrow-left"></i>
                                </span>
                                Kembali
                            </x-secondary-button>
                        </a>

                        <x-primary-button class="btn-left-icon hidden md:block">
                            <span class="h-6 w-6">
                                <i class="fa-solid fa-plus"></i>
                            </span>
                            Tambah Data
                        </x-primary-button>
                    </div>
                </div>
            </div>

            {{-- Button for create PO Mobile --}}
            <div
                class="absolute bottom-16 text-center justify-center z-50 p-4 w-full border-t border-slate-200 bg-slate-50 dark:border-slate-800 dark:bg-slate-900 md:hidden">
                <x-primary-button class="btn-left-icon w-full">
                    <span class="h-6 w-6">
                        <i class="fa-solid fa-plus"></i>
                    </span>
                    Tambah Data
                </x-primary-button>
            </div>
        </form>
    </div>
</x-app-layout>
