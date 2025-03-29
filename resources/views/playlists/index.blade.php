<x-base-layout title="Playlists">
    <x-navigation />

    <div class="py-12">
        <div class="max-w-7xl px-4 mx-auto sm:px-6 lg:px-8">
            <div class="flex gap-3 items-center justify-between">
                <!-- Search -->
                <x-input-group class="max-w-lg w-full">
                    <x-gmdi-search data-slot="icon" />
                    <x-input size="small" placeholder="Search for a playlist" class="w-full" />
                </x-input-group>

                <!-- Create (link) -->
                <x-button href="{{ route('playlists.create') }}" style="height: 36px;">
                    <x-gmdi-add /><span class="hidden sm:inline">New Playlist</span>
                </x-button>
            </div>

            <!-- Playlists Grid -->
            <div id="playlists-grid" data-playlist-count="{{ $playlist_count }}"
                class="py-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($playlists as $playlist)
                    <div
                        class="group relative flex flex-col overflow-hidden rounded-xl bg-white dark:bg-zinc-950 shadow-sm ring-1 ring-zinc-200 dark:ring-zinc-800">
                        <div class="aspect-video relative w-full overflow-hidden">
                            <img class="h-full w-full object-cover transition duration-300 group-hover:scale-105"
                                src="{{ $playlist->images->high->url }}" alt="{{ $playlist->title }}">
                            <div
                                class="absolute bottom-2 right-2 text-white text-[13px] px-[5px] py-[1px] font-semibold rounded bg-black/80">
                                {{ $playlist->total_duration ?? '00:00' }}
                            </div>
                        </div>
                        <div class="p-5 pt-4 flex flex-col flex-1">
                            <!-- Wrap top content in a flex-grow div -->
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <span
                                        class="inline-flex items-center rounded-full bg-zinc-100 dark:bg-zinc-800 px-2.5 py-0.5 text-xs font-medium text-zinc-800 dark:text-zinc-200">
                                        {{ $playlist->video_count }} videos
                                    </span>
                                    @if ($playlist->progress == 100)
                                        <span class="text-sm text-green-700 dark:text-green-500">
                                            completed <i class="fa-solid ms-0.5 fa-circle-check"></i>
                                        </span>
                                    @else
                                        <span data-progress="{{ $playlist->progress }}"
                                            class="text-sm text-zinc-500 dark:text-zinc-400 data-[progress=0]:[&_span]:text-zinc-500 dark:data-[progress=0]:[&_span]:text-zinc-400 [&_span]:text-green-600 dark:[&_span]:text-green-500">
                                            <span>{{ $playlist->progress }}%</span> complete
                                        </span>
                                    @endif
                                </div>
                                <h3 class="mt-4 font-semibold text-zinc-900 dark:text-white">{{ $playlist->title }}</h3>
                            </div>
                            <!-- Bottom content -->
                            <div class="mt-auto">
                                <div class="mt-4">
                                    <div
                                        class="relative h-1.5 w-full overflow-hidden rounded-full bg-zinc-100 dark:bg-zinc-800">
                                        <div class="absolute h-full bg-zinc-900 dark:bg-white"
                                            style="width: {{ $playlist->progress }}%"></div>
                                    </div>
                                </div>
                                <div class="mt-3.5 flex items-center gap-x-2 text-sm text-zinc-600 dark:text-zinc-400">
                                    @if ($playlist->watched)
                                        <x-gmdi-schedule class="size-4" />
                                        Last watched {{ $playlist->updated_at->diffForHumans() }}
                                    @else
                                        <x-gmdi-block-o class="size-4" /> Unwatched
                                    @endif
                                </div>
                            </div>
                        </div>
                        <a href="{{ env('APP_URL') }}/playlist?list={{ $playlist->playlist_id }}"
                            class="absolute inset-0">
                            <span class="sr-only">View playlist</span>
                        </a>
                    </div>
                @endforeach
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
        </div>
    </div>

    <div id="alert-container" class="fixed top-4 right-4 space-y-2 min-w-xl z-50"></div>

    <x-slot name="script">
        @vite('resources/js/playlists/index.js')
    </x-slot>
</x-base-layout>
