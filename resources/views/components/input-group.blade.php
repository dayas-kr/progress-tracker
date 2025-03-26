@php
    $baseClass = implode(' ', [
        'relative isolate',
        'grid grid-cols-[var(--spacing-10)_1fr_var(--spacing-10)]',
        '[&>[data-slot=icon]:first-child]:col-start-1 [&>[data-slot=icon]:last-child]:col-start-3 [&>[data-slot=icon]]:row-start-1 [&>[data-slot=icon]]:place-self-center [&>[data-slot=icon]]:size-5 [&>[data-slot=icon]]:fill-neutral-500 dark:[&>[data-slot=icon]]:fill-neutral-400 [&>[data-slot=icon]]:z-10 [&>[data-slot=control]]:col-span-3 [&>[data-slot=control]]:col-start-1 [&>[data-slot=control]]:row-start-1',
        '[&>[data-slot=icon]+[data-slot=control]]:pl-9',
        '[&:has([data-slot=control]+[data-slot=icon])>[data-slot=control]]:pr-9',
    ]);
@endphp

<div data-slot="control" {{ $attributes->merge(['class' => $baseClass]) }}>
    {{ $slot }}
</div>
