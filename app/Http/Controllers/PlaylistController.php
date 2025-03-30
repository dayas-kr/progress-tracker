<?php

namespace App\Http\Controllers;

use App\Models\Playlist;
use Illuminate\Support\Facades\Cache;

class PlaylistController extends Controller
{
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
