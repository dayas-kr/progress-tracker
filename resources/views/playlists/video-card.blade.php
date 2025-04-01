@foreach ($videos as $video)
    <div class="hover:bg-zinc-100 rounded-lg p-3 hover:dark:bg-zinc-900 grid grid-cols-[1rem_auto_1fr_auto] gap-x-2 items-center"
        data-video-id="{{ $video->video_id }}" data-completed="{{ $video->is_completed ? 'true' : 'false' }}"
        data-video-card>
        <div class="text-sm font-mono text-right text-zinc-600 dark:text-zinc-400">{{ $video->position + 1 }}
        </div>

        <div class="relative w-50 ml-2 aspect-video rounded-lg overflow-hidden">
            <img class="h-full w-full object-cover" src="{{ $video->thumbnails->high->url }}" alt="{{ $video->title }}">

            @php
                if ($video->progress < 30 && $video->duration_in_seconds - $video->progress > 30) {
                    $progress = 0;
                } else {
                    $progress = min(($video->progress / $video->duration_in_seconds) * 100, 100);
                }
            @endphp

            <div data-progress="{{ $progress }}" data-progress-bg
                class="absolute h-1 bottom-0 left-0 right-0 bg-white/50 data-[progress=0]:hidden data-[progress=100]:hidden">
            </div>
            <div data-progress="{{ $progress }}" style="width: {{ $progress }}%" data-progress-indicator
                class="absolute h-1 bottom-0 left-0 bg-[#F03] data-[progress=0]:hidden data-[progress=100]:hidden">
            </div>

            <div
                class="video-duration-container absolute bottom-2 right-1 text-white text-[13px] px-[5px] py-[1px] font-semibold rounded bg-black/80">
                <span class="video-duration">{{ $video->duration }}</span>
                <!-- Dynamically show "Now playing" -->
                <span class="video-now-playing hidden">Now playing</span>
            </div>

            <a href="{{ env('APP_URL') }}/watch?v={{ $video->video_id }}&list={{ $playlist->playlist_id }}&index={{ $video->position + 1 }}"
                class="absolute inset-0">
                <span class="sr-only">View video</span>
            </a>
        </div>

        <div class="space-y-1 pl-1 video-info h-full">
            <h3 class="font-medium line-clamp-2 text-zinc-900 dark:text-zinc-100">
                <a href="https://youtu.be/{{ $video->video_id }}" target="_blank">{{ $video->title }}</a>
            </h3>
            <div class="flex items-center text-xs gap-x-1.5 text-zinc-500 dark:text-zinc-400">
                <span class="font-medium">{{ $video->channel->channelTitle }}</span>
                <span class="hidden sm:inline-block">•</span>
                <span class="hidden sm:inline-block">
                    <span class="uppercase">{{ $video->view_count }}</span> views
                </span>
                <span class="hidden sm:inline-block">•</span>
                <span class="hidden sm:inline-block">{{ $video->published_at->diffForHumans() }}</span>
            </div>
            @if ($video->is_completed)
                <x-badge size="md" variant="green-subtle" class="completed-badge">
                    <i class="fa-regular fa-circle-check"></i><span class="hidden sm:inline">Completed</span>
                </x-badge>
            @endif
        </div>

        <x-dropdown align="right" :gap="0">
            <x-slot name="trigger">
                <x-button variant="ghost" icon radius="full" class="shrink-0 size-[38px]">
                    <x-gmdi-more-vert class="size-6 text-zinc-600 dark:text-zinc-300" />
                </x-button>
            </x-slot>
            <x-slot name="content">
                <x-dropdown-item disabled data-video-id="{{ $video->video_id }}" data-watch-later
                    title="Mark as Broken" />
                <x-dropdown-item data-marked="{{ $video->is_completed ? 'true' : 'false' }}"
                    data-video-id="{{ $video->video_id }}" data-mark-completed title="Mark as completed" />
                <x-dropdown-item
                    data-reseted="{{ !$video->is_completed && $video->progress === 0 ? 'true' : 'false' }}"
                    data-reset-progress data-video-id="{{ $video->video_id }}" data-video-title="{{ $video->title }}"
                    data-dialog-target="video-progress-reset-dialog" title="Reset progress" />
            </x-slot>
        </x-dropdown>
    </div>
@endforeach
