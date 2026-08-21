@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge([
    'class' =>
        'border-gray-300 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300 focus:border-primary-600 dark:focus:border-primary-600 focus:ring-primary-600 dark:focus:ring-primary-600 rounded-xl shadow-sm',
]) !!}>
