<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function show(Request $request, Video $video)
    {
        $videoId = $request->input('v');
        $youtubePlaylistId = $request->input('list');

        $video = Video::where('video_id', $videoId)
            ->whereHas('playlist', function ($query) use ($youtubePlaylistId) {
                $query->where('playlist_id', $youtubePlaylistId);
            })->first() ?? [];

        if (!$video) {
            return response()->json(['error' => 'Video not found'], 404);
        }

        $playlist = $video->playlist;
        $index = $this->calculateIndex($request, $playlist->video_count);
        $positionWidth = $this->getPositionWidth($playlist->video_count);
        $completed = $video->is_completed;

        return view('videos.show', compact('video', 'playlist', 'index', 'positionWidth', 'completed'));
    }

    private function calculateIndex(Request $request, $playlistVideoCount): int
    {
        $index = $request->input('index');

        if (!$index) return 0; // Default to 0 if index is missing

        $listInput = (int) $index - 1;

        return ($listInput < 0 || $listInput >= $playlistVideoCount) ? 0 : $listInput;
    }

    /**
     * Gets the width of the video position container based on the playlist size.
     *
     * @param  int  $videoCount
     * @return string
     */
    private function getPositionWidth(int $videoCount): string
    {
        if ($videoCount > 99) {
            return 'w-6 me-0';
        } elseif ($videoCount > 9) {
            return 'w-4 me-0';
        }
        return 'w-2 me-0.5';
    }
}
