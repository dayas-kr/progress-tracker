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

    public function show(Request $request)
    {
        $playlist = Playlist::where('playlist_id', $request->input('list'))->first() ?? [];

        if (!$playlist) return abort(404);

        $videos = $playlist->videos()->paginate(10);

        $positionWidth = $this->getPositionWidth($playlist->video_count);

        $playlist_stats = $this->playlistStats($playlist);

        return view('playlists.show', compact('playlist', 'videos', 'positionWidth', 'playlist_stats'));
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

    private function playlistStats(Playlist $playlist): object
    {
        $totat_duration_sec = $playlist->videos->sum(function ($video) {
            return DurationConverter::convertToSecond($video->content_details->duration);
        });

        $total_duration = DurationConverter::convertSecondsToYouTubeDuration($totat_duration_sec);

        $average_duration_sec = round($totat_duration_sec / $playlist->video_count);

        $average_duration = DurationConverter::convertSecondsToYouTubeDuration($average_duration_sec);

        $completed_videos = $playlist->videos()->where('is_completed', true)->count();

        $playlist_progress = $this->playlistProgress($playlist, $totat_duration_sec);

        $remaing_duration = $this->remaingDuration($playlist, $totat_duration_sec);

        return (object)
        [
            'total_duration' => $total_duration,
            'average_duration' => $average_duration,
            'completed_videos' => $completed_videos,
            'playlist_progress' => $playlist_progress,
            'remaing_duration' => $remaing_duration
        ];
    }

    private function playlistProgress(Playlist $playlist, int $totat_duration_sec)
    {
        $completed_duration_sec = $playlist->videos()->where('is_completed', true)->get()->sum(function ($video) {
            return DurationConverter::convertToSecond($video->content_details->duration);
        });

        $playlist_progress = round(($completed_duration_sec * 100) / $totat_duration_sec);

        return $playlist_progress;
    }

    private function remaingDuration(Playlist $playlist, int $totat_duration_sec)
    {
        $completed_duration_sec = $playlist->videos()->where('is_completed', true)->get()->sum(function ($video) {
            return DurationConverter::convertToSecond($video->content_details->duration);
        });

        $remaing_duration_sec = $totat_duration_sec - $completed_duration_sec;

        return DurationConverter::convertSecondsToYouTubeDuration($remaing_duration_sec);
    }
}
