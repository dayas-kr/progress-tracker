<?php

namespace App\Http\Controllers;

use App\Helpers\DurationConverter;
use App\Models\Playlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PlaylistController extends Controller
{
    public function index()
    {
        // Cache the playlists count for 30 minutes
        $playlist_count = Cache::remember('playlist_count_' . auth()->id(), 30, function () {
            return auth()->user()->playlists()->count();
        });

        // Retrieve the paginated playlists
        $playlists = auth()->user()->playlists()->paginate(6);

        return view('playlists.index', compact('playlists', 'playlist_count'));
    }

    public function create()
    {
        return view('playlists.create');
    }

    public function destroy(Playlist $playlist)
    {
        $playlist->delete(); // Delete the playlist from the database

        return response()->json([
            'success' => true,
            'message' => 'Playlist deleted successfully'
        ]);
    }
}
