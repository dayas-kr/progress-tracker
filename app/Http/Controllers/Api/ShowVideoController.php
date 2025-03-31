<?php

namespace App\Http\Controllers\Api;

use App\Models\Video;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ShowVideoController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        // Retrieve session settings
        $video_options = $this->getSessionSettings();

        $videoId = $request->input('v');
        $youtubePlaylistId = $request->input('list');

        // Retrieve the video using a dedicated method.
        $video = $this->getVideo($videoId, $youtubePlaylistId);

        if (!$video) abort(404);

        $playlist = $video->playlist;
        $index = $this->calculateIndex($request, $playlist->video_count);
        $completed = $video->is_completed;

        // Determine the start time based on video progress.
        $start_time = $this->calculateStartTime($video);

        $nextVideo = $playlist->videos()->where('position', $index + 1)->first();

        return view('videos.show', compact('video', 'playlist', 'index', 'completed', 'start_time', 'nextVideo', 'video_options'));
    }

    /**
     * Retrieve video based on video id and playlist id.
     */
    private function getVideo(string $videoId, string $youtubePlaylistId): ?Video
    {
        return Video::where('video_id', $videoId)
            ->whereHas('playlist', function ($query) use ($youtubePlaylistId) {
                $query->where('playlist_id', $youtubePlaylistId);
            })
            ->first();
    }

    /**
     * Calculate the index value.
     */
    private function calculateIndex(Request $request, int $playlistVideoCount): int
    {
        $index = $request->input('index');
        if (!$index) {
            return 0; // Default to 0 if index is missing.
        }

        $listInput = (int) $index - 1;
        return ($listInput < 0 || $listInput >= $playlistVideoCount) ? 0 : $listInput;
    }

    /**
     * Calculate the start time based on video progress and duration.
     *
     * New logic: if progress is less than 30 seconds and there's at least 30 seconds left in the video,
     * the video should start at 0; otherwise, resume at the progress time.
     */
    private function calculateStartTime(Video $video): int
    {
        // if ($video->is_completed) return 0;
        // // Check if progress is less than 30 seconds and at least 30 seconds remain.
        // if ($video->progress < 30 && ($video->duration_in_seconds - $video->progress) > 30) {
        //     return 0;
        // }
        // return (int) $video->progress;
        if ($video->is_completed) return 0;

        // Calculate progress and remaining percentages.
        $progressPercent = ($video->progress / $video->duration_in_seconds) * 100;
        $remainingPercent = ((($video->duration_in_seconds - $video->progress) / $video->duration_in_seconds) * 100);

        // Check if progress is less than 30% and at least 30% remains.
        if ($progressPercent < 30 && $remainingPercent > 30) {
            return 0;
        }

        return (int) $progressPercent;
    }

    /**
     * Retrieve session settings with default values.
     */
    private function getSessionSettings(): object
    {
        $auto_play = session()->get('auto_play', true);
        $auto_complete = session()->get('auto_complete', true);
        $loop_playlist = session()->get('loop_playlist', true);

        return (object)[
            'autoplay'     => (bool) $auto_play,
            'auto_complete' => (bool) $auto_complete,
            'loop_playlist' => (bool) $loop_playlist
        ];
    }
}
