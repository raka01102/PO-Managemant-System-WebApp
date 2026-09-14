@props(['showOnDesktop' => false])
<div
    {{ $attributes->merge(['class' => 'absolute bottom-16 left-0 z-20 pt-4 pb-8 px-4 border-t w-full bg-slate-50 text-slate-800 dark:bg-slate-900 dark:text-slate-200 border-slate-200 dark:border-slate-800 ' . ($showOnDesktop ? 'md:static md:flex md:justify-end gap-4 md:py-0 md:px-0 md:mt-4 md:bg-transparent dark:md:bg-transparent md:border-none' : 'md:hidden')]) }}>
    {{ $slot }}
</div>
