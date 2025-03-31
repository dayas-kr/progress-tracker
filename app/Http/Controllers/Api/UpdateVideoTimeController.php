<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use App\Models\Playlist;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UpdateVideoTimeController extends Controller
{
    /**
     * Update the progress of a video in a playlist.
     */
    public function __invoke(Request $request)
    {
        // Validate request inputs
        $request->validate([
            'list' => 'required|string',
            'v'    => 'required|string',
            't'    => 'required|numeric|min:0',
        ]);

        try {
            $playlist = Playlist::where('playlist_id', $request->input('list'))->first();

            if (!$playlist) {
                throw new ModelNotFoundException('Playlist not found.');
            }

            $video = $playlist->videos()->where('video_id', $request->input('v'))->first();

            if (!$video) {
                throw new ModelNotFoundException('Video not found in the playlist.');
            }

            $video->update(['progress' => $request->input('t')]);

            return response()->json([
                'status'  => 'success',
                'message' => 'Video progress updated successfully.',
            ], Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage(),
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            Log::error('Error updating video progress: ' . $e->getMessage());

            return response()->json([
                'status'  => 'error',
                'message' => 'An error occurred while updating video progress.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
