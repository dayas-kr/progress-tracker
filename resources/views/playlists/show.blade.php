<x-base-layout title="{{ $playlist->title }} - Progress Tracker">
    <div class="min-h-screen pt-14 dark:bg-zinc-950 bg-white">
        <header
            class="bg-white z-10 flex items-center fixed top-0 right-0 left-0 justify-between dark:bg-zinc-950 border-b dark:border-zinc-800 py-3 sm:px-5 px-4 h-14">
            <a href="{{ route('playlists.index') }}"
                class="size-10 md:hidden dark:active:bg-zinc-900 dark:hover:bg-zinc-800 dark:text-zinc-300 active:bg-zinc-100 flex items-center justify-center rounded-full">
                <x-gmdi-arrow-back class="size-6" />
            </a>

            <x-button class="w-fit hidden md:flex" variant="ghost" href="{{ route('playlists.index') }}">
                <x-gmdi-arrow-back class="size-4 sm:me-0.5" />
                <span>Go Back</span>
            </x-button>

            <div class="relative hidden md:block md:max-w-md lg:max-w-lg w-full z-10">
                <x-input-group class="max-w-lg w-full">
                    <x-gmdi-search data-slot="icon" />
                    <x-input size="small" autocomplete="off" icon id="search" :prefixStyling="false"
                        prefix='gmdi-search' placeholder="Search Videos" class="w-full bg-white dark:bg-transparent" />
                </x-input-group>
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
        </header>

        <div
            class="max-h-screen font-roboto yt-lg:p-4 yt-lg:pr-0 flex justify-between flex-col yt-lg:gap-3 yt-lg:flex-row">

            <!-- Playlist Info -->
            <div
                class="bg-white yt-lg:overflow-hidden yt-lg:bg-zinc-50 yt-lg:sticky yt-lg:top-[72px] yt-lg:h-[calc(100vh-(56px+32px))] yt-lg:border dark:text-zinc-300 dark:bg-zinc-950 yt-lg:dark:bg-zinc-900/50 dark:border-zinc-800 yt-lg:shadow-2xs yt-lg:rounded-lg yt-lg:max-w-sm w-full">
                <div class="w-full max-w-4xl mx-auto p-3.5 flex yt-lg:flex-col flex-col yt-xs:flex-row space-x-4">
                    <div
                        class="yt-lg:w-full yt-md:w-[428px] mb-2.5 yt-xs:w-[328px] w-full aspect-video overflow-hidden rounded-md">
                        <img class="h-full w-full object-cover" src="{{ $playlist->images->high->url }}"
                            alt="{{ $playlist->title }}">
                    </div>
                    <div class="flex-1 space-y-2">
                        <h1 class="text-3xl font-semibold text-zinc-900 dark:text-zinc-100">{{ $playlist->title }}
                        </h1>
                        <div class="flex gap-3 items-center">
                            <div class="size-7 rounded-full overflow-hidden">
                                <img class="h-full w-full object-cover" src="{{ $playlist->channel_images->high->url }}"
                                    alt="{{ $playlist->title }}">
                            </div>
                            <div>
                                <h2 class="text-sm font-semibold text-zinc-700 dark:text-zinc-300">
                                    <a href="https://www.youtube.com/channel/{{ $playlist->channel_id }}">
                                        {{ $playlist->channel_title }}
                                    </a>
                                </h2>
                            </div>
                        </div>
                        @if ($playlist->description)
                            <div class="text-sm truncate py-1.5 relative text-zinc-600 dark:text-zinc-300 max-w-2xs">
                                {{ $playlist->description }}
                                @if (strlen($playlist->description) > 52)
                                    <div
                                        class="absolute right-0 pl-12 top-0 bottom-0 flex items-center justify-end bg-linear-to-l to-transparent dark:to-transparent yt-lg:dark:from-[#111114] yt-lg:dark:via-[#111114] yt-lg:from-zinc-50 yt-lg:via-zinc-50 dark:from-zinc-950 dark:via-zinc-950 from-white via-white">
                                        <div data-dialog-target="description-dialog"
                                            class="yt-lg:bg-zinc-50 cursor-pointer font-semibold yt-lg:dark:bg-[#111114] bg-white dark:bg-zinc-950">
                                            ...more
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif

                        <div class="flex justify-between gap-3">
                            <x-button href="{{ env('APP_URL') }}/watch?list={{ $playlist->playlist_id }}&play_all=1"
                                radius="full" class="font-semibold w-full h-9">
                                <x-gmdi-play-arrow class="size-6 me-0.5" /><span>Play All</span>
                            </x-button>
                            <!-- Dropdown -->
                            <x-dropdown align="right">
                                <x-slot name="trigger">
                                    <x-button variant="secondary" icon radius="full" class="shrink-0 size-9">
                                        <x-gmdi-more-vert class="size-6 text-zinc-600 dark:text-zinc-300" />
                                    </x-button>
                                </x-slot>
                                <x-slot name="content">
                                    <x-dropdown-item title="Save to watch later" />
                                    <x-dropdown-item
                                        data-completed="{{ $playlist->progress === $playlist->total_duration ? 'true' : 'false' }}"
                                        title="Mark as completed" id="mark-completed">
                                        @if ($playlist->progress === $playlist->total_duration)
                                            <x-slot name="suffix">
                                                <i class="fa-solid fa-circle-check text-[1.025rem]"></i>
                                            </x-slot>
                                        @endif
                                    </x-dropdown-item>
                                    <x-dropdown-item data-dialog-target="playlist-progress-reset-dialog"
                                        title="Reset progress" />
                                    <x-dropdown-item disabled title="Re-fetch" />
                                    <x-dropdown-item title="Edit" />
                                    <x-dropdown-item destructive data-dialog-target="delete-playlist-dialog"
                                        title="Delete" />
                                </x-slot>
                            </x-dropdown>
                        </div>
                    </div>
                </div>
                <div class="w-full max-w-4xl mx-auto">
                    <div
                        class="grid rounded-lg overflow-hidden mb-3.5 mx-3.5 yt-lg:grid-cols-2 grid-cols-3 border dark:border-zinc-800">
                        <div
                            class="yt-lg:px-4 px-3.5 py-2 yt-lg:border-r border-b yt-lg:border-b-0 dark:border-zinc-800">
                            <span class="block text-xs font-medium text-zinc-600 dark:text-zinc-400">Progress</span>
                            <span id="progress" class="text-lg font-bold  text-green-600 dark:text-green-500">
                                {{ $playlist->playlist_progress }}%
                            </span>
                        </div>
                        <div class="yt-lg:px-4 px-3.5 py-2 yt-lg:border-0 border-x border-b dark:border-zinc-800">
                            <span class="block text-xs font-medium text-zinc-600 dark:text-zinc-400">Videos</span>
                            <span class="text-lg font-bold  text-zinc-700 dark:text-zinc-200">
                                <span id="completed-count">{{ $playlist->completed_video_count }}</span>
                                <span> / </span>
                                <span id="total-count">{{ $playlist->video_count }}</span>
                            </span>
                        </div>
                        <div
                            class="yt-lg:px-4 px-3.5 py-2 yt-lg:border border-b yt-lg:border-l-0 dark:border-zinc-800">
                            <span class="block text-xs font-medium text-zinc-600 dark:text-zinc-400">
                                Total duration
                            </span>
                            <span id="total-duration" class="text-lg font-bold  text-zinc-700 dark:text-zinc-200">
                                {{ $playlist->duration }}
                            </span>
                        </div>
                        <div class="yt-lg:px-4 px-3.5 py-2 yt-lg:border-y dark:border-zinc-800">
                            <span class="block text-xs font-medium text-zinc-600 dark:text-zinc-400">
                                Average duration
                            </span>
                            <span class="text-lg font-bold  text-zinc-700 dark:text-zinc-200">
                                {{ $playlist->average_duration }}
                            </span>
                        </div>
                        <div
                            class="yt-lg:px-4 px-3.5 py-2 yt-lg:border-r border-x yt-lg:border-l-0 dark:border-zinc-800">
                            <span class="block text-xs font-medium text-zinc-600 dark:text-zinc-400">
                                Remaining duration
                            </span>
                            <span id="remaing-duration" class="text-lg font-bold  text-zinc-700 dark:text-zinc-200">
                                {{ $playlist->remaining_duration }}
                            </span>
                        </div>
                        <div class="yt-lg:px-4 px-3.5 py-2 yt-lg:border-0 dark:border-zinc-800">
                            <span class="block text-xs font-medium text-zinc-600 dark:text-zinc-400">
                                Time Spent
                            </span>
                            <span class="text-lg font-bold  text-zinc-700 dark:text-zinc-200">00:00</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Playlist Videos Container -->
            <div id="playlist-videos-container" data-video-count="{{ $playlist->video_count }}"
                class="py-3 yt-lg:pr-5 yt-lg:py-0 yt-lg:h-[calc(100vh-(56px+32px))] custom-scrollbar dark:text-zinc-300 yt-lg:shadow-2xs yt-lg:rounded-lg flex-1 yt-lg:overflow-y-auto">
                <div id="playlist-videos" data-playlist-id="{{ $playlist->playlist_id }}"
                    data-videos-loaded="{{ $playlist->video_count > 10 ? 'false' : 'true' }}"
                    class="max-w-4xl mx-auto yt-lg:max-w-full">
                    @include('playlists.video-card', compact('videos'))
                </div>
                <div class="p-2 flex justify-center" id="playlist-loading-spinner" style="display: none;">
                    <svg class="text-zinc-300 dark:text-zinc-600 animate-spin" viewBox="0 0 64 64" fill="none"
                        xmlns="http://www.w3.org/2000/svg" width="24" height="24">
                        <path
                            d="M32 3C35.8083 3 39.5794 3.75011 43.0978 5.20749C46.6163 6.66488 49.8132 8.80101 52.5061 11.4939C55.199 14.1868 57.3351 17.3837 58.7925 20.9022C60.2499 24.4206 61 28.1917 61 32C61 35.8083 60.2499 39.5794 58.7925 43.0978C57.3351 46.6163 55.199 49.8132 52.5061 52.5061C49.8132 55.199 46.6163 57.3351 43.0978 58.7925C39.5794 60.2499 35.8083 61 32 61C28.1917 61 24.4206 60.2499 20.9022 58.7925C17.3837 57.3351 14.1868 55.199 11.4939 52.5061C8.801 49.8132 6.66487 46.6163 5.20749 43.0978C3.7501 39.5794 3 35.8083 3 32C3 28.1917 3.75011 24.4206 5.2075 20.9022C6.66489 17.3837 8.80101 14.1868 11.4939 11.4939C14.1868 8.80099 17.3838 6.66487 20.9022 5.20749C24.4206 3.7501 28.1917 3 32 3L32 3Z"
                            stroke="currentColor" stroke-width="5" stroke-linecap="round" stroke-linejoin="round">
                        </path>
                        <path
                            d="M32 3C36.5778 3 41.0906 4.08374 45.1692 6.16256C49.2477 8.24138 52.7762 11.2562 55.466 14.9605C58.1558 18.6647 59.9304 22.9531 60.6448 27.4748C61.3591 31.9965 60.9928 36.6232 59.5759 40.9762"
                            stroke="currentColor" stroke-width="5" stroke-linecap="round" stroke-linejoin="round"
                            class="text-zinc-700 dark:text-zinc-200">
                        </path>
                    </svg>
                </div>
                <hr class="dark:border-zinc-800">
            </div>
        </div>
    </div>

    <!-- Alert Dialog for Delete -->
    <div id="delete-playlist-dialog" data-dialog-backdrop
        class="fixed inset-0 bg-zinc-900/50 dark:bg-black/60 z-[1000] flex items-center justify-center px-5 sm:px-0"
        style="display: none;">
        <div data-dialog-content
            class="w-full max-w-lg p-6 border border-white dark:border-zinc-800 bg-white dark:bg-zinc-900 rounded-xl"
            aria-modal="true" tabindex="0">
            <h3 class="text-lg font-bold text-zinc-900 dark:text-zinc-100">Delete playlist</h3>
            <p class="mt-2 text-sm text-zinc-800 dark:text-zinc-200">
                Are you sure? This will permanently delete the playlist and its contents. This action cannot be undone.
            </p>
            <div class="mt-4 flex justify-end gap-2">
                <x-button data-dialog-cancel class="w-fit" variant="secondary">Cancel</x-button>
                <x-button data-dialog-confirm id="delete-confirm" data-playlist-id="{{ $playlist->playlist_id }}"
                    variant="destructive" class="w-fit">
                    Delete
                </x-button>
            </div>
        </div>
    </div>

    <!-- Alert Dialog for Progress Reset for Playlist -->
    <div id="playlist-progress-reset-dialog" data-dialog-backdrop
        class="fixed inset-0 bg-zinc-900/50 dark:bg-black/60 z-[1000] flex items-center justify-center px-5 sm:px-0"
        style="display: none;">
        <div data-dialog-content
            class="w-full max-w-lg p-6 border border-white dark:border-zinc-800 bg-white dark:bg-zinc-900 rounded-xl"
            aria-modal="true" tabindex="0">
            <h3 class="text-lg font-bold text-zinc-900 dark:text-zinc-100">Reset progress</h3>
            <p class="mt-2 text-sm text-zinc-800 dark:text-zinc-200">
                Are you sure? This will reset all videos and clear your progress.
            </p>
            <div class="mt-4 flex justify-end gap-2">
                <x-button data-dialog-cancel class="w-fit" variant="secondary">Cancel</x-button>
                <x-button data-dialog-confirm id="reset-progress-confirm"
                    data-reseted="{{ $playlist->progress === 0 ? 'true' : 'false' }}" class="w-fit">
                    Reset Progress
                </x-button>
            </div>
        </div>
    </div>

    <!-- Dialog for Playlist Description -->
    @if ($playlist->description && strlen($playlist->description) > 52)
        <div id="description-dialog" data-dialog-backdrop
            class="fixed inset-0 bg-zinc-900/50 dark:bg-black/60 z-[1000] flex items-center justify-center px-5 sm:px-0"
            style="display: none;">
            <div data-dialog-content
                class="w-full max-w-lg p-6 pt-4 border border-white dark:border-zinc-800 bg-white dark:bg-zinc-900 rounded-xl"
                aria-modal="true" tabindex="0">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold text-zinc-900 dark:text-zinc-100">Description</h3>
                    <button data-dialog-cancel
                        class="size-10 cursor-pointer hover:text-zinc-800 dark:hover:text-zinc-300 -mr-3 -mt-0.5 text-zinc-500 hover:bg-zinc-100 hover:dark:bg-zinc-800 flex items-center justify-center rounded-full focus:outline-none">
                        <x-gmdi-close class="size-6" />
                    </button>
                </div>
                <p class="mt-2 text-sm/7 text-zinc-800 dark:text-zinc-200">{{ $playlist->description }}</p>
            </div>
        </div>
    @endif

    <div id="alert-container" class="fixed top-4 right-4 space-y-2 min-w-xl z-50"></div>

    <x-slot name="script">
        @vite('resources/js/playlists/show.js')
    </x-slot>
</x-base-layout>
