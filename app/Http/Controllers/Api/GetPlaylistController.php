<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class GetPlaylistController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        try {
            $playlists = auth()->user()->playlists()->paginate(6);

            if (!$playlists) {
                return response()->json([
                    'status' => 404,
                    'error'  => 'Playlists not found'
                ], 404);
            }

            // Render the Blade view into an HTML snippet
            $htmlView = view('playlists.playlist-card', compact('playlists'))->render();

            // Build a custom response structure
            $response = [
                'current_page'   => $playlists->currentPage(),
                'data'           => ['html' => $htmlView],
                'first_page_url' => $playlists->url(1),
                'from'           => $playlists->firstItem(),
                'last_page'      => $playlists->lastPage(),
                'last_page_url'  => $playlists->url($playlists->lastPage()),
                'links'          => [],
                'next_page_url'  => $playlists->nextPageUrl(),
                'path'           => $playlists->path(),
                'per_page'       => $playlists->perPage(),
                'prev_page_url'  => $playlists->previousPageUrl(),
                'to'             => $playlists->lastItem(),
                'total'          => $playlists->total(),
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
}
