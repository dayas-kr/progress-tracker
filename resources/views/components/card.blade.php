@props(['radius' => 'large'])

@php
    $radiusValue = match ($radius) {
        'none' => '',
        'small' => 'rounded-lg',
        'medium' => 'rounded-xl',
        'large' => 'rounded-2xl',
        default => 'rounded-xl', // default radius
    };
@endphp

<div
    {{ $attributes->merge(['class' => "bg-white border dark:text-zinc-300 dark:bg-zinc-950 dark:border-zinc-800 shadow-2xs {$radiusValue}"]) }}>
    {{ $slot }}
</div>
