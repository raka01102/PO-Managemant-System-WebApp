@props([
    'title',
    'buttonIndexUrl' => null,
    'buttonUrl' => null,
    'typeButtonIndexUrl' => null,
    'typeButtonUrl' => null,
    'unDoUrl' => null,
    'isIndex' => true,
    'isShow' => false,
])

<div
    class="flex px-4 pt-4 !mx-0 border-b border-slate-200 dark:border-slate-800 md:border-none md:pt-0 md:px-0 md:flex-row items-center md:justify-between">
    @if (!$isIndex)
        <a href="{{ $unDoUrl }}" class="md:hidden mr-2">
            <span class="h-8 w-8 md:h-10 md:w-10">
                <i class="fa-solid fa-arrow-left"></i>
            </span>
        </a>
    @endif

    <h1 class="text-2xl md:text-4xl font-bold tracking-tight
        text-slate-900 dark:text-white">
        {{ $title }}
    </h1>

    @if ($isIndex)
        <a href="{{ $buttonIndexUrl }}" class="hidden md:block">
            <x-primary-button type="button" class="btn-left-icon">
                <span class="h-6 w-6">
                    <i class="fa-solid fa-plus"></i>
                </span>
                Buat {{ $typeButtonIndexUrl }} Baru
            </x-primary-button>
        </a>
    @endif

    @if ($isShow)
        <div class="flex gap-4">
            <a href="{{ $unDoUrl }}">
                <x-secondary-button class="btn-left-icon hidden md:block">
                    <span class="h-6 w-6">
                        <i class="fa-solid fa-arrow-left"></i>
                    </span>
                    Kembali
                </x-secondary-button>
            </a>

            <a href="{{ $buttonUrl }}">
                <x-primary-button type="button" class="btn-left-icon hidden md:block">
                    <span class="h-6 w-6">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </span>
                    Edit {{ $typeButtonUrl }}
                </x-primary-button>
            </a>
        </div>
    @endif
</div>
