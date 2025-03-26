@props([
    'value',
    'title' => null,
    'disabled' => false,
    'href' => '',
    'type' => 'button',
    'destructive' => false,
    'size' => 'md',
])

@php
    $baseClasses =
        'dropdown-item rounded-md relative flex items-center text-gray-700 cursor-pointer select-none dark:text-zinc-200 w-full focus:outline-none';

    $sizeClasses = match ($size) {
        'sm' => 'py-1.5 pl-3 pr-4',
        'md' => 'py-2 pl-3 pr-4',
        'lg' => 'py-2.5 pl-3.5 pr-4',
        default => 'py-1.5 pl-3 pr-4',
    };

    $stateClasses =
        'data-[active=true]:bg-zinc-100 data-[active=true]:text-gray-900 dark:data-[active=true]:bg-zinc-800 dark:data-[active=true]:text-zinc-100 data-[disabled=true]:opacity-50 data-[active=true]:data-[distructive=true]:bg-red-50 data-[active=true]:data-[distructive=true]:text-red-500 dark:data-[active=true]:data-[distructive=true]:bg-red-950/40 dark:data-[active=true]:data-[distructive=true]:text-red-400';
    $mergedClasses = "$baseClasses $stateClasses $sizeClasses";
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $mergedClasses]) }}
        data-disabled="{{ $disabled ? 'true' : 'false' }}">
        {{ $title ?? $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $mergedClasses]) }}
        data-value="{{ $value ?? '' }}" data-disabled="{{ $disabled ? 'true' : 'false' }}"
        data-distructive="{{ $destructive ? 'true' : 'false' }}">
        <div class="flex justify-between w-full items-center">
            <div class="flex items-center space-x-2">
                @isset($prefix)
                    {{ $prefix }}
                @endisset
                <span>{{ $title ?? $slot }}</span>
            </div>
            @isset($suffix)
                <div>{{ $suffix }}</div>
            @endisset
        </div>
    </button>
@endif
