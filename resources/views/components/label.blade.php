@props(['value'])

<label data-slot="label"
    {{ $attributes->merge(['class' => 'block font-medium text-sm text-zinc-800 dark:text-zinc-100']) }}>
    {{ $value ?? $slot }}
</label>
