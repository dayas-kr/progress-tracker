<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class VideoSessionController extends Controller
{
    /**
     * Update session settings for video preferences.
     */
    public function __invoke(Request $request)
    {
        try {
            if ($request->has('autoplay')) {
                session()->put('auto_play', $request->boolean('autoplay'));
            }

            if ($request->has('auto_complete')) {
                session()->put('auto_complete', $request->boolean('auto_complete'));
            }

            // Retrieve updated session values
            return response()->json([
                'status'        => 'success',
                'message'       => 'Session settings updated successfully.',
                'autoplay'     => session()->get('auto_play'),
                'auto_complete' => session()->get('auto_complete'),
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error('Error updating session settings: ' . $e->getMessage());

            return response()->json([
                'status'  => 'error',
                'message' => 'An unexpected error occurred while updating session settings.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
