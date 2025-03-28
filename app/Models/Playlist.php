<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\Traits\ConvertsJson;

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
