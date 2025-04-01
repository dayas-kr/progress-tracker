<?php

namespace App\Models;

use App\Helpers\DurationConverter;
use App\Helpers\Traits\ConvertsJson;
use Illuminate\Database\Eloquent\Model;

class Playlist extends Model
{
    use ConvertsJson;

    protected $guarded = [];

    protected $casts = [
        'video_count' => 'integer',
        'subscriber_count' => 'integer',
        'images' => 'array',
        'channel_images' => 'array',
    ];

    /**
     * Get the user that owns the playlist.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the videos for the playlist.
     */
    public function videos()
    {
        return $this->hasMany(Video::class);
    }

    public function getDurationAttribute()
    {
        return DurationConverter::convertSecondsToYouTubeDuration($this->total_duration);
    }

    public function getAverageDurationAttribute()
    {
        $averageDurationSec = $this->video_count > 0 ? round($this->total_duration / $this->video_count) : 0;

        return DurationConverter::convertSecondsToYouTubeDuration($averageDurationSec);
    }

    public function getRemaingDurationAttribute()
    {
        $completedDurationSec = $this->videos()->sum('progress');
        $remainingDurationSec = $this->total_duration - $completedDurationSec;
        return DurationConverter::convertSecondsToYouTubeDuration($remainingDurationSec);
    }

    public function getPlaylistProgressAttribute()
    {
        $completedDurationSec = $this->videos()->sum('progress');
        return $this->total_duration > 0 ? round(($completedDurationSec * 100) / $this->total_duration) : 0;
    }

    public function getCompletedVideoCountAttribute()
    {
        return $this->videos()->where('is_completed', true)->count();
    }

    /**
     * Convert JSON to object.
     */
    public function getImagesAttribute($value)
    {
        return $this->convertJsonToObject($value);
    }

    public function getChannelImagesAttribute($value)
    {
        return $this->convertJsonToObject($value);
    }
}
