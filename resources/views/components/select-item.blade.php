@props(['value', 'title', 'disabled' => false])

<div {{ $attributes->merge(['class' => 'relative h-full w-full py-2 pl-4 text-gray-700 rounded-md cursor-default select-none selectItem dark:text-zinc-200 data-[active=true]:bg-zinc-100 data-[active=true]:text-gray-900 data-[active=true]:dark:bg-zinc-800 data-[active=true]:dark:text-zinc-100']) }}
    data-value="{{ $value }}" data-disabled="{{ $disabled ? 'true' : 'false' }}">
    {{ $title ?? $slot }}
</div>
