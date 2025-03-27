<div
    {{ $attributes->merge(['class' => '[&>[data-slot=control]]:mt-2 [&>[data-slot=control]+[data-slot=description]]:mt-2']) }}>
    {{ $slot }}
</div>
