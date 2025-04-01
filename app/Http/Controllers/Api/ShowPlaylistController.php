<?php

namespace App\Http\Controllers\Api;

use App\Models\Playlist;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ShowPlaylistController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $playlist = Playlist::where('playlist_id', $request->input('list'))->first() ?? [];

        if (!$playlist) return abort(404);

        $videos = $playlist->videos()->paginate(10);
        $positionWidth = $this->getPositionWidth($playlist->video_count);

        return view('playlists.show', compact('playlist', 'videos', 'positionWidth'));
    }

    private function getPositionWidth(int $playlistCount): string
    {
        if ($playlistCount > 99) return 'w-7 me-1.5';
        elseif ($playlistCount > 9) return 'w-5 me-2';
        return 'w-2 me-2.5';
    }
}
