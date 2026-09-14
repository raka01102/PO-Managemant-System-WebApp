@props(['icon', 'label', 'value'])

<div class="flex items-center gap-1 sm:gap-2">
    <div class="flex h-9 w-9 shrink-0 items-center">
        <i class="fa-solid {{ $icon }} text-xl"></i>
    </div>

    <div class="min-w-0">
        <p class="text-xs text-slate-500">
            {{ $label }}
        </p>

        <p class="truncate font-semibold">
            {{ $value }}
        </p>
    </div>
</div>
