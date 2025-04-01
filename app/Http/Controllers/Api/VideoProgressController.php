<?php

namespace App\Http\Controllers\Api;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class VideoProgressController extends Controller
{
    /**
     * Mark a video as completed.
     */
    public function complete(Request $request)
    {
        // Validate the request input
        $request->validate([
            'v' => 'required|string',
            'advancedInfo' => 'nullable'
        ]);

        try {
            $video = $this->getVideo($request->input('v'));

            // Update video progress and completion status
            $video->progress = $video->duration_in_seconds;
            $video->is_completed = 1;
            $video->save();

            // Update the associated playlist progress if applicable
            $this->updatePlaylistProgress($video);

            if ($request->boolean('advancedInfo') === true) {
                $playlist = $video->playlist;

                return response()->json([
                    'status'  => 'success',
                    'message' => 'Video marked as completed successfully.',
                    'data'    => [
                        'progress'            => $playlist->playlist_progress,
                        'remainingDuration'   => $playlist->remaining_duration,
                        'completedVideoCount' => $playlist->completed_video_count,
                    ],
                ]);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Video marked as completed successfully.'
            ], Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Video not found.'
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            Log::error('Error marking video as completed: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while updating the video.'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Reset a video's progress.
     */
    public function reset(Request $request)
    {
        // Validate the request input
        $request->validate([
            'v' => 'required|string',
            'advancedInfo' => 'nullable'
        ]);

        try {
            $video = $this->getVideo($request->input('v'));

            // Reset video progress and completion status
            $video->progress = 0;
            $video->is_completed = 0;
            $video->save();

            // Update the associated playlist progress if applicable
            $this->updatePlaylistProgress($video);

            if ($request->boolean('advancedInfo') === true) {
                $playlist = $video->playlist;

                return response()->json([
                    'status'  => 'success',
                    'message' => 'Video progress reset successfully.',
                    'data'    => [
                        'progress'            => $playlist->playlist_progress,
                        'remainingDuration'   => $playlist->remaining_duration,
                        'completedVideoCount' => $playlist->completed_video_count,
                    ],
                ]);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Video progress reset successfully.'
            ], Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Video not found.'
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            Log::error('Error resetting video progress: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while resetting the video progress.'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Retrieve a video by its ID.
     *
     * @param string $videoId
     * @return \App\Models\Video
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    private function getVideo(string $videoId)
    {
        $video = Video::where('video_id', $videoId)->first();
        if (!$video) {
            throw new ModelNotFoundException('Video not found.');
        }
        return $video;
    }

    /**
     * Update the progress of the associated playlist.
     *
     * @param \App\Models\Video $video
     */
    private function updatePlaylistProgress(Video $video)
    {
        $playlist = $video->playlist;
        if ($playlist) {
            $totalProgress = $playlist->videos()->sum('progress');
            $playlist->progress = $totalProgress;
            $playlist->save();
        }
    }
}
