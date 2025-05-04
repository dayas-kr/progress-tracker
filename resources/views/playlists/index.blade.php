<x-base-layout title="Playlists">
    <x-navigation />

    <div class="py-12">
        <div class="max-w-7xl px-4 mx-auto sm:px-6 lg:px-8">
            <div class="flex gap-3 items-center justify-between">
                <!-- Search -->
                <div class="relative max-w-lg w-full">
                    <x-input-group>
                        <x-gmdi-search data-slot="icon" />
                        <x-input size="small" placeholder="Search for a playlist" class="w-full" />
                    </x-input-group>
                    <x-cmdk-kbd class="absolute right-2 top-2" />
                </div>

                <!-- Create (link) -->
                <x-button href="{{ route('playlists.create') }}" style="height: 36px;">
                    <x-gmdi-add /><span class="hidden sm:inline">New Playlist</span>
                </x-button>
            </div>

            <!-- Playlists Grid -->
            <div id="playlists-grid" data-playlist-count="{{ $playlist_count }}"
                class="py-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @if (!$playlists->isEmpty())
                    @include('playlists.playlist-card', compact('playlists'))
                @else
                    <p class="mt-4 text-sm text-zinc-600 dark:text-zinc-500">No playlists found.</p>
                @endif
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
