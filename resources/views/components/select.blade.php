<div {{ $attributes->merge(['class' => 'custom-select relative w-64']) }}>
    <!-- Hidden input for form submission -->
    <input type="hidden" name="{{ $name }}" value="{{ $value ?? '' }}" class="selectInput">
    <button type="button"
        class="relative flex items-center justify-between w-full py-2 pl-3 pr-10 text-sm text-left bg-transparent border rounded-md shadow-xs cursor-default selectButton min-h-9 border-zinc-200 dark:border-zinc-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:dark:ring-blue-400 focus-visible:ring-offset-2 focus-visible:dark:ring-offset-zinc-900">
        <span data-placeholder
            class="truncate selectedText text-zinc-500">{{ $placeholder ?? 'Select an option' }}</span>
        <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"
                class="size-4 text-zinc-400 dark:text-zinc-500">
                <path fill-rule="evenodd"
                    d="M10 3a.75.75 0 01.55.24l3.25 3.5a.75.75 0 11-1.1 1.02L10 4.852 7.3 7.76a.75.75 0 01-1.1-1.02l3.25-3.5A.75.75 0 0110 3zm-3.76 9.2a.75.75 0 011.06.04l2.7 2.908 2.7-2.908a.75.75 0 111.1 1.02l-3.25 3.5a.75.75 0 01-1.1 0l-3.25-3.5a.75.75 0 01.04-1.06z"
                    clip-rule="evenodd"></path>
            </svg>
        </span>
    </button>
    <div tabindex="0" data-dropdown-position="bottom"
        class="absolute hidden w-full p-1 overflow-auto z-50 text-sm bg-white shadow-md rounded-xl dropdownList dark:bg-zinc-950 max-h-56 ring-1 ring-zinc-200 dark:ring-zinc-800 focus:outline-none data-[dropdown-position=bottom]:mt-1.5 data-[dropdown-position=bottom]:top-full data-[dropdown-position=bottom]:bottom-auto data-[dropdown-position=top]:mb-1.5 data-[dropdown-position=top]:bottom-full data-[dropdown-position=top]:top-auto">
        {{ $slot }}
    </div>
</div>
