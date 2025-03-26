@props([
    'href' => '',
    'size' => 'default',
    'variant' => 'primary',
    'radius' => 'medium',
    'icon' => false,
])

@php
    // Determine size value
    $sizeValue = match ($size) {
        'small' => 'small',
        'medium' => 'medium',
        'large' => 'large',
        'extra-large' => 'extra-large',
        default => 'medium', // default size
    };

    // Determine variant value
    $variantValue = match ($variant) {
        'primary' => 'primary',
        'secondary' => 'secondary',
        'ghost' => 'ghost',
        'outline' => 'outline',
        'destructive' => 'destructive',
        'warning' => 'warning',
        default => 'primary',
    };

    // Determine radius value
    $radiusValue = match ($radius) {
        'none' => 'none',
        'full' => 'full',
        default => 'default', // default radius
    };

    // Classes based on size already-exists-btn
    $sizeClasses = match ($sizeValue) {
        'small' => 'px-2.5 py-1 h-[1.625rem] text-xs',
        'medium' => 'px-3 py-1.5 h-8 text-sm',
        'large' => 'px-4 py-2 h-10 text-base',
        'extra-large' => 'px-5 py-2.5 h-12 text-base',
        default => 'px-3 py-1.5 h-8 text-sm',
    };

    // Classes based on variant
    $variantClasses = match ($variantValue) {
        'primary' => implode(' ', [
            'text-white dark:text-zinc-900',
            'bg-zinc-800 dark:bg-zinc-100',
            'hover:bg-zinc-900 dark:hover:bg-zinc-100',
            'active:bg-zinc-700 dark:active:bg-zinc-300',
        ]),
        'secondary' => implode(' ', [
            'text-zinc-800 dark:text-zinc-200',
            'bg-zinc-100 dark:bg-zinc-800',
            'hover:bg-zinc-200/75 dark:hover:bg-zinc-700/75',
            'active:bg-zinc-200 dark:active:bg-zinc-700',
        ]),
        'ghost' => implode(' ', [
            'text-zinc-800 dark:text-zinc-200',
            'bg-transparent',
            'hover:bg-zinc-100 dark:hover:bg-zinc-800',
            'active:bg-zinc-200 dark:active:bg-zinc-700',
        ]),
        'outline' => implode(' ', [
            'text-zinc-800 dark:text-zinc-200',
            'border border-zinc-200 dark:border-zinc-700',
            'hover:bg-zinc-100 dark:hover:bg-zinc-800',
            'active:bg-zinc-200/75 dark:active:bg-zinc-700/75',
        ]),
        'destructive' => implode(' ', [
            'text-white',
            'bg-[#E5484D] dark:bg-[#E5484D]',
            'hover:bg-red-700 dark:hover:bg-red-400',
            'active:bg-red-800 dark:active:bg-red-500',
        ]),
        'warning' => 'text-zinc-800 bg-amber-500 hover:bg-[#D27507] active:bg-amber-500',
        default => implode(' ', [
            'text-white dark:text-zinc-900',
            'bg-zinc-800 dark:bg-zinc-100',
            'hover:bg-zinc-900 dark:hover:bg-zinc-100',
            'active:bg-zinc-700 dark:active:bg-zinc-300',
        ]),
    };

    // Classes based on radius
    $radiusClasses = match ($radiusValue) {
        'none' => 'rounded-none',
        'full' => 'rounded-full',
        default => match ($sizeValue) {
            'small' => 'rounded-sm',
            'medium' => 'rounded-md',
            'large', 'extra-large' => 'rounded-lg',
            default => 'rounded-md',
        },
    };

    // Icon sizing classes
    $iconClasses = match ($sizeValue) {
        'small' => 'size-6 p-1',
        'medium' => 'size-8 p-1.5',
        'large' => 'size-10 p-2',
        'extra-large' => 'size-12 p-2',
        default => 'size-8 p-1.5',
    };

    // Gap classes
    $gapClasses = match ($sizeValue) {
        'small' => 'gap-1.5',
        'medium' => 'gap-1.5',
        'large' => 'gap-2',
        'extra-large' => 'gap-2',
        default => 'gap-1.5',
    };

    $svgSizeClasses = match ($sizeValue) {
        'small' => '[&_svg]:size-3.5',
        'medium' => '[&_svg]:size-4.5',
        'large' => '[&_svg]:size-5',
        'extra-large' => '[&_svg]:size-6',
        default => '[&_svg]:size-4.5',
    };

    // Focus styles
    $focusClasses = implode(' ', [
        'focus-visible:outline-hidden',
        'focus-visible:ring-2 focus-visible:ring-offset-2',
        'focus-visible:ring-blue-500 dark:focus-visible:ring-offset-zinc-800',
    ]);

    // Disabled classes
    $disabledClasses = implode(' ', [
        'disabled:cursor-not-allowed',
        'disabled:text-zinc-400 dark:disabled:text-zinc-500',
        'disabled:bg-zinc-100 dark:disabled:bg-zinc-800',
        'disabled:border disabled:border-zinc-200 dark:disabled:border-zinc-700',
    ]);

    $btnClasses = implode(' ', [
        $variantClasses,
        $radiusClasses,
        $icon ? $iconClasses : $sizeClasses,
        $focusClasses,
        $disabledClasses,
        $svgSizeClasses,
        $gapClasses,
        'font-medium cursor-pointer text-nowrap',
        'flex items-center justify-center',
        '[&_svg]:h-full',
    ]);
@endphp

@if ($href)
    <a href="{{ $href }}" class="w-fit">
@endif

<button {{ $attributes->merge(['class' => $btnClasses]) }}>
    {{ $slot }}
</button>

@if ($href)
    </a>
@endif
