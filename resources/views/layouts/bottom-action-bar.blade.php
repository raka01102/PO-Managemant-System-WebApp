@props([
    'showOnDesktop' => false,
    'type' => null,
    'buttonUrl' => null,
    'typeButtonUrl' => null,
    'unDoUrl' => null,
    'paymentUrl' => null,
    'shippingUrl' => null,
    'editUrl' => null,
])

<div
    {{ $attributes->merge(['class' => 'absolute bottom-16 left-0 z-20 py-2 px-4 border-t w-full bg-slate-50 text-slate-800 dark:bg-slate-900 dark:text-slate-200 border-slate-200 dark:border-slate-800 ' . ($showOnDesktop ? 'md:static md:flex md:justify-end gap-4 md:py-0 md:px-0 md:mt-4 md:bg-transparent dark:md:bg-transparent md:border-none' : 'md:hidden')]) }}>
    @switch($type)
        @case('index')
            <a href="{{ $buttonUrl }}" class="md:hidden">
                <x-primary-button type="button" class="btn-left-icon w-full">
                    <span class="h-6 w-6">
                        <i class="fa-solid fa-plus"></i>
                    </span>
                    Buat {{ $typeButtonUrl }} Baru
                </x-primary-button>
            </a>
        @break

        @case('create')
            <a href="{{ $buttonUrl }}" class="hidden md:block">
                <x-secondary-button class="btn-left-icon">
                    <i class="fa-solid fa-arrow-left"></i>
                    Kembali
                </x-secondary-button>
            </a>

            <x-primary-button class="btn-left-icon w-full md:w-auto">
                <i class="fa-solid fa-plus"></i>
                Tambah Data
            </x-primary-button>
        @break

        @case('showPO')
            <div class="flex flex-col w-full space-y-2">
                <a href="{{ $paymentUrl }}">
                    <x-secondary-button class="btn-left-icon !bg-emerald-600 !hover:bg-emerald-700 !h-full !w-full">
                        <i class="fa-solid fa-wallet text-xl"></i>
                        Update Pembayaran
                    </x-secondary-button>
                </a>

                <a href="{{ $shippingUrl }}">
                    <x-primary-button type="button" class="btn-left-icon !h-full !w-full">
                        <i class="fa-solid fa-truck-fast text-xl"></i>
                        Update Pengiriman
                    </x-primary-button>
                </a>

                <a href="{{ $editUrl }}">
                    <x-info-button class="btn-left-icon !h-full !w-full">
                        <i class="fa-solid fa-pen-to-square text-xl"></i>
                        Edit PO
                    </x-info-button>
                </a>
            </div>
        @break

        @case('show')
            <a href="{{ $buttonUrl }}">
                <x-primary-button type="button" class="btn-left-icon w-full">
                    <i class="fa-solid fa-pen-to-square"></i>
                    Edit {{ $typeButtonUrl }}
                </x-primary-button>
            </a>
        @break

        @case('edit')
            <a href="{{ $buttonUrl }}" class="hidden md:block">
                <x-secondary-button class="btn-left-icon">
                    <i class="fa-solid fa-arrow-left"></i>
                    Batal
                </x-secondary-button>
            </a>

            <x-primary-button class="btn-left-icon w-full md:w-auto">
                <i class="fa-solid fa-plus"></i>
                Simpan Perubahan
            </x-primary-button>
        @break

        @default
    @endswitch
</div>
