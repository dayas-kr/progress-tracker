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
}
