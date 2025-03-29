<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UpdateVideoTimeController extends Controller
{
    public function __invoke(Request $request)
    {
        $playlist_id = $request->input('list');
        $video_id = $request->input('v');
        $time = $request->input('t');

        $playlist = \App\Models\Playlist::where('playlist_id', $playlist_id)->first();

        if (!$playlist) {
            return response()->json(['error' => 'Playlist not found'], 404);
        }

        $playlist->videos()->where('video_id', $video_id)->update(['progress' => $time]);

        return response()->json(['success' => true]);
    }
}
