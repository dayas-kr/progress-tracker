<?php

namespace App\Http\Controllers;

use App\Models\Playlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;

class FetchPlaylistController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $playlist_id = $request->input('playlist_id');

        if (!$playlist_id) {
            return response()->json(['error' => 'Playlist ID is required'], 400);
        }

        try {
            $url = "http://localhost:3000/api/playlist/info?id={$playlist_id}"; // Replace with the actual API URL

            $response = Http::withToken(env('YOUTUBE_API_TOKEN'))->get($url);

            if ($response->successful()) {
                return response()->json([
                    'data' => $response->json(),
                    // does playlist exist in the database
                    'exists' => $this->checkIfPlaylistExists($playlist_id)
                ], 200);
            }

            return response()->json([
                'error' => 'Failed to fetch playlist',
                'status' => $response->status(),
                'message' => $response->body()
            ], $response->status());
        } catch (RequestException $e) {
            return response()->json([
                'error' => 'Request failed',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // check if playlist already exists in the database
    private function checkIfPlaylistExists(string $playlist_id)
    {
        return Playlist::where('playlist_id', $playlist_id)->exists();
    }
}
