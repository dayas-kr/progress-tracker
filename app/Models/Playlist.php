<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Playlist extends Model
{
    protected $guarded = [];

    protected $casts = [
        'video_count' => 'integer',
        'subscriber_count' => 'integer',
        'images' => 'array',
        'channel_images' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function videos()
    {
        return $this->hasMany(Video::class);
    }
}
