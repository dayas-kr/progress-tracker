<?php

namespace App\Http\Controllers\Api;

use App\Helpers\DurationConverter;
use App\Http\Controllers\Controller;
use App\Models\Video;
use App\Models\Playlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class StorePlaylistController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        // Directly use the input as an array
        $playlistData = json_decode($request->input('playlist_data'), true);

        if (!is_array($playlistData)) {
            return response()->json(['error' => 'Invalid playlist data provided'], 422);
        }

        DB::beginTransaction();

        try {
            // Step 1: Create the playlist
            $playlist = $this->createPlaylist($playlistData);

            if (!$playlist) {
                DB::rollBack();
                return response()->json(['error' => 'Failed to create playlist'], 500);
            }

            // Step 2: Fetch and store videos
            $this->fetchAndStoreVideos($playlist->id, $playlistData['playlistId']);

            DB::commit();

            return response()->json([
                'success'     => true,
                'playlistId' => $playlistData['playlistId'],
                'message'     => 'Playlist created successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Playlist creation failed: " . $e->getMessage());
            return response()->json([
                'error'   => 'Failed to create playlist',
                'message' => $e->getMessage()
            ], 500);
        }
    }


    private function createPlaylist(array $playlistData)
    {
        return Playlist::create([
            'user_id'          => Auth::user()->id,
            'playlist_id'      => $playlistData['playlistId'],
            'title'            => $playlistData['playlistTitle'],
            'description'      => $playlistData['playlistDescription'],
            'images'           => json_encode($playlistData['playlistImages']),
            'channel_images'   => json_encode($playlistData['channelImages']),
            'channel_id'       => $playlistData['channelId'],
            'channel_title'    => $playlistData['channelTitle'],
            'video_count'      => $playlistData['videoCount'],
            'subscriber_count' => $playlistData['subscriberCount'],
        ]);
    }

    private function fetchAndStoreVideos($playlistId, $externalPlaylistId)
    {
        $url = "http://localhost:3000/api/playlist/videos?id={$externalPlaylistId}";

        $response = Http::withToken(env('YOUTUBE_API_TOKEN'))->get($url);

        if (!$response->successful()) {
            throw new \Exception("Failed to fetch videos: " . $response->body(), $response->status());
        }

        $videos = json_decode($response->body(), true);

        foreach ($videos as $video) {
            $this->storeVideo($playlistId, $video);
        }

        return true;
    }

    private function storeVideo($playlistId, array $video)
    {
        Video::create([
            'playlist_id'     => $playlistId,
            'video_id'        => $video['id'],
            'title'           => $video['title'],
            'description'     => $video['description'],
            'published_at'    => $video['publishedAt'],
            'position'        => $video['position'],
            'tags'            => json_encode($video['tags']),
            'thumbnails'      => json_encode($video['thumbnails']),
            'content_details' => json_encode($video['contentDetails']),
            'statistics'      => json_encode($video['statistics']),
            'channel'         => json_encode($video['channel']),
            'status'          => json_encode($video['status']),
            'player'          => json_encode($video['player']),
            'duration_in_seconds' => DurationConverter::convertToSecond($video['contentDetails']['duration']),
        ]);
    }
}
