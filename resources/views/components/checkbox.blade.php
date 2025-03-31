@props([
    'label' => null,
    'name' => null,
    'id' => '',
    'description' => '',
    'disabled' => false,
    'checked' => false,
    'size' => 'medium', // Added size prop with default 'medium'
])

@php
    // Generate an ID if one isn't provided.
$id = $id ?: 'input-' . Str::random(8);

// Disabled state classes for the input/checkbox.
$disabledClasses = implode(' ', [
    'peer-disabled:cursor-not-allowed',
    'peer-disabled:peer-checked:cursor-not-allowed',
    'peer-disabled:peer-checked:[&_.custom-checkbox]:bg-zinc-400 dark:peer-disabled:peer-checked:[&_.custom-checkbox]:bg-zinc-500',
    'peer-disabled:peer-checked:[&_.custom-checkbox]:border-zinc-400 dark:peer-disabled:peer-checked:[&_.custom-checkbox]:border-zinc-500',
]);

// Label text styling based on disabled state.
$disabledLabelClasses = $disabled ? 'text-zinc-400 dark:text-zinc-600' : 'text-zinc-900 dark:text-zinc-50';

// Size-based classes
$checkboxSizes = [
    'small' => 'w-4 h-4',
    'medium' => 'w-5 h-5',
    'large' => 'w-6 h-6',
];

$checkboxSize = $checkboxSizes[$size] ?? $checkboxSizes['medium'];

$iconSizes = [
    'small' => 'size-3',
    'medium' => 'size-3.5',
    'large' => 'size-4',
];

$iconSize = $iconSizes[$size] ?? $iconSizes['medium'];
@endphp

<div class="grid grid-cols-[auto_1fr] gap-x-2 gap-y-1 data-[disabled=true]:[&>[data-main-checkbox-label]]:opacity-50 data-[disabled=true]:[&>[data-checkbox-description]]:opacity-50"
    data-custom-checkbox data-disabled={{ $disabled ? 'true' : 'false' }}>
    <input type="checkbox" id="{{ $id }}" name="{{ $name ?? '' }}" @disabled($disabled)
        @checked($checked) {{ $attributes->merge(['class' => 'hidden peer']) }}>

    <label tabindex="0" data-checkbox-label for="{{ $id }}"
        class="{{ $disabledLabelClasses }} dark:text-zinc-400 dark:peer-checked:text-zinc-300 peer-checked:text-zinc-800
                  [&_svg]:scale-0 peer-checked:[&_.custom-checkbox]:border-zinc-800 dark:peer-checked:[&_.custom-checkbox]:border-white peer-checked:[&_.custom-checkbox]:bg-zinc-800 dark:peer-checked:[&_.custom-checkbox]:bg-white
                  peer-checked:[&_svg]:scale-100 {{ $disabledClasses }} peer-disabled:focus-visible:[&_.custom-checkbox]:ring-0 peer-disabled:focus-visible:[&_.custom-checkbox]:ring-offset-0
                  focus-visible:outline-0 focus-visible:ring-none focus-visible:[&_.custom-checkbox]:ring-2 focus-visible:[&_.custom-checkbox]:ring-blue-500
                  focus-visible:[&_.custom-checkbox]:ring-offset-2 dark:focus-visible:[&_.custom-checkbox]:ring-offset-zinc-900">
        <span
            class="flex justify-center items-center rounded-[0.3125rem] border border-zinc-300 dark:border-zinc-700 custom-checkbox text-zinc-900 {{ $checkboxSize }} {{ $disabled ? 'bg-zinc-100 dark:bg-zinc-800' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" fill="currentColor"
                class="{{ $iconSize }} duration-300 ease-out peer-checked:scale-100 scale-0 text-white dark:text-zinc-800">
                <path
                    d="m382-393 321-321q20.09-20 48.55-20Q780-734 800-714.21q20 19.79 20 48.5T800-617L430-248q-19.82 20-47.91 20Q354-228 334-248L161-420q-20-20.73-20-48.87Q141-497 160.79-517q20.79-21 49-21T258-517l124 124Z" />
            </svg>
        </span>
    </label>
    @if ($label)
        <x-label data-main-checkbox-label for="{{ $id }}"
            class="col-start-2 select-none">{{ $label }}</x-label>
    @endif
    @if ($description)
        <x-description data-checkbox-description class="col-start-2 select-none">{{ $description }}</x-description>
    @endif
</div>
