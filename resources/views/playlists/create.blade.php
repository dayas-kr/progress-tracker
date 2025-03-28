<x-base-layout title="Create Playlist">
    <x-navigation />

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <x-card class="p-6">
            <form id="fetch-playlist-form" class="flex flex-col md:flex-row gap-3">
                <x-input name="playlist-link" id="playlist-link" size="small" type="text"
                    placeholder="Paste YouTube playlist link or ID here" class="w-full md:max-w-lg" autofocus />
                <x-button data-submit-button type="submit" style="height: 36px">
                    <x-loading-spinner data-loading-spinner
                        class="me-1 size-4 animate-spin hidden dark:text-zinc-200 text-zinc-700" />
                    Fetch Playlist
                </x-button>
                <x-button type="reset" variant="outline" style="height: 36px;" id="reset-form-btn"
                    class="w-full hidden sm:w-fit shrink-0">
                    <x-gmdi-close class="size-4" />
                    Cancel
                </x-button>
            </form>
            <p id="playlist-link-error" class="text-sm text-red-600 dark:text-red-400 mt-1.5"></p>
        </x-card>

        <x-card id="playlist-info-skeleton" class="my-8 md:p-6 hidden">
            <div>
                <div class="flex flex-col lg:flex-row gap-4 lg:gap-12">
                    <div class="w-full lg:max-w-md flex flex-col gap-2 p-6 pb-0 md:p-0">
                        <div
                            class="h-fit dark:bg-zinc-900/50 rounded-lg border border-zinc-200 dark:border-zinc-800 overflow-hidden relative">
                            <div class="w-full aspect-video bg-zinc-300 dark:bg-zinc-700 animate-pulse mb-3">
                                <!-- Playlist thumbnail -->
                            </div>
                            <div class="flex px-4 pb-3">
                                <div
                                    class="size-8 shrink-0 overflow-hidden me-2 bg-zinc-300 dark:bg-zinc-700 rounded-full animate-pulse">
                                    <!-- Channel thumbnail -->
                                </div>
                                <div class="w-full">
                                    <div class="bg-zinc-300 dark:bg-zinc-700 rounded animate-pulse h-6 w-[70%]">
                                    </div>
                                    <div class="bg-zinc-300 dark:bg-zinc-700 rounded animate-pulse mt-1.5 h-4 w-1/3">
                                    </div>
                                </div>
                            </div>
                            <div
                                class="flex absolute bottom-3 right-3 bg-zinc-300 dark:bg-zinc-700 rounded-full animate-pulse h-5 w-16">
                            </div>
                        </div>

                        <div class="bg-zinc-300 mt-2 dark:bg-zinc-700 rounded-md h-9 animate-pulse"></div>
                    </div>

                    <div class="w-full mt-4 lg:mt-0 md:p-6 md:py-0 p-0 sm:p-4 pb-0">
                        <div class="px-4 sm:px-0">
                            <h3 class="text-base/7 font-semibold text-zinc-900 dark:text-zinc-100">YouTube Playlist
                                Information</h3>
                            <p class="mt-1 max-w-2xl text-sm/6 text-zinc-500 dark:text-zinc-400">
                                View YouTube playlist information before adding it to the database
                            </p>
                        </div>
                        <div class="mt-6 border-t border-zinc-100 dark:border-zinc-800">
                            <dl class="divide-y divide-zinc-100 dark:divide-zinc-800">
                                @foreach (['Playlist ID', 'Playlist Title', 'Playlist Description', 'Channel URL', 'Subscriber Count', 'Video Count'] as $info)
                                    <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                        <dt class="text-sm/6 font-medium text-zinc-900 dark:text-zinc-100">
                                            {{ $info }}</dt>
                                        <dd
                                            class="mt-1 sm:col-span-2 sm:mt-0 break-all bg-zinc-300 dark:bg-zinc-700 rounded animate-pulse">
                                        </dd>
                                    </div>
                                @endforeach
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </x-card>

        <x-card id="playlist-info-card" class="mt-6 sm:mt-8 md:p-6 hidden">
            <div id="playlist-results relative">
                <div class="flex flex-col lg:flex-row gap-4 lg:gap-12">
                    <div class="w-full lg:max-w-md flex flex-col justify-between p-3 gap-2 sm:p-6 pb-0 md:p-0">
                        <div
                            class="h-fit dark:bg-zinc-900/50 rounded-lg border border-zinc-200 dark:border-zinc-800 overflow-hidden relative">
                            <div class="w-full aspect-video bg-zinc-300 dark:bg-zinc-700 rounded mb-3">
                                <!-- Playlist thumbnail -->
                                <img id="playlist-thumbnail" class="w-full h-full object-cover" src=""
                                    alt="">
                            </div>
                            <div class="flex px-4 pb-3">
                                <div
                                    class="size-8 shrink-0 overflow-hidden me-2 bg-zinc-300 dark:bg-zinc-700 rounded-full">
                                    <!-- Channel thumbnail -->
                                    <img id="channel-thumbnail" class="w-full h-full object-cover" src=""
                                        alt="">
                                </div>
                                <div>
                                    <div id="playlist-title" class="text-[17px] font-medium">
                                        <!-- Updated dynamically -->
                                    </div>
                                    <div id="channel-title"
                                        class="text-[13px] font-medium text-zinc-600 dark:text-zinc-400">
                                        <!-- Updated dynamically -->
                                    </div>
                                </div>
                            </div>
                            <div
                                class="flex absolute bottom-3 right-3 items-center rounded-full bg-zinc-100 dark:bg-zinc-800 px-2.5 py-0.5 text-xs font-medium text-zinc-800 dark:text-zinc-200">
                                <span id="video-count"><!-- Updated dynamically --></span>&nbsp;videos
                            </div>
                        </div>

                        <x-button type="button" id="add-playlist-btn" style="height: 35px"
                            class="shrink-0 mt-2 sm:w-auto w-full">
                            <x-loading-spinner data-loading-spinner
                                class="me-1 size-4 animate-spin hidden dark:text-zinc-200 text-zinc-700" />
                            <span>Add to Database</span>
                        </x-button>
                    </div>

                    <div class="w-full mt-4 lg:mt-0 md:p-6 md:px-0 md:py-0 p-0 sm:p-4 pb-0 flex flex-col">
                        <div class="px-4 sm:px-0">
                            <h3 class="text-base/7 font-semibold text-zinc-900 dark:text-zinc-100">
                                YouTube Playlist Information
                            </h3>
                            <p class="mt-1 max-w-2xl text-sm/6 text-zinc-500 dark:text-zinc-400">
                                View YouTube playlist information before adding it to the database
                            </p>
                        </div>
                        <div class="mt-6 border-t border-zinc-100 dark:border-zinc-800">
                            <dl class="divide-y divide-zinc-100 dark:divide-zinc-800">
                                <!-- Playlist Information -->
                                <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                    <dt class="text-sm/6 font-medium text-zinc-900 dark:text-zinc-100">Playlist ID
                                    </dt>
                                    <dd id="playlist-id"
                                        class="mt-1 text-sm/6 text-zinc-700 dark:text-zinc-300 sm:col-span-2 sm:mt-0 break-all">
                                        <!-- Updated dynamically -->
                                    </dd>
                                </div>
                                <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                    <dt class="text-sm/6 font-medium text-zinc-900 dark:text-zinc-100">Playlist
                                        Title
                                    </dt>
                                    <dd id="playlist-info-title"
                                        class="mt-1 text-sm/6 text-zinc-700 dark:text-zinc-300 sm:col-span-2 sm:mt-0">
                                        <!-- Updated dynamically -->
                                    </dd>
                                </div>
                                <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                    <dt class="text-sm/6 font-medium text-zinc-900 dark:text-zinc-100">Playlist
                                        Description</dt>
                                    <dd id="playlist-description"
                                        class="mt-1 text-sm/6 text-zinc-700 dark:text-zinc-300 sm:col-span-2 sm:mt-0 truncate">
                                        <!-- Updated dynamically -->
                                    </dd>
                                </div>
                                <!-- Channel Information -->
                                <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                    <dt class="text-sm/6 font-medium text-zinc-900 dark:text-zinc-100">Channel URL
                                    </dt>
                                    <dd id="channel-url"
                                        class="mt-1 text-sm/6 text-zinc-700 dark:text-zinc-300 sm:col-span-2 sm:mt-0">
                                        <!-- Updated dynamically -->
                                    </dd>
                                </div>
                                <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                    <dt class="text-sm/6 font-medium text-zinc-900 dark:text-zinc-100">Subscriber
                                        Count
                                    </dt>
                                    <dd id="subscriber-count"
                                        class="mt-1 text-sm/6 text-zinc-700 dark:text-zinc-300 sm:col-span-2 sm:mt-0">
                                        <!-- Updated dynamically -->
                                    </dd>
                                </div>
                                <div class="px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                    <dt class="text-sm/6 font-medium text-zinc-900 dark:text-zinc-100">Video Count
                                    </dt>
                                    <dd id="playlist-video-count"
                                        class="mt-1 text-sm/6 text-zinc-700 dark:text-zinc-300 sm:col-span-2 sm:mt-0">
                                        <!-- Updated dynamically -->
                                    </dd>
                                </div>
                            </dl>
                        </div>
                        <div class="p-4 pt-0 sm:p-0 mt-auto hidden" id="playlist-already-exists-msg">
                            <div
                                class="rounded-md border-blue-200 dark:border-blue-950 bg-blue-50 dark:bg-blue-900/20 text-blue-800 dark:text-blue-300 font-medium flex items-center gap-2 text-sm border w-full py-2 px-5">
                                <x-gmdi-info-o class="size-5" />
                                <span>Playlist already exists in the database</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </x-card>
    </div>

    <div id="alert-container" class="fixed top-4 right-4 space-y-2 min-w-xl"></div>

    <x-slot name="script">
        @vite('resources/js/playlists/create.js')
    </x-slot>
</x-base-layout>
