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

        if (!$playlist) return abort(404);

        $videos = $playlist->videos()->paginate(10);

        $positionWidth = $this->getPositionWidth($playlist->video_count);

        return view('playlists.show', compact('playlist', 'videos', 'positionWidth'));
    }

    public function destroy(Playlist $playlist)
    {
        $playlist->delete(); // Delete the playlist from the database

        return response()->json([
            'success' => true,
            'message' => 'Playlist deleted successfully'
        ]);
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
