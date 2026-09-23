<x-app-layout>
    <div class="mx-auto max-w-7xl space-y-4 px-4 md:px-6 md:py-4 lg:px-8" x-data="productManager()">
        {{-- HEADER --}}
        <div
            class="flex pt-4 border-b border-slate-200 dark:border-slate-800 md:border-none md:pt-0 md:flex-row md:items-center md:justify-between md:gap-4">
            <h1 class="page-title">
                Daftar Produk
            </h1>

            <a href="{{ route('products.create') }}" class="hidden md:block">
                <x-primary-button type="button" class="btn-left-icon">
                    <span class="h-6 w-6">
                        <i class="fa-solid fa-plus"></i>
                    </span>
                    Buat Produk Baru
                </x-primary-button>
            </a>
        </div>

        @if (session('success'))
            <x-alert type="success" title="Berhasil" :message="session('success')" />
        @elseif(session('error'))
            <x-alert type="error" title="Gagal" :message="session('error')" />
        @elseif(session('warning'))
            <x-alert type="warning" title="Perhatian" message="Data belum lengkap." />
        @endif

        {{-- Search & Filter --}}
        <form method="GET" action="{{ route('products.index') }}" class="flex items-center gap-2 md:gap-4">
            <div class="flex-1 relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>

                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama produk"
                    class="w-full rounded-xl border border-slate-200 py-3 lg:py-2 pl-10 pr-4 dark:border-slate-700 dark:bg-slate-950 dark:text-white">
            </div>

            <select name="unit" onchange="this.form.submit()"
                class="rounded-xl border border-slate-200 py-3 lg:py-2 px-4 dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                <option value="">Semua Satuan</option>
                @foreach ($units as $unitOption)
                    <option value="{{ $unitOption }}" @selected(request('unit') === $unitOption)>
                        {{ $unitOption }}
                    </option>
                @endforeach
            </select>
        </form>

        {{-- Card Product --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-2 md:gap-4">
            @forelse ($products as $product)
                <div class="card">
                    <div class="card-header flex items-center justify-between">
                        <div class="flex gap-1 items-center">
                            <span class="flex items-center w-6 h-6">
                                <i class="fa-solid fa-cubes"></i>
                            </span>

                            <div class="flex flex-col font-semibold">
                                <p>
                                    {{ $product->code }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="card-body space-y-2">
                        <div class="flex flex-col">
                            <p class="text-base font-semibold">{{ $product->name }}</p>

                            <p class="text-xs text-slate-500">{{ $product->unit }}</p>
                        </div>

                        <div class="flex justify-between items-center">
                            <div class="flex flex-col">
                                <p class="text-xs">Harga Barang</p>

                                <p class="text-base font-bold">Rp
                                    {{ number_format($product->price, 0, ',', '.') }}</p>
                            </div>

                            <div class="flex gap-2">
                                <x-danger-button @click="initDelete({{ json_encode($product) }})" class="btn-icon">
                                    <span class="w-6 h-6">
                                        <i class="fa-solid fa-trash"></i>
                                    </span>
                                </x-danger-button>

                                <a href="{{ route('products.show', $product->id) }}">
                                    <x-info-button class="btn-icon">
                                        <span class="w-6 h-6">
                                            <i class="fa-solid fa-eye"></i>
                                        </span>
                                    </x-info-button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center text-sm text-slate-500 py-8">
                    Tidak ada produk yang cocok dengan pencarian.
                </div>
            @endforelse
        </div>

        {{-- PAGINATION --}}
        <div>
            {{ $products->links() }}
        </div>

        <!-- DELETE MODAL -->
        <x-modal name="delete-product-modal" maxWidth="md">
            <div class="flex flex-col p-6 gap-2 md:gap-4">
                <div class="flex gap-2 md:gap-4 items-center">
                    {{-- ICON --}}
                    <div
                        class="flex h-9 w-9 items-center justify-center rounded-lg bg-rose-100 text-rose-600 dark:bg-rose-900/20 dark:text-rose-400">
                        <i class="fa-solid fa-trash-can text-2xl"></i>
                    </div>

                    <h2 class="text-2xl font-bold text-slate-900 dark:text-white">
                        Hapus Data Produk
                    </h2>
                </div>

                <p>Data yang telah dihapus tidak bisa dikembalikan. Apakah anda ingin melakukan tindakan ini?</p>

                <!-- ACTION -->
                <form :action="actionUrl" method="POST" class="flex items-center justify-end gap-2 md:gap-4">
                    @csrf
                    @method('DELETE')
                    <x-secondary-button @click="$dispatch('close-modal', 'delete-product-modal')" class="btn">
                        Simpan Data
                    </x-secondary-button>

                    <x-danger-button class="btn-left-icon">
                        <i class="fa-solid fa-trash-can"></i>
                        Hapus Produk
                    </x-danger-button>
                </form>
            </div>
        </x-modal>
    </div>

    {{-- Button for create PO Mobile --}}
    <div
        class="absolute bottom-16 text-center justify-center z-40 p-4 w-full border-t border-slate-200 bg-slate-50 dark:border-slate-800 dark:bg-slate-900 md:hidden">
        <a href="{{ route('products.create') }}" class="md:hidden">
            <x-primary-button type="button" class="btn-left-icon w-full">
                <span class="h-6 w-6">
                    <i class="fa-solid fa-plus"></i>
                </span>
                Buat Produk Baru
            </x-primary-button>
        </a>
    </div>
</x-app-layout>
