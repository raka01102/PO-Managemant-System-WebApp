<button
    {{ $attributes->merge([
        'type' => 'submit',
        'class' =>
            'inline-flex items-center justify-center rounded-lg bg-primary-600 text-base font-semibold text-white shadow-sm hover:bg-primary-700 hover:shadow-md active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 disabled:opacity-50 disabled:pointer-events-none',
    ]) }}>
    {{ $slot }}
</button>
