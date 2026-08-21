<button
    {{ $attributes->merge([
        'type' => 'button',
        'class' =>
            'inline-flex items-center justify-center rounded-lg bg-blue-600 text-base font-semibold text-white shadow-sm hover:bg-blue-700 hover:shadow-md active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:pointer-events-none',
    ]) }}>
    {{ $slot }}
</button>
