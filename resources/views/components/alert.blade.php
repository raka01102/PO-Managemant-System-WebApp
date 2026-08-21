@props([
    'type' => 'success',
    'title' => 'Berhasil',
    'message' => null,
])

@php
    $styles = [
        'success' => [
            'border' => 'border-green-200 dark:border-green-900',
            'iconBg' => 'bg-green-100 dark:bg-green-900/20',
            'iconText' => 'text-green-600 dark:text-green-400',
            'icon' => 'fa-solid fa-circle-check',
        ],

        'error' => [
            'border' => 'border-rose-200 dark:border-rose-900',
            'iconBg' => 'bg-rose-100 dark:bg-rose-900/20',
            'iconText' => 'text-rose-600 dark:text-rose-400',
            'icon' => 'fa-solid fa-circle-xmark',
        ],

        'warning' => [
            'border' => 'border-yellow-200 dark:border-yellow-900',
            'iconBg' => 'bg-yellow-100 dark:bg-yellow-900/20',
            'iconText' => 'text-yellow-600 dark:text-yellow-400',
            'icon' => 'fa-solid fa-triangle-exclamation',
        ],
    ];

    $style = $styles[$type];
@endphp

<div class="card {{ $style['border'] }}">
    <div class="flex items-center justify-between px-6 py-4">
        <div class="flex items-center gap-3">
            <div
                class="flex h-10 w-10 items-center justify-center rounded-xl {{ $style['iconBg'] }} {{ $style['iconText'] }}">
                <i class="{{ $style['icon'] }}"></i>
            </div>

            <div>
                <h3 class="font-semibold text-slate-900 dark:text-white">
                    {{ $title }}
                </h3>

                <p class="text-sm text-slate-500 dark:text-slate-400">
                    {{ $message }}
                </p>
            </div>
        </div>
    </div>
</div>
