@props(['active'])

@php
    $classes =
        $active ?? false
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-primary-600 dark:border-primary-400 text-sm font-medium leading-5 text-primary-600 dark:text-primary-200 focus:outline-none focus:border-primary-600 dark:focus:border-primary-300 transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-slate-600 dark:text-slate-400 hover:text-primary-600 dark:hover:text-primary-300 hover:border-primary-600 dark:hover:border-primary-300 focus:outline-none focus:text-primary-600 dark:focus:text-primary-300 focus:border-primary-600 dark:focus:border-primary-300 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
