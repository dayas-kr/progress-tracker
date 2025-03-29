<x-base-layout title="{{ $video->title }} - Progress Tracker">
    <div class="min-h-screen pt-14 dark:bg-zinc-950 bg-white">
        <!-- Header -->
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

        <div class="font-roboto pt-5 sm:px-4 px-0 3xl:px-0">
            <!-- On mobile, use column layout; on larger screens (lg and up) use row -->
            <div class="max-w-[1705px] mx-auto w-full flex flex-col lg:flex-row gap-5 px-3.5 lg:px-0">
                <!-- Video and Details Section -->
                <div class="flex-1 flex flex-col lg:h-[calc(100vh-(56px+40px))]">
                    <div id="player" data-time={{ $video->progress ?? 0 }} data-list="{{ $playlist->playlist_id }}"
                        data-video-id="{{ $video->video_id }}"
                        class="relative w-full h-auto aspect-video bg-zinc-200 overflow-hidden dark:bg-zinc-800 rounded-xl">
                        <!-- YouTube player will be injected here -->
                    </div>
                    <div class="py-2.5 space-y-3">
                        <div class="text-xl font-bold text-zinc-800 dark:text-zinc-50">
                            <!-- Video Title -->
                            {{ $video->title }}
                        </div>
                        <div class="flex flex-col justify-between gap-3 lg:flex-row lg:items-center">
                            <div class="flex gap-3 items-center">
                                <div class="size-10 rounded-full overflow-hidden bg-zinc-200 dark:bg-zinc-800">
                                    <!-- Channel Thumbnail -->
                                    <img src="{{ $video->channel->channelImages->medium->url }}"
                                        alt="Channel Thumbnail">
                                </div>
                                <div class="flex-1">
                                    <div class="font-medium text-nowrap text-zinc-800 dark:text-zinc-100">
                                        <!-- Channel Title -->
                                        <a href="#">{{ $video->channel->channelTitle }}</a>
                                    </div>
                                    <div class="text-xs text-zinc-600 dark:text-zinc-400">
                                        <!-- Subscribers -->
                                        3.77M subscribers
                                    </div>
                                </div>
                            </div>

                            <div class="flex gap-2.5">
                                <x-switch id="auto-play-switch" label="Auto Play" />

                                <x-button id="reset-video-progress" radius="full" variant="outline"
                                    class="{{ !$completed ? 'hidden' : '' }}">
                                    <i style="display: none"
                                        class="fa-solid animate-spin fa-spinner text-[1.005rem]"></i>
                                    <i class="fa-regular fa-circle-xmark text-[1.005rem]"></i>
                                    <span>Reset Progress</span>
                                </x-button>
                                <x-button :disabled="$completed" data-completed="{{ $completed ? 'true' : 'false' }}"
                                    id="mark-video-as-completed" radius="full" variant="outline">
                                    <i style="display: {{ $completed ? 'inline' : 'none' }};"
                                        class="fa-solid text-green-600 dark:text-green-500 fa-circle-check text-[1.005rem]">
                                    </i>
                                    <i style="display: {{ $completed ? 'none' : 'inline' }};"
                                        class="fa-regular fa-circle-check text-[1.005rem]"></i>
                                    <i style="display: none" data-loading-spinner
                                        class="fa-solid animate-spin fa-spinner text-[1.005rem]"></i>
                                    <span>Mark as completed</span>
                                </x-button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Playlist Sidebar -->
                <div
                    class="lg:max-w-[400px] w-full border dark:border-zinc-700 rounded-xl h-fit flex flex-col max-h-[50vh] sm:max-h-[calc(100vh-(56px+40px))] overflow-hidden">
                    <!-- Playlist Video Header -->
                    <div class="h-16 bg-white dark:bg-zinc-900 py-2 pl-6 pr-2.5">
                        <a href="{{ env('APP_URL') }}/playlist?list={{ $playlist->playlist_id }}"
                            class="block text-lg font-bold pr-2.5 text-zinc-800 dark:text-zinc-50 truncate">
                            {{ $playlist->title }}
                        </a>
                        <div class="text-[13px] text-zinc-800 dark:text-zinc-400">
                            <a href="#">{{ $video->channel->channelTitle }}</a>
                            <span>- {{ $video->position + 1 }}/{{ $playlist->video_count }}</span>
                        </div>
                    </div>
                    <!-- Video List -->
                    <div class="flex-1 overflow-y-auto custom-scrollbar">
                        @foreach ($playlist->videos as $_video)
                            <div
                                class="flex relative justify-between p-2 flex-wrap {{ $_video->video_id === $video->video_id ? 'bg-blue-100 dark:bg-blue-900/25' : 'hover:bg-zinc-100 dark:hover:bg-zinc-800' }}">
                                <div class="my-auto text-sm text-zinc-600 dark:text-zinc-400 {{ $positionWidth }}">
                                    {{ $_video->position + 1 }}
                                </div>
                                <div
                                    class="bg-zinc-200 relative overflow-hidden dark:bg-zinc-800 rounded-lg w-24 sm:w-30 aspect-video me-2 ms-1">
                                    <img class="h-full w-full object-cover"
                                        src="{{ $_video->thumbnails->medium->url }}" alt="Thumbnail">
                                    <div
                                        class="video-duration-container absolute bottom-1 right-1 text-white text-xs px-[5px] py-[1px] font-semibold rounded bg-black/80">
                                        <span class="video-duration">{{ $playlist->total_duration ?? '00:00' }}</span>
                                    </div>
                                </div>
                                <div class="flex-1 space-y-1">
                                    <div
                                        class="text-sm font-medium w-full line-clamp-2 text-zinc-800 dark:text-zinc-100">
                                        {{ $_video->title }}</div>
                                    <div class="text-xs text-zinc-600 dark:text-zinc-400">
                                        {{ $_video->channel->channelTitle }}</div>
                                </div>
                                <a href="{{ env('APP_URL') }}/watch?v={{ $_video->video_id }}&list={{ $playlist->playlist_id }}&index={{ $_video->position + 1 }}"
                                    class="absolute inset-0">
                                    <span class="sr-only">View video</span>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Load the YouTube IFrame API -->
    <script src="https://www.youtube.com/iframe_api"></script>

    <x-slot name="script">
        @vite('resources/js/videos/show.js')
    </x-slot>
</x-base-layout>
