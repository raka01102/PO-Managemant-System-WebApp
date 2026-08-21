@props(['active'])

@php
$classes = ($active ?? false)
            ? 'flex items-center gap-4 p-3 bg-[#003399] text-white rounded-xl shadow-lg shadow-blue-100 dark:shadow-none font-bold text-sm transition'
            : 'flex items-center gap-4 p-3 text-gray-500 dark:text-gray-400 hover:bg-blue-50 dark:hover:bg-slate-700 hover:text-[#003399] rounded-xl font-bold text-sm transition';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
