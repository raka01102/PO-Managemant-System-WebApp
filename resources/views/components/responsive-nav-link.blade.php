@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-[#003399] dark:border-blue-400 text-start text-base font-medium text-[#003399] dark:text-blue-200 bg-[#f4f8ff] dark:bg-slate-700 focus:outline-none focus:text-blue-700 dark:focus:text-blue-100 focus:bg-[#e7f0ff] dark:focus:bg-slate-600 focus:border-blue-700 dark:focus:border-blue-300 transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-slate-600 dark:text-slate-400 hover:text-[#003399] dark:hover:text-blue-300 hover:bg-[#f4f8ff] dark:hover:bg-slate-700 hover:border-[#003399] dark:hover:border-blue-300 focus:outline-none focus:text-[#003399] dark:focus:text-blue-300 focus:bg-[#e7f0ff] dark:focus:bg-slate-600 focus:border-[#003399] dark:focus:border-blue-300 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>

