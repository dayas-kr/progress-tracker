<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\Traits\ConvertsJson;

class Video extends Model
{
    use ConvertsJson;

    protected $guarded = [];

    protected $casts = [
        'published_at' => 'datetime',
        'thumbnails' => 'array',
        'tags' => 'array',
        'content_details' => 'array',
        'statistics' => 'array',
        'channel' => 'array',
        'status' => 'array',
        'player' => 'array',
    ];

    /**
     * Get the playlist that owns the video.
     */
    public function playlist()
    {
        return $this->belongsTo(Playlist::class);
    }

    /**
     * Convert JSON to object.
     */
    public function getThumbnailsAttribute($value)
    {
        return $this->convertJsonToObject($value);
    }

    public function getContentDetailsAttribute($value)
    {
        return $this->convertJsonToObject($value);
    }

    public function getStatisticsAttribute($value)
    {
        return $this->convertJsonToObject($value);
    }

    public function getChannelAttribute($value)
    {
        return $this->convertJsonToObject($value);
    }

    public function getStatusAttribute($value)
    {
        return $this->convertJsonToObject($value);
    }

    public function getPlayerAttribute($value)
    {
        return $this->convertJsonToObject($value);
    }
}
