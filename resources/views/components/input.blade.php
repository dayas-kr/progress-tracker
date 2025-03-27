@props([
    'size' => 'default',
    'radius' => 'default',
])

@php
    // Define size-related classes.
    $sizeClasses = match ($size) {
        'xSmall' => 'text-xs h-6 text-xs',
        'small' => 'text-sm h-9 text-sm',
        'default' => 'text-sm h-10 text-sm',
        'large' => 'text-base h-12 text-base',
        default => 'text-sm h-10 text-sm',
    };

    // Define radius-related classes.
    $radiusClasses = match ($radius) {
        'none' => 'rounded-none',
        'large' => 'rounded-md',
        'full' => 'rounded-full',
        default => 'rounded-md',
    };

    $baseClasses = implode(' ', [
        $sizeClasses,
        $radiusClasses,
        'block',
        'py-2 sm:py-2.5 px-3',
        'bg-white dark:bg-zinc-950 text-zinc-900 dark:text-zinc-100',
        'placeholder:text-zinc-400 dark:placeholder:text-zinc-500',
        'border border-zinc-200 dark:border-zinc-700',
        'focus:border-zinc-400 dark:focus:border-zinc-500',
        'focus:ring-3 focus:ring-zinc-200 dark:focus:ring-zinc-700',
        'focus:outline-none transition-ring duration-200',
    ]);

@endphp


<input data-slot="control" {{ $attributes->merge([
    'class' => $baseClasses,
]) }} />
