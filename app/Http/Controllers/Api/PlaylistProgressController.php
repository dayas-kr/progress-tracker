<?php

namespace App\Http\Controllers\Api;

use App\Models\Playlist;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PlaylistProgressController extends Controller
{
    /**
     * Mark a playlist as completed.
     */
    public function complete(Request $request)
    {
        // Validate the request input
        $request->validate(['list' => 'required|string']);

        try {
            $playlist = $this->getPlaylist($request->input('list'));

            $playlist->videos->each(function ($video) {
                $video->progress = $video->duration_in_seconds;
                $video->is_completed = true;
                $video->save();
            });

            $playlist->progress = $playlist->total_duration;
            $playlist->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Playlist marked as completed successfully.'
            ], Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            Log::error('Playlist not found: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Playlist not found.'
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            Log::error('Error marking playlist as completed: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while updating the playlist.'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Reset a playlist's progress.
     */
    public function reset(Request $request)
    {
        // Validate the request input
        $request->validate(['list' => 'required|string']);

        try {
            $playlist = $this->getPlaylist($request->input('list'));

            $playlist->videos->each(function ($video) {
                $video->progress = 0;
                $video->is_completed = false;
                $video->save();
            });

            $playlist->progress = 0;
            $playlist->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Playlist progress reset successfully.'
            ], Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            Log::error('Playlist not found: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Playlist not found.'
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            Log::error('Error resetting playlist progress: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while resetting the playlist progress.'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function getPlaylist(string $playlistId)
    {
        $playlist = Playlist::where('playlist_id', $playlistId)->first();
        if (!$playlist) {
            throw new ModelNotFoundException('Playlist not found.');
        }
        return $playlist;
    }
}
