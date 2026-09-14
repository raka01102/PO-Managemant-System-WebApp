@props(['title', 'attachments'])

@if ($attachments->isNotEmpty())
    <div x-data="{ open: true }" class="card overflow-hidden">
        <div class="card-header">
            <h2 class="font-bold">
                {{ $title }}
            </h2>

            <button class="flex h-9 w-9 items-center justify-center">
                <i class="fa-solid fa-chevron-down transition-transform" :class="{ 'rotate-180': !open }"></i>
            </button>
        </div>

        <div x-show="open" x-collapse class="card-body">
            <div class="divide-y divide-slate-200 dark:divide-slate-800">
                @foreach ($attachments as $attachment)
                    @php
                        $extension = strtolower(pathinfo($attachment->file_path, PATHINFO_EXTENSION));
                    @endphp

                    <div class="flex items-center justify-between">
                        <div class="flex min-w-0 items-center gap-3">
                            <div class="flex h-9 w-9 shrink-0 items-center justify-center">
                                @if ($extension === 'pdf')
                                    <i class="fa-solid fa-file-pdf text-xl text-red-500"></i>
                                @else
                                    <i class="fa-solid fa-file-image text-xl text-blue-500"></i>
                                @endif
                            </div>

                            <p class="truncate text-sm font-medium text-slate-600 dark:text-slate-300">
                                {{ basename($attachment->file_type ?? $attachment->file_path) }}
                            </p>
                        </div>

                        <button type="button"
                            class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg transition hover:bg-slate-100 dark:hover:bg-slate-800"
                            @click="previewDokumen('{{ $attachment->file_path }}', '{{ $attachment->file_type }}')">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif
