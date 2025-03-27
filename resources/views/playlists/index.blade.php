<x-base-layout title="Playlists">
    <x-navigation />

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <x-input-group class="max-w-lg w-full">
                    <x-gmdi-search data-slot="icon" />
                    <x-input size="small" placeholder="Search for a playlist" class="w-full" />
                </x-input-group>

                <x-button href="#" style="height: 36px;">
                    <x-gmdi-add />New Playlist
                </x-button>
            </div>
        </div>

        {{-- TODO: Add playlist list --}}
</x-base-layout>
