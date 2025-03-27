@props([
    'position' => 'bottom', // allowed: bottom or top
    'message' => 'Tooltip Text',
])

@php
    // Tooltip container classes based on position
    $tooltipPositionClasses =
        $position === 'top'
            ? 'bottom-full left-1/2 transform -translate-x-1/2 -translate-y-1'
            : 'top-full left-1/2 transform -translate-x-1/2 translate-y-1';

    // Margin spacing: bottom tooltip gets margin-top, top tooltip gets margin-bottom
    $marginClass = $position === 'top' ? 'mb-2' : 'mt-2';

    // Arrow classes change based on the position
    $arrowClasses =
        $position === 'top'
            ? 'absolute top-full left-1/2 -translate-x-1/2 w-0 h-0 border-l-8 border-r-8 border-t-8 border-l-transparent border-r-transparent border-t-zinc-800 dark:border-t-zinc-100'
            : 'absolute bottom-full left-1/2 -translate-x-1/2 w-0 h-0 border-l-8 border-r-8 border-b-8 border-l-transparent border-r-transparent border-b-zinc-800 dark:border-b-zinc-100';
@endphp

<div {{ $attributes->merge(['class' => 'relative group']) }} data-tooltip-position="{{ $position }}">
    <div
        class="absolute {{ $tooltipPositionClasses }} bg-zinc-800 text-white text-xs rounded-md px-3 py-2 pointer-events-none opacity-0 group-hover:opacity-100 transition-all duration-300 ease-in-out shadow-lg z-50 whitespace-nowrap {{ $marginClass }} dark:bg-zinc-100 dark:text-zinc-900">
        {{ $message }}
        <div class="{{ $arrowClasses }}"></div>
    </div>
    <!-- Tooltip trigger slot -->
    {{ $slot }}
</div>
