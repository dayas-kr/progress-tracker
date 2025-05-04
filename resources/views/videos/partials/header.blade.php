<header
    class="bg-white z-[100] flex items-center fixed top-0 right-0 left-0 justify-between dark:bg-zinc-950 border-b dark:border-zinc-800 py-3 sm:px-5 px-4 h-14">
    <a href="{{ env('APP_URL') }}/playlist?list={{ $playlist->playlist_id }}"
        class="size-10 md:hidden dark:active:bg-zinc-900 dark:hover:bg-zinc-800 dark:text-zinc-300 active:bg-zinc-100 flex items-center justify-center rounded-full">
        <x-gmdi-arrow-back class="size-6" />
    </a>

    <x-button class="w-fit hidden md:flex" variant="ghost"
        href="{{ env('APP_URL') }}/playlist?list={{ $playlist->playlist_id }}">
        <x-gmdi-arrow-back class="size-4 sm:me-0.5" />
        <span>Go Back</span>
    </x-button>

    <div class="relative hidden md:block md:max-w-md lg:max-w-lg w-full z-10">
        <x-input-group class="max-w-lg w-full">
            <x-gmdi-search data-slot="icon" />
            <x-input size="small" autocomplete="off" icon id="search" :prefixStyling="false" prefix='gmdi-search'
                placeholder="Search Videos" class="w-full bg-white dark:bg-transparent" />
        </x-input-group>
        <x-cmdk-kbd class="absolute right-2 top-2" />
    </div>

    <div class="flex items-center justify-between gap-1 sm:gap-4">
        <button
            class="size-10 md:hidden dark:active:bg-zinc-900 dark:hover:bg-zinc-800 dark:text-zinc-300 active:bg-zinc-100 flex items-center justify-center rounded-full cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                stroke="currentColor" class="size-5">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
            </svg>
        </button>

        <x-button variant="secondary" radius="full" class="w-fit hidden sm:flex"
            href="{{ route('playlists.create') }}">
            <x-gmdi-add class="size-4" />
            <span>Create</span>
        </x-button>

        <div class="-mb-0.5">
            <div class="-mb-0.5 hidden sm:block">
                <x-dropdown align="right" :gap="1">
                    <x-slot name="trigger">
                        <button data-dropdown-trigger type="button"
                            class="focus:outline-none size-8 cursor-pointer rounded-full overflow-hidden">
                            <img class="w-full object-cover" src="https://vercel.com/api/www/avatar?s=64"
                                alt="">
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <div
                            class="text-xs border-b dark:border-zinc-800 mb-1.5 px-3 py-2 text-zinc-700 dark:text-zinc-300">
                            Manage Account
                        </div>
                        <x-dropdown-item href="/profile" title="Profile" />
                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-item type="submit" title="Log Out" />
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="sm:hidden">
                <button
                    class="size-10 cursor-pointer md:hidden dark:active:bg-zinc-900 dark:hover:bg-zinc-800 dark:text-zinc-300 active:bg-zinc-100 flex items-center justify-center rounded-full">
                    <x-gmdi-more-vert class="size-6 sm:size-4 sm:me-0.5" />
                </button>
            </div>
        </div>
    </div>
</header>
