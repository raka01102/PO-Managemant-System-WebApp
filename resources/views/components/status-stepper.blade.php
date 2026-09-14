@props(['title', 'steps', 'currentStep', 'type' => 'primary'])

<div class="card">
    <div class="card-header text-center">
        <h2 class="sm:text-lg font-bold">{{ $title }}</h2>
    </div>

    <div class="card-body">
        <div class="grid grid-cols-3">
            @foreach ($steps as $key => $step)
                @php
                    $keys = array_keys($steps);

                    $stepIndex = array_search($key, $keys);

                    $active = $stepIndex <= $currentStep;
                @endphp

                <div class="relative">
                    <div class="relative flex justify-center">
                        @if (!$loop->last)
                            <div
                                class="absolute left-1/2 top-1/2 h-1 w-full {{ $stepIndex < $currentStep ? 'bg-primary-600' : 'bg-slate-200 dark:bg-slate-700' }}">
                            </div>
                        @endif

                        <div
                            class="flex h-9 w-9 sm:h-14 sm:w-14 items-center justify-center rounded-full z-10 {{ $active ? ($type == 'primary' ? 'bg-primary-100 text-primary-600' : "{$step['bg']} {$step['text']}") : 'bg-slate-200 text-slate-500 dark:bg-slate-700' }}">
                            <i class="fa-solid {{ $step['icon'] }}"></i>
                        </div>
                    </div>

                    <p
                        class="my-2 text-center text-xs font-bold {{ $active ? ($type == 'primary' ? 'text-primary-600' : $step['text']) : 'text-slate-400' }}">
                        {{ $step['label'] }}
                    </p>
                </div>
            @endforeach
        </div>
    </div>
</div>
