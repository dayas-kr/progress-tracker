@props(['align' => 'left', 'width' => '2', 'gap' => 2])

@php
    $align = match ($align) {
        'left' => 'left-0',
        'right' => 'right-0',
        default => 'left-0',
    };

    $width = match ($width) {
        '1' => 'w-48',
        '2' => 'w-52',
        '3' => 'w-56',
        '4' => 'w-58',
        default => 'w-48',
    };

    $gapClass = match ($gap) {
        0 => 'data-[dropdown-position=bottom]:mt-0 data-[dropdown-position=top]:mb-0',
        1 => 'data-[dropdown-position=bottom]:mt-1 data-[dropdown-position=top]:mb-1.5',
        2 => 'data-[dropdown-position=bottom]:mt-2 data-[dropdown-position=top]:mb-2',
        3 => 'data-[dropdown-position=bottom]:mt-2.5 data-[dropdown-position=top]:mb-2.5',
        4 => 'data-[dropdown-position=bottom]:mt-3 data-[dropdown-position=top]:mb-3',
        5 => 'data-[dropdown-position=bottom]:mt-3.5 data-[dropdown-position=top]:mb-3.5',
        default => 'data-[dropdown-position=bottom]:mt-2 data-[dropdown-position=top]:mb-2',
    };

    $positionClasss = implode(' ', [
        'data-[dropdown-position=bottom]:top-full data-[dropdown-position=bottom]:bottom-auto',
        'data-[dropdown-position=top]:bottom-full data-[dropdown-position=top]:top-auto',
        $gapClass,
    ]);
@endphp

<div class="relative" data-dropdown>
    <!-- Trigger slot -->
    <div data-dropdown-trigger>
        {{ $trigger }}
    </div>

    <!-- Dropdown menu -->
    <div tabindex="0" data-dropdown-menu
        class="absolute {{ $align }} {{ $width }} focus:outline-none hidden px-0.5
         py-1 text-sm bg-white shadow-md rounded-lg dark:bg-zinc-950 ring-1 ring-black/10 dark:ring-zinc-800 z-50 {{ $positionClasss }}">
        <div data-dropdown-content class="p-0.5 relative custom-scrollbar scrollbar-extra-small">{{ $content }}
        </div>
    </div>
</div>
