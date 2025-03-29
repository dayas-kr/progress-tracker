<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\Request;

class ToggleVideoCompletionController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $video = Video::where('video_id', $request->input('v'))->first();

        if (!$video) {
            return response()->json([
                'success' => false,
                'error'   => 'Video not found',
                'message' => 'No video exists for the provided ID'
            ], 404);
        }

        if (!$this->toggleCompletion($video, (bool) $request->input('completed'))) {
            return response()->json([
                'success' => false,
                'error'   => 'Something went wrong',
                'message' => 'Unable to update video status'
            ], 500);
        }

        return response()->json([
            'success' => true,
            'completed'  => $request->input('completed')
        ]);
    }

    /**
     * Toggle the video completion status.
     *
     * @param  \App\Models\Video  $video
     * @param  bool  $isCompleted
     * @return bool
     */
    private function toggleCompletion(Video $video, bool $isCompleted): bool
    {
        return $video->update([
            'is_completed' => $isCompleted,
        ]);
    }
}
