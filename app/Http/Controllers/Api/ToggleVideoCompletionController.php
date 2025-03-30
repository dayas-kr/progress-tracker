<?php

namespace App\Http\Controllers\Api;

use App\Models\Video;
use App\Models\Playlist;
use Illuminate\Http\Request;
use App\Helpers\DurationConverter;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class ToggleVideoCompletionController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $video = Video::where('video_id', $request->input('v'))->first();

        if (!$video) {
            return response()->json([
                'success' => false,
                'error'   => 'Video not found',
                'message' => 'No video exists for the provided ID'
            ], 404);
        }

        if (!$this->toggleCompletion($video, (bool) $request->input('completed'))) {
            return response()->json([
                'success' => false,
                'error'   => 'Something went wrong',
                'message' => 'Unable to update video status'
            ], 500);
        }

        return response()->json([
            'success' => true,
            'completed'  => $request->input('completed')
        ]);
    }

    /**
     * Toggle the video completion status.
     *
     * @param  \App\Models\Video  $video
     * @param  bool  $isCompleted
     * @return bool
     */
    private function toggleCompletion(Video $video, bool $isCompleted): bool
    {
        $progress = DurationConverter::convertToSecond($video->content_details->duration);

        // Update video completion status and progress
        $videoUpdated = $video->update([
            'is_completed' => $isCompleted,
            'progress'     => $isCompleted ? $progress : 0,
        ]);

        // Ensure playlist relationship is loaded
        if (!$video->relationLoaded('playlist')) {
            $video->load('playlist');
        }

        // Check if playlist exists
        if ($video->playlist) {
            // Refresh playlist videos to ensure latest data
            $video->playlist->load('videos');

            // Calculate the total progress for the playlist
            $total_video_progress = $video->playlist->videos()->sum('progress');

            // Log debug info
            Log::debug('Updating playlist progress', [
                'playlist_id'         => $video->playlist->id,
                'total_video_progress' => $total_video_progress,
            ]);

            // Update playlist progress
            $playlistUpdated = $video->playlist->update([
                'progress' => $total_video_progress,
            ]);

            return $videoUpdated && $playlistUpdated;
        }

        return $videoUpdated;
    }
}
