<button
    {{ $attributes->merge([
        'type' => 'button',
        'class' =>
            'inline-flex items-center justify-center rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-base font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2',
    ]) }}>

    {{ $slot }}
</button>
