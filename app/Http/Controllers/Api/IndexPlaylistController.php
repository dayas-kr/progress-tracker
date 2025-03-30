<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class IndexPlaylistController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke()
    {
        // Cache the playlists count for 30 minutes
        $playlist_count = Cache::remember('playlist_count_' . auth()->id(), 30, function () {
            return auth()->user()->playlists()->count();
        });

        // Retrieve the paginated playlists
        $playlists = auth()->user()->playlists()->paginate(6);

        return view('playlists.index', compact('playlists', 'playlist_count'));
    }
}
