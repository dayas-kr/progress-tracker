<?php

namespace App\Http\Controllers;

use App\Models\Playlist;
use Illuminate\Http\Request;

class PlaylistController extends Controller
{
    public function index()
    {
        $playlists = auth()->user()->playlists()->paginate(6);
        return view('playlists.index', compact('playlists'));
    }

    public function create()
    {
        return view('playlists.create');
    }

    public function show(Request $request)
    {
        $playlist = Playlist::where('playlist_id', $request->input('list'))->first() ?? [];

        $videos = $playlist->videos;

        $positionWidth = $this->getPositionWidth($playlist->video_count);

        return view('playlists.show', compact('playlist', 'videos', 'positionWidth'));
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
