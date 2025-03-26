@props([
    'label' => null,
    'name' => null,
    'id' => '',
    'disabled' => false,
    'checked' => false,
    'direction' => 'default',
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

// Direction classes for ordering the label text.
$directionClasses = match ($direction) {
    'switch-first' => 'order-first me-2',
    default => 'order-none',
    };
@endphp

<div class="flex items-center h-5" data-custom-checkbox>
    <input type="checkbox" id="{{ $id }}" name="{{ $name ?? '' }}" class="hidden peer"
        @disabled($disabled) @checked($checked) {{ $attributes->whereStartsWith('wire:model') }}>

    <label tabindex="0" data-checkbox-label for="{{ $id }}"
        class="flex items-center space-x-2 select-none text-sm {{ $disabledLabelClasses }} dark:text-zinc-400 dark:peer-checked:text-zinc-300 peer-checked:text-zinc-800
                  [&_svg]:scale-0 peer-checked:[&_.custom-checkbox]:border-zinc-800 peer-checked:[&_.custom-checkbox]:bg-zinc-800 dark:peer-checked:[&_.custom-checkbox]:bg-white
                  peer-checked:[&_svg]:scale-100 {{ $disabledClasses }}
                  focus-visible:outline-0 focus-visible:ring-none focus-visible:[&_.custom-checkbox]:ring-2 focus-visible:[&_.custom-checkbox]:ring-blue-500
                  focus-visible:[&_.custom-checkbox]:ring-offset-2 dark:focus-visible:[&_.custom-checkbox]:ring-offset-zinc-900">
        <span
            class="flex justify-center items-center w-5 h-5 rounded-[0.3125rem] border border-zinc-300 dark:border-zinc-700 custom-checkbox text-zinc-900 {{ $disabled ? 'bg-zinc-100 dark:bg-zinc-800' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" fill="currentColor"
                class="size-3.5 duration-300 ease-out peer-checked:scale-100 scale-0 text-white dark:text-zinc-800">
                <path
                    d="m382-393 321-321q20.09-20 48.55-20Q780-734 800-714.21q20 19.79 20 48.5T800-617L430-248q-19.82 20-47.91 20Q354-228 334-248L161-420q-20-20.73-20-48.87Q141-497 160.79-517q20.79-21 49-21T258-517l124 124Z" />
            </svg>
        </span>
        <span class="text-[13px] {{ $directionClasses }}">{{ $label ?? '' }}</span>
    </label>
</div>
