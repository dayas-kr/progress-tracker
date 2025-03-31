@props(['size' => 'default', 'label' => '', 'direction' => 'default', 'disabled' => false])

@php
    $direction = match ($direction) {
        'switch-first' => 'order-first me-2',
        default => 'order-none ms-2',
    };

    $sizeClasses = match ($size) {
        'default' => 'w-9 h-5 peer-checked:after:translate-x-4 after:h-4.5 after:w-4.5',
        'large' => 'w-11 h-6 peer-checked:after:translate-x-5 after:h-5.5 after:w-5.5',
    };

    $disabledClasses = $disabled
        ? 'ring ring-zinc-200 dark:ring-zinc-800 after:bg-zinc-200 dark:after:bg-zinc-700 peer-checked:bg-blue-600 dark:peer-checked:bg-blue-500'
        : 'bg-zinc-200 dark:bg-zinc-700 after:bg-white peer-checked:bg-blue-600 dark:peer-checked:bg-blue-500';

@endphp

<label data-switch-label tabindex="0"
    class="inline-flex items-center select-none w-fit rounded-full focus-visible:outline-none focus-visible:[&_.xxx]:rounded-full focus-visible:[&_.xxx]:ring-offset-2 dark:focus-visible:[&_.xxx]:ring-offset-zinc-950 focus-visible:[&_.xxx]:ring-2 focus-visible:[&_.xxx]:ring-blue-500 switch-label">
    <div class="relative translate-y-0 xxx">
        <input type="checkbox" @disabled($disabled) {{ $attributes->merge(['tabindex' => '0']) }}
            class="sr-only peer">
        <div
            class="relative rounded-full peer-focus:outline-none rtl:peer-checked:after:-translate-x-full after:absolute after:content-[''] after:top-px after:left-px after:rounded-full after:transition-all {{ $sizeClasses }} {{ $disabledClasses }}">
        </div>
    </div>
    @if ($label)
        <span class="text-xs font-medium text-zinc-700 dark:text-zinc-400 {{ $direction }}">{{ $label }}</span>
    @endif
</label>
