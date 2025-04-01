@props(['size' => 'md', 'variant' => 'gray', 'icon' => null])

@php
    $sizeClasses = match ($size) {
        'sm' => 'h-5 px-1.5 text-[11px] gap-1',
        'md', 'default' => 'px-2 h-6 text-xs gap-1.5',
        'lg' => 'px-2.5 h-8 text-sm gap-1.5',
        default => 'px-2 h-6 text-xs gap-1.5', // Fallback
    };

    $variantClasses = match ($variant) {
        'gray' => 'gray',
        'blue' => 'blue',
        'purple' => 'purple',
        'amber' => 'amber',
        'red' => 'red',
        'pink' => 'pink',
        'green' => 'green',
        'teal' => 'teal',
        'gray-subtle' => 'gray-subtle',
        'blue-subtle' => 'blue-subtle',
        'purple-subtle' => 'purple-subtle',
        'amber-subtle' => 'amber-subtle',
        'red-subtle' => 'red-subtle',
        'pink-subtle' => 'pink-subtle',
        'green-subtle' => 'green-subtle',
        'teal-subtle' => 'teal-subtle',
        'invert' => 'invert',
        default => 'gray', // Fallback
    };
@endphp

<div {{ $attributes->merge(['class' => collect(['badge', $sizeClasses, $variantClasses])->join(' ')]) }}>
    {{ $slot }}
</div>
