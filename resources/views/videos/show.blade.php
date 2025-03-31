<x-base-layout title="{{ $video->title }} - Progress Tracker">
    <div class="min-h-screen pt-14 dark:bg-zinc-950 bg-white">
        <!-- Header -->
        @include('videos.partials.header')

        <div class="font-roboto pt-5 sm:px-4 px-0 3xl:px-0">
            <!-- On mobile, use column layout; on larger screens (lg and up) use row -->
            <div class="max-w-[1705px] mx-auto w-full flex flex-col lg:flex-row gap-5 px-3.5 lg:px-0">
                <!-- Video and Details Section -->
                <div id="player-container" class="flex-1 flex relative flex-col lg:h-[calc(100vh-(56px+40px))]">
                    <div id="player" data-time={{ $start_time }} data-list="{{ $playlist->playlist_id }}"
                        data-video-id="{{ $video->video_id }}"
                        data-completed="{{ $video->is_completed ? 'true' : 'false' }}"
                        data-video-options="{{ json_encode($video_options) }}"
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
                                        {{ rand(1, 100) }}.{{ rand(1, 100) }}M subscribers
                                    </div>
                                </div>
                            </div>

                            <div class="flex gap-2.5">
                                <x-button id="reset-progress" radius="full" variant="outline"
                                    data-video-completed="{{ $video->is_completed ? 'true' : 'false' }}">
                                    <i class="fa-solid reset-icon fa-rotate-left text-[0.9rem]"></i>
                                    <span>Reset progress</span>
                                </x-button>
                                <x-button id="mark-completed" radius="full" variant="outline"
                                    data-video-completed="{{ $video->is_completed ? 'true' : 'false' }}">
                                    <x-loading-spinner class="animate-spin size-4 spinner-icon hidden" />
                                    <i
                                        class="fa-regular fa-circle-check {{ $video->is_completed ? 'display-none' : '' }} check-icon text-[1.005rem]"></i>
                                    <i
                                        class="fa-solid text-green-600 {{ $video->is_completed ? '' : 'display-none' }} dark:text-green-500 fa-circle-check checked-icon text-[1.005rem]"></i>
                                    <span>Mark{{ $video->is_completed ? 'ed' : '' }} as completed</span>
                                </x-button>
                                <x-button data-dialog-target="video-option-dialog" radius="full" variant="outline">
                                    Options
                                </x-button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Playlist Sidebar -->
                <div
                    class="lg:max-w-[400px] w-full border dark:border-zinc-700 rounded-xl h-fit flex flex-col max-h-[50vh] sm:max-h-[calc(100vh-(56px+40px))] overflow-hidden pb-1">
                    <!-- Playlist Video Header -->
                    <div class="h-16 bg-zinc-50 border-b dark:border-zinc-700 dark:bg-zinc-900 py-2 pl-6 pr-2.5">
                        <a href="{{ env('APP_URL') }}/playlist?list={{ $playlist->playlist_id }}"
                            class="block text-lg font-bold w-fit pr-2.5 text-zinc-800 dark:text-zinc-50 truncate">
                            {{ $playlist->title }}
                        </a>
                        <div class="text-[13px] text-zinc-800 dark:text-zinc-400">
                            <a href="#">{{ $video->channel->channelTitle }}</a>
                            <span>- {{ $video->position + 1 }}/{{ $playlist->video_count }}</span>
                        </div>
                    </div>
                    <!-- Video List -->
                    <div id="playlist-videos-container" class="flex-1 overflow-y-auto custom-scrollbar"
                        id="playlist-videos">
                        @foreach ($playlist->videos as $_video)
                            <div data-video-card data-video-id="{{ $_video->video_id }}"
                                class="grid grid-cols-[1rem_auto_1fr_auto] gap-x-2 relative py-2 pl-2 pr-1 items-center {{ $_video->video_id === $video->video_id ? 'bg-blue-100 dark:bg-blue-900/25' : 'hover:bg-zinc-100 dark:hover:bg-zinc-800' }}">
                                <div class="text-sm text-zinc-600 dark:text-zinc-400 text-right pr-1">
                                    {{ $_video->position + 1 }}
                                </div>
                                <div
                                    class="bg-zinc-200 ml-1 relative overflow-hidden dark:bg-zinc-800 rounded-lg w-24 sm:w-30 aspect-video">
                                    <img class="h-full w-full object-cover"
                                        src="{{ $_video->thumbnails->medium->url }}" alt="Thumbnail">
                                    <div
                                        class="video-duration-container absolute bottom-1 right-1 text-white text-xs px-[5px] py-[1px] font-semibold rounded bg-black/80">
                                        <span
                                            class="video-duration">{{ \App\Helpers\DurationConverter::convertYouTubeDuration($video->content_details->duration) }}</span>
                                    </div>
                                </div>
                                <div class="pl-1 h-full">
                                    <div class="space-y-1">
                                        <div
                                            class="text-sm font-medium w-full line-clamp-2 text-zinc-800 dark:text-zinc-100">
                                            {{ $_video->title }}</div>
                                        <div class="text-xs line-clamp-1 text-zinc-600 dark:text-zinc-400">
                                            {{ $_video->channel->channelTitle }}
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center relative z-20">
                                    <x-checkbox class="checkbox" id="checkbox-{{ $_video->video_id }}" size="small"
                                        :checked="$_video->is_completed" data-video-id="{{ $_video->video_id }}" />
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

    <div id="video-option-dialog" data-dialog-backdrop
        class="fixed inset-0 bg-zinc-900/50 dark:bg-black/60 z-[1000] flex items-center justify-center px-5 sm:px-0"
        style="display: none;">
        <div data-dialog-content
            class="w-full max-w-xl p-6 border border-white dark:border-zinc-800 bg-white dark:bg-zinc-900 rounded-xl"
            aria-modal="true" tabindex="0">
            <h3 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100">Video Playback Options</h3>
            <p class="mb-5 mt-2 text-sm text-zinc-800 dark:text-zinc-200">
                Customize your video settings and behavior below.
            </p>
            <div class="space-y-4">
                <x-checkbox :checked="$video_options->auto_complete" id="auto_complete" name="auto_complete"
                    label="Automatically Mark as Completed"
                    description="The video will be marked as completed once it has been fully played." />
                <x-checkbox :checked="$video_options->autoplay" id="autoplay" name="autoplay" label="Auto Play"
                    description="Plays the next video automatically." />
                {{-- <x-checkbox label="Loop Video" description="The video will repeat when it ends." /> --}}
            </div>
            <div class="mt-5 flex justify-end gap-2.5">
                <x-button radius="full" data-dialog-cancel class="w-fit" variant="secondary">Cancel</x-button>
                <x-button id="video-options-submit" radius="full" data-dialog-confirm class="w-fit">Save
                    Changes</x-button>
            </div>
        </div>
    </div>


    <div id="alert-container" class="fixed top-4 z-[100] right-4 space-y-2 min-w-xl"></div>

    <!-- Load the YouTube IFrame API -->
    <script src="https://www.youtube.com/iframe_api"></script>

    <x-slot name="script">
        @vite('resources/js/videos/show.js')
    </x-slot>
</x-base-layout>
