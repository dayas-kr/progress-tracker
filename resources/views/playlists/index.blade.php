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
            <div class="py-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
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
                        {{-- TODO: Add a link to the playlist --}}
                        <a href="{{ env('APP_URL') }}/playlist?list={{ $playlist->playlist_id }}"
                            class="absolute inset-0">
                            <span class="sr-only">View playlist</span>
                        </a>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            {{ $playlists->links() }}
        </div>
    </div>

    <div id="alert-container" class="fixed top-4 right-4 space-y-2 min-w-xl z-50"></div>

    <x-slot name="script">
        @vite('resources/js/playlists/index.js')
    </x-slot>
</x-base-layout>
