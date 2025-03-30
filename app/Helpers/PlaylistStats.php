<?php

namespace App\Helpers;

use App\Models\Playlist;
use App\Helpers\DurationConverter;

class PlaylistStats
{
    /**
     * Get all the statistics for the given playlist.
     *
     * @param Playlist $playlist
     * @return object
     */
    public static function getStats(Playlist $playlist): object
    {
        $totalDurationSec = $playlist->total_duration;
        $totalDuration    = DurationConverter::convertSecondsToYouTubeDuration($totalDurationSec);
        $averageDurationSec = $playlist->video_count > 0 ? round($totalDurationSec / $playlist->video_count) : 0;
        $averageDuration  = DurationConverter::convertSecondsToYouTubeDuration($averageDurationSec);
        $completedVideos  = $playlist->videos()->where('is_completed', true)->count();
        $playlistProgress = self::calculatePlaylistProgress($playlist, $totalDurationSec);
        $remaingDuration  = self::calculateRemaingDuration($playlist, $totalDurationSec);

        return (object)[
            'total_duration'    => $totalDuration,
            'average_duration'  => $averageDuration,
            'completed_videos'  => $completedVideos,
            'playlist_progress' => $playlistProgress,
            'remaing_duration'  => $remaingDuration,
        ];
    }

    /**
     * Calculate the playlist progress percentage.
     *
     * @param Playlist $playlist
     * @param int $totalDurationSec
     * @return int
     */
    private static function calculatePlaylistProgress(Playlist $playlist, int $totalDurationSec): int
    {
        $completedDurationSec = $playlist->videos()->sum('progress');
        return $totalDurationSec > 0 ? round(($completedDurationSec * 100) / $totalDurationSec) : 0;
    }

    /**
     * Calculate the remaining duration in YouTube duration format.
     *
     * @param Playlist $playlist
     * @param int $totalDurationSec
     * @return string
     */
    private static function calculateRemaingDuration(Playlist $playlist, int $totalDurationSec): string
    {
        $completedDurationSec = $playlist->videos()->sum('progress');
        $remainingDurationSec = $totalDurationSec - $completedDurationSec;
        return DurationConverter::convertSecondsToYouTubeDuration($remainingDurationSec);
    }
}
