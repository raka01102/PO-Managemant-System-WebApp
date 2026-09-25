@props(['action', 'placeholder', 'isCustomer' => false])
<form method="GET" action="{{ $action }}" class="flex items-center gap-2 md:gap-4">
    <div class="flex-1 relative">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
            <i class="fa-solid fa-magnifying-glass"></i>
        </span>

        <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ $placeholder }}"
            class="w-full rounded-xl border border-slate-200 py-3 lg:py-2 pl-10 pr-4 dark:border-slate-700 dark:bg-slate-950 dark:text-white">
    </div>

    @if ($isCustomer)
        <select name="filter" onchange="this.form.submit()"
            class="rounded-xl border border-slate-200 py-3 lg:py-2 px-4 w-40 dark:border-slate-700 dark:bg-slate-950 dark:text-white">
            {{ $slot }}
        </select>
    @endif
</form>
