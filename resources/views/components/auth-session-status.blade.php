@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'font-medium text-sm text-green-600 dark:text-green-400 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700/50 px-4 py-3 rounded-lg']) }}>
        {{ $status }}
    </div>
@endif

