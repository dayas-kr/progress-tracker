<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Playlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GetPlaylistVideoController extends Controller
{
    /**
     * Handle the incoming AJAX request to fetch additional videos.
     */
    public function __invoke(Request $request, Playlist $playlist)
    {
        try {
            if (!$playlist) {
                return response()->json([
                    'status' => 404,
                    'error'  => 'Playlist not found'
                ], 404);
            }

            // Paginate videos (10 per page)
            $videos = $playlist->videos()->paginate(10);

            $positionWidth = $this->getPositionWidth($playlist->video_count);

            // Render the Blade view into an HTML snippet
            $htmlView = view('playlists.video-card', compact('videos', 'positionWidth', 'playlist'))->render();

            // Build a custom response structure
            $response = [
                'current_page'   => $videos->currentPage(),
                'data'           => ['html' => $htmlView],
                'first_page_url' => $videos->url(1),
                'from'           => $videos->firstItem(),
                'last_page'      => $videos->lastPage(),
                'last_page_url'  => $videos->url($videos->lastPage()),
                'links'          => [],
                'next_page_url'  => $videos->nextPageUrl(),
                'path'           => $videos->path(),
                'per_page'       => $videos->perPage(),
                'prev_page_url'  => $videos->previousPageUrl(),
                'to'             => $videos->lastItem(),
                'total'          => $videos->total(),
            ];

            return response()->json($response);
        } catch (\Exception $e) {
            // Log the error details to laravel.log
            Log::error("Error in FetchPlaylistVideoController: " . $e->getMessage());
            // Optionally, log more details such as file and line
            Log::error($e->getFile() . ' line ' . $e->getLine());

            // Return a JSON response with the error message (for debugging only)
            return response()->json([
                'error' => 'Something went wrong. Please check the logs.'
            ], 500);
        }
    }

    /**
     * Gets the width of the video position container based on the playlist size.
     *
     * @param  int  $videoCount
     * @return string
     */
    private function getPositionWidth(int $playlistCount): string
    {
        if ($playlistCount > 99) {
            return 'w-7 me-1.5';
        } else if ($playlistCount > 9) {
            return 'w-5 me-2';
        }
        return 'w-2 me-2.5';
    }
}
